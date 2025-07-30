<?php
/**
 * Provide a admin area view for the product management page.
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
        <input type="hidden" name="action" value="als_catalog_save_product">
        <?php wp_nonce_field( 'als_catalog_save_product_nonce', 'als_catalog_nonce' ); ?>
        <?php if ( ! empty( $this->product_id ) ) : ?>
            <input type="hidden" name="product_id" value="<?php echo esc_attr( $this->product_id ); ?>">
        <?php endif; ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="product_name"><?php _e( 'Product Name', 'als-catalog' ); ?></label></th>
                    <td><input type="text" name="product_name" id="product_name" class="regular-text" value="<?php echo esc_attr( $this->product['name'] ?? '' ); ?>" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="product_category"><?php _e( 'Category', 'als-catalog' ); ?></label></th>
                    <td><input type="text" name="product_category" id="product_category" class="regular-text" value="<?php echo esc_attr( $this->product['category'] ?? '' ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="is_active"><?php _e( 'Is Active', 'als-catalog' ); ?></label></th>
                    <td>
                        <select name="is_active" id="is_active">
                            <option value="1" <?php selected( $this->product['is_active'] ?? 1, 1 ); ?>><?php _e( 'Yes', 'als-catalog' ); ?></option>
                            <option value="0" <?php selected( $this->product['is_active'] ?? 1, 0 ); ?>><?php _e( 'No', 'als-catalog' ); ?></option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <?php submit_button( __( 'Save Product', 'als-catalog' ) ); ?>
    </form>
</div>
