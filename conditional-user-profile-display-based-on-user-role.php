<?PHP

add_shortcode( 'custom_user_view_profile', function( $atts ) {

    global $ppress_frontend_profile_user_obj;

    $current_user = $ppress_frontend_profile_user_obj;

    if($current_user && isset($current_user->roles)) {

        $user_roles = (array) $current_user->roles;

        if ( in_array( 'ppress_plan_3', $user_roles ) ) { // vendor user roles here
            return do_shortcode('[profilepress-user-profile id="2"]'); // vendor user profile shortcode here
        }
    }

    return do_shortcode('[profilepress-user-profile id="1"]'); // normal user profile shortcode here
});
