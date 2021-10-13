<?php
/**
 * Increase content protection excerpt length or word limit.
 */
add_filter('ppress_content_protection_excerpt_length', function () {
	return 150;
});
