<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show user profile dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get order statistics
        $orderStats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'pending_orders' => Order::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'completed_orders' => Order::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount')
        ];

        return view('profile.index', compact('user', 'recentOrders', 'orderStats'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female,other',
            
            // Address fields
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            
            // Avatar upload
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            // Password change (optional)
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                    unlink(public_path('storage/' . $user->avatar));
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            // Update basic information
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'avatar' => $user->avatar,
            ]);

            // Handle password change
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()
                        ->withErrors(['current_password' => __('Current password is incorrect')])
                        ->withInput();
                }

                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);

                // Log out other sessions for security
                Auth::logoutOtherDevices($request->new_password);
            }

            return redirect()->route('profile.index')
                ->with('success', __('Profile updated successfully'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Failed to update profile. Please try again.'));
        }
    }

    /**
     * Show user's order history
     */
    public function orders(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id)
            ->with(['orderItems.product.images']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'LIKE', '%' . $request->search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        // Get available statuses for filter
        $statuses = Order::where('user_id', $user->id)
            ->distinct()
            ->pluck('status')
            ->toArray();

        return view('profile.orders', compact('orders', 'statuses', 'request'));
    }

    /**
     * Show specific order details
     */
    public function showOrder(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403, __('You are not authorized to view this order'));
        }

        $order->load(['orderItems.product.images']);

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Cancel an order (if allowed)
     */
    public function cancelOrder(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'confirmed'])) {
            return redirect()->back()
                ->with('error', __('This order cannot be cancelled'));
        }

        try {
            // Restore product stock
            foreach ($order->orderItems as $item) {
                $item->product->increment('stock_quantity', $item->quantity);
            }

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Cancelled by customer'
            ]);

            return redirect()->route('profile.orders')
                ->with('success', __('Order cancelled successfully'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('Failed to cancel order. Please contact support.'));
        }
    }

    /**
     * Reorder items from a previous order
     */
    public function reorder(Order $order)
    {
        // Verify order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => Auth::id()],
                ['total_items' => 0, 'total_amount' => 0]
            );

            $addedItems = 0;
            $unavailableItems = [];

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                // Check if product is still available
                if (!$product->is_active || !$product->is_in_stock) {
                    $unavailableItems[] = $product->name;
                    continue;
                }

                // Check if sufficient stock is available
                $availableQuantity = min($orderItem->quantity, $product->stock_quantity);
                
                if ($availableQuantity > 0) {
                    // Check if product already exists in cart
                    $existingCartItem = \App\Models\CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->first();

                    if ($existingCartItem) {
                        $newQuantity = min(
                            $existingCartItem->quantity + $availableQuantity,
                            $product->stock_quantity
                        );
                        $existingCartItem->update(['quantity' => $newQuantity]);
                    } else {
                        \App\Models\CartItem::create([
                            'cart_id' => $cart->id,
                            'product_id' => $product->id,
                            'quantity' => $availableQuantity,
                            'price' => $product->price
                        ]);
                    }

                    $addedItems++;
                }

                if ($availableQuantity < $orderItem->quantity) {
                    $unavailableItems[] = $product->name . ' (limited stock)';
                }
            }

            // Update cart totals
            $this->updateCartTotals($cart);

            $message = __(':count items added to cart', ['count' => $addedItems]);
            
            if (!empty($unavailableItems)) {
                $message .= '. ' . __('Some items were not available: :items', 
                    ['items' => implode(', ', $unavailableItems)]);
            }

            return redirect()->route('cart.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('Failed to add items to cart'));
        }
    }

    /**
     * Update cart totals
     */
    private function updateCartTotals($cart)
    {
        $cartItems = \App\Models\CartItem::where('cart_id', $cart->id)->get();
        
        $totalItems = $cartItems->sum('quantity');
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $cart->update([
            'total_items' => $totalItems,
            'total_amount' => $totalAmount
        ]);
    }
}