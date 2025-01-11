/**
 * By default, ProfilePress sets the subscription to Complete status (granting lifetime access) on a subscription with a total payment.
 * This code snippet ensures subscriptions are expired, removing the user's access after their last payment.
 */
add_action('ppress_subscription_completed', function($subscription) {
    $subscription->expire();
});
