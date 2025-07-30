<?php
/**
 * ALS Catalog - Direct Database Debugger
 * 
 * Place this file in your WordPress root directory and access it via your browser.
 * Example: http://your-wordpress-site.com/debug-api.php
 */

// Bootstrap WordPress
define( 'WP_USE_THEMES', false );
require( './wp-load.php' );

// Security check: Only allow administrators to run this script.
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You do not have permission to access this file.' );
}

header( 'Content-Type: text/html; charset=utf-8' );

global $wpdb;

function test_table_query($table_name_suffix, $title) {
    global $wpdb;
    $table_name = $wpdb->prefix . $table_name_suffix;

    echo "<h2>Testing Table: `{$table_name}`</h2>";
    
    // Check if the table exists
    if($wpdb->get_var("SHOW TABLES LIKE '{$table_name}'") != $table_name) {
        echo "<p style='color: red;'><strong>Error: Table does not exist.</strong></p>";
        echo "<hr>";
        return;
    }

    $results = $wpdb->get_results( "SELECT * FROM `{$table_name}` LIMIT 15" );

    if ( $wpdb->last_error ) {
        echo "<p style='color: red;'><strong>Database Error:</strong> " . esc_html($wpdb->last_error) . "</p>";
    } elseif ( empty($results) ) {
        echo "<p style='color: orange;'><strong>Warning: Query was successful, but the table is empty.</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>Success! Found " . count($results) . " records.</strong></p>";
        echo '<pre>';
        print_r($results);
        echo '</pre>';
    }
    echo '<hr>';
}

echo "<h1>ALS Catalog - Direct Database Debugger</h1>";

test_table_query( 'als_catalog_products', 'Products' );
test_table_query( 'als_catalog_quotes', 'Quotes' );

exit;
