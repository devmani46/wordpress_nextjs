<?php
/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

require_once $inc . 'enqueue-scripts.php';

// Load projects includes
require_once $inc . 'projects/custom-post-type.php';
require_once $inc . 'projects/meta-boxes.php';

// Load faqs includes
require_once $inc . 'faqs/custom-post-type.php';
require_once $inc . 'faqs/meta-boxes.php';

// Load notices includes
require_once $inc . 'notices/custom-post-type.php';
require_once $inc . 'notices/meta-boxes.php';

// Load activities includes
require_once $inc . 'activities/custom-post-type.php';
require_once $inc . 'activities/meta-boxes.php';

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