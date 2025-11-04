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
