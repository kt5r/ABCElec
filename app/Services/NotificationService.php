<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;

class NotificationService
{
    public function sendOrderNotification(Order $order, string $type = 'created'): void
    {
        $message = $this->getOrderMessage($order, $type);
        
        // Store notification in session for now (could be database in production)
        session()->flash('notification', [
            'type' => $type === 'created' ? 'success' : 'info',
            'title' => $this->getNotificationTitle($type),
            'message' => $message,
            'timestamp' => now(),
        ]);
    }

    public function sendUserNotification(User $user, string $type, string $message): void
    {
        session()->flash('notification', [
            'type' => $type,
            'title' => $this->getUserNotificationTitle($type),
            'message' => $message,
            'timestamp' => now(),
        ]);
    }

    public function sendSystemNotification(string $type, string $message): void
    {
        session()->flash('notification', [
            'type' => $type,
            'title' => 'System Notification',
            'message' => $message,
            'timestamp' => now(),
        ]);
    }

    private function getOrderMessage(Order $order, string $type): string
    {
        return match($type) {
            'created' => "Order #{$order->order_number} has been successfully placed.",
            'updated' => "Order #{$order->order_number} status has been updated to {$order->status}.",
            'cancelled' => "Order #{$order->order_number} has been cancelled.",
            'shipped' => "Order #{$order->order_number} has been shipped and is on its way.",
            'delivered' => "Order #{$order->order_number} has been delivered successfully.",
            default => "Order #{$order->order_number} has been {$type}.",
        };
    }

    private function getNotificationTitle(string $type): string
    {
        return match($type) {
            'created' => 'Order Placed',
            'updated' => 'Order Updated',
            'cancelled' => 'Order Cancelled',
            'shipped' => 'Order Shipped',
            'delivered' => 'Order Delivered',
            default => 'Order Notification',
        };
    }

    private function getUserNotificationTitle(string $type): string
    {
        return match($type) {
            'success' => 'Success',
            'error' => 'Error',
            'warning' => 'Warning',
            'info' => 'Information',
            default => 'Notification',
        };
    }

    public function getRecentNotifications(): array
    {
        // In production, this would fetch from database
        $notification = session('notification');
        return $notification ? [$notification] : [];
    }

    public function markAsRead(string $notificationId): void
    {
        // In production, this would update database
        session()->forget('notification');
    }
}