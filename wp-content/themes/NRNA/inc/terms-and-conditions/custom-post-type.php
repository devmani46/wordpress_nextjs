<?php
// Register Terms and Conditions custom post type
function nrna_register_terms_and_conditions_cpt() {
    $labels = [
        'name'               => _x('Terms and Conditions', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Terms and Conditions', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Terms and Conditions', 'nrna'),
        'name_admin_bar'     => __('Terms and Conditions', 'nrna'),
        'add_new_item'       => __('Add New Terms and Conditions', 'nrna'),
        'edit_item'          => __('Edit Terms and Conditions', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-admin-page',
        'menu_position'      => 22,
    ];

    register_post_type('terms-and-conditions', $args);
}
add_action('init', 'nrna_register_terms_and_conditions_cpt');
