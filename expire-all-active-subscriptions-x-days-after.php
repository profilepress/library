<?php
/* Expires all active subscriptions of plan ID 5, 5 days after */
add_action('wp', function () {
    // Define the option name to store the last run timestamp
    $option_name = 'daily_subscription_expiry_last_run';

    // Get the last run time from the WordPress database
    $last_run_timestamp = get_option($option_name, 0);

    // Current time
    $current_time = time();

    // Check if 24 hours (1 day) have passed since the last execution
    if ($current_time - $last_run_timestamp < DAY_IN_SECONDS) {
        return; // Less than 1 day has passed, so exit
    }

    // Update the last run timestamp
    update_option($option_name, $current_time);

    $plans = [5];

    $users = get_users(); // Get all WordPress users

    foreach ($users as $user) {
        $user_id = $user->ID;

        $subs = CustomerFactory::fromUserId($user_id)->get_active_subscriptions();

        foreach ($subs as $sub) {
            if (in_array($sub->get_plan_id(), $plans)) {
                $created_date = $sub->created_date;

                $created_timestamp = strtotime($created_date . ' UTC');

                // Calculate the timestamp for 5 days ago in UTC
                $five_days_ago_timestamp = strtotime('-5 days UTC');

                // Check if the created date is 5 days ago or older
                if ($created_timestamp <= $five_days_ago_timestamp) {
                    $sub->expire();
                }
            }
        }
    }
});
