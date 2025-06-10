<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->total_items == 0) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty'));
        }

        $cartItems = CartItem::with(['product.images'])
            ->where('cart_id', $cart->id)
            ->get();

        // Verify all items are still available
        foreach ($cartItems as $item) {
            if (!$item->product->is_active || !$item->product->is_in_stock) {
                return redirect()->route('cart.index')
                    ->with('error', __('Some items in your cart are no longer available'));
            }

            if ($item->product->stock_quantity < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', __('Some items in your cart have insufficient stock'));
            }
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

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
    public function process(Request $request)
    {
        $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'terms_accepted' => 'required|accepted'
        ]);

        // Validate shipping address if different from billing
        if ($request->has('different_shipping') && $request->different_shipping) {
            $request->validate([
                'shipping_first_name' => 'required|string|max:255',
                'shipping_last_name' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'required|string|max:100',
                'shipping_state' => 'required|string|max:100',
                'shipping_postal_code' => 'required|string|max:20',
                'shipping_country' => 'required|string|max:100',
            ]);
        }

        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->total_items == 0) {
            return redirect()->route('cart.index')
                ->with('error', __('Your cart is empty'));
        }

        try {
            DB::beginTransaction();

            $cartItems = CartItem::with('product')
                ->where('cart_id', $cart->id)
                ->get();

            // Verify stock availability one more time
            foreach ($cartItems as $item) {
                if (!$item->product->is_active || 
                    $item->product->stock_quantity < $item->quantity) {
                    throw new \Exception(__('Product :name is no longer available or has insufficient stock', 
                        ['name' => $item->product->name]));
                }
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            $taxRate = 0.10;
            $tax = $subtotal * $taxRate;
            $shippingCost = $this->calculateShipping($subtotal);
            $total = $subtotal + $tax + $shippingCost;

            // Create order
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
                
                // Billing address
                'billing_first_name' => $request->billing_first_name,
                'billing_last_name' => $request->billing_last_name,
                'billing_email' => $request->billing_email,
                'billing_phone' => $request->billing_phone,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_state' => $request->billing_state,
                'billing_postal_code' => $request->billing_postal_code,
                'billing_country' => $request->billing_country,
                
                // Shipping address
                'shipping_first_name' => $request->different_shipping ? 
                    $request->shipping_first_name : $request->billing_first_name,
                'shipping_last_name' => $request->different_shipping ? 
                    $request->shipping_last_name : $request->billing_last_name,
                'shipping_address' => $request->different_shipping ? 
                    $request->shipping_address : $request->billing_address,
                'shipping_city' => $request->different_shipping ? 
                    $request->shipping_city : $request->billing_city,
                'shipping_state' => $request->different_shipping ? 
                    $request->shipping_state : $request->billing_state,
                'shipping_postal_code' => $request->different_shipping ? 
                    $request->shipping_postal_code : $request->billing_postal_code,
                'shipping_country' => $request->different_shipping ? 
                    $request->shipping_country : $request->billing_country,
                
                'notes' => $request->notes
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

            // Clear cart
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->update([
                'total_items' => 0,
                'total_amount' => 0
            ]);

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
            'card_cvv' => 'required|string|min:3|max:4',
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