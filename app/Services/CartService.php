<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * Get all cart items for a user
     */
    public function getCartItems(User $user): Collection
    {
        return CartItem::with(['product.category'])
            ->where('user_id', $user->id)
            ->get();
    }

    /**
     * Add item to cart
     */
    public function addToCart(User $user, Product $product, int $quantity = 1): array
    {
        // Check if product is available
        if (!$product->is_active || $product->stock < $quantity) {
            return [
                'success' => false,
                'message' => 'Product is not available or insufficient stock.',
            ];
        }

        // Check if item already exists in cart
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if item exists
            $newQuantity = $cartItem->quantity + $quantity;
            
            // Check stock availability
            if ($newQuantity > $product->stock) {
                return [
                    'success' => false,
                    'message' => 'Cannot add more items. Insufficient stock available.',
                ];
            }

            $cartItem->update(['quantity' => $newQuantity]);
            $message = 'Cart updated successfully.';
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $message = 'Item added to cart successfully.';
        }

        return [
            'success' => true,
            'message' => $message,
        ];
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(User $user, int $cartItemId, int $quantity): array
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->with('product')
            ->first();

        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }

        if ($quantity <= 0) {
            return $this->removeFromCart($user, $cartItemId);
        }

        // Check stock availability
        if ($quantity > $cartItem->product->stock) {
            return [
                'success' => false,
                'message' => 'Insufficient stock available.',
            ];
        }

        $cartItem->update(['quantity' => $quantity]);

        return [
            'success' => true,
            'message' => 'Cart updated successfully.',
        ];
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(User $user, int $cartItemId): array
    {
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('id', $cartItemId)
            ->first();

        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Cart item not found.',
            ];
        }

        $cartItem->delete();

        return [
            'success' => true,
            'message' => 'Item removed from cart.',
        ];
    }

    /**
     * Clear all cart items for a user
     */
    public function clearCart(User $user): void
    {
        CartItem::where('user_id', $user->id)->delete();
    }

    /**
     * Get cart total
     */
    public function getCartTotal(User $user): float
    {
        return CartItem::where('user_id', $user->id)
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
    }

    /**
     * Get cart item count
     */
    public function getCartItemCount(User $user): int
    {
        return CartItem::where('user_id', $user->id)->sum('quantity');
    }

    /**
     * Validate cart before checkout
     */
    public function validateCart(User $user): array
    {
        $cartItems = $this->getCartItems($user);
        $errors = [];

        if ($cartItems->isEmpty()) {
            return [
                'valid' => false,
                'errors' => ['Your cart is empty.'],
            ];
        }

        foreach ($cartItems as $item) {
            // Check if product is still active
            if (!$item->product->is_active) {
                $errors[] = "Product '{$item->product->name}' is no longer available.";
                continue;
            }

            // Check stock availability
            if ($item->quantity > $item->product->stock) {
                $availableStock = $item->product->stock;
                $errors[] = "Only {$availableStock} units of '{$item->product->name}' are available.";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Get cart summary for checkout
     */
    public function getCartSummary(User $user): array
    {
        $cartItems = $this->getCartItems($user);
        $subtotal = 0;
        $totalItems = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->product->price;
            $totalItems += $item->quantity;
        }

        $tax = $subtotal * 0.1; // 10% tax
        $shipping = $subtotal > 100 ? 0 : 10; // Free shipping over $100
        $total = $subtotal + $tax + $shipping;

        return [
            'items' => $cartItems,
            'item_count' => $totalItems,
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'shipping' => round($shipping, 2),
            'total' => round($total, 2),
        ];
    }
}