<?php

/**
 * The core plugin class.
 */
class Als_Catalog {

    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->version = '1.0.1';
        $this->plugin_name = 'als-catalog';
    }
    
    /**
     * Run the plugin, registering all hooks.
     */
    public function run() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once ALS_CATALOG_DIR . 'includes/class-als-catalog-rest-api.php';
        require_once ALS_CATALOG_DIR . 'admin/class-als-catalog-admin.php';
    }

    private function define_admin_hooks() {
        $admin = new Als_Catalog_Admin($this->plugin_name, $this->version);
        add_action( 'admin_menu', array( $admin, 'add_plugin_admin_menu' ) );
        add_action( 'admin_post_als_catalog_save_product', array( $admin, 'save_product' ) );
        add_action( 'admin_post_als_catalog_save_quote', array( $admin, 'save_quote' ) );
        add_action( 'wp_dashboard_setup', array( $admin, 'add_dashboard_widgets' ) );
    }
    
    private function define_public_hooks() {
        $api = new Als_Catalog_Rest_Api();
        add_action( 'rest_api_init', array( $api, 'register_routes' ) );
        add_shortcode( 'als_product_catalog', array( $this, 'render_product_catalog_shortcode' ) );
    }
    
    public function render_product_catalog_shortcode( $atts ) {
        $iframe_src = add_query_arg(
            ['apiUrl' => rest_url('als-catalog/v1/')],
            ALS_CATALOG_URL . 'out/index.html'
        );
        return '<iframe src="' . esc_url($iframe_src) . '" style="width: 100%; height: 1200px; border: none; overflow: hidden;" scrolling="no"></iframe>';
    }
    
    public static function activate() {
        self::create_als_tables();
        self::import_csv_data();
    }
    
    public static function deactivate() {
        // Data is preserved on deactivation.
    }

    private static function create_als_tables() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
             "{$wpdb->prefix}als_catalog_products" => "CREATE TABLE `{$wpdb->prefix}als_catalog_products` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `color_name` varchar(255) DEFAULT NULL,
                `color_hex` varchar(7) DEFAULT NULL,
                `color_description` text DEFAULT NULL,
                `category` varchar(255) DEFAULT NULL,
                `tags` text DEFAULT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                `slug` varchar(255) DEFAULT NULL,
                `description` text DEFAULT NULL,
                `image_url` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `slug` (`slug`)
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_product_options" => "CREATE TABLE `{$wpdb->prefix}als_catalog_product_options` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `product_id` bigint(20) unsigned NOT NULL,
                `size` varchar(255) DEFAULT NULL,
                `closure_type` varchar(255) DEFAULT NULL,
                `color` varchar(255) DEFAULT NULL,
                `color_hex` varchar(7) DEFAULT NULL,
                `part_number` varchar(255) DEFAULT NULL,
                `price_modifier` decimal(10,2) DEFAULT NULL,
                `capacity` varchar(255) DEFAULT NULL,
                `dimensions` varchar(255) DEFAULT NULL,
                `weight` varchar(255) DEFAULT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `product_id` (`product_id`)
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_quotes" => "CREATE TABLE `{$wpdb->prefix}als_catalog_quotes` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `product_id` bigint(20) unsigned NOT NULL,
                `product_name` varchar(255) DEFAULT NULL,
                `size` varchar(255) DEFAULT NULL,
                `closure_type` varchar(255) DEFAULT NULL,
                `color` varchar(255) DEFAULT NULL,
                `quantity` int(11) DEFAULT NULL,
                `box_quantity` int(11) DEFAULT NULL,
                `unit_price` decimal(10,2) DEFAULT NULL,
                `total_price` decimal(10,2) DEFAULT NULL,
                `currency` varchar(10) DEFAULT NULL,
                `customer_name` varchar(255) DEFAULT NULL,
                `customer_email` varchar(255) DEFAULT NULL,
                `customer_phone` varchar(255) DEFAULT NULL,
                `company_name` varchar(255) DEFAULT NULL,
                `message` text DEFAULT NULL,
                `status` varchar(50) NOT NULL DEFAULT 'pending',
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_closure_types" => "CREATE TABLE `{$wpdb->prefix}als_catalog_closure_types` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `sort_order` int(11) NOT NULL DEFAULT 0,
                `created_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `name` (`name`)
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_currencies" => "CREATE TABLE `{$wpdb->prefix}als_catalog_currencies` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `currency_code` varchar(10) NOT NULL,
                `currency_name` varchar(255) NOT NULL,
                `currency_symbol` varchar(10) DEFAULT NULL,
                `exchange_rate` decimal(10,4) DEFAULT NULL,
                `is_default` tinyint(1) NOT NULL DEFAULT 0,
                `is_active` tinyint(1) NOT NULL DEFAULT 1,
                `last_updated` datetime DEFAULT NULL,
                `created_at` datetime DEFAULT NULL,
                `updated_at` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `currency_code` (`currency_code`)
            ) $charset_collate;"
        ];

        foreach ($tables as $table_name => $sql) {
            dbDelta($sql);
        }
    }

    private static function import_csv_data() {
        global $wpdb;
        $csv_files = [
            'currencies'    => [ 'file' => 'wp_als_currencies.csv', 'table' => $wpdb->prefix . 'als_catalog_currencies' ],
            'closure_types' => [ 'file' => 'wp_als_closure_types.csv', 'table' => $wpdb->prefix . 'als_catalog_closure_types' ],
            'products'      => [ 'file' => 'wp_als_products.csv', 'table' => $wpdb->prefix . 'als_catalog_products' ],
            'product_options' => [ 'file' => 'wp_als_product_options_simple.csv', 'table' => $wpdb->prefix . 'als_catalog_product_options' ],
            'quotes'        => [ 'file' => 'wp_als_quotes_simple.csv', 'table' => $wpdb->prefix . 'als_catalog_quotes' ],
        ];

        foreach ( $csv_files as $key => $info ) {
            $file_path = ALS_CATALOG_DIR . 'data/' . $info['file'];
            $table_name = $info['table'];
            if ( ! file_exists( $file_path ) ) continue;
            
            $count = $wpdb->get_var("SELECT COUNT(*) FROM `{$table_name}`");
            if ($count > 0) continue;

            if ( ( $handle = fopen( $file_path, 'r' ) ) !== FALSE ) {
                $header = fgetcsv( $handle ); 
                while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                    if ( count( $header ) !== count( $data ) ) continue;
                    $row_data = array_combine( $header, $data );
                    if ($key === 'products' && empty($row_data['slug'])) {
                        $row_data['slug'] = sanitize_title($row_data['name']);
                    }
                    $wpdb->insert( $table_name, $row_data );
                }
                fclose( $handle );
            }
        }
    }
}
