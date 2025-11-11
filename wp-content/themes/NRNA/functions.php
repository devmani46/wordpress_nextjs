<?php
/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

require_once $inc . 'enqueue-scripts.php';

// Load home meta box
require_once $inc . 'home-meta-box.php';

// Load about meta box
require_once $inc . 'about-meta-box.php';

// Load projects includes
require_once $inc . 'projects/custom-post-type.php';
require_once $inc . 'projects/meta-boxes.php';

// Load events includes
require_once $inc . 'events/custom-post-type.php';
require_once $inc . 'events/meta-boxes.php';


// Load faqs includes
require_once $inc . 'faqs/custom-post-type.php';
require_once $inc . 'faqs/meta-boxes.php';

// Load notices includes
require_once $inc . 'notices/custom-post-type.php';
require_once $inc . 'notices/meta-boxes.php';

// Load activities includes
require_once $inc . 'activities/custom-post-type.php';
require_once $inc . 'activities/meta-boxes.php';

// Load resources includes
require_once $inc . 'resources/custom-post-type.php';
require_once $inc . 'resources/meta-boxes.php';

// Load news includes
require_once $inc . 'news/custom-post-type.php';
require_once $inc . 'news/meta-boxes.php';

// Load executive committee includes
require_once $inc . 'executive-committee/custom-post-type.php';
require_once $inc . 'executive-committee/meta-boxes.php';

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