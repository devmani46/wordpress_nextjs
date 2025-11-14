<?php
/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

require_once $inc . 'enqueue-scripts.php';

// Load home meta box
require_once $inc . 'home-meta-box.php';

// Load who-we-are meta box
require_once $inc . 'who-we-are-meta-box.php';

// Load organizational structure meta box
require_once $inc . 'organizational-structure-meta-box.php';

// Load contact meta box
require_once $inc . 'contact-meta-box.php';



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

// Load reports & publications includes
require_once $inc . 'reports-publications/custom-post-type.php';
require_once $inc . 'reports-publications/meta-boxes.php';

// Load news includes
require_once $inc . 'news/custom-post-type.php';
require_once $inc . 'news/meta-boxes.php';

// Load executive committee includes
require_once $inc . 'executive-committee/custom-post-type.php';
require_once $inc . 'executive-committee/meta-boxes.php';

// Load galleries includes
require_once $inc . 'galleries/custom-post-type.php';
require_once $inc . 'galleries/meta-boxes.php';

// Load videos includes
require_once $inc . 'videos/custom-post-type.php';
require_once $inc . 'videos/meta-boxes.php';

// Load committees, taskforces & subcommittees includes
require_once $inc . 'committees-taskforces-subcommittees/index.php';

// Load privacy policy includes
require_once $inc . 'privacy-policy/custom-post-type.php';
require_once $inc . 'privacy-policy/meta-boxes.php';

// Load terms and conditions includes
require_once $inc . 'terms-and-conditions/custom-post-type.php';
require_once $inc . 'terms-and-conditions/meta-boxes.php';

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
