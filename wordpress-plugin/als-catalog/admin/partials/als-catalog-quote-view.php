<?php
/**
 * Provide a admin area view for the quote view page.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Als_Catalog
 * @subpackage Als_Catalog/admin/partials
 */

?>
<div class="wrap">
    <h1><?php echo esc_html( $this->page_title ); ?></h1>

    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><?php _e( 'Product Name', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['product_name'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Customer Name', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['customer_name'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Customer Email', 'als-catalog' ); ?></th>
                <td><a href="mailto:<?php echo esc_attr( $this->quote['customer_email'] ); ?>"><?php echo esc_html( $this->quote['customer_email'] ); ?></a></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Customer Phone', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['customer_phone'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Company Name', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['company_name'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Status', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['status'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Message', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['message'] ); ?></td>
            </tr>
            <tr>
                <th scope="row"><?php _e( 'Date', 'als-catalog' ); ?></th>
                <td><?php echo esc_html( $this->quote['created_at'] ); ?></td>
            </tr>
        </tbody>
    </table>
    
    <p>
        <a href="<?php echo admin_url('admin.php?page=' . $this->plugin_name . '-quotes'); ?>" class="button"><?php _e( 'Back to Quotes', 'als-catalog' ); ?></a>
    </p>
</div>
