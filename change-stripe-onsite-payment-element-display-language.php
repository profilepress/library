<?php
/** Code below changes the display language of the payment element to German. */
add_filter('ppress_stripe_js_args', function ($args) {
  $args['locale'] = 'de';
  return $args;
});
