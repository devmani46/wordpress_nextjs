<?php
// Register meta fields for Committees page
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
            'object_subtype' => 'page',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_meta('post', 'committees_hero_images', [
        'object_subtype' => 'page',
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
        'object_subtype' => 'page',
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
        'object_subtype' => 'page',
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
