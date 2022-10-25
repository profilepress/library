<?php

/* Specify a url to redirect your users who only log in or signup to your site via social login
*/

add_filter('ppress_social_login_redirect', function() {
    return 'https://url-to-redirect-to-here.com';
});
