<?php get_header(); ?>

<!-- ============ SPLASH ============
     Homepage only - deliberately not in header.php, so it never
     shows again on the shop page or a product page. -->
<div class="splash" id="splash">
  <canvas class="splash__canvas" id="splashCanvas"></canvas>
  <div class="splash__mark">
    <svg class="splash__logo" viewBox="-6 -6 476 112" aria-label="Khoji">
      <g><path class="ltr" style="animation-delay:.05s" d="M0 0h26v100H0z"/><path class="ltr" style="animation-delay:.05s" d="M34 50 74 0h26L60 50l40 50H74z"/></g>
      <g transform="translate(118,0)"><path class="ltr" style="animation-delay:.14s" d="M0 0h26v100H0z"/><path class="ltr" style="animation-delay:.14s" d="M66 0h26v100H66z"/><path class="ltr" style="animation-delay:.14s" d="M26 37h40v26H26z"/></g>
      <g class="ring"></g>
      <g transform="translate(346,0)"><path class="ltr" style="animation-delay:.32s" d="M74 0v70c0 18-14 30-32 30S10 88 10 70v-8h26v8c0 4 3 7 6 7s6-3 6-7V0z"/></g>
      <g transform="translate(438,0)"><path class="ltr" style="animation-delay:.41s" d="M0 0h26v100H0z"/></g>
    </svg>
    <span class="splash__word u-condensed">Find your fit</span>
  </div>
  <span class="splash__skip">Tap to skip</span>
</div>


<!-- ============ HERO ============
     Full-bleed shoe photo on black, headline sits BELOW the image -
     not squeezed onto it. Keeps text readable on any photo the
     client uploads, since it never sits on top of image detail. -->
<section class="hero">
  <div class="hero__frame">
    <?php
    // Client changes this from wp-admin: Appearance > Customize > Homepage Hero
    $hero_img = get_theme_mod( 'khoji_hero_image', '' );
    if ( $hero_img ) {
        echo '<img class="hero__img" src="' . esc_url( $hero_img ) . '" alt="Khoji">';
    } else {
        echo '<div class="hero__img ph"><span class="u-mono">Set in Customizer &rarr; Homepage Hero</span></div>';
    }
    ?>
  </div>

  <div class="wrap hero__caption">
    <span class="u-eyebrow hero__eyebrow">Just in</span>
    <h1 class="hero__title u-display">Runner 90</h1>
    <p class="hero__lede">Cushioned for every step. Cash on delivery, nationwide.</p>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="hero__cta">Shop men's</a>
  </div>
</section>

<!-- ============ MARQUEE ============ -->
<?php
$marquee_items = array_filter( array_map( 'trim',
    explode( '|', get_theme_mod( 'khoji_marquee_text', 'Free delivery nationwide | Cash on delivery | 7-day exchange | Find your fit' ) )
) );
$marquee_html = '';
foreach ( array_merge( $marquee_items, $marquee_items ) as $item ) {
    $marquee_html .= '<span class="marquee__item u-condensed">' . esc_html( $item ) . '</span>';
}
?>
<div class="marquee" aria-hidden="true">
  <div class="marquee__track">
    <div class="marquee__group"><?php echo $marquee_html; ?></div>
    <div class="marquee__group"><?php echo $marquee_html; ?></div>
  </div>
</div>

<!-- ============ CATEGORIES ============ -->
<section class="cats wrap">
  <div class="cats__grid">
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="cat cat--live">
      <span class="cat__name u-display">Men</span>
      <span class="cat__go">Shop now</span>
    </a>
    <div class="cat cat--soon">
      <span class="cat__name u-display">Women</span>
      <span class="cat__go">Coming soon</span>
    </div>
    <div class="cat cat--soon">
      <span class="cat__name u-display">Kids</span>
      <span class="cat__go">Coming soon</span>
    </div>
  </div>
</section>

<!-- ============ MID-PAGE FEATURE ============
     Sits right after Men/Women/Kids. A single strong photo, full
     width, with moving white lines drifting over the dark overlay -
     same visual family as the splash and footer dot fields.
     Client sets the photo and caption from
     Appearance > Customize > Homepage Hero. -->
