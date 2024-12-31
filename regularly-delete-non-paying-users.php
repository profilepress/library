<?php

use ProfilePress\Core\Membership\Models\Customer\CustomerFactory;
use ProfilePress\Core\Membership\Models\Subscription\SubscriptionStatus;
use ProfilePress\Core\Membership\Repositories\CustomerRepository;

add_action('admin_init', function () {
    check_ppress_subscription_status_for_users();
});

/**
 * Function to check subscription status for registered users in batches.
 */
function check_ppress_subscription_status_for_users()
{
    // Set batch size to process users in smaller chunks.
    $batch_size = 50;

    // Get users who registered more than 6 hours ago, in batches.
    $six_hours_ago = date('Y-m-d H:i:s', strtotime('-6 hours'));

    for ($offset = 0; ; $offset += $batch_size) {
        // Get users for the current batch who registered more than 6 hours ago.
        $args = [
            'number'     => $batch_size,
            'offset'     => $offset,
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

                if ( ! $has_active_subscription && ! user_can($user_id, 'manage_options')) {

                    wp_delete_user($user_id);

                    CustomerRepository::init()->delete(
                        CustomerFactory::fromUserId($user_id)->get_id()
                    );
                }
            }
        }
    }
}

/**
 * Check if the user has any paid subscription using ProfilePress.
 *
 * @param int $user_id The user ID.
 *
 * @return bool True if the user has any paid subscription, false otherwise.
 */
function ppress_has_any_paid_subscription($user_id)
{
    $statuses = [
        SubscriptionStatus::ACTIVE,
        SubscriptionStatus::COMPLETED,
        SubscriptionStatus::CANCELLED,
        SubscriptionStatus::TRIALLING,
        SubscriptionStatus::EXPIRED
    ];

    $customer = CustomerFactory::fromUserId($user_id);

    $subs = $customer->get_subscriptions($statuses);

    return ! empty($subs);
}
