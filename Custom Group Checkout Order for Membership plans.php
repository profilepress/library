// Custom Group Checkout Order for Membership plans

add_filter('ppress_checkout_group_selector_plans', function ($plan_ids, $group) {

    // Please ensure all IDs are valid
     return [14, 15, 13];
}, 10, 2);
