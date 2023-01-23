<?php
/**
 * Use this code to extend the login session of passwordless login
 */
add_filter('ppress_passwordless_login_remember', '__return_true');
