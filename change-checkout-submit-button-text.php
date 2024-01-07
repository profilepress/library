<?php
/**
 * Change the label or text of the checkout submit button
 */
add_filter('ppress_checkout_order_button_text', function() {
   return 'Buy Now'; 
});
