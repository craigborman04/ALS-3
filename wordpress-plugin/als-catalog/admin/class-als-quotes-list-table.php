<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Als_Quotes_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => __( 'Quote', 'als-catalog' ),
            'plural'   => __( 'Quotes', 'als-catalog' ),
            'ajax'     => false
        ] );
    }

    public static function get_quotes( $per_page = 20, $page_number = 1 ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}als_catalog_quotes";
        $sql .= " ORDER BY created_at DESC";
        $sql .= " LIMIT $per_page";
        $sql .= " OFFSET " . ( $page_number - 1 ) * $per_page;

        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        return $result;
    }

    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}als_catalog_quotes";
        return $wpdb->get_var( $sql );
    }

    public function no_items() {
        _e( 'No quotes found.', 'als-catalog' );
    }

    function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'product_name':
            case 'customer_name':
            case 'customer_email':
            case 'status':
            case 'created_at':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting
        }
    }
    
    function column_product_name( $item ) {
        $title = '<strong>' . $item['product_name'] . '</strong>';
        $actions = [
            'edit'   => sprintf( '<a href="?page=%s&action=%s&id=%s">Edit</a>', 'als-catalog-quote-manage', 'edit', absint( $item['id'] ) ),
            'view'   => sprintf( '<a href="?page=%s&action=%s&id=%s">View</a>', $_REQUEST['page'], 'view', absint( $item['id'] ) ),
            'delete' => sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s">Delete</a>', $_REQUEST['page'], 'delete', absint( $item['id'] ), wp_create_nonce( 'als_catalog_delete_quote' ) )
        ];
        return $title . $this->row_actions( $actions );
    }

    function get_columns() {
        $columns = [
            'cb'            => '<input type="checkbox" />',
            'product_name'  => __( 'Product', 'als-catalog' ),
            'customer_name' => __( 'Customer', 'als-catalog' ),
            'customer_email'=> __( 'Email', 'als-catalog' ),
            'status'        => __( 'Status', 'als-catalog' ),
            'created_at'    => __( 'Date', 'als-catalog' )
        ];
        return $columns;
    }

    public function get_sortable_columns() {
        $sortable_columns = array(
            'product_name' => array( 'product_name', true ),
            'customer_name' => array( 'customer_name', true ),
            'status' => array( 'status', true ),
            'created_at' => array( 'created_at', true )
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

        $per_page     = $this->get_items_per_page( 'quotes_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page
        ] );

        $this->items = self::get_quotes( $per_page, $current_page );
    }
}
