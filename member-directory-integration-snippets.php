<?php

/**
 * ProfilePress Member Directory Integration
 */
global $ppress_frontend_profile_user_obj;

/**
 * Shortcode to display user meta in ProfilePress member directory
 * 
 * Usage: [user_meta key="meta_key" output="list" default=""]
 */
function user_meta_shortcode($atts) {
    global $ppress_frontend_profile_user_obj;
    
    // Return empty if not in a member directory card
    if (!$ppress_frontend_profile_user_obj || !isset($ppress_frontend_profile_user_obj->ID)) {
        return '';
    }
    
    // Get user ID
    $user_id = $ppress_frontend_profile_user_obj->ID;
    
    // Process attributes
    $atts = shortcode_atts(array(
        'key'     => '',
        'output'  => 'text',
        'default' => '',
    ), $atts, 'user_meta');
    
    if (empty($atts['key'])) {
        return '';
    }
    
    // Get user meta
    $value = get_user_meta($user_id, $atts['key'], true);
    
    // Use default if value is empty
    if (empty($value) && !empty($atts['default'])) {
        $value = $atts['default'];
    }
    
    // Process output
    switch ($atts['output']) {
        case 'html':
            return wp_kses_post($value);
        case 'url':
            $url = esc_url($value);
            return '<a href="' . $url . '" rel="noopener">' . $url . '</a>';
        case 'email':
            $email = sanitize_email($value);
            return '<a href="mailto:' . $email . '">' . $email . '</a>';
        case 'list':
            return ppress_meta_list_output($value);
        case 'checkboxes':
            return ppress_meta_checkboxes_output($value);
        case 'postcode':
            return get_outward_postcode($value);
        default:
            return esc_html($value);
    }
}

/**
 * Convert line-break separated text into unordered list
 * 
 * @param string $value The meta value with line breaks
 * @return string HTML unordered list
 */
function ppress_meta_list_output($value) {
    // If empty, return empty string
    if (empty($value)) return '';
    
    // Split by line breaks
    $items = preg_split("/\r\n|\n|\r/", $value);
    
    // Filter out empty lines and sanitize
    $items = array_filter(array_map('trim', $items), 'strlen');
    
    // Return if no valid items
    if (empty($items)) return '';
    
    // Generate list items
    $list = '';
    foreach ($items as $item) {
        $list .= '<li>' . esc_html($item) . '</li>';
    }
    
    return '<ul class="pp-meta-list">' . $list . '</ul>';
}

/**
 * Convert line-break separated text into unordered list
 * 
 * @param string $value The meta value with line breaks
 * @return string HTML unordered list
 */
function ppress_meta_checkboxes_output($values) {
    // If empty, return empty string
    if (empty($values)) return '';
    
    // Generate list items, excluding 'Other' or 'other'
    $list = '';
    foreach ($values as $value) {
        $trimmed_value = trim($value);
        if (strtolower($trimmed_value) === 'other') {
            continue; // Skip this value
        }
        $list .= '<li>' . esc_html($trimmed_value) . '</li>';
    }
    
    // Return empty if all items were filtered out
    if (empty($list)) return '';
    
    return '<ul class="pp-meta-list">' . $list . '</ul>';
}

/**
 * Extracts the outward code (first part) of a UK postcode.
 *
 * @param string $full_postcode The complete UK postcode (e.g., 'DY12 1JR' or 'N1 3AB')
 * @return string The outward code (first part) of the postcode
 */
function get_outward_postcode($full_postcode) {
    // Trim whitespace from both ends
    $postcode = trim($full_postcode);
    
    // Find the position of the space (if any)
    $space_pos = strpos($postcode, ' ');
    
    if ($space_pos !== false) {
        // If there's a space, return everything before it
        return substr($postcode, 0, $space_pos);
    }
    
    // For postcodes without a space but with a number followed by letters (e.g., "DY121JR")
    if (preg_match('/^([A-Za-z]{1,2}\d[A-Za-z\d]?)\d[A-Za-z]{2}$/', $postcode, $matches)) {
        return strtoupper($matches[1]);
    }
    
    // For very short postcodes or unexpected formats, return the first part that matches the outward pattern
    if (preg_match('/^[A-Za-z]{1,2}\d[A-Za-z\d]?/', $postcode, $matches)) {
        return strtoupper($matches[0]);
    }
    
    // Fallback - return the original string if nothing matches
    return strtoupper($postcode);
}
add_shortcode('user_meta', 'user_meta_shortcode');





