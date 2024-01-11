<?php
/* Require Stripe to always collect billing information on Stripe offsite checkout page */
add_filter('ppress_stripe_create_session_args', function($create_session_args) {
  $create_session_args['billing_address_collection'] = 'required';

  return $create_session_args;
});
