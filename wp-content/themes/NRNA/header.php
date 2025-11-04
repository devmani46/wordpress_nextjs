<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header id="site-header" class="site-header">
  <div class="container flex justify-between items-center py-4">
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="site-title">
      <?php bloginfo('name'); ?>
    </a>
    <nav class="main-navigation">
      <?php wp_nav_menu(array('theme_location' => 'primary')); ?>
    </nav>
  </div>
</header>
