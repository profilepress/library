<?php
/**
* Linkify custom field with the field key fs_listings
*/
add_filter('ppress_drag_drop_profile_listing_item', function ($parsed_shortcode, $field_type, $field_key) {
  if(strpos($field_type, 'profile-cpf') !== false && $field_key == 'fs_listings') {
    $parsed_shortcode = sprintf('<a href="%1$s">%1$s</a>', $parsed_shortcode);
  }
  return $parsed_shortcode;
}, 10, 3);
