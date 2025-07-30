<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Als_Catalog
 * @subpackage Als_Catalog/admin
 * @author     ALS Team <example@example.com>
 */
class Als_Catalog_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
    private $page_title;
    private $product_id;
    private $product;
    private $quote_id;
    private $quote;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();

    }
    
    /**
     * Load dependencies for the admin area.
     *
     * @since    1.0.0
     */
    public function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-als-quotes-list-table.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-als-products-list-table.php';
    }


    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/als-catalog-admin.css', array(), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/als-catalog-admin.js', array( 'jquery' ), $this->version, false );
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            'ALS Product Catalog',
            'ALS Catalog',
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_plugin_setup_page' ),
            'dashicons-cart',
            25
        );

        add_submenu_page(
            $this->plugin_name,
            'Products',
            'Products',
            'manage_options',
            $this->plugin_name . '-products',
            array( $this, 'display_products_page' )
        );
        
        // This is a hidden page for adding/editing a single product.
        add_submenu_page(
            null, // Don't show in the menu
            'Manage Product',
            'Manage Product',
            'manage_options',
            $this->plugin_name . '-product-manage',
            array( $this, 'display_product_manage_page' )
        );

        add_submenu_page(
            $this->plugin_name,
            'Quotes',
            'Quotes',
            'manage_options',
            $this->plugin_name . '-quotes',
            array( $this, 'display_quotes_page' )
        );
        
        // This is a hidden page for adding/editing a single quote.
        add_submenu_page(
            null, // Don't show in the menu
            'Manage Quote',
            'Manage Quote',
            'manage_options',
            $this->plugin_name . '-quote-manage',
            array( $this, 'display_quote_manage_page' )
        );
    }
    
    /**
     * Register the dashboard widgets.
     *
     * @since    1.0.0
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'als_catalog_recent_quotes_widget',
            __( 'Recent Pending Quotes', 'als-catalog' ),
            array( $this, 'display_recent_quotes_widget' )
        );
    }
    
    /**
     * Display the recent quotes dashboard widget.
     *
     * @since    1.0.0
     */
    public function display_recent_quotes_widget() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        $quotes = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_name} WHERE status = %s ORDER BY created_at DESC LIMIT 5", 'pending' ), ARRAY_A );

        if ( empty( $quotes ) ) {
            echo '<p>' . __( 'No pending quotes.', 'als-catalog' ) . '</p>';
            return;
        }

        echo '<ul>';
        foreach ( $quotes as $quote ) {
            $view_url = admin_url( 'admin.php?page=' . $this->plugin_name . '-quotes&action=view&id=' . $quote['id'] );
            echo '<li>';
            echo '<a href="' . esc_url( $view_url ) . '">';
            echo esc_html( $quote['product_name'] ) . ' - ' . esc_html( $quote['customer_name'] );
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
    }
    
    /**
     * Render the main admin page.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page() {
        require_once 'partials/als-catalog-admin-display.php';
    }

    /**
     * Render the products admin page.
     *
     * @since    1.0.0
     */
    public function display_products_page() {
        if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) {
            $this->delete_product();
        }
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <a href="<?php echo admin_url( 'admin.php?page=' . $this->plugin_name . '-product-manage' ); ?>" class="page-title-action">Add New</a>
            <hr class="wp-header-end">
            
            <form method="post">
                <?php
                $products_list_table = new Als_Products_List_Table();
                $products_list_table->prepare_items();
                $products_list_table->display();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render the page for adding or editing a single product.
     *
     * @since    1.0.0
     */
    public function display_product_manage_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        $this->product_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        
        if ( $this->product_id > 0 ) {
            $this->page_title = __( 'Edit Product', 'als-catalog' );
            $this->product = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $this->product_id ), ARRAY_A );
        } else {
            $this->page_title = __( 'Add New Product', 'als-catalog' );
        }

        require_once 'partials/als-catalog-product-manage.php';
    }
    
    public function save_product() {
        if ( ! isset( $_POST['als_catalog_nonce'] ) || ! wp_verify_nonce( $_POST['als_catalog_nonce'], 'als_catalog_save_product_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'als-catalog' ) );
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        
        $data = array(
            'name' => sanitize_text_field( $_POST['product_name'] ),
            'category' => sanitize_text_field( $_POST['product_category'] ),
            'is_active' => absint( $_POST['is_active'] )
        );
        
        if ( $product_id > 0 ) {
            // Update existing product
            $wpdb->update( $table_name, $data, array( 'id' => $product_id ) );
        } else {
            // Insert new product
            $data['created_at'] = current_time( 'mysql' );
            $data['updated_at'] = current_time( 'mysql' );
            $wpdb->insert( $table_name, $data );
        }
        
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-products' ) );
        exit;
    }
    
    public function delete_product() {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'als_catalog_delete_product' ) ) {
            wp_die( __( 'Security check failed.', 'als-catalog' ) );
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        $product_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        
        if ( $product_id > 0 ) {
            $wpdb->delete( $table_name, array( 'id' => $product_id ) );
        }
        
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-products' ) );
        exit;
    }

    /**
     * Render the quotes admin page.
     *
     * @since    1.0.0
     */
    public function display_quotes_page() {
        if ( isset( $_GET['action'] ) ) {
            if ( $_GET['action'] == 'delete' ) {
                $this->delete_quote();
            } elseif ( $_GET['action'] == 'view' ) {
                $this->display_quote_view_page();
                return;
            }
        }
        ?>
        <div class="wrap">
            <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
            <form method="post">
                <?php
                $quotes_list_table = new Als_Quotes_List_Table();
                $quotes_list_table->prepare_items();
                $quotes_list_table->display();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function display_quote_manage_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        
        $this->quote_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        
        if ( $this->quote_id > 0 ) {
            $this->page_title = __( 'Edit Quote', 'als-catalog' );
            $this->quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $this->quote_id ), ARRAY_A );
        } else {
            // We don't have a UI for creating quotes from the admin for now.
             wp_die( __( 'Invalid quote ID.', 'als-catalog' ) );
        }

        require_once 'partials/als-catalog-quote-manage.php';
    }
    
    public function save_quote() {
        if ( ! isset( $_POST['als_catalog_nonce'] ) || ! wp_verify_nonce( $_POST['als_catalog_nonce'], 'als_catalog_save_quote_nonce' ) ) {
            wp_die( __( 'Security check failed.', 'als-catalog' ) );
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        
        $quote_id = isset( $_POST['quote_id'] ) ? absint( $_POST['quote_id'] ) : 0;
        
        $data = array(
            'status' => sanitize_text_field( $_POST['status'] )
        );
        
        if ( $quote_id > 0 ) {
            // Update existing quote
            $wpdb->update( $table_name, $data, array( 'id' => $quote_id ) );
        }
        
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-quotes' ) );
        exit;
    }
    
    public function display_quote_view_page() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        
        $quote_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        
        if ( $quote_id > 0 ) {
            $this->page_title = __( 'View Quote', 'als-catalog' );
            $this->quote = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $quote_id ), ARRAY_A );
            require_once 'partials/als-catalog-quote-view.php';
        } else {
            wp_die( __( 'Invalid quote ID.', 'als-catalog' ) );
        }
    }
    
    public function delete_quote() {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'als_catalog_delete_quote' ) ) {
            wp_die( __( 'Security check failed.', 'als-catalog' ) );
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        
        $quote_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        
        if ( $quote_id > 0 ) {
            $wpdb->delete( $table_name, array( 'id' => $quote_id ) );
        }
        
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-quotes' ) );
        exit;
    }

}
