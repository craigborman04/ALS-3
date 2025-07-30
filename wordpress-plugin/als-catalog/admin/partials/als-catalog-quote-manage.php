<?php
/**
 * Provide a admin area view for the quote management page.
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

    <form method="post" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="als_catalog_save_quote">
        <?php wp_nonce_field( 'als_catalog_save_quote_nonce', 'als_catalog_nonce' ); ?>
        <?php if ( ! empty( $this->quote_id ) ) : ?>
            <input type="hidden" name="quote_id" value="<?php echo esc_attr( $this->quote_id ); ?>">
        <?php endif; ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="status"><?php _e( 'Status', 'als-catalog' ); ?></label></th>
                    <td>
                        <select name="status" id="status">
                            <option value="pending" <?php selected( $this->quote['status'] ?? 'pending', 'pending' ); ?>><?php _e( 'Pending', 'als-catalog' ); ?></option>
                            <option value="processing" <?php selected( $this->quote['status'] ?? 'pending', 'processing' ); ?>><?php _e( 'Processing', 'als-catalog' ); ?></option>
                            <option value="completed" <?php selected( $this->quote['status'] ?? 'pending', 'completed' ); ?>><?php _e( 'Completed', 'als-catalog' ); ?></option>
                            <option value="cancelled" <?php selected( $this->quote['status'] ?? 'pending', 'cancelled' ); ?>><?php _e( 'Cancelled', 'als-catalog' ); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button( __( 'Save Quote', 'als-catalog' ) ); ?>
    </form>
</div>
