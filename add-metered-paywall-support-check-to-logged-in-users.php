<?php
/* Give logged-in users free views from metered paywall addon */
 add_filter('ppress_metered_paywall_logged_in_user_check_support', '__return_true');
