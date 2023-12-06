<?php
/**
 * Redirect users/customers to referrer page after checkout/payment
 */
add_filter('ppress_checkout_redirect_to_referrer_after_payment', '__return_true');
