<?php
/* 
* Specify a url to redirect your users who only log in or signup to your site via social login
*/
add_filter('ppress_social_login_redirect', function() {
    return 'https://url-to-redirect-to-here.com';
});

/* 
* Specify a url to redirect users who is just registered via social login after they are auto-logged in
*/
add_filter('ppress_social_login_redirect', function($social_login_redirect, $provider, $user_id, $registration_flag) {

    if($registration_flag) {
        $social_login_redirect = 'https://url-to-redirect-to-here.com';
    }

    return $social_login_redirect;

}, 10, 4);
