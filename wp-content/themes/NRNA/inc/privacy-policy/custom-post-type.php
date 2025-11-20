<?php
// Register Privacy Policy custom post type
function nrna_register_privacy_policy_cpt() {
    $labels = [
        'name'               => _x('Privacy Policies', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Privacy Policy', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Privacy Policies', 'nrna'),
        'name_admin_bar'     => __('Privacy Policy', 'nrna'),
        'add_new_item'       => __('Add New Privacy Policy', 'nrna'),
        'edit_item'          => __('Edit Privacy Policy', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'show_in_nav_menus'  => false,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-privacy',
        'menu_position'      => 21,
    ];

    register_post_type('privacy-policy', $args);
}
add_action('init', 'nrna_register_privacy_policy_cpt');
