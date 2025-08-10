<?php

// ProfilePress Geo-Based Pricing

add_action( 'init', function () {
    if ( ! session_id() ) {
        session_start();
    }
}, 1 );

add_filter( 'ppress_membership_plan_price', function ( $price, $plan ) {

    // Only apply to a specific plan (e.g., ID 1), or remove this check to apply globally
    if ( $plan->get_id() != 1 ) {
        return $price;
    }

    // Detect country using session cache
    $country = '';
    if ( isset( $_SESSION['geo_country'] ) ) {
        $country = strtoupper( $_SESSION['geo_country'] );
    } else {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ( ! empty( $ip ) && $ip !== '127.0.0.1' && $ip !== '::1' ) {
            $response = wp_remote_get( "https://ipapi.co/{$ip}/country/" );
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $country = strtoupper( trim( wp_remote_retrieve_body( $response ) ) );
                $_SESSION['geo_country'] = $country;
            }
        }
    }

    // Define country-based overrides
    $country_prices = [
        'DE' => 30.00, // Germany
        'AU' => 45.00, // Australia
    ];

    // Only override price if there's a match
    if ( isset( $country_prices[ $country ] ) ) {
        $price = $country_prices[ $country ];
    }

    return number_format( (float) $price, 2, '.', '' );

}, 10, 2 );
