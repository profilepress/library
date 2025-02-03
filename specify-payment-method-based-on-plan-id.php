<?php
/**
 * This lets you specify which payment method should be displayed for a specific plan based on its ID.
 */

add_filter('ppress_checkout_available_payment_methods', function($methods, $plan_id) {
    // Example: Restricting plan ID 123 to only use "paypal"

    if ($plan_id == 123) {
        return array_filter($methods, function($method) {
            return $method->get_id() === 'paypal'; // Change 'paypal' to the actual payment method ID like stripe, razorpay, or paystack
        });
    }
    
    return $methods;

}, 10, 2);
