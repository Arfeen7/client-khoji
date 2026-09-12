<?php
get_header();

while ( have_posts() ) : the_post();
    global $product;

    $gallery_ids = $product->get_gallery_image_ids();
    $main_id     = $product->get_image_id();
    $shots       = array();
    if ( $main_id ) $shots[] = $main_id;
    foreach ( $gallery_ids as $id ) $shots[] = $id;
    $shots = array_slice( $shots, 0, 4 );

    $brand_terms = wc_get_product_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) );
    $brand       = ( is_array( $brand_terms ) && ! empty( $brand_terms ) ) ? reset( $brand_terms ) : '';
    $sizes       = khoji_get_sizes( $product );
    ?>

<section class="wrap page-top">
  <nav class="crumbs">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>">Men</a><span>/</span>
    <?php the_title(); ?>
  </nav>
</section>

<div class="wrap pdp">
  <div class="gal">
    <div class="gal__main" id="galMain">
      <?php if ( ! empty( $shots ) ) : ?>
        <img src="<?php echo esc_url( wp_get_attachment_image_url( $shots[0], 'large' ) ); ?>" alt="<?php the_title_attribute(); ?>">
      <?php else : ?>
        <div class="ph"><span class="u-mono">side</span></div>
      <?php endif; ?>
    </div>
    <div class="gal__thumbs">
      <?php foreach ( $shots as $i => $id ) : ?>
        <button class="gal__thumb<?php echo $i === 0 ? ' on' : ''; ?>"
                data-full="<?php echo esc_url( wp_get_attachment_image_url( $id, 'large' ) ); ?>">
          <img class="gal__timg" src="<?php echo esc_url( wp_get_attachment_image_url( $id, 'thumbnail' ) ); ?>" alt="">
        </button>
      <?php endforeach; ?>
      <?php for ( $i = count( $shots ); $i < 2; $i++ ) : ?>
        <div class="gal__thumb ph"><span class="u-mono"><?php echo $i === 0 ? 'side' : 'top'; ?></span></div>
      <?php endfor; ?>
    </div>
  </div>

  <div class="pinfo">
    <span class="pinfo__brand"><?php echo esc_html( $brand ); ?></span>
    <h1 class="pinfo__title u-display"><?php the_title(); ?></h1>
    <div class="pinfo__price">
      <?php echo wp_kses_post( $product->get_price_html() ); ?>
    </div>

    <div class="szhead">
      <b>Select size</b>
      <a href="#">Size guide</a>
    </div>

    <?php if ( $product->is_type( 'variable' ) ) : ?>
      <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype="multipart/form-data">
        <div class="szgrid">
          <?php foreach ( $product->get_available_variations() as $variation ) :
              $v_obj  = wc_get_product( $variation['variation_id'] );
              $label  = $variation['attributes']['attribute_pa_size'] ?? '';
              $in_stock = $v_obj && $v_obj->is_in_stock();
              $parts = preg_split( '/\s*\/\s*/', $label ); // "UK 8 / EU 42" -> ["UK 8","EU 42"]
              ?>
              <label class="sz<?php echo $in_stock ? '' : ' out'; ?>">
                <input type="radio" name="variation_id" value="<?php echo esc_attr( $variation['variation_id'] ); ?>"
                       style="display:none" <?php disabled( ! $in_stock ); ?>>
                <?php echo esc_html( $parts[0] ?? $label ); ?>
                <small><?php echo esc_html( $parts[1] ?? '' ); ?></small>
              </label>
          <?php endforeach; ?>
        </div>
        <button type="submit" class="addbtn" id="addBtn" disabled>Select a size</button>
      </form>
    <?php else : ?>
      <?php woocommerce_template_single_add_to_cart(); ?>
    <?php endif; ?>

    <button class="wishbtn">&#9825;&nbsp; Save for later</button>

    <div class="psell">
      <div><i></i><span><b>Cash on delivery.</b> Pay the rider at your door.</span></div>
      <div><i></i><span><b>7-day exchange.</b> Wrong size? Send it back unworn.</span></div>
      <div><i></i><span><b>Ships in 48 hours</b> from Rawalpindi, nationwide.</span></div>
    </div>

    <div class="acc">
      <div class="acc__item on">
        <button class="acc__btn">Description <span>+</span></button>
        <div class="acc__body"><p><?php echo wp_kses_post( $product->get_description() ); ?></p></div>
      </div>
      <div class="acc__item">
        <button class="acc__btn">Size &amp; fit <span>+</span></button>
        <div class="acc__body"><p>Listed in UK with the EU equivalent underneath. If you are between sizes, take the larger one. Measure your foot in the evening, when it is at its widest.</p></div>
      </div>
      <div class="acc__item">
        <button class="acc__btn">Delivery &amp; returns <span>+</span></button>
        <div class="acc__body"><p>Dispatched within 48 hours from Rawalpindi. Free nationwide delivery, paid in cash on arrival. Unworn pairs can be exchanged within 7 days &mdash; message us on WhatsApp to arrange pickup.</p></div>
      </div>
    </div>
  </div>
</div>

<section class="shelf wrap">
  <div class="shelf__head">
    <div>
      <span class="u-eyebrow">Same size, same fit</span>
      <h2 class="shelf__title u-display">You may also like</h2>
    </div>
    <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="shelf__all">View all</a>
  </div>
  <div class="grid">
    <?php
    $related = wc_get_related_products( $product->get_id(), 4 );
    foreach ( $related as $rid ) {
        $rp = wc_get_product( $rid );
        get_template_part( 'template-parts/product-card', null, array( 'product' => $rp ) );
    }
    if ( empty( $related ) ) echo '<p class="empty">Add more products to see recommendations here.</p>';
    ?>
  </div>
</section>

<script>
/* thumbnail click + size pick - same behaviour as the HTML prototype,
   just talking to real markup instead of a JS product array */
document.querySelectorAll('.gal__thumb[data-full]').forEach(function(t){
  t.addEventListener('click', function(){
    document.querySelectorAll('.gal__thumb').forEach(function(x){ x.classList.remove('on'); });
    t.classList.add('on');
    document.getElementById('galMain').innerHTML =
      '<img src="' + t.dataset.full + '" alt="">';
  });
});
document.querySelectorAll('.sz:not(.out)').forEach(function(s){
  s.addEventListener('click', function(){
    document.querySelectorAll('.sz').forEach(function(x){ x.classList.remove('on'); });
    s.classList.add('on');
    var btn = document.getElementById('addBtn');
    if(btn){ btn.disabled = false; btn.textContent = 'Add to cart'; }
  });
});
document.querySelectorAll('.acc__btn').forEach(function(b){
  b.addEventListener('click', function(){ b.parentElement.classList.toggle('on'); });
});
</script>

<?php endwhile; ?>
<?php get_footer(); ?>
