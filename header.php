<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/logo/khoji-mark-dark.svg">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ============ HEADER ============ -->
<header class="header" id="header">
  <nav class="nav">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" aria-label="Khoji home">
      <span class="brand__full"><?php bloginfo( 'name' ); ?></span>
      <span class="brand__mark">K</span>
    </a>

    <div class="nav__links">
      <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="nav__link">Men</a>
      <span class="nav__link nav__link--soon">Women</span>
      <span class="nav__link nav__link--soon">Kids</span>
      <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="nav__link">New in</a>
      <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) . '?on_sale=1' ); ?>" class="nav__link nav__link--sale">Sale</a>
    </div>

    <div class="nav__tools">
      <div class="search-wrap" id="searchWrap">
        <button class="icon-btn" id="searchToggle" aria-label="Search">
          <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
        </button>
        <form class="search-box" action="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" method="get">
          <input type="search" name="s" placeholder="Search shoes..." autocomplete="off">
          <button type="submit" aria-label="Go">
            <svg viewBox="0 0 24 24"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </button>
        </form>
      </div>
      <a class="icon-btn" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="Account">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
      </a>
      <a class="icon-btn cart-btn" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Cart">
        <svg viewBox="0 0 24 24"><path d="M6 7h12l-1.2 12.2a2 2 0 01-2 1.8H9.2a2 2 0 01-2-1.8L6 7z"/><path d="M9 7V5.5a3 3 0 016 0V7"/></svg>
        <span><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
      </a>
      <button class="icon-btn burger" id="burger" aria-label="Menu">
        <svg viewBox="0 0 24 24"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </nav>
</header>

<div class="mmenu" id="mmenu">
  <button class="mmenu__close" id="mclose" aria-label="Close menu">&times;</button>
  <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="u-display">Men</a>
  <a href="#" class="u-display is-soon">Women</a>
  <a href="#" class="u-display is-soon">Kids</a>
</div>
