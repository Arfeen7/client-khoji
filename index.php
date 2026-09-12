<?php
/**
 * Fallback template.
 * WordPress requires every theme to have an index.php, even though
 * front-page.php, archive-product.php and single-product.php handle
 * the pages this store actually uses. This only ever shows if none
 * of those more specific templates match.
 */
get_header();
?>
<div class="wrap page-top">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
    <h1 class="page-title u-display"><?php the_title(); ?></h1>
    <div><?php the_content(); ?></div>
  <?php endwhile; else : ?>
    <p class="empty">Nothing here yet.</p>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
