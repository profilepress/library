<?php
/**
 * Customize the "You are already logged in".
 */
 
add_filter('ppress_login_form_already_loggedin_message', function() {
  return 'customize the text here';
});
