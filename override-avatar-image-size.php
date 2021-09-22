<?php
/**
 * Overrides user profile or avatar image size.
 */
add_filter('ppress_user_avatar_image_size', function () {
    return 250;
});
