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

        $sql = "SELECT * FROM {$wpdb->prefix}als_catalog_products";
        $sql .= " ORDER BY sort_order ASC";
        $sql .= " LIMIT $per_page";
        $sql .= " OFFSET " . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
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
        switch ( $column_name ) {
            case 'name':
            case 'category':
            case 'is_active':
            case 'sort_order':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting
        }
    }
    
    function column_name( $item ) {
        $title = '<strong>' . $item['name'] . '</strong>';
        $actions = [
            'edit' => sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>', 'als-catalog-product-manage', 'edit', absint( $item['id'] ) ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Delete</a>', $_REQUEST['page'], 'delete', absint( $item['id'] ), wp_create_nonce( 'als_catalog_delete_product' ) )
        ];
        return $title . $this->row_actions( $actions );
    }


    function get_columns() {
        $columns = [
            'cb'         => '<input type="checkbox" />',
            'name'       => __( 'Name', 'als-catalog' ),
            'category'   => __( 'Category', 'als-catalog' ),
            'is_active'  => __( 'Active', 'als-catalog' ),
            'sort_order' => __( 'Sort Order', 'als-catalog' )
        ];
        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'name' => array( 'name', true ),
            'category' => array( 'category', true ),
            'is_active' => array( 'is_active', true ),
            'sort_order' => array( 'sort_order', true )
        );
        return $sortable_columns;
    }
    
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
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
