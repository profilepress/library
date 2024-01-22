<?php
/**
* The code snippet below sets plan ID "2" as the default plan of group ID 9 on the group checkout plan selection field. 
*/
add_filter('ppress_default_plan_id', function ($default, $group) {
    if (method_exists($group, 'get_id') && $group->get_id() == 9) {
        $default = 2;
    }

    return $default;
}, 10, 2);
