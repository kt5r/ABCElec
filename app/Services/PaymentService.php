<?php

namespace App\Services;

use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment for an order
     */
    public function processPayment(Order $order, array $paymentData): array
    {
        try {
            // Simulate payment processing
            $paymentMethod = $paymentData['payment_method'];
            
            // Simulate different payment outcomes
            $success = $this->simulatePaymentGateway($paymentMethod, $order->total_amount);
            
            if ($success) {
                return [
                    'success' => true,
                    'transaction_id' => $this->generateTransactionId(),
                    'message' => 'Payment processed successfully',
                    'payment_method' => $paymentMethod,
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Payment failed. Please try again.',
                    'error_code' => 'PAYMENT_DECLINED',
                ];
            }

        } catch (Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'payment_data' => $paymentData
            ]);

            return [
                'success' => false,
                'message' => 'Payment processing error. Please try again.',
                'error_code' => 'PAYMENT_ERROR',
            ];
        }
    }

    /**
     * Simulate payment gateway response
     */
    private function simulatePaymentGateway(string $paymentMethod, float $amount): bool
    {
        // Simulate processing delay
        usleep(rand(500000, 2000000)); // 0.5-2 seconds

        // Simulate success rate based on payment method
        $successRates = [
            'credit_card' => 0.95,
            'debit_card' => 0.92,
            'paypal' => 0.98,
            'bank_transfer' => 0.90,
        ];

        $successRate = $successRates[$paymentMethod] ?? 0.85;
        
        // Simulate failure for very high amounts (over $10,000)
        if ($amount > 10000) {
            $successRate *= 0.7;
        }

        return (rand(1, 100) / 100) <= $successRate;
    }

    /**
     * Generate a unique transaction ID
     */
    private function generateTransactionId(): string
    {
        return 'TXN_' . strtoupper(uniqid()) . '_' . time();
    }

    /**
     * Validate payment data
     */
    public function validatePaymentData(array $paymentData): array
    {
        $errors = [];

        if (in_array($paymentData['payment_method'], ['credit_card', 'debit_card'])) {
            // Validate card number (basic Luhn algorithm check)
            if (!$this->isValidCardNumber($paymentData['card_number'] ?? '')) {
                $errors[] = 'Invalid card number';
            }

            // Validate expiry date
            if (!$this->isValidExpiryDate($paymentData['card_expiry'] ?? '')) {
                $errors[] = 'Invalid or expired card';
            }
        }

        return $errors;
    }

    /**
     * Basic Luhn algorithm for card number validation
     */
    private function isValidCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }

        $sum = 0;
        $isEven = false;

        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = intval($cardNumber[$i]);

            if ($isEven) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;
            $isEven = !$isEven;
        }

        return ($sum % 10) === 0;
    }

    /**
     * Validate card expiry date
     */
    private function isValidExpiryDate(string $expiry): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry, $matches)) {
            return false;
        }

        $month = intval($matches[1]);
        $year = intval('20' . $matches[2]);
        $currentYear = intval(date('Y'));
        $currentMonth = intval(date('m'));

        // Check if the card has expired
        if ($year < $currentYear || ($year === $currentYear && $month < $currentMonth)) {
            return false;
        }

        // Check if expiry is not too far in the future (more than 10 years)
        if ($year > $currentYear + 10) {
            return false;
        }

        return true;
    }

    /**
     * Process refund for an order
     */
    public function processRefund(Order $order, float $amount = null): array
    {
        try {
            $refundAmount = $amount ?? $order->total_amount;
            
            // Simulate refund processing
            $success = rand(1, 100) <= 95; // 95% success rate for refunds

            if ($success) {
                return [
                    'success' => true,
                    'refund_id' => $this->generateTransactionId(),
                    'amount' => $refundAmount,
                    'message' => 'Refund processed successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Refund processing failed. Please contact support.',
                ];
            }

        } catch (Exception $e) {
            Log::error('Refund processing failed: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'amount' => $amount,
            ]);

            return [
                'success' => false,
                'message' => 'Refund processing error. Please contact support.',
            ];
        }
    }
}