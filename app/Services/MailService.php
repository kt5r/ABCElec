<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\OrderStatusUpdate;
use App\Mail\WelcomeUser;

class MailService
{
    public function sendOrderConfirmation(Order $order): void
    {
        try {
            Mail::to($order->user->email)->send(new OrderConfirmation($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function sendOrderStatusUpdate(Order $order, string $oldStatus): void
    {
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdate($order, $oldStatus));
        } catch (\Exception $e) {
            \Log::error('Failed to send order status update email', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeUser($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}