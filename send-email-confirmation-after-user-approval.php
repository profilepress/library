<?php
/**
 * Only send email confirmation email after user is approved when both user moderation and email confirmation addons are active.
 */
add_action('ppress_after_approve_user', function ($user_id) {
    \ProfilePress\Libsodium\EmailConfirmation::get_instance()->send_email_confirmation('', [], $user_id);
});

add_action('ppress_before_registration', 'ppress_w3guy_remove_email_confirmation_hook');
add_action('ppress_before_checkout_registration', 'ppress_w3guy_remove_email_confirmation_hook');

function ppress_w3guy_remove_email_confirmation_hook()
{
    remove_action('ppress_after_registration', [\ProfilePress\Libsodium\EmailConfirmation::get_instance(), 'send_email_confirmation'], 10);
}
