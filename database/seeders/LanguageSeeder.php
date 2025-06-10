<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create language directories if they don't exist
        $langPath = resource_path('lang');
        
        if (!File::exists($langPath . '/en')) {
            File::makeDirectory($langPath . '/en', 0755, true);
        }
        
        if (!File::exists($langPath . '/si')) {
            File::makeDirectory($langPath . '/si', 0755, true);
        }

        // English translations
        $englishTranslations = [
            'auth' => [
                'login' => 'Login',
                'register' => 'Register',
                'logout' => 'Logout',
                'email' => 'Email Address',
                'password' => 'Password',
                'confirm_password' => 'Confirm Password',
                'remember_me' => 'Remember Me',
                'forgot_password' => 'Forgot Your Password?',
                'name' => 'Full Name',
                'phone' => 'Phone Number',
                'address' => 'Address',
            ],
            'navigation' => [
                'home' => 'Home',
                'products' => 'Products',
                'categories' => 'Categories',
                'cart' => 'Cart',
                'profile' => 'Profile',
                'dashboard' => 'Dashboard',
                'orders' => 'Orders',
                'logout' => 'Logout',
                'language' => 'Language',
            ],
            'product' => [
                'name' => 'Product Name',
                'description' => 'Description',
                'price' => 'Price',
                'category' => 'Category',
                'stock' => 'Stock',
                'add_to_cart' => 'Add to Cart',
                'out_of_stock' => 'Out of Stock',
                'view_details' => 'View Details',
            ],
            'cart' => [
                'title' => 'Shopping Cart',
                'empty' => 'Your cart is empty',
                'quantity' => 'Quantity',
                'total' => 'Total',
                'subtotal' => 'Subtotal',
                'checkout' => 'Checkout',
                'continue_shopping' => 'Continue Shopping',
                'remove' => 'Remove',
            ],
            'order' => [
                'history' => 'Order History',
                'number' => 'Order Number',
                'date' => 'Order Date',
                'status' => 'Status',
                'total' => 'Total Amount',
                'view' => 'View Order',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'checkout' => [
                'title' => 'Checkout',
                'shipping_address' => 'Shipping Address',
                'billing_address' => 'Billing Address',
                'payment_method' => 'Payment Method',
                'place_order' => 'Place Order',
                'order_summary' => 'Order Summary',
            ],
            'common' => [
                'welcome' => 'Welcome',
                'search' => 'Search',
                'filter' => 'Filter',
                'sort' => 'Sort',
                'save' => 'Save',
                'cancel' => 'Cancel',
                'edit' => 'Edit',
                'delete' => 'Delete',
                'create' => 'Create',
                'update' => 'Update',
                'back' => 'Back',
                'next' => 'Next',
                'previous' => 'Previous',
                'loading' => 'Loading...',
                'no_results' => 'No results found',
            ],
        ];

        // Sinhala translations
        $sinhalaTranslations = [
            'auth' => [
                'login' => 'පුරනය වන්න',
                'register' => 'ලියාපදිංචි වන්න',
                'logout' => 'ලොග් අවුට්',
                'email' => 'විද්‍යුත් ලිපිනය',
                'password' => 'මුරපදය',
                'confirm_password' => 'මුරපදය තහවුරු කරන්න',
                'remember_me' => 'මාව මතක තබා ගන්න',
                'forgot_password' => 'මුරපදය අමතකද?',
                'name' => 'සම්පූර්ණ නම',
                'phone' => 'දුරකථන අංකය',
                'address' => 'ලිපිනය',
            ],
            'navigation' => [
                'home' => 'මුල් පිටුව',
                'products' => 'නිෂ්පාදන',
                'categories' => 'වර්ග',
                'cart' => 'කරත්තය',
                'profile' => 'පැතිකඩ',
                'dashboard' => 'උපකරණ පුවරුව',
                'orders' => 'ඇණවුම්',
                'logout' => 'ලොග් අවුට්',
                'language' => 'භාෂාව',
            ],
            'product' => [
                'name' => 'නිෂ්පාදන නම',
                'description' => 'විස්තරය',
                'price' => 'මිල',
                'category' => 'වර්ගය',
                'stock' => 'තොගය',
                'add_to_cart' => 'කරත්තයට එක් කරන්න',
                'out_of_stock' => 'තොගයේ නැත',
                'view_details' => 'විස්තර බලන්න',
            ],
            'cart' => [
                'title' => 'සාප්පු කරත්තය',
                'empty' => 'ඔබේ කරත්තය හිස්ය',
                'quantity' => 'ප්‍රමාණය',
                'total' => 'මුළු එකතුව',
                'subtotal' => 'උප එකතුව',
                'checkout' => 'ගෙවීම',
                'continue_shopping' => 'සාප්පු යෑම දිගටම කරන්න',
                'remove' => 'ඉවත් කරන්න',
            ],
            'order' => [
                'history' => 'ඇණවුම් ඉතිහාසය',
                'number' => 'ඇණවුම් අංකය',
                'date' => 'ඇණවුම් දිනය',
                'status' => 'තත්වය',
                'total' => 'මුළු මුදල',
                'view' => 'ඇණවුම බලන්න',
                'pending' => 'පොරොත්තුවේ',
                'confirmed' => 'තහවුරු කළ',
                'shipped' => 'යවන ලද',
                'delivered' => 'භාර දුන්',
                'cancelled' => 'අවලංගු කළ',
            ],
            'checkout' => [
                'title' => 'ගෙවීම',
                'shipping_address' => 'යවන ලිපිනය',
                'billing_address' => 'බිල් ලිපිනය',
                'payment_method' => 'ගෙවීමේ ක්‍රමය',
                'place_order' => 'ඇණවුම දෙන්න',
                'order_summary' => 'ඇණවුම් සාරාංශය',
            ],
            'common' => [
                'welcome' => 'ස්වාගතයි',
                'search' => 'සොයන්න',
                'filter' => 'පෙරහන',
                'sort' => 'පිළිවෙල',
                'save' => 'සුරකින්න',
                'cancel' => 'අවලංගු කරන්න',
                'edit' => 'සංස්කරණය',
                'delete' => 'මකන්න',
                'create' => 'සාදන්න',
                'update' => 'යාවත්කාලීන කරන්න',
                'back' => 'පෙර',
                'next' => 'ඊළඟ',
                'previous' => 'පෙරට',
                'loading' => 'පූරණය වෙමින්...',
                'no_results' => 'ප්‍රතිඵල හමු නොවීය',
            ],
        ];

        // Write English translations
        foreach ($englishTranslations as $file => $translations) {
            File::put(
                $langPath . '/en/' . $file . '.php',
                "<?php\n\nreturn " . var_export($translations, true) . ";\n"
            );
        }

        // Write Sinhala translations
        foreach ($sinhalaTranslations as $file => $translations) {
            File::put(
                $langPath . '/si/' . $file . '.php',
                "<?php\n\nreturn " . var_export($translations, true) . ";\n"
            );
        }

        $this->command->info('Language files created successfully!');
    }
}