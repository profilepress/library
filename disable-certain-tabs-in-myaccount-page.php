<?php
/** Code snippet below disables subscription, orders, downloads and billing tabs from the My Account page */
add_filter('ppress_myaccount_tabs', function($tabs) {
  unset($tabs['list-subscriptions']);
  unset($tabs['list-orders']);
  unset($tabs['list-downloads']);
  unset($tabs['billing-details']);

  return $tabs;
});
