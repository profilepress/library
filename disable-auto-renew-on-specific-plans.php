<?php
/** Disable auto renewal on membership plans with the IDs in the array */
add_filter('ppress_subscription_is_auto_renew', function($result, $planEntity) {
  if(in_array($planEntity->get_id(), [33, 31])) { // Please replace 33, 31 with your plan IDs
    return false;
  }
  return $result;
            
}, 10, 2);
