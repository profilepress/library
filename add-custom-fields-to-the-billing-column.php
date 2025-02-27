<?php
/* Add a custom field to the "Billed to" column of ProfilePress receipts */
add_action('ppress_receipt_billed_to_column_end', function($order) {
    echo '<div> Custom Field: ' . esc_html(get_user_meta($order->get_customer()->get_user_id(), 'custom_field_key', true)) . '</div>';
});
