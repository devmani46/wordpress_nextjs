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
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_post_meta('page', 'committees_hero_images', [
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

    register_post_meta('page', 'committees_banner1_stats', [
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

    register_post_meta('page', 'committees_teams_members', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'project' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'role' => ['type' => 'string'],
                'email' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_committees_meta_fields');
