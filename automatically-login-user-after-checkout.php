<?php

//Automatically Login user after Checkout

add_filter('ppress_autologin_after_checkout', '__return_true');
