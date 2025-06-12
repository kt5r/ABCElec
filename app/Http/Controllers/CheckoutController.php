<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    /**
     * Show checkout page
     */
    public function index()
    {
        $cartItems = CartItem::forUser(Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty'));
        }

        // Verify all items are still available
        foreach ($cartItems as $item) {
            if (!$item->product->status || !$item->product->in_stock) {
                return redirect()->route('cart.index')
                    ->with('error', __('Some items in your cart are no longer available'));
            }

            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', __('Some items in your cart have insufficient stock'));
            }
        }

        $subtotal = $cartItems->sum('subtotal');

        // Calculate tax and shipping
        $taxRate = 0.10; // 10% tax
        $tax = $subtotal * $taxRate;
        $shippingCost = $this->calculateShipping($subtotal);
        $total = $subtotal + $tax + $shippingCost;

        $user = Auth::user(); // Pre-fill user information

        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'taxRate',
            'shippingCost',
            'total',
            'user'
        ));
    }

    /**
     * Process checkout
     */
    public function process(CheckoutRequest $request)
    {
        $cartItems = CartItem::with('product')
            ->forUser(Auth::id())
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty'));
        }

        try {
            DB::beginTransaction();

            // Verify stock availability one more time
            foreach ($cartItems as $item) {
                if (!$item->product->status || 
                    $item->product->stock_quantity < $item->quantity) {
                    throw new \Exception(__('Product :name is no longer available or has insufficient stock', 
                        ['name' => $item->product->name]));
                }
            }

            // Calculate totals
            $subtotal = $cartItems->sum('subtotal');

            $taxRate = 0.10;
            $tax = $subtotal * $taxRate;
            $shippingCost = $this->calculateShipping($subtotal);
            $total = $subtotal + $tax + $shippingCost;

            // Create order - update field names to match form
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'tax_rate' => $taxRate,
                'shipping_cost' => $shippingCost,
                'total_amount' => $total,
                'currency' => 'USD',
                
                // Billing address - use form field names
                'billing_first_name' => $request->first_name,
                'billing_last_name' => $request->last_name,
                'billing_email' => $request->email,
                'billing_phone' => $request->phone,
                'billing_address' => $request->address,
                'billing_city' => $request->city,
                'billing_state' => $request->state,
                'billing_postal_code' => $request->postal_code,
                'billing_country' => $request->country,
                
                // Shipping address
                'shipping_first_name' => $request->different_shipping ? 
                    $request->shipping_first_name : $request->first_name,
                'shipping_last_name' => $request->different_shipping ? 
                    $request->shipping_last_name : $request->last_name,
                'shipping_address' => $request->different_shipping ? 
                    $request->shipping_address : $request->address,
                'shipping_city' => $request->different_shipping ? 
                    $request->shipping_city : $request->city,
                'shipping_state' => $request->different_shipping ? 
                    $request->shipping_state : $request->state,
                'shipping_postal_code' => $request->different_shipping ? 
                    $request->shipping_postal_code : $request->postal_code,
                'shipping_country' => $request->different_shipping ? 
                    $request->shipping_country : $request->country,
                
                'notes' => $request->order_notes
            ]);

            // Create order items and update product stock
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'total' => $cartItem->quantity * $cartItem->product->price
                ]);

                // Update product stock
                $cartItem->product->decrement('stock_quantity', $cartItem->quantity);
            }

            // Process payment (simulate payment gateway)
            $paymentResult = $this->processPayment($order, $request);
            
            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message']);
            }

            // Update order payment status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'payment_transaction_id' => $paymentResult['transaction_id']
            ]);

            // Clear cart items for the user
            CartItem::forUser(Auth::id())->delete();

            DB::commit();

            // Send order confirmation email (implement as needed)
            // $this->sendOrderConfirmationEmail($order);

            return redirect()->route('checkout.success', $order)
                ->with('success', __('Order placed successfully!'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show order success page
     */
    public function success(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.product']);

        return view('checkout.success', compact('order'));
    }

    /**
     * Show order cancel page
     */
    public function cancel()
    {
        return view('checkout.cancel')
            ->with('message', __('Payment was cancelled. You can try again anytime.'));
    }

    /**
     * Calculate shipping cost
     */
    private function calculateShipping($subtotal)
    {
        // Free shipping over $100
        if ($subtotal >= 100) {
            return 0;
        }

        // Standard shipping rates
        if ($subtotal >= 50) {
            return 5.99;
        }

        return 9.99;
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Y') . '-' . strtoupper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Simulate payment processing
     */
    private function processPayment(Order $order, Request $request)
    {
        // This is a simulation - in real implementation, integrate with actual payment gateway
        switch ($request->payment_method) {
            case 'credit_card':
                return $this->processCreditCardPayment($order, $request);
            case 'paypal':
                return $this->processPayPalPayment($order, $request);
            case 'bank_transfer':
                return $this->processBankTransferPayment($order, $request);
            default:
                return [
                    'success' => false,
                    'message' => __('Invalid payment method')
                ];
        }
    }
   /**
     * Simulate credit card payment
     */
    private function processCreditCardPayment(Order $order, Request $request)
    {
        // Validate credit card fields
        $request->validate([
            'card_number' => 'required|string',
            'card_expiry' => 'required|string',
            'card_cvc' => 'required|string|min:3|max:4',
            'card_name' => 'required|string|max:255'
        ]);

        // Simulate payment processing delay
        sleep(1);

        // Simulate 95% success rate
        if (rand(1, 100) <= 95) {
            return [
                'success' => true,
                'transaction_id' => 'TXN_' . strtoupper(Str::random(12)),
                'message' => __('Payment processed successfully')
            ];
        }

        return [
            'success' => false,
            'message' => __('Payment failed. Please check your card details and try again.')
        ];
    }
   /**
     * Simulate PayPal payment
     */
    private function processPayPalPayment(Order $order, Request $request)
    {
        // In real implementation, redirect to PayPal and handle callback
        return [
            'success' => true,
            'transaction_id' => 'PP_' . strtoupper(Str::random(12)),
            'message' => __('PayPal payment processed successfully')
        ];
    }

    /**
     * Process bank transfer payment
     */
    private function processBankTransferPayment(Order $order, Request $request)
    {
        // Bank transfer requires manual verification
        $order->update([
            'payment_status' => 'pending',
            'status' => 'awaiting_payment'
        ]);

        return [
            'success' => true,
            'transaction_id' => 'BT_' . strtoupper(Str::random(12)),
            'message' => __('Bank transfer details sent. Please complete the payment within 24 hours.')
        ];
    }
}