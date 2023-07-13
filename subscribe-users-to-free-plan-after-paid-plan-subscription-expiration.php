<?php
/**
 * Subscribe customers to a free plan (with plan ID 13) after subscription to paid plans (10, 11, 12) expires
 */
add_action('ppress_subscription_expired', function($sub) {
    if(in_array($sub->get_id(), [10, 11, 12])) {
        ppress_subscribe_user_to_plan(13, $sub->get_customer_id());
    }
});
