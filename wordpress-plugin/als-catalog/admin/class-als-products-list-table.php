<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Als_Products_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => __( 'Product', 'als-catalog' ),
            'plural'   => __( 'Products', 'als-catalog' ),
            'ajax'     => false
        ] );
    }

    public static function get_products( $per_page = 20, $page_number = 1 ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        $sql = "SELECT * FROM {$table_name}";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        } else {
             $sql .= " ORDER BY sort_order ASC";
        }

        $sql .= " LIMIT %d";
        $sql .= " OFFSET %d";

        $query = $wpdb->prepare($sql, $per_page, ($page_number - 1) * $per_page);
        
        return $wpdb->get_results( $query, 'ARRAY_A' );
    }

    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}als_catalog_products";
        return $wpdb->get_var( $sql );
    }

    public function no_items() {
        _e( 'No products found.', 'als-catalog' );
    }

    function column_default( $item, $column_name ) {
        if (isset($item[$column_name])) {
            return esc_html($item[$column_name]);
        }
        return 'N/A';
    }
    
    function column_name( $item ) {
        $name = $item['name'] ?? 'No Name Found';
        $title = '<strong>' . esc_html($name) . '</strong>';
        $actions = [
            'edit' => sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>', 'als-catalog-product-manage', 'edit', absint( $item['id'] ) ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Delete</a>', $_REQUEST['page'], 'delete', absint( $item['id'] ), wp_create_nonce( 'als_catalog_delete_product' ) )
        ];
        return $title . $this->row_actions( $actions );
    }


    function get_columns() {
        return [
            'cb'         => '<input type="checkbox" />',
            'name'       => __( 'Name', 'als-catalog' ),
            'category'   => __( 'Category', 'als-catalog' ),
            'is_active'  => __( 'Active', 'als-catalog' ),
            'sort_order' => __( 'Sort Order', 'als-catalog' )
        ];
    }

    public function get_sortable_columns() {
        return [
            'name' => array( 'name', true ),
            'category' => array( 'category', true ),
            'is_active' => array( 'is_active', true ),
            'sort_order' => array( 'sort_order', true )
        ];
    }
    
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id'] ?? ''
        );
    }

    public function prepare_items() {
        $this->_column_headers = $this->get_column_info();
        $per_page     = $this->get_items_per_page( 'products_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();
        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page
        ] );
        $this->items = self::get_products( $per_page, $current_page );
    }
}
