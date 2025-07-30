<?php
/**
 * Plugin Name: ALS Product Catalog
 * Plugin URI:  https://github.com/craigborman04/ALS-3
 * Description: Manages product catalog, product options, and quotes for ALS, integrating with a React frontend.
 * Version:     1.0.2
 * Author:      ALS Team
 * Author URI:  https://example.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: als-catalog
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// ===================================================================================
//  TEMPORARY DEBUGGER - This will be removed once the issue is solved.
// ===================================================================================
add_action('admin_init', function() {
    if (isset($_GET['page']) && $_GET['page'] === 'als-catalog-products') {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        echo "<h1>Direct Database Test</h1>";
        echo "<p>Attempting to query the table: <strong>{$table_name}</strong></p>";

        $results = $wpdb->get_results("SELECT * FROM `{$table_name}` LIMIT 5");

        if ($wpdb->last_error) {
            echo "<h2><strong style='color: red;'>DATABASE ERROR:</strong></h2>";
            echo "<pre>" . esc_html($wpdb->last_error) . "</pre>";
        } elseif (is_null($results)) {
            echo "<h2><strong style='color: orange;'>QUERY FAILED:</strong></h2>";
            echo "<p>The query returned NULL. This can happen if the table doesn't exist or there's a severe issue.</p>";
        } elseif (empty($results)) {
            echo "<h2><strong style='color: orange;'>QUERY SUCCESSFUL, BUT NO RESULTS:</strong></h2>";
            echo "<p>The query ran without errors, but the table appears to be empty.</p>";
        } else {
            echo "<h2><strong style='color: green;'>QUERY SUCCESSFUL!</strong></h2>";
            echo "<p>Here is the raw data from the first 5 products:</p>";
            echo "<pre>";
            print_r($results);
            echo "</pre>";
        }
        
        // Stop all other execution
        wp_die(); 
    }
});
// ===================================================================================
//  END TEMPORARY DEBUGGER
// ===================================================================================


if (!function_exists('als_catalog_log')) {
    function als_catalog_log($message) {
        if (defined('WP_DEBUG') && WP_DEBUG === true && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true) {
            error_log(is_array($message) || is_object($message) ? print_r($message, true) : $message);
        }
    }
}

// Define plugin constants
define( 'ALS_CATALOG_VERSION', '1.0.2' );
define( 'ALS_CATALOG_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALS_CATALOG_URL', plugin_dir_url( __FILE__ ) );

// Core plugin class
require_once ALS_CATALOG_DIR . 'includes/class-als-catalog.php';

// Activation and deactivation hooks
register_activation_hook( __FILE__, array( 'Als_Catalog', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Als_Catalog', 'deactivate' ) );

// Begins execution of the plugin.
function run_als_catalog() {
    $plugin = new Als_Catalog();
    $plugin->run();
}
run_als_catalog();
