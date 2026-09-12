<?php
/**
 * One product card - used on the homepage, the shop page, and
 * "you may also like".
 *
 * Single photo per product. The client uploads ONE clean shot in
 * "Product image" - no second angle required. (We can bring back
 * the side/top hover-swap later once there's a consistent set of
 * two-angle photos - it isn't needed for the site to look right.)
 */
$product = $args['product'];
if ( ! $product ) return;

$permalink   = get_permalink( $product->get_id() );
$name        = $product->get_name();
$brand_terms = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
$brand       = ( is_array( $brand_terms ) && ! empty( $brand_terms ) ) ? reset( $brand_terms ) : '';

$img_id  = $product->get_image_id();
$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : '';

$is_on_sale = $product->is_on_sale();
$is_new     = ( strtotime( $product->get_date_created() ) > strtotime( '-14 days' ) );
?>
<article class="card">
  <a class="card__media" href="<?php echo esc_url( $permalink ); ?>">
    <?php if ( $is_on_sale ) : ?>
      <span class="card__badge card__badge--sale">Sale</span>
    <?php elseif ( $is_new ) : ?>
      <span class="card__badge">New in</span>
    <?php endif; ?>

    <?php if ( $img_url ) : ?>
      <img class="shoe" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
    <?php else : ?>
      <div class="shoe ph"><span class="u-mono">photo</span></div>
    <?php endif; ?>

    <div class="card__sizes">
      <p>Available sizes &mdash; UK</p>
      <div class="sizes">
        <?php foreach ( khoji_get_sizes( $product ) as $size ) : ?>
          <span class="size<?php echo $size['in_stock'] ? '' : ' is-out'; ?>">
            <?php echo esc_html( preg_replace( '/UK\s*/i', '', $size['label'] ) ); ?>
          </span>
        <?php endforeach; ?>
      </div>
    </div>
  </a>

  <div class="card__meta">
    <span class="card__brandline"><?php echo esc_html( $brand ); ?></span>
    <h3 class="card__name"><a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $name ); ?></a></h3>
    <div class="card__price">
      <?php echo wp_kses_post( $product->get_price_html() ); ?>
    </div>
  </div>
</article>
