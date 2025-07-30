<?php
/**
 * Plugin Name: ALS Product Catalog
 * Plugin URI:  https://github.com/craigborman04/ALS-3
 * Description: Manages product catalog, product options, and quotes for ALS, integrating with a React frontend.
 * Version:     1.0.0
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

if (!function_exists('als_catalog_log')) {
    /**
     * A unique logger function for this plugin.
     */
    function als_catalog_log($message) {
        if (defined('WP_DEBUG') && WP_DEBUG === true && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }
}

// Define plugin constants
define( 'ALS_CATALOG_VERSION', '1.0.0' );
define( 'ALS_CATALOG_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALS_CATALOG_URL', plugin_dir_url( __FILE__ ) );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing hooks.
 */
require_once ALS_CATALOG_DIR . 'includes/class-als-catalog.php';

/**
 * Begins execution of the plugin.
 */
function run_als_catalog() {
    $plugin = new Als_Catalog();
    $plugin->run();
}
run_als_catalog();
