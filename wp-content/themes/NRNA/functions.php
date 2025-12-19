<?php

/**
 * NRNA Theme Functions
 */

// Load includes
$inc = get_template_directory() . '/inc/';

// Load all includes
require_once $inc . 'index.php';

// Theme setup
function nrna_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'nrna'),
    ]);
}
add_action('after_setup_theme', 'nrna_theme_setup');

// Load Parent Menus
require_once $inc . 'parent-menus.php';


// Load Contact Us
require_once $inc . 'contact-us.php';

// Load Subscribe
require_once $inc . 'subscribe.php';

// Load Event Registrations
require_once $inc . 'event-registrations.php';

// Load Regional Meeting Registrations
require_once $inc . 'regional-meeting-registrations.php';

// Load Search API
require_once $inc . 'search-api.php';

// Load Project Inquiries
require_once $inc . 'project-inquiries.php';

// Auto-update slug when title updates
function nrna_auto_update_slug($data, $postarr)
{
    if (!in_array($data['post_status'], ['draft', 'pending', 'auto-draft'])) {
        $data['post_name'] = sanitize_title($data['post_title']);
    }
    return $data;
}
add_filter('wp_insert_post_data', 'nrna_auto_update_slug', 99, 2);