<?php
// Register FAQ custom post type
function nrna_register_faq_cpt() {
    $labels = [
        'name'               => _x('FAQs', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('FAQ', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('FAQs', 'nrna'),
        'name_admin_bar'     => __('FAQ', 'nrna'),
        'add_new_item'       => __('Add New FAQ', 'nrna'),
        'edit_item'          => __('Edit FAQ', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => false,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-editor-help',
        'menu_position'      => 20,
    ];

    register_post_type('faqs', $args);
}
add_action('init', 'nrna_register_faq_cpt');

// Register Notice custom post type
function nrna_register_notice_cpt() {
    $labels = [
        'name'               => _x('Notices', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Notice', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Notices', 'nrna'),
        'name_admin_bar'     => __('Notice', 'nrna'),
        'add_new_item'       => __('Add New Notice', 'nrna'),
        'edit_item'          => __('Edit Notice', 'nrna'),
        'view_item'          => __('View Notice', 'nrna'),
        'all_items'          => __('All Notices', 'nrna'),
        'search_items'       => __('Search Notices', 'nrna'),
        'not_found'          => __('No notices found.', 'nrna'),
        'not_found_in_trash' => __('No notices found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => false,
        'supports'           => ['title', 'editor', 'thumbnail'],
        'menu_icon'          => 'dashicons-megaphone',
        'menu_position'      => 21,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'notices'],
    ];

    register_post_type('notices', $args);
}
add_action('init', 'nrna_register_notice_cpt');