/**
 * Enclosing shortcode to conditionally display content if user meta exists
 * 
 * Usage: [meta_exists key="meta_key"]Content[/meta_exists]
 */
function meta_exists_shortcode($atts, $content = null) {
    global $ppress_frontend_profile_user_obj;
    
    // Return empty if not in a member directory card
    if (!$ppress_frontend_profile_user_obj || !isset($ppress_frontend_profile_user_obj->ID)) {
        return '';
    }
    
    // Get user ID
    $user_id = $ppress_frontend_profile_user_obj->ID;
    
    // Process attributes
    $atts = shortcode_atts(array(
        'key' => '',
    ), $atts, 'meta_exists');
    
    // Return empty if no key specified
    if (empty($atts['key'])) {
        return '';
    }
    
    // Check if meta exists and has a value
    $value = get_user_meta($user_id, $atts['key'], true);
    if (empty($value)) {
        return '';
    }
    
    // Process the content (which may contain other shortcodes)
    return do_shortcode($content);
}
add_shortcode('meta_exists', 'meta_exists_shortcode');




/**
 * ProfilePress Search Fields
 * 
 * This filter allows you to add custom fields to the ProfilePress member directory search.
 */
add_filter('ppress_member_directory_parsed_args', function($parsed_args) {
    // Add custom meta fields
    $custom_meta_fields = [
        'ppress_billing_city', // Billing city
        'ppress_billing_state', // Billing state
        'ppress_billing_postcode', // Billing postcode
        'qualifications', // Custom field for qualifications
        'other_qualifications', // Custom field for other qualifications
        'cimspa_membership_number', // CIMSPA membership number
    ];
    
    // Merge with existing meta fields
    if (is_array($parsed_args['search_meta_fields'])) {
        $parsed_args['search_meta_fields'] = array_merge(
            $parsed_args['search_meta_fields'],
            $custom_meta_fields
        );
    } else {
        $parsed_args['search_meta_fields'] = $custom_meta_fields;
    }
    
    // Add special handling for serialized qualifications
    add_filter('get_meta_sql', function($sql) use ($parsed_args) {
        if (!$parsed_args['is_search_query'] || empty($parsed_args['search_q'])) {
            return $sql;
        }
        
        global $wpdb;
        $search_term = $parsed_args['search_q'];
        
        // Pattern to match serialized array items (exact match)
        $serialized_pattern = '%s:' . strlen($search_term) . ':"' . $search_term . '"%';
        
        // Pattern to match serialized array items (partial match)
        $partial_pattern = '%' . $wpdb->esc_like($search_term) . '%';
        
        // Modify the WHERE clause
        $sql['where'] = str_replace(
            ')))',
            "))) OR ($wpdb->usermeta.meta_key = 'qualifications' AND " .
            "($wpdb->usermeta.meta_value LIKE '$serialized_pattern' OR " .
            "$wpdb->usermeta.meta_value LIKE '$partial_pattern'))",
            $sql['where']
        );
        
        return $sql;
    });
    //echo '<pre>';
    //print_r($parsed_args);
    //echo '</pre>';
    return $parsed_args;
});

/**
 * Filter to modify WP_User_Query arguments for ProfilePress member directory
 * 
 * This filter ensures that only approved members are shown in the directory.
 */
add_filter('ppress_member_directory_wp_user_args', function($args, $form_id, $directory_instance) {
    // Add meta query to only show approved members
    $args['meta_query'] = isset($args['meta_query']) ? $args['meta_query'] : [];
    
    $args['meta_query'][] = [
        'key' => 'member_approved',
        'value' => '1',
        'compare' => '='
    ];
    
    return $args;
}, 10, 3);
