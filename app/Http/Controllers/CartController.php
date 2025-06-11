<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
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
    public function add(Request $request, $product)
    {
        $product = Product::where('id', $product)->first();
        // Check if product is active and in stock
        if (!$product->status) {
            return redirect()->back()->with('error', __('Product is not available'));
        }

        if (!$product->in_stock || $product->stock_quantity < $request->quantity) {
            return redirect()->back()->with('error', __('Insufficient stock available'));
        }

        try {
            DB::beginTransaction();

            // Check if product already exists in user's cart
            $existingCartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if ($existingCartItem) {
                $newQuantity = $existingCartItem->quantity + $request->quantity;
                
                // Check stock availability for new quantity
                if ($product->stock_quantity < $newQuantity) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => __('Cannot add more items. Only :stock items available', ['stock' => $product->stock_quantity])
                        ]);
                    }
                    return redirect()->back()->with('error', __('Cannot add more items. Only :stock items available', ['stock' => $product->stock_quantity]));
                }

                $existingCartItem->update(['quantity' => $newQuantity]);
            } else {
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price
                ]);
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Product added to cart successfully'),
                    'cart_count' => $this->getCartItemsCount(),
                    'cart_total' => $this->getCartTotal()
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
    public function update(Request $request, $cartItem)
    {
        $cartItem = CartItem::where('id', $cartItem)->first();

        // Verify cart item belongs to current user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        $product = $cartItem->product;
        
        // Determine new quantity based on action
        $action = $request->input('action', 'update'); // 'update', 'increase', 'decrease'
        
        switch ($action) {
            case 'decrease':
                $newQuantity = max(1, $cartItem->quantity - 1);
                break;
            case 'increase':
                $newQuantity = $cartItem->quantity + 1;
                break;
            default:
                $newQuantity = $request->quantity ?? $cartItem->quantity;
                break;
        }

        // Validate quantity
        if ($newQuantity < 1) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Quantity must be at least 1')
                ], 400);
            }
            return redirect()->back()->with('error', __('Quantity must be at least 1'));
        }

        // Check stock availability
        if ($product->stock_quantity < $newQuantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Only :stock items available', ['stock' => $product->stock_quantity])
                ], 400);
            }
            return redirect()->back()->with('error', __('Only :stock items available', ['stock' => $product->stock_quantity]));
        }

        try {
            DB::beginTransaction();

            $cartItem->update(['quantity' => $newQuantity]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('Cart updated successfully'),
                    'item_total' => $cartItem->quantity * $cartItem->price,
                    'cart_total' => $this->getCartTotal(),
                    'cart_count' => $this->getCartItemsCount()
                ]);
            }

            return redirect()->back()->with('success', __('Cart updated successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Failed to update cart')
                ], 500);
            }
            return redirect()->back()->with('error', __('Failed to update cart'));
        }
    }

    /**
     * Remove item from cart
     */
    public function remove(CartItem $cartItem)
    {
        // Verify cart item belongs to current user
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            DB::beginTransaction();

            $cartItem->delete();

            DB::commit();

            return redirect()->back()->with('error', __('Removed from the cart'));

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', __('Failed to remove the item'));
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
            DB::beginTransaction();

            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();
            
            return redirect()->back()->with('error', __('Cart cleared successfully'));

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', __('Failed to clear cart'));
        }
    }

    /**
     * Get cart items for AJAX
     */
    public function getCartItems()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'formatted_price' => 'LKR ' . number_format($item->price, 2),
                    'quantity' => $item->quantity,
                    'image' => $item->product->primary_image_url ?? '',
                    'subtotal' => $item->subtotal,
                    'formatted_subtotal' => $item->formatted_subtotal
                ];
            }),
            'cart_count' => $this->getCartItemsCount(),
            'cart_total' => $this->getCartTotal()
        ]);
    }

    /**
     * Get total number of items in cart
     */
    private function getCartItemsCount()
    {
        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }

    /**
     * Get cart total amount
     */
    private function getCartTotal()
    {
        return CartItem::where('user_id', Auth::id())
            ->get()
            ->sum('subtotal');
    }

    /**
     * Get formatted cart total
     */
    private function getFormattedCartTotal()
    {
        return 'LKR ' . number_format($this->getCartTotal(), 2);
    }
}