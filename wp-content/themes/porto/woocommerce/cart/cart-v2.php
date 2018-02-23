<?php
/**
 * Cart Version 2
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$porto_woo_version = porto_get_woo_version_number();
?>
<div class="cart-v2">
	<h2 class="heading-primary m-b-md font-weight-normal clearfix">
		<span><?php _e( 'Shopping Cart', 'porto' ); ?></span>
		<a href="<?php echo esc_url( wc_get_checkout_url() );?>" class="btn btn-primary pull-right proceed-to-checkout"><?php esc_html_e( 'Proceed to Checkout', 'porto' ); ?></a>
	</h2>
	<div class="row">
		<div class="col-md-8 col-lg-9">
		
			<div class="featured-box align-left">
				<div class="box-content">
					<form class="woocommerce-cart-form" action="<?php echo esc_url( version_compare($porto_woo_version, '2.5', '<') ? WC()->cart->get_cart_url() : wc_get_cart_url() ); ?>" method="post">
						<?php do_action( 'woocommerce_before_cart_table' ); ?>
						<table class="shop_table responsive cart woocommerce-cart-form__contents" cellspacing="0">
							<thead>
								<tr>
									<th class="product-remove">&nbsp;</th>
									<th class="product-thumbnail">&nbsp;</th>
									<th class="product-name"><?php _e( 'Product Name', 'porto' ); ?></th>
									<th class="product-price"><?php _e( 'Unit Price', 'porto' ); ?></th>
									<th class="product-quantity"><?php _e( 'Qty', 'porto' ); ?></th>
									<th class="product-subtotal"><?php _e( 'Subtotal', 'porto' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php do_action( 'woocommerce_before_cart_contents' ); ?>
								<?php
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
									$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
									if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
										?>
										<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
											<td class="product-remove">
												<?php
													echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
														'<a href="%s" class="remove remove-product" aria-label="%s" data-product_id="%s" data-product_sku="%s" data-cart_id="%s">&times;</a>',
														esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
														__( 'Remove this item', 'porto' ),
														esc_attr( $product_id ),
														esc_attr( $_product->get_sku() ),
														esc_attr( $cart_item_key )
													), $cart_item_key );
												?>
											</td>
											<td class="product-thumbnail">
												<?php
													$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
													if ( ! $_product->is_visible() ) {
														echo $thumbnail;
													} else {
														printf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $thumbnail );
													}
												?>
											</td>
											<td class="product-name">
												<?php
													if ( ! $_product->is_visible() ) {
														echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
													} else {
														echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink( $cart_item ), $_product->get_name() ), $cart_item, $cart_item_key );
													}
													// Meta data
													echo WC()->cart->get_item_data( $cart_item );
													// Backorder notification
													if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
														echo '<p class="backorder_notification">' . __( 'Available on backorder', 'porto' ) . '</p>';
													}
												?>
											</td>
											<td class="product-price">
												<?php
													echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key, $cart_item );
												?>
											</td>
											<td class="product-quantity">
												<?php
													if ( $_product->is_sold_individually() ) {
														$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
													} else {
														$product_quantity = woocommerce_quantity_input( array(
															'input_name'  => "cart[{$cart_item_key}][qty]",
															'input_value' => $cart_item['quantity'],
															'max_value'   => $_product->get_max_purchase_quantity(),
															'min_value'   => '0'
														), $_product, false );
													}
													echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
												?>
											</td>
											<td class="product-subtotal">
												<?php
													echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
												?>
											</td>
										</tr>
										<?php
									}
								}
								do_action( 'woocommerce_cart_contents' );
								?>
								<tr>
									<td colspan="6" class="actions">
										<?php if ( version_compare($porto_woo_version, '2.5', '<') ? WC()->cart->coupons_enabled() : wc_coupons_enabled() ) { ?>
											<div class="panel-group">
												<div class="panel panel-default">
													<div class="panel-heading arrow">
														<h2 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" href="#panel-cart-discount"><?php _e( 'DISCOUNT CODE', 'porto' ); ?></a></h2>
													</div>
													<div id="panel-cart-discount" class="accordion-body collapse">
														<div class="panel-body">
															<div class="coupon">
																<label for="coupon_code"><?php _e( 'Enter your coupon code if you have one:', 'porto' ); ?></label>
																<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" />
																<input type="submit" class="btn btn-primary" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'porto' ); ?>" />
																<?php do_action('woocommerce_cart_coupon'); ?>
															</div>
														</div>
													</div>
												</div>
											</div>
											
										<?php } ?>
										<?php wp_nonce_field( 'woocommerce-cart' ); ?>
									</td>
								</tr>
								
								<?php do_action( 'woocommerce_after_cart_contents' ); ?>
							</tbody>
						</table>
						
						<div class="cart-actions">
							<a class="btn btn-default" href="<?php echo get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>"><?php _e( 'Continue Shopping', 'porto' ); ?></a>
							<input type="submit" class="btn btn-default pt-right" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'porto' ); ?>" />
							<?php do_action( 'woocommerce_cart_actions' ); ?>
						</div>
						<div class="clear"></div>
						<?php do_action( 'woocommerce_after_cart_table' ); ?>
					</form>
				</div>
			</div>
		</div>
		
		<div class="col-md-4 col-lg-3">
			<div class="cart-collaterals">
				<div class="cart_totals<?php if ( WC()->customer->has_calculated_shipping() ) echo ' calculated_shipping'; ?>">
					<div class="panel-group">
						<?php
							/**
							 * woocommerce_cart_collaterals hook.
							 *
							 * @hooked woocommerce_cross_sell_display
							 * @hooked woocommerce_cart_totals - 10
							 */
							do_action( 'woocommerce_cart_collaterals' ); 
						?>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>