<?php

add_filter('ppress_before_social_signup_init', function ($response, $user_data) {

    $valid_email_domain = 'wordpress.test';

    if(!empty($user_data['user_email'])) {

        $explode = explode('@', $user_data['user_email']);

        if(isset($explode[1]) && $explode[1] != $valid_email_domain) {
            $response = new \WP_Error('invalid_email_domain', __('Email address you are registering with is not supported.'));
        }
    }

    return $response;

}, 999999999, 2);
