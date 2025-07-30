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
 * Since everything within the plugin is registered via hooks, calling it now will initiate all of the hooks.
 */
function run_als_catalog() {
    $plugin = new Als_Catalog();
    $plugin->run();
}
run_als_catalog();

