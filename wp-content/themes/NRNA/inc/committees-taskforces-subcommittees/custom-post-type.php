<?php
// Register Committees, Taskforces & Subcommittees custom post type
function nrna_register_committees_cpt() {
    $labels = [
        'name'               => _x('Committees, Taskforces & Subcommittees', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Committee, Taskforce & Subcommittee', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Committees, Taskforces & Subcommittees', 'nrna'),
        'name_admin_bar'     => __('Committees, Taskforces & Subcommittees', 'nrna'),
        'add_new_item'       => __('Add New Committee, Taskforce & Subcommittee', 'nrna'),
        'edit_item'          => __('Edit Committee, Taskforce & Subcommittee', 'nrna'),
        'view_item'          => __('View Committee, Taskforce & Subcommittee', 'nrna'),
        'all_items'          => __('All Committees, Taskforces & Subcommittees', 'nrna'),
        'search_items'       => __('Search Committees, Taskforces & Subcommittees', 'nrna'),
        'not_found'          => __('No committees, taskforces & subcommittees found.', 'nrna'),
        'not_found_in_trash' => __('No committees, taskforces & subcommittees found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 24, // Below Events (23)
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'committees-taskforces-subcommittees'],
        'show_in_menu'       => true,
        'hierarchical'       => false,
    ];

    register_post_type('committees-taskforces-subcommittees', $args);
}
add_action('init', 'nrna_register_committees_cpt');

// Register meta fields for Committees, Taskforces & Subcommittees
function nrna_register_committees_meta_fields() {
    $fields = [
        'committees_hero_title' => ['type' => 'string'],
        'committees_hero_description' => ['type' => 'string'],
        'committees_why_title' => ['type' => 'string'],
        'committees_why_description' => ['type' => 'string'],
        'committees_why_image' => ['type' => 'integer'],
        'committees_how_title' => ['type' => 'string'],
        'committees_how_description' => ['type' => 'string'],
        'committees_how_image' => ['type' => 'integer'],
        'committees_banner1_title' => ['type' => 'string'],
        'committees_banner1_description' => ['type' => 'string'],
        'committees_banner1_cta_link' => ['type' => 'string'],
        'committees_banner1_cta_title' => ['type' => 'string'],
        'committees_teams_title' => ['type' => 'string'],
        'committees_banner2_title' => ['type' => 'string'],
        'committees_banner2_description' => ['type' => 'string'],
        'committees_banner2_cta_link' => ['type' => 'string'],
        'committees_banner2_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'committees-taskforces-subcommittees',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_meta('post', 'committees_hero_images', [
        'object_subtype' => 'committees-taskforces-subcommittees',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'image' => ['type' => 'integer'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_meta('post', 'committees_banner1_stats', [
        'object_subtype' => 'committees-taskforces-subcommittees',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_meta('post', 'committees_teams_members', [
        'object_subtype' => 'committees-taskforces-subcommittees',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'project' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'email' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_committees_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_committees_rest($response, $post, $request) {
    if ($post->post_type !== 'committees-taskforces-subcommittees') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'committees_hero_title',
        'committees_hero_description',
        'committees_why_title',
        'committees_why_description',
        'committees_why_image',
        'committees_how_title',
        'committees_how_description',
        'committees_how_image',
        'committees_banner1_title',
        'committees_banner1_description',
        'committees_banner1_cta_link',
        'committees_banner1_cta_title',
        'committees_teams_title',
        'committees_banner2_title',
        'committees_banner2_description',
        'committees_banner2_cta_link',
        'committees_banner2_cta_title',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['committees_hero_images'] = get_post_meta($post->ID, 'committees_hero_images', true);
    $data['committees_banner1_stats'] = get_post_meta($post->ID, 'committees_banner1_stats', true);
    $data['committees_teams_members'] = get_post_meta($post->ID, 'committees_teams_members', true);

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_committees-taskforces-subcommittees', 'nrna_prepare_committees_rest', 10, 3);
