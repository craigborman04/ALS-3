<?php

/**
 * The core plugin class.
 */
class Als_Catalog {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'als-catalog';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        
        register_deactivation_hook( ALS_CATALOG_DIR . 'als-catalog.php', array( $this, 'deactivate' ) );
        register_activation_hook( ALS_CATALOG_DIR . 'als-catalog.php', array( $this, 'activate' ) );

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
        add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
        add_shortcode( 'als_product_catalog', array( $this, 'render_product_catalog_shortcode' ) );
    }

    public function register_rest_routes() {
        $api = new Als_Catalog_Rest_Api();
        $api->register_routes();
    }
    
    public function render_product_catalog_shortcode( $atts ) {
        $iframe_src = add_query_arg(
            ['apiUrl' => rest_url('als-catalog/v1/')],
            ALS_CATALOG_URL . 'out/index.html'
        );
        return '<iframe src="' . esc_url($iframe_src) . '" style="width: 100%; height: 1200px; border: none;"></iframe>';
    }

    public function run() {
        // The plugin is now running through the hooks defined in the constructor.
    }

    public function activate() {
        $this->create_als_tables();
        $this->import_csv_data();
    }
    
    public function deactivate() {
        // Data is preserved on deactivation.
    }

    private function create_als_tables() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();

        $tables = [
            "{$wpdb->prefix}als_catalog_products" => "CREATE TABLE {$wpdb->prefix}als_catalog_products (
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
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_product_options" => "CREATE TABLE {$wpdb->prefix}als_catalog_product_options (
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
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_quotes" => "CREATE TABLE {$wpdb->prefix}als_catalog_quotes (
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
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_closure_types" => "CREATE TABLE {$wpdb->prefix}als_catalog_closure_types (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL UNIQUE,
                description TEXT NULL,
                is_active TINYINT(1) DEFAULT 1 NOT NULL,
                sort_order INT(11) DEFAULT 0 NOT NULL,
                created_at DATETIME NULL,
                PRIMARY KEY (id)
            ) $charset_collate;",
            "{$wpdb->prefix}als_catalog_currencies" => "CREATE TABLE {$wpdb->prefix}als_catalog_currencies (
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
            ) $charset_collate;"
        ];

        foreach ($tables as $table_name => $sql) {
            dbDelta($sql);
        }
    }

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
            $file_path = ALS_CATALOG_DIR . 'data/' . $info['file'];
            $table_name = $info['table'];

            if ( ! file_exists( $file_path ) ) {
                als_catalog_log( "ALS Catalog: CSV file not found at " . $file_path );
                continue;
            }
            
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            if ($count > 0) {
                continue;
            }

            if ( ( $handle = fopen( $file_path, 'r' ) ) !== FALSE ) {
                $header = fgetcsv( $handle ); 

                while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                    if ( count( $header ) !== count( $data ) ) {
                        als_catalog_log( "ALS Catalog: Mismatch in column count for " . $info['file'] . ". Skipping row." );
                        continue;
                    }

                    $row_data = array_combine( $header, $data );

                    if ($key === 'products' && empty($row_data['slug'])) {
                        $row_data['slug'] = sanitize_title($row_data['name']);
                    }
                    
                    $insert_data = [];
                    foreach ($row_data as $col_name => $col_value) {
                        $insert_data[$col_name] = sanitize_text_field($col_value);
                    }

                    $wpdb->insert( $table_name, $insert_data );

                    if ( $wpdb->last_error ) {
                        als_catalog_log( "ALS Catalog: Error inserting into $table_name: " . $wpdb->last_error );
                    }
                }
                fclose( $handle );
            }
        }
    }
}
