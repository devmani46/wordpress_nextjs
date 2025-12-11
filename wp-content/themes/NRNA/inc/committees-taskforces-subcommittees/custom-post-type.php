<?php
// Register meta fields for Committees page
function nrna_register_committees_meta_fields()
{
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
                'tenure_from' => ['type' => 'string'],
                'tenure_to' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_committees_meta_fields');

// Customize REST API response for Committees page
function nrna_custom_committees_rest_response($response, $post, $request)
{
    if ($post->post_type === 'page' && get_post_meta($post->ID, '_wp_page_template', true) === 'template-committees-taskforces-subcommittees.php') {
        // Only override data for non-edit contexts to avoid breaking the editor
        if ($request->get_param('context') !== 'edit') {
            $data = [
                'hero' => [
                    'title' => get_post_meta($post->ID, 'committees_hero_title', true),
                    'description' => get_post_meta($post->ID, 'committees_hero_description', true),
                    'images' => get_post_meta($post->ID, 'committees_hero_images', true) ?: [],
                ],
                'why' => [
                    'title' => get_post_meta($post->ID, 'committees_why_title', true),
                    'description' => get_post_meta($post->ID, 'committees_why_description', true),
                    'image' => get_post_meta($post->ID, 'committees_why_image', true),
                ],
                'how' => [
                    'title' => get_post_meta($post->ID, 'committees_how_title', true),
                    'description' => get_post_meta($post->ID, 'committees_how_description', true),
                    'image' => get_post_meta($post->ID, 'committees_how_image', true),
                ],
                'banner1' => [
                    'title' => get_post_meta($post->ID, 'committees_banner1_title', true),
                    'description' => get_post_meta($post->ID, 'committees_banner1_description', true),
                    'cta_link' => get_post_meta($post->ID, 'committees_banner1_cta_link', true),
                    'cta_title' => get_post_meta($post->ID, 'committees_banner1_cta_title', true),
                    'stats' => get_post_meta($post->ID, 'committees_banner1_stats', true) ?: [],
                ],
                'teams' => [
                    'title' => get_post_meta($post->ID, 'committees_teams_title', true),
                    'members' => get_post_meta($post->ID, 'committees_teams_members', true) ?: [],
                ],
                'banner2' => [
                    'title' => get_post_meta($post->ID, 'committees_banner2_title', true),
                    'description' => get_post_meta($post->ID, 'committees_banner2_description', true),
                    'cta_link' => get_post_meta($post->ID, 'committees_banner2_cta_link', true),
                    'cta_title' => get_post_meta($post->ID, 'committees_banner2_cta_title', true),
                ],
            ];

            $response->set_data($data);
        }
    }

    return $response;
}
add_filter('rest_prepare_page', 'nrna_custom_committees_rest_response', 10, 3);
