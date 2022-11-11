<?php

add_filter('ppress_login_validation', function (\WP_Error $login_errors) {
    
    $email_domain = 'company.com';

    if (strstr($_POST['login_username'], '@' . $email_domain) !== false) {
        $login_errors->add('invalid_login', 'Invalid login email');
    }

    return $login_errors;
});
