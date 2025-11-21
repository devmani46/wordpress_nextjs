<?php
/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

// Load all includes
require_once $inc . 'index.php';

// Theme setup
function nrna_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'nrna'),
    ]);
}
add_action('after_setup_theme', 'nrna_theme_setup');

// Add News & Events parent menu
function nrna_add_news_events_menu() {
    add_menu_page(
        'News & Events',
        'News & Events',
        'manage_options',
        'news-events-menu',
        '',
        'dashicons-admin-site',
        20
    );
}
add_action('admin_menu', 'nrna_add_news_events_menu');

// Add Gallery parent menu
function nrna_add_gallery_menu() {
    add_menu_page(
        'Gallery',
        'Gallery',
        'manage_options',
        'gallery-menu',
        '',
        'dashicons-images-alt2',
        25
    );
}
add_action('admin_menu', 'nrna_add_gallery_menu');