<?php
$parallax_img  = get_theme_mod( 'khoji_parallax_image', '' );
$parallax_text = get_theme_mod( 'khoji_parallax_text', 'Built for the street. Made to be worn.' );
?>
<section class="feature">
  <div class="feature__bg" <?php echo $parallax_img ? 'style="background-image:url(' . esc_url( $parallax_img ) . ')"' : ''; ?>>
    <?php if ( ! $parallax_img ) : ?>
      <div class="feature__ph"><span class="u-mono">Set in Customizer &rarr; Mid-page feature image</span></div>
    <?php endif; ?>
  </div>
  <div class="feature__overlay"></div>
  <canvas class="feature__lines" id="featureLines" aria-hidden="true"></canvas>
  <p class="feature__text u-display"><?php echo esc_html( $parallax_text ); ?></p>
</section>

<!-- ============ SHOP BY BRAND ============
     Real WooCommerce product categories. In wp-admin, create a
     category per brand (Nike, Adidas...) with a category thumbnail -
     that thumbnail is what shows here. -->
<section class="brands wrap" id="brands">
  <div class="brands__head">
    <div>
      <span class="u-eyebrow">Every pair listed in UK and EU sizes</span>
      <h2 class="brands__title u-display">Shop by brand</h2>
    </div>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="shelf__all">All brands</a>
  </div>
  <div class="brands__grid">
    <?php
    $brand_terms = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0, // top-level categories only
    ) );

    if ( ! empty( $brand_terms ) && ! is_wp_error( $brand_terms ) ) :
        foreach ( $brand_terms as $term ) :
            if ( 'uncategorized' === $term->slug ) continue;
            $term_url = get_term_link( $term );
            if ( is_wp_error( $term_url ) ) continue; // broken link - skip the tile rather than link to nowhere
            $thumb_id  = get_term_meta( $term->term_id, 'thumbnail_id', true );
            $thumb_url = $thumb_id ? wp_get_attachment_url( $thumb_id ) : '';
            ?>
            <a href="<?php echo esc_url( $term_url ); ?>" class="brand-tile">
              <div class="brand-tile__media">
                <?php if ( $thumb_url ) : ?>
                  <img class="brand-tile__img" src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy">
                <?php else : ?>
                  <div class="brand-tile__img ph"><span class="u-mono"><?php echo esc_html( $term->name ); ?></span></div>
                <?php endif; ?>
              </div>
              <span class="brand-tile__name"><?php echo esc_html( $term->name ); ?></span>
              <span class="brand-tile__count"><?php echo intval( $term->count ); ?> styles</span>
            </a>
        <?php endforeach;
    else : ?>
      <p class="empty">Add product categories in wp-admin (e.g. Nike, Adidas) to fill this in.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ============ BEST SELLERS ============
     Real products, newest 8. Swap 'date' for 'popularity' once you
     have real order history (WooCommerce → Settings → Products). -->
<section class="shelf wrap" id="shelf">
  <div class="shelf__head">
    <div>
      <span class="u-eyebrow">In stock &middot; ships in 48 hours</span>
      <h2 class="shelf__title u-display">Best sellers</h2>
    </div>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="shelf__all">View all</a>
  </div>
  <div class="grid">
    <?php
    $products = wc_get_products( array(
        'status'  => 'publish',
        'limit'   => 8,
        'orderby' => 'date',
        'order'   => 'DESC',
    ) );

    if ( $products ) {
        foreach ( $products as $product ) {
            get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
        }
    } else {
        echo '<p class="empty">No products yet &mdash; add some in WooCommerce &rarr; Products &rarr; Add New.</p>';
    }
    ?>
  </div>
</section>

<!-- ============ PROMISES ============ -->
<section class="promo">
  <div class="wrap promo__grid">
    <div class="promo__item">
      <span class="promo__num">01</span>
      <h3>Cash on delivery</h3>
      <p>Pay the rider when the box is in your hands. No card, no advance, no account needed.</p>
    </div>
    <div class="promo__item">
      <span class="promo__num">02</span>
      <h3>7-day exchange</h3>
      <p>Wrong size is the most common problem in online shoes. Send it back unworn within 7 days and we swap it.</p>
    </div>
    <div class="promo__item">
      <span class="promo__num">03</span>
      <h3>UK and EU sizing</h3>
      <p>Every pair is listed in both. Measure your foot once against our chart and order with confidence.</p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
