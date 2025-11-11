<?php
// Register Who We Are custom post type
function nrna_register_who_we_are_cpt() {
    $labels = [
        'name'               => _x('Who We Are', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Who We Are', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Who We Are', 'nrna'),
        'name_admin_bar'     => __('Who We Are', 'nrna'),
        'add_new_item'       => __('Add New Who We Are', 'nrna'),
        'edit_item'          => __('Edit Who We Are', 'nrna'),
        'view_item'          => __('View Who We Are', 'nrna'),
        'all_items'          => __('All Who We Are', 'nrna'),
        'search_items'       => __('Search Who We Are', 'nrna'),
        'not_found'          => __('No who we are found.', 'nrna'),
        'not_found_in_trash' => __('No who we are found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 21,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'who-we-are'],
        'show_in_menu'       => true,
    ];

    register_post_type('who-we-are', $args);
}
add_action('init', 'nrna_register_who_we_are_cpt');

// Register meta fields for Who We Are
function nrna_register_who_we_are_meta_fields() {
    $fields = [
        'who_we_are_hero_title' => ['type' => 'string'],
        'who_we_are_hero_description' => ['type' => 'string'],
        'who_we_are_vision_title' => ['type' => 'string'],
        'who_we_are_vision_description' => ['type' => 'string'],
        'who_we_are_vision_image' => ['type' => 'integer'],
        'who_we_are_goals_title' => ['type' => 'string'],
        'who_we_are_goals_description' => ['type' => 'string'],
        'who_we_are_goals_image' => ['type' => 'integer'],
        'who_we_are_certificate_title' => ['type' => 'string'],
        'who_we_are_certificate_description' => ['type' => 'string'],
        'who_we_are_certificate_image' => ['type' => 'integer'],
        'who_we_are_message_title' => ['type' => 'string'],
        'who_we_are_message_description' => ['type' => 'string'],
        'who_we_are_message_image' => ['type' => 'integer'],
        'who_we_are_message_representative_name' => ['type' => 'string'],
        'who_we_are_message_representative_role' => ['type' => 'string'],
        'who_we_are_team_title' => ['type' => 'string'],
        'who_we_are_team_description' => ['type' => 'string'],
        'who_we_are_team_cta_link' => ['type' => 'string'],
        'who_we_are_team_cta_title' => ['type' => 'string'],
        'who_we_are_team_image' => ['type' => 'integer'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'who-we-are',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_meta('post', 'who_we_are_slider_items', [
        'object_subtype' => 'who-we-are',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'image' => ['type' => 'integer'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_who_we_are_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_who_we_are_rest($response, $post, $request) {
    if ($post->post_type !== 'who-we-are') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'who_we_are_hero_title',
        'who_we_are_hero_description',
        'who_we_are_vision_title',
        'who_we_are_vision_description',
        'who_we_are_vision_image',
        'who_we_are_goals_title',
        'who_we_are_goals_description',
        'who_we_are_goals_image',
        'who_we_are_certificate_title',
        'who_we_are_certificate_description',
        'who_we_are_certificate_image',
        'who_we_are_message_title',
        'who_we_are_message_description',
        'who_we_are_message_image',
        'who_we_are_message_representative_name',
        'who_we_are_message_representative_role',
        'who_we_are_team_title',
        'who_we_are_team_description',
        'who_we_are_team_cta_link',
        'who_we_are_team_cta_title',
        'who_we_are_team_image',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['who_we_are_slider_items'] = get_post_meta($post->ID, 'who_we_are_slider_items', true);

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_who-we-are', 'nrna_prepare_who_we_are_rest', 10, 3);
