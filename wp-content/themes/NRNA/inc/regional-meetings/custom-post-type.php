<?php
// Register Regional Meetings custom post type
function nrna_register_regional_meetings_cpt()
{
    $labels = [
        'name'               => _x('Regional Meetings', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Regional Meeting', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Regional Meetings', 'nrna'),
        'name_admin_bar'     => __('Regional Meeting', 'nrna'),
        'add_new_item'       => __('Add New Regional Meeting', 'nrna'),
        'edit_item'          => __('Edit Regional Meeting', 'nrna'),
        'view_item'          => __('View Regional Meeting', 'nrna'),
        'all_items'          => __('All Regional Meetings', 'nrna'),
        'search_items'       => __('Search Regional Meetings', 'nrna'),
        'not_found'          => __('No regional meetings found.', 'nrna'),
        'not_found_in_trash' => __('No regional meetings found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 23,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'regional-meetings'],
        'show_in_menu'       => 'news-events-menu',
    ];

    register_post_type('regional_meetings', $args);
}
add_action('init', 'nrna_register_regional_meetings_cpt');

// Register meta fields for Regional Meetings
function nrna_register_regional_meetings_meta_fields()
{
    $fields = [
        // Hero Section
        'rm_hero_title' => ['type' => 'string'],
        'rm_location' => ['type' => 'string'],
        'rm_start_date' => ['type' => 'string'],
        'rm_end_date' => ['type' => 'string'],
        'rm_cta_link' => ['type' => 'string'],
        'rm_cta_title' => ['type' => 'string'],
        'rm_description' => ['type' => 'string'],

        // Agenda Section
        'rm_agenda_title' => ['type' => 'string'],
        'rm_agenda_description' => ['type' => 'string'],

        // Contact Information
        'rm_contact_title' => ['type' => 'string'],
        'rm_contact_description' => ['type' => 'string'],

        // Sponsorship Opportunities
        'rm_sponsorship_title' => ['type' => 'string'],
        'rm_sponsorship_description' => ['type' => 'string'],

        // Organizing Committee
        'rm_organizing_committee_title' => ['type' => 'string'],

        // Our Sponsors
        'rm_sponsors_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'regional_meetings',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields

    // Sponsorship Opportunities Table
    register_meta('post', 'rm_sponsorships', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'category' => ['type' => 'string'],
                'amount' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Organizing Committee Table
    register_meta('post', 'rm_organizing_committee', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'photo' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'role' => ['type' => 'string'],
                'service' => ['type' => 'string'],
                'country' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Our Sponsors Table
    register_meta('post', 'rm_sponsors', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'photo' => ['type' => 'string'],
                'name' => ['type' => 'string'],
                'role' => ['type' => 'string'],
                'service' => ['type' => 'string'],
                'country' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Partners
    register_meta('post', 'rm_partners', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'category' => ['type' => 'string'], // gold, silver, event, airlines
                'logo' => ['type' => 'string'],
                'name' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Image Gallery
    register_meta('post', 'rm_image_gallery', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'string', // URL of the image
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Video Gallery
    register_meta('post', 'rm_video_gallery', [
        'object_subtype' => 'regional_meetings',
        'type' => 'array',
        'items' => [
            'type' => 'string', // YouTube URL
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_regional_meetings_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_regional_meetings_rest($response, $post, $request)
{
    if ($post->post_type !== 'regional_meetings') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'rm_hero_title',
        'rm_location',
        'rm_start_date',
        'rm_end_date',
        'rm_cta_link',
        'rm_cta_title',
        'rm_description',
        'rm_agenda_title',
        'rm_agenda_description',
        'rm_contact_title',
        'rm_contact_description',
        'rm_sponsorship_title',
        'rm_sponsorship_description',
        'rm_organizing_committee_title',
        'rm_sponsors_title',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['rm_sponsorships'] = get_post_meta($post->ID, 'rm_sponsorships', true);
    $data['rm_organizing_committee'] = get_post_meta($post->ID, 'rm_organizing_committee', true);
    $data['rm_sponsors'] = get_post_meta($post->ID, 'rm_sponsors', true);
    $data['rm_partners'] = get_post_meta($post->ID, 'rm_partners', true);
    $data['rm_image_gallery'] = get_post_meta($post->ID, 'rm_image_gallery', true);
    $data['rm_video_gallery'] = get_post_meta($post->ID, 'rm_video_gallery', true);

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_regional_meetings', 'nrna_prepare_regional_meetings_rest', 10, 3);

// Register rm_downloads REST field with file metadata
function nrna_register_rm_downloads_rest_field()
{
    register_rest_field('regional_meetings', 'rm_downloads', array(
        'get_callback' => function ($post) {
            $downloads = get_post_meta($post['id'], 'rm_downloads', true);
            if (! is_array($downloads)) {
                $downloads = [];
            }
            $downloads_data = [];
            foreach ($downloads as $download) {
                $download_item = [
                    'title' => $download['title'] ?? '',
                ];
                if (! empty($download['file'])) {
                    $file_id = $download['file'];
                    $file_url = wp_get_attachment_url($file_id);
                    $file_name = basename($file_url);
                    $download_item['file'] = [
                        'id' => $file_id,
                        'url' => $file_url,
                        'filename' => $file_name,
                    ];
                }
                $downloads_data[] = $download_item;
            }
            return $downloads_data;
        },
        'schema' => array(
            'description' => 'Regional Meeting downloads',
            'type' => 'array',
            'items' => array(
                'type' => 'object',
                'properties' => array(
                    'title' => array(
                        'type' => 'string',
                    ),
                    'file' => array(
                        'type' => 'object',
                        'properties' => array(
                            'id' => array(
                                'type' => 'integer',
                            ),
                            'url' => array(
                                'type' => 'string',
                            ),
                            'filename' => array(
                                'type' => 'string',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ));
}
add_action('rest_api_init', 'nrna_register_rm_downloads_rest_field');
