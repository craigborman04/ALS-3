<?php
/**
 * The admin-specific functionality of the plugin.
 */
class Als_Catalog_Admin {

    private $plugin_name;
    private $version;
    private $page_title;
    private $product_id;
    private $product;
    private $quote_id;
    private $quote;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->load_dependencies();
    }
    
    public function load_dependencies() {
        // Corrected, reliable path to load dependencies.
        require_once ALS_CATALOG_DIR . 'admin/class-als-products-list-table.php';
        require_once ALS_CATALOG_DIR . 'admin/class-als-quotes-list-table.php';
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'ALS Product Catalog', 'ALS Catalog', 'manage_options',
            $this->plugin_name, array( $this, 'display_products_page' ), 'dashicons-cart', 25
        );
        add_submenu_page(
            $this->plugin_name, 'Products', 'Products', 'manage_options',
            $this->plugin_name . '-products', array( $this, 'display_products_page' )
        );
        add_submenu_page(
            $this->plugin_name, 'Quotes', 'Quotes', 'manage_options',
            $this->plugin_name . '-quotes', array( $this, 'display_quotes_page' )
        );
        add_submenu_page(
            null, 'Manage Product', 'Manage Product', 'manage_options',
            $this->plugin_name . '-product-manage', array( $this, 'display_product_manage_page' )
        );
        add_submenu_page(
            null, 'Manage Quote', 'Manage Quote', 'manage_options',
            $this->plugin_name . '-quote-manage', array( $this, 'display_quote_manage_page' )
        );
    }
    
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'als_catalog_recent_quotes_widget', __( 'Recent Pending Quotes', 'als-catalog' ),
            array( $this, 'display_recent_quotes_widget' )
        );
    }
    
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
            echo '<li><a href="' . esc_url( $view_url ) . '">' . esc_html( $quote['product_name'] ) . ' - ' . esc_html( $quote['customer_name'] ) . '</a></li>';
        }
        echo '</ul>';
    }
    
    public function display_products_page() {
        if ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) $this->delete_product();
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
        if ( ! isset( $_POST['als_catalog_nonce'] ) || ! wp_verify_nonce( $_POST['als_catalog_nonce'], 'als_catalog_save_product_nonce' ) ) wp_die( 'Security check failed.' );
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
        $data = ['name' => sanitize_text_field( $_POST['product_name'] ), 'category' => sanitize_text_field( $_POST['product_category'] ), 'is_active' => absint( $_POST['is_active'] )];
        if ( $product_id > 0 ) {
            $wpdb->update( $table_name, $data, array( 'id' => $product_id ) );
        } else {
            $data['created_at'] = current_time( 'mysql' );
            $data['updated_at'] = current_time( 'mysql' );
            $wpdb->insert( $table_name, $data );
        }
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-products' ) );
        exit;
    }
    
    public function delete_product() {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'als_catalog_delete_product' ) ) wp_die( 'Security check failed.' );
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        $product_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        if ( $product_id > 0 ) $wpdb->delete( $table_name, array( 'id' => $product_id ) );
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-products' ) );
        exit;
    }

    public function display_quotes_page() {
        if ( isset( $_GET['action'] ) ) {
            if ( $_GET['action'] == 'delete' ) $this->delete_quote();
            elseif ( $_GET['action'] == 'view' ) { $this->display_quote_view_page(); return; }
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
             wp_die( 'Invalid quote ID.' );
        }
        require_once 'partials/als-catalog-quote-manage.php';
    }
    
    public function save_quote() {
        if ( ! isset( $_POST['als_catalog_nonce'] ) || ! wp_verify_nonce( $_POST['als_catalog_nonce'], 'als_catalog_save_quote_nonce' ) ) wp_die( 'Security check failed.' );
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        $quote_id = isset( $_POST['quote_id'] ) ? absint( $_POST['quote_id'] ) : 0;
        $data = ['status' => sanitize_text_field( $_POST['status'] )];
        if ( $quote_id > 0 ) $wpdb->update( $table_name, $data, array( 'id' => $quote_id ) );
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
            wp_die( 'Invalid quote ID.' );
        }
    }
    
    public function delete_quote() {
        if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'als_catalog_delete_quote' ) ) wp_die( 'Security check failed.' );
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        $quote_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        if ( $quote_id > 0 ) $wpdb->delete( $table_name, array( 'id' => $quote_id ) );
        wp_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-quotes' ) );
        exit;
    }
}
