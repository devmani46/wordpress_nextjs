<?php
// Register Executive Committee custom post type
function nrna_register_executive_committee_cpt() {
    $labels = [
        'name'               => _x('Executive Committee', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Committee Member', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Executive Committee', 'nrna'),
        'name_admin_bar'     => __('Committee Member', 'nrna'),
        'add_new_item'       => __('Add New Committee Member', 'nrna'),
        'edit_item'          => __('Edit Committee Member', 'nrna'),
        'view_item'          => __('View Committee Member', 'nrna'),
        'all_items'          => __('All Committee Members', 'nrna'),
        'search_items'       => __('Search Committee Members', 'nrna'),
        'not_found'          => __('No committee members found.', 'nrna'),
        'not_found_in_trash' => __('No committee members found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 25,
        'has_archive'        => false,
        'rewrite'            => ['slug' => 'executive-committee'],
    ];

    register_post_type('executive_committee', $args);
}
add_action('init', 'nrna_register_executive_committee_cpt');

// Register meta fields for REST API
function nrna_register_executive_committee_meta_rest() {
    register_rest_field('executive_committee', 'committee_role', [
        'get_callback' => function($post) {
            return get_post_meta($post['id'], 'committee_role', true);
        },
        'schema' => [
            'type' => 'string',
            'description' => 'Committee role',
        ],
    ]);

    register_rest_field('executive_committee', 'hierarchy_order', [
        'get_callback' => function($post) {
            return (int) get_post_meta($post['id'], 'hierarchy_order', true);
        },
        'schema' => [
            'type' => 'integer',
            'description' => 'Hierarchy order',
        ],
    ]);
}
add_action('rest_api_init', 'nrna_register_executive_committee_meta_rest');
