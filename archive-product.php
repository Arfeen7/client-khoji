<?php get_header(); ?>

<section class="wrap page-top">
  <nav class="crumbs">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span>
    <?php echo is_product_category() ? single_term_title( '', false ) : "Men's shoes"; ?>
  </nav>
  <h1 class="page-title u-display">
    <?php echo is_product_category() ? single_term_title( '', false ) : "Men's shoes"; ?>
  </h1>
  <p class="page-count"><?php global $wp_query; echo intval( $wp_query->found_posts ); ?> styles</p>
</section>

<div class="wrap shop">

  <!-- ============ FILTERS ============
       Real WooCommerce filtering. These use WooCommerce's own
       widgets so ticking a box actually reloads the filtered list -
       no custom JS filter logic needed like in the HTML prototype. -->
  <aside class="filters" id="filters">
    <div class="fgroup">
      <h4>Brand</h4>
      <?php
      if ( is_active_sidebar( 'khoji-shop-filters' ) ) {
          dynamic_sidebar( 'khoji-shop-filters' );
      } else {
          echo '<p style="font-size:12px;color:var(--mute)">Add the "Filter Products by Category" and "Filter by Attribute (Size)" widgets here: Appearance &rarr; Widgets &rarr; Khoji Shop Filters.</p>';
      }
      ?>
    </div>
    <div class="fgroup">
      <button class="fclear" id="fClear" onclick="location.href='<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>'">Clear all</button>
    </div>
  </aside>

  <div>
    <div class="shopbar">
      <button class="fbtn" id="fToggle">Filters</button>
      <?php woocommerce_catalog_ordering(); ?>
    </div>

    <div class="grid">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post();
          global $product;
          get_template_part( 'template-parts/product-card', null, array( 'product' => $product ) );
        endwhile; ?>
      <?php else : ?>
        <p class="empty">No shoes match those filters yet.</p>
      <?php endif; ?>
    </div>

    <?php
    echo '<div class="wrap" style="padding-block:30px 0">';
    woocommerce_pagination();
    echo '</div>';
    ?>
  </div>
</div>

<?php get_footer(); ?>
