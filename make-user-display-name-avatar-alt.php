<?php

add_filter('ppress_avatar_img_alt', function($alt, $id_or_email) {
    if(is_numeric($id_or_email)) {
        $user  = get_user_by('id', $id_or_email);
    } else {
        $user  = get_user_by('email', $id_or_email);
    }

    if($user instanceof \WP_User) {
        $alt = 'alt="'.$user->display_name. '"';
    }

    return $alt;
  
}, 10, 2);
