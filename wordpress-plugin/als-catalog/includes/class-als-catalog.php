<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that holds all of the hooks that display and enqueue the admin-specific assets.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Als_Catalog
 * @subpackage Als_Catalog/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Als_Catalog
 * @subpackage Als_Catalog/includes
 * @author     ALS Team <example@example.com>
 */
class Als_Catalog {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      Als_Catalog_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'als-catalog';
        $this->version = ALS_CATALOG_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        // Add deactivation hook
        register_deactivation_hook( ALS_CATALOG_DIR . 'als-catalog.php', array( $this, 'deactivate' ) );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        $admin = new Als_Catalog_Admin($this->plugin_name, $this->version);

        add_action( 'plugins_loaded', array( $this, 'load_dependencies' ) );
        register_activation_hook( ALS_CATALOG_DIR . 'als-catalog.php', array( $this, 'activate' ) );
        
        // Add REST API routes
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

        // Add menu
        add_action( 'admin_menu', array( $admin, 'add_plugin_admin_menu' ) );
        
        // Add action to save product
        add_action( 'admin_post_als_catalog_save_product', array( $admin, 'save_product' ) );
        
        // Add action to save quote
        add_action( 'admin_post_als_catalog_save_quote', array( $admin, 'save_quote' ) );
        
        // Add dashboard widget
        add_action( 'wp_dashboard_setup', array( $admin, 'add_dashboard_widgets' ) );

    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {
        add_shortcode( 'als_product_catalog', array( $this, 'render_product_catalog_shortcode' ) );
    }

    /**
     * Load the required dependencies for this plugin.
     * Include the following files that make up the plugin.
     * @since    1.0.0
     * @access   public
     */
    public function load_dependencies() {
        require_once ALS_CATALOG_DIR . 'includes/class-als-catalog-rest-api.php';
        require_once ALS_CATALOG_DIR . 'admin/class-als-catalog-admin.php';
    }

    /**
     * Register the REST API routes.
     * @since    1.0.0
     */
    public function register_rest_routes() {
        $api = new Als_Catalog_Rest_Api();
        $api->register_routes();
    }
    
    /**
     * Render the product catalog shortcode using an iframe.
     *
     * @since    1.0.0
     */
    public function render_product_catalog_shortcode( $atts ) {
        // Pass the REST API URL to the iframe via a query parameter
        $iframe_src = add_query_arg(
            ['apiUrl' => rest_url('als-catalog/v1/')],
            ALS_CATALOG_URL . 'out/index.html'
        );
        return '<iframe src="' . esc_url($iframe_src) . '" style="width: 100%; height: 1200px; border: none;"></iframe>';
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     * @since    1.0.0
     */
    public function run() {
        // This method would typically call $this->loader->run(); if you had a loader class.
        // For simplicity, we're registering hooks directly in __construct for now.
    }

    /**
     * Fired during plugin activation.
     * @since    1.0.0
     */
    public function activate() {
        $this->create_als_tables();
        $this->import_csv_data();
    }
    
    /**
     * Fired during plugin deactivation.
     * This will clean up the database tables.
     * @since    1.0.0
     */
    public function deactivate() {
        // Tables are no longer dropped on deactivation to preserve data.
    }


    /**
     * Create custom database tables for ALS Catalog.
     * @since    1.0.0
     * @access   private
     */
    private function create_als_tables() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $charset_collate = $wpdb->get_charset_collate();

        // Table for wp_als_catalog_products
        $table_name = $wpdb->prefix . 'als_catalog_products';
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            color_name VARCHAR(255) NULL,
            color_hex VARCHAR(7) NULL,
            color_description TEXT NULL,
            category VARCHAR(255) NULL,
            tags TEXT NULL,
            is_active TINYINT(1) DEFAULT 1 NOT NULL,
            sort_order INT(11) DEFAULT 0 NOT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            slug VARCHAR(255) NULL,
            description TEXT NULL,
            image_url VARCHAR(255) NULL,
            PRIMARY KEY (id),
            UNIQUE KEY slug (slug)
        ) $charset_collate;";
        dbDelta( $sql );

