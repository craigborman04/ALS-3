<?php
// Place this file in your WordPress root directory and access it via your browser.
// Example: http://your-wordpress-site.com/debug-api.php

// Bootstrap WordPress
define( 'WP_USE_THEMES', false );
require( './wp-load.php' );

// Check if the user is an administrator
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You do not have permission to access this file.' );
}

header( 'Content-Type: application/json' );

function test_endpoint($endpoint_path) {
    echo "<h2>Testing Endpoint: {$endpoint_path}</h2>";
    $request = new WP_REST_Request( 'GET', $endpoint_path );
    $response = rest_do_request( $request );
    $data = rest_get_server()->response_to_data( $response, true );
    
    echo '<pre>';
    echo json_encode($data, JSON_PRETTY_PRINT);
    echo '</pre>';
    echo '<hr>';
}

echo "<h1>ALS Catalog API Debugger</h1>";

test_endpoint( '/als-catalog/v1/products' );
test_endpoint( '/als-catalog/v1/filter-options' );
test_endpoint( '/als-catalog/v1/quotes' );

exit;
