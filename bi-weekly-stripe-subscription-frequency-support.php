<?php
// Add support for charging every 2 weeks via Stripe in ProfilePress
add_filter('ppress_subscription_billing_frequency', function ($frequency) {
    $frequency['bi_weekly'] = __('Bi-Weekly', 'wp-user-avatar');

    return $frequency;
});


add_filter('ppress_stripe_billing_frequency_interval', function ($frequency, $billing_frequency) {
    if ('bi_weekly' == $billing_frequency) return '2';

    return $frequency;
}, 10, 2);

add_filter('ppress_stripe_billing_period_interval_count', function ($period, $billing_frequency) {
    if ('bi_weekly' == $billing_frequency) return 'week';

    return $period;
}, 10, 2);
