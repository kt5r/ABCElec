<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Order;

class ProfileController extends BaseController
{
    public function __construct(){
        $this->middleware('auth');
        $this->applyLocaleMiddleware();
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile details.
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        $totalOrders = $user->orders()->count();
        $totalSpent = $user->orders()->where('status', 'completed')->sum('total_amount');
        $recentOrders = $user->orders()
            ->with(['orderItems.product'])
            ->latest()
            ->take(5)
            ->get();

        return view('profile.show', compact('user', 'totalOrders', 'totalSpent', 'recentOrders'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', __('profile.updated'));
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', __('profile.password_updated'));
    }

    /**
     * Display the user's order history.
     */
    public function orderHistory(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with(['orderItems.product.category'])
            ->latest()
            ->paginate(10);

        return view('profile.order-history', compact('orders'));
    }

    /**
     * Display a specific order details.
     */
    public function orderDetails(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Load order items with products
        $order->load(['orderItems.product']);

        // Handle reorder action
        if (request('action') === 'reorder') {
            // Add reorder logic here
            return redirect()->route('cart.index')->with('success', __('profile.items_added_to_cart'));
        }

        return view('profile.order-details', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (in_array($order->status, ['pending', 'processing']) && 
            $order->created_at->diffInHours() < 24) {
            
            $order->update(['status' => 'cancelled']);
            
            return redirect()->route('profile.order-history')
                ->with('success', __('profile.order_cancelled_successfully'));
        }
        
        return redirect()->back()
            ->with('error', __('profile.order_cannot_be_cancelled'));
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}