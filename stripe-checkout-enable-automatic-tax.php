<?php
/**
 * Use this snippet to enable automatic tax calculation on Stripe offsite checkout
 */
add_filter('ppress_stripe_create_session_args', function($create_session_args) {
	$create_session_args['automatic_tax'] = ['enabled' => true];              
	return $create_session_args;
});
