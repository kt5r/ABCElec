<?php

if (!function_exists('formatPrice')) {
    /**
     * Format price with currency symbol
     */
    function formatPrice($price, $currency = 'LKR'): string
    {
        return $currency . ' ' . number_format($price, 2);
    }
}

if (!function_exists('generateOrderNumber')) {
    /**
     * Generate unique order number
     */
    function generateOrderNumber(): string
    {
        return 'ABC-' . date('Y') . '-' . strtoupper(uniqid());
    }
}

if (!function_exists('generateSKU')) {
    /**
     * Generate SKU for products
     */
    function generateSKU(string $category, string $name): string
    {
        $categoryCode = strtoupper(substr($category, 0, 3));
        $nameCode = strtoupper(substr(str_replace(' ', '', $name), 0, 3));
        $randomNumber = rand(100, 999);
        
        return $categoryCode . '-' . $nameCode . '-' . $randomNumber;
    }
}

if (!function_exists('getImageUrl')) {
    /**
     * Get full image URL
     */
    function getImageUrl($imagePath): string
    {
        if (empty($imagePath)) {
            return asset('images/placeholder.jpg');
        }
        
        if (str_starts_with($imagePath, 'http')) {
            return $imagePath;
        }
        
        return asset('storage/images/' . $imagePath);
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text to specified length
     */
    function truncateText(string $text, int $length = 100): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
}

if (!function_exists('getOrderStatus')) {
    /**
     * Get order status with color class
     */
    function getOrderStatus(string $status): array
    {
        $statuses = [
            'pending' => ['label' => __('messages.pending'), 'class' => 'badge-warning'],
            'confirmed' => ['label' => __('messages.confirmed'), 'class' => 'badge-info'],
            'processing' => ['label' => __('messages.processing'), 'class' => 'badge-primary'],
            'shipped' => ['label' => __('messages.shipped'), 'class' => 'badge-secondary'],
            'delivered' => ['label' => __('messages.delivered'), 'class' => 'badge-success'],
            'cancelled' => ['label' => __('messages.cancelled'), 'class' => 'badge-danger'],
        ];
        
        return $statuses[$status] ?? ['label' => ucfirst($status), 'class' => 'badge-secondary'];
    }
}

if (!function_exists('getPaymentStatus')) {
    /**
     * Get payment status with color class
     */
    function getPaymentStatus(string $status): array
    {
        $statuses = [
            'pending' => ['label' => __('messages.pending'), 'class' => 'badge-warning'],
            'paid' => ['label' => __('messages.paid'), 'class' => 'badge-success'],
            'failed' => ['label' => __('messages.failed'), 'class' => 'badge-danger'],
            'refunded' => ['label' => __('messages.refunded'), 'class' => 'badge-info'],
        ];
        
        return $statuses[$status] ?? ['label' => ucfirst($status), 'class' => 'badge-secondary'];
    }
}

if (!function_exists('getStockStatus')) {
    /**
     * Get stock status with color class
     */
    function getStockStatus(int $quantity): array
    {
        if ($quantity <= 0) {
            return ['label' => __('messages.out_of_stock'), 'class' => 'text-danger'];
        } elseif ($quantity <= 10) {
            return ['label' => __('messages.low_stock'), 'class' => 'text-warning'];
        } else {
            return ['label' => __('messages.in_stock'), 'class' => 'text-success'];
        }
    }
}

if (!function_exists('calculateTax')) {
    /**
     * Calculate tax amount
     */
    function calculateTax(float $amount, float $taxRate = 0.15): float
    {
        return $amount * $taxRate;
    }
}

if (!function_exists('calculateDiscount')) {
    /**
     * Calculate discount amount
     */
    function calculateDiscount(float $amount, float $discountPercent): float
    {
        return $amount * ($discountPercent / 100);
    }
}

if (!function_exists('isActiveRoute')) {
    /**
     * Check if current route is active
     */
    function isActiveRoute(string $route): bool
    {
        return request()->routeIs($route);
    }
}

if (!function_calls('getUserRole')) {
    /**
     * Get user role display name
     */
    function getUserRole($user): string
    {
        if (!$user || !$user->roles) {
            return __('messages.no_role');
        }
        
        $role = $user->roles->first();
        return $role ? ucfirst(str_replace('_', ' ', $role->name)) : __('messages.no_role');
    }
}

if (!function_exists('canAccessBackOffice')) {
    /**
     * Check if user can access back office
     */
    function canAccessBackOffice($user): bool
    {
        if (!$user) {
            return false;
        }
        
        return $user->hasRole(['admin', 'operation_manager', 'sales_manager']);
    }
}

if (!function_exists('getCartItemCount')) {
    /**
     * Get cart item count for user
     */
    function getCartItemCount(): int
    {
        if (!auth()->check()) {
            return 0;
        }
        
        return auth()->user()->cartItems()->sum('quantity');
    }
}

if (!function_exists('sanitizeInput')) {
    /**
     * Sanitize user input
     */
    function sanitizeInput(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('isValidEmail')) {
    /**
     * Validate email address
     */
    function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

if (!function_exists('generateToken')) {
    /**
     * Generate secure random token
     */
    function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}