<?php

/**
* Define url to redirect to after a user logs in via passwordless login
*/

add_filter('ppress_login_redirect', function($url, $form_type) {
    if($form_type == 'ppress_passwordless_login') {
        $url = 'url to redirect to here';
    }
    return $url;

}, 10, 2);
