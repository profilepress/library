<?php
/**
 * By default, Completed subscriptions (subscriptions whose status is set to "completed") are considered active.
 * This code snippet ensures they are considered inactive.
 */
add_filter('ppress_subscription_is_active', function($is_active, $sub_id, $sub) {
    if($sub->is_completed()) {
        $is_active = false;
    }

    return $is_active;

}, 10, 3);
