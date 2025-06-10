<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        
        $cartItems = CartItem::with(['product.images'])
            ->where('cart_id', $cart->id)
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Calculate tax (assuming 10% tax rate)
        $taxRate = 0.10;
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'total',
            'taxRate'
        ));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('Product is not available')
            ], 400);
        }

        if (!$product->is_in_stock || $product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => __('Insufficient stock available')
            ], 400);
        }

        try {
            DB::beginTransaction();

            $cart = $this->getOrCreateCart();
            
            // Check if product already exists in cart
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;
                
                // Check stock availability for new quantity
                if ($product->stock_quantity < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => __('Cannot add more items. Only :stock items available', ['stock' => $product->stock_quantity])
                    ], 400);
                }

                $existingCartItem->update(['quantity' => $newQuantity]);
                $cartItem = $existingCartItem;
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price
                ]);
            }

            // Update cart totals
            $this->updateCartTotals($cart);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Product added to cart successfully'),
                    'cart_count' => $this->getCartItemsCount(),
                    'cart_total' => $cart->total_amount
                ]);
            }

            return redirect()->back()->with('success', __('Product added to cart successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to add product to cart')
                ], 500);
            }

            return redirect()->back()->with('error', __('Failed to add product to cart'));
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Verify cart item belongs to current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $product = $cartItem->product;

        // Check stock availability
        if ($product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => __('Only :stock items available', ['stock' => $product->stock_quantity])
            ], 400);
        }

        try {
            DB::beginTransaction();

            $cartItem->update(['quantity' => $request->quantity]);
            
            // Update cart totals
            $this->updateCartTotals($cartItem->cart);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Cart updated successfully'),
                    'item_total' => $cartItem->quantity * $cartItem->product->price,
                    'cart_total' => $cartItem->cart->total_amount
                ]);
            }

            return redirect()->back()->with('success', __('Cart updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('Failed to update cart')
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        // Verify cart item belongs to current user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $cart = $cartItem->cart;
            $cartItem->delete();
            
            // Update cart totals
            $this->updateCartTotals($cart);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('Item removed from cart'),
                'cart_count' => $this->getCartItemsCount(),
                'cart_total' => $cart->total_amount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => __('Failed to remove item from cart')
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            $cart = $this->getOrCreateCart();
            
            CartItem::where('cart_id', $cart->id)->delete();
            
            $cart->update([
                'total_items' => 0,
                'total_amount' => 0
            ]);

            return response()->json([
                'success' => true,
                'message' => __('Cart cleared successfully')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('Failed to clear cart')
            ], 500);
        }
    }

    /**
     * Get cart items count
     */
    public function getCartCount()
    {
        return response()->json([
            'count' => $this->getCartItemsCount()
        ]);
    }

    /**
     * Get cart items for AJAX
     */
    public function getCartItems()
    {
        $cart = $this->getOrCreateCart();
        
        $cartItems = CartItem::with(['product.images'])
            ->where('cart_id', $cart->id)
            ->get();

        return response()->json([
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'formatted_price' => $item->product->formatted_price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->primary_image_url,
                    'total' => $item->quantity * $item->product->price
                ];
            }),
            'total_items' => $cart->total_items,
            'total_amount' => $cart->total_amount
        ]);
    }

    /**
     * Get or create cart for current user
     */
    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'total_items' => 0,
                'total_amount' => 0
            ]
        );
    }

    /**
     * Update cart totals
     */
    private function updateCartTotals(Cart $cart)
    {
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        
        $totalItems = $cartItems->sum('quantity');
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $cart->update([
            'total_items' => $totalItems,
            'total_amount' => $totalAmount
        ]);
    }

    /**
     * Get total cart items count
     */
    private function getCartItemsCount()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        return $cart ? $cart->total_items : 0;
    }
}