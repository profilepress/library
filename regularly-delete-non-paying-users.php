<?php

use ProfilePress\Core\Membership\Models\Customer\CustomerFactory;
use ProfilePress\Core\Membership\Models\Subscription\SubscriptionStatus;

add_action('init', function() {
    if (!wp_next_scheduled('check_profilepress_subscription_status')) {
        wp_schedule_event(time(), 'hourly', 'check_profilepress_subscription_status');
    }
});

// Hook for the cron job to execute.
add_action('check_profilepress_subscription_status', 'check_subscription_status_for_users');

/**
 * Function to check subscription status for registered users in batches.
 */
function check_subscription_status_for_users() {
    // Set batch size to process users in smaller chunks.
    $batch_size = 50;

    // Get users who registered more than 6 hours ago, in batches.
    $six_hours_ago = date('Y-m-d H:i:s', strtotime('-6 hours'));

    for ($offset = 0; ; $offset += $batch_size) {
        // Get users for the current batch who registered more than 6 hours ago.
        $args = [
            'number' => $batch_size,
            'offset' => $offset,
            'date_query' => [
                [
                    'column' => 'user_registered',
                    'before' => $six_hours_ago,
                ],
            ],
        ];
        $users = get_users($args);

        if (empty($users)) {
            break; // Stop if there are no more users to process.
        }

        // Process each user in the batch.
        foreach ($users as $user) {
            $user_id = $user->ID;

            // Check if the user has any paid subscription using ProfilePress API.
            if (function_exists('ppress_has_any_paid_subscription')) {
                $has_active_subscription = ppress_has_any_paid_subscription($user_id);

                if (!$has_active_subscription) {
                    wp_delete_user($user_id);
                }
            }
        }
    }
}

/**
 * Check if the user has any paid subscription using ProfilePress.
 *
 * @param int $user_id The user ID.
 * @return bool True if the user has any paid subscription, false otherwise.
 */
function ppress_has_any_paid_subscription($user_id) {

    $statuses = [
        SubscriptionStatus::ACTIVE,
        SubscriptionStatus::COMPLETED,
        SubscriptionStatus::CANCELLED,
        SubscriptionStatus::TRIALLING,
        SubscriptionStatus::EXPIRED
    ];

    $customer = CustomerFactory::fromUserId($user_id);

    $subs = $customer->get_subscriptions($statuses);

    return !empty($subs);
}
