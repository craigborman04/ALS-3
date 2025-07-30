<?php
/**
 * The REST API controller for the ALS Product Catalog.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Als_Catalog
 * @subpackage Als_Catalog/includes
 */

class Als_Catalog_Rest_Api {

    /**
     * The namespace for the custom REST API endpoints.
     * @since    1.0.0
     * @access   protected
     * @var      string
     */
    protected $namespace = 'als-catalog/v1';

    /**
     * Register the routes for the objects of the plugin.
     * @since    1.0.0
     */
    public function register_routes() {
        // Endpoint for getting all products (with filtering)
        register_rest_route( $this->namespace, '/products', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_products' ),
            'permission_callback' => '__return_true', // Publicly accessible
        ) );

        // Endpoint for filter options (sizes, colors, etc.)
        register_rest_route( $this->namespace, '/filter-options', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_filter_options' ),
            'permission_callback' => '__return_true',
        ) );

        // Endpoint for submitting a new quote
        register_rest_route( $this->namespace, '/quotes', array(
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => array( $this, 'create_quote' ),
            'permission_callback' => '__return_true', // Anyone can submit a quote
        ) );
        
        // Endpoint for getting all quotes (for admin)
        register_rest_route( $this->namespace, '/quotes', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array( $this, 'get_quotes' ),
            'permission_callback' => array( $this, 'can_manage_quotes' ),
        ) );
    }

    /**
     * Get a list of products, with optional filtering.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_products( $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_products';
        
        // We'll add filtering logic here later based on request params
        $query = "SELECT * FROM {$table_name} WHERE is_active = 1 ORDER BY sort_order ASC";
        $results = $wpdb->get_results( $query );

        if ( $wpdb->last_error ) {
            return new WP_Error( 'db_error', __( 'Could not retrieve products.', 'als-catalog' ), array( 'status' => 500 ) );
        }

        return new WP_REST_Response( $results, 200 );
    }
    
    /**
     * Get a list of filter options.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_filter_options( $request ) {
        global $wpdb;
        $options_table = $wpdb->prefix . 'als_catalog_product_options';

        $sizes = $wpdb->get_col("SELECT DISTINCT size FROM {$options_table} WHERE size IS NOT NULL AND size != '' ORDER BY size ASC");
        $colors = $wpdb->get_col("SELECT DISTINCT color FROM {$options_table} WHERE color IS NOT NULL AND color != '' ORDER BY color ASC");
        $closure_types = $wpdb->get_col("SELECT DISTINCT closure_type FROM {$options_table} WHERE closure_type IS NOT NULL AND closure_type != '' ORDER BY closure_type ASC");
        
        $response = [
            'sizes' => $sizes,
            'colors' => $colors,
            'closureTypes' => $closure_types,
        ];
        
        return new WP_REST_Response( $response, 200 );
    }

    /**
     * Create a new quote.
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function create_quote( $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';

        $params = $request->get_json_params();

        $data = [
            'product_id'     => isset( $params['product_id'] ) ? absint( $params['product_id'] ) : 0,
            'product_name'   => isset( $params['product_name'] ) ? sanitize_text_field( $params['product_name'] ) : '',
            'size'           => isset( $params['size'] ) ? sanitize_text_field( $params['size'] ) : '',
            'closure_type'   => isset( $params['closure_type'] ) ? sanitize_text_field( $params['closure_type'] ) : '',
            'color'          => isset( $params['color'] ) ? sanitize_text_field( $params['color'] ) : '',
            'quantity'       => isset( $params['quantity'] ) ? absint( $params['quantity'] ) : 0,
            'customer_name'  => isset( $params['customer_name'] ) ? sanitize_text_field( $params['customer_name'] ) : '',
            'customer_email' => isset( $params['customer_email'] ) ? sanitize_email( $params['customer_email'] ) : '',
            'customer_phone' => isset( $params['customer_phone'] ) ? sanitize_text_field( $params['customer_phone'] ) : '',
            'company_name'   => isset( $params['company_name'] ) ? sanitize_text_field( $params['company_name'] ) : '',
            'message'        => isset( $params['message'] ) ? sanitize_textarea_field( $params['message'] ) : '',
            'status'         => 'pending',
            'created_at'     => current_time( 'mysql' ),
            'updated_at'     => current_time( 'mysql' ),
        ];

        $result = $wpdb->insert( $table_name, $data );

        if ( $result === false ) {
            return new WP_Error( 'db_error', __( 'Could not save the quote.', 'als-catalog' ), array( 'status' => 500 ) );
        }

        // Send email notification
        $this->send_quote_notification_email( $data );

        return new WP_REST_Response( ['message' => 'Quote submitted successfully.'], 201 );
    }
    
    /**
     * Send quote notification email to admin.
     *
     * @param array $quote_data The quote data.
     */
    private function send_quote_notification_email( $quote_data ) {
        $to = get_option( 'admin_email' );
        $subject = __( 'New Quote Submission', 'als-catalog' );
        
        $body = "<h2>" . __( 'New Quote Details', 'als-catalog' ) . "</h2>";
        $body .= "<ul>";
        $body .= "<li><strong>" . __( 'Product:', 'als-catalog' ) . "</strong> " . esc_html( $quote_data['product_name'] ) . "</li>";
        $body .= "<li><strong>" . __( 'Customer:', 'als-catalog' ) . "</strong> " . esc_html( $quote_data['customer_name'] ) . "</li>";
        $body .= "<li><strong>" . __( 'Email:', 'als-catalog' ) . "</strong> " . esc_html( $quote_data['customer_email'] ) . "</li>";
        $body .= "<li><strong>" . __( 'Phone:', 'als-catalog' ) . "</strong> " . esc_html( $quote_data['customer_phone'] ) . "</li>";
        $body .= "<li><strong>" . __( 'Company:', 'als-catalog' ) . "</strong> " . esc_html( $quote_data['company_name'] ) . "</li>";
        $body .= "<li><strong>" . __( 'Message:', 'als-catalog' ) . "</strong><br>" . nl2br( esc_html( $quote_data['message'] ) ) . "</li>";
        $body .= "</ul>";

        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
    }
    
    /**
     * Get all quotes (for admin).
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_quotes( $request ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'als_catalog_quotes';
        $results = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY created_at DESC" );
        
        if ( $wpdb->last_error ) {
            return new WP_Error( 'db_error', __( 'Could not retrieve quotes.', 'als-catalog' ), array( 'status' => 500 ) );
        }

        return new WP_REST_Response( $results, 200 );
    }
    
    /**
     * Check if a given request has permission to manage quotes.
     *
     * @param  WP_REST_Request $request Full details about the request.
     * @return bool True if the request has permission, false otherwise.
     */
    public function can_manage_quotes( $request ) {
        return current_user_can( 'manage_options' ); // Only administrators can manage quotes.
    }
}