        // Table for wp_als_catalog_product_options
        $table_name = $wpdb->prefix . 'als_catalog_product_options';
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id BIGINT(20) UNSIGNED NOT NULL,
            size VARCHAR(255) NULL,
            closure_type VARCHAR(255) NULL,
            color VARCHAR(255) NULL,
            color_hex VARCHAR(7) NULL,
            part_number VARCHAR(255) NULL,
            price_modifier DECIMAL(10,2) NULL,
            capacity VARCHAR(255) NULL,
            dimensions VARCHAR(255) NULL,
            weight VARCHAR(255) NULL,
            is_active TINYINT(1) DEFAULT 1 NOT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id),
            KEY product_id (product_id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Table for wp_als_catalog_quotes
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            product_id BIGINT(20) UNSIGNED NOT NULL,
            product_name VARCHAR(255) NULL,
            size VARCHAR(255) NULL,
            closure_type VARCHAR(255) NULL,
            color VARCHAR(255) NULL,
            quantity INT(11) NULL,
            box_quantity INT(11) NULL,
            unit_price DECIMAL(10,2) NULL,
            total_price DECIMAL(10,2) NULL,
            currency VARCHAR(10) NULL,
            customer_name VARCHAR(255) NULL,
            customer_email VARCHAR(255) NULL,
            customer_phone VARCHAR(255) NULL,
            company_name VARCHAR(255) NULL,
            message TEXT NULL,
            status VARCHAR(50) DEFAULT 'pending' NOT NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Table for wp_als_catalog_closure_types
        $table_name = $wpdb->prefix . 'als_catalog_closure_types';
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL UNIQUE,
            description TEXT NULL,
            is_active TINYINT(1) DEFAULT 1 NOT NULL,
            sort_order INT(11) DEFAULT 0 NOT NULL,
            created_at DATETIME NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Table for wp_als_catalog_currencies
        $table_name = $wpdb->prefix . 'als_catalog_currencies';
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            currency_code VARCHAR(10) NOT NULL UNIQUE,
            currency_name VARCHAR(255) NOT NULL,
            currency_symbol VARCHAR(10) NULL,
            exchange_rate DECIMAL(10,4) NULL,
            is_default TINYINT(1) DEFAULT 0 NOT NULL,
            is_active TINYINT(1) DEFAULT 1 NOT NULL,
            last_updated DATETIME NULL,
            created_at DATETIME NULL,
            updated_at DATETIME NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );
    }

    /**
     * Import data from CSV files into custom database tables.
     * @since    1.0.0
     * @access   private
     */
    private function import_csv_data() {
        global $wpdb;

        $csv_files = [
            'currencies'    => [ 'file' => 'wp_als_currencies.csv', 'table' => $wpdb->prefix . 'als_catalog_currencies' ],
            'closure_types' => [ 'file' => 'wp_als_closure_types.csv', 'table' => $wpdb->prefix . 'als_catalog_closure_types' ],
            'products'      => [ 'file' => 'wp_als_products.csv', 'table' => $wpdb->prefix . 'als_catalog_products' ],
            'product_options' => [ 'file' => 'wp_als_product_options_simple.csv', 'table' => $wpdb->prefix . 'als_catalog_product_options' ],
            'quotes'        => [ 'file' => 'wp_als_quotes_simple.csv', 'table' => $wpdb->prefix . 'als_catalog_quotes' ],
        ];

        foreach ( $csv_files as $key => $info ) {
            // The CSVs must be inside a /data/ folder within the plugin directory.
            $file_path = ALS_CATALOG_DIR . 'data/' . $info['file'];
            $table_name = $info['table'];

            if ( ! file_exists( $file_path ) ) {
                error_log( "ALS Catalog: CSV file not found at " . $file_path );
                continue;
            }
            
            // Fixed check: Only import if the table is empty.
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            if ($count > 0) {
                continue;
            }

            if ( ( $handle = fopen( $file_path, 'r' ) ) !== FALSE ) {
                $header = fgetcsv( $handle ); 

                while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                    if ( count( $header ) !== count( $data ) ) {
                        error_log( "ALS Catalog: Mismatch in column count for " . $info['file'] . ". Skipping row." );
                        continue;
                    }

                    $row_data = array_combine( $header, $data );

                    // Auto-generate slug for products if it's empty
                    if ($key === 'products' && empty($row_data['slug'])) {
                        $row_data['slug'] = sanitize_title($row_data['name']);
                    }
                    
                    $insert_data = [];
                    foreach ($row_data as $col_name => $col_value) {
                         // Sanitize all values as strings, as $wpdb->insert handles the rest based on format.
                        $insert_data[$col_name] = sanitize_text_field($col_value);
                    }


                    $wpdb->insert( $table_name, $insert_data );

                    if ( $wpdb->last_error ) {
                        error_log( "ALS Catalog: Error inserting into $table_name: " . $wpdb->last_error );
                    }
                }
                fclose( $handle );
            }
        }
    }
}
