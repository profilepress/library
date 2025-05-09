<?php
/* The code below removes the specified taxonomy from showing up in Restriction setting repeater in metered paywall setup */
add_filter('ppress_metered_paywall_settings_taxonomies', function ($taxes) {
    unset($taxes['category']);
    unset($taxes['post_tag']);
    unset($taxes['cuisines']);
    unset($taxes['ingredients']);

    return $taxes;
});
