<?php

add_filter('ppress_registration_validation', function ($reg_errors, $form_id, $user_data) {

    $valid_email_domain = 'ventura.aero';

    if(!empty($user_data['user_email'])) {

        $explode = explode('@', $user_data['user_email']);

        if(isset($explode[1]) && $explode[1] != $valid_email_domain) {
            $reg_errors->add('invalid_email_domain', __('Email address you are registering with is not supported.'));
        }
    }

    return $reg_errors;

}, 999999999, 3);
