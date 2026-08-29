<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="elan-header" id="top">
  <div class="elan-shell elan-header__inner">
    <a class="elan-brand" href="<?php echo esc_url(home_url('/')); ?>">Maison Élan</a>
    <nav class="elan-nav" aria-label="Primary navigation">
      <?php wp_nav_menu(['theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'elan_menu_fallback']); ?>
    </nav>
    <a class="elan-button" href="#contact">Book consultation</a>
    <button class="elan-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">☰</button>
  </div>
</header>
