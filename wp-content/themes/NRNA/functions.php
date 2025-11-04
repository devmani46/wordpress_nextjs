<?php
/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

require_once $inc . 'custom-post-types.php';
require_once $inc . 'meta-fields.php';
require_once $inc . 'enqueue-scripts.php';

// Theme setup
function nrna_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'nrna'),
    ]);
}
add_action('after_setup_theme', 'nrna_theme_setup');
?>