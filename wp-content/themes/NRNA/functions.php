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

// Add News & Events parent menu
function nrna_add_news_events_menu()
{
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
function nrna_add_gallery_menu()
{
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

// Add About Us parent menu
function nrna_add_about_us_menu()
{
    add_menu_page(
        'About Us',
        'About Us',
        'manage_options',
        'about-us-menu',
        '',
        'dashicons-info',
        21
    );

    // 1. Who We Are (Page)
    // Find page by template
    $who_we_are_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-who-we-are.php',
        'number' => 1
    ]);

    $who_we_are_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($who_we_are_page)) {
        $who_we_are_link = 'post.php?post=' . $who_we_are_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Who We Are',
        'Who We Are',
        'manage_options',
        $who_we_are_link
    );

    // 2. Executive Committee (CPT)
    add_submenu_page(
        'about-us-menu',
        'Executive Committee',
        'Executive Committee',
        'manage_options',
        'edit.php?post_type=executive_committee'
    );

    // 3. Our NCCs (CPT)
    add_submenu_page(
        'about-us-menu',
        'Our NCCs',
        'Our NCCs',
        'manage_options',
        'edit.php?post_type=our_ncc'
    );

    // 4. Committees, Taskforces & Subcommittees (Page)
    $committees_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-committees-taskforces-subcommittees.php',
        'number' => 1
    ]);

    $committees_link = 'post-new.php?post_type=page';
    if (!empty($committees_page)) {
        $committees_link = 'post.php?post=' . $committees_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Committees & Taskforces',
        'Committees & Taskforces',
        'manage_options',
        $committees_link
    );

    // 5. NRNA Organizational Structure (Page)
    $org_structure_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-organizational-structure.php',
        'number' => 1
    ]);

    $org_structure_link = 'post-new.php?post_type=page';
    if (!empty($org_structure_page)) {
        $org_structure_link = 'post.php?post=' . $org_structure_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'about-us-menu',
        'Organizational Structure',
        'Organizational Structure',
        'manage_options',
        $org_structure_link
    );

    // Remove the default "About Us" submenu created by add_menu_page
    remove_submenu_page('about-us-menu', 'about-us-menu');
}
add_action('admin_menu', 'nrna_add_about_us_menu');

// Add NRNA Area parent menu
function nrna_add_nrna_area_menu()
{
    add_menu_page(
        'NRNA Area',
        'NRNA Area',
        'manage_options',
        'nrna-area-menu',
        '',
        'dashicons-networking',
        22
    );

    // 1. Contact Us (Page)
    // Find page by template
    $contact_us_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-contact.php',
        'number' => 1
    ]);

    $contact_us_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($contact_us_page)) {
        $contact_us_link = 'post.php?post=' . $contact_us_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'Contact Us',
        'Contact Us',
        'manage_options',
        $contact_us_link
    );

    // 3. NRNA Discount (Page)
    // Find page by template
    $nrna_discount_page = get_pages([
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-nrna-discount.php',
        'number' => 1
    ]);

    $nrna_discount_link = 'post-new.php?post_type=page'; // Default fallback
    if (!empty($nrna_discount_page)) {
        $nrna_discount_link = 'post.php?post=' . $nrna_discount_page[0]->ID . '&action=edit';
    }

    add_submenu_page(
        'nrna-area-menu',
        'NRNA Discount',
        'NRNA Discount',
        'manage_options',
        $nrna_discount_link
    );

    // Remove the default "NRNA Area" submenu created by add_menu_page
    remove_submenu_page('nrna-area-menu', 'nrna-area-menu');
}
add_action('admin_menu', 'nrna_add_nrna_area_menu');
