<?php
// Register Events custom post type
function nrna_register_events_cpt()
{
    $labels = [
        'name'               => _x('Events', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Event', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Events', 'nrna'),
        'name_admin_bar'     => __('Event', 'nrna'),
        'add_new_item'       => __('Add New Event', 'nrna'),
        'edit_item'          => __('Edit Event', 'nrna'),
        'view_item'          => __('View Event', 'nrna'),
        'all_items'          => __('All Events', 'nrna'),
        'search_items'       => __('Search Events', 'nrna'),
        'not_found'          => __('No events found.', 'nrna'),
        'not_found_in_trash' => __('No events found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-calendar',
        'menu_position'      => 23,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'events'],
        'show_in_menu'       => 'news-events-menu',
    ];

    register_post_type('events', $args);
}
add_action('init', 'nrna_register_events_cpt');

// Register meta fields for Events
function nrna_register_events_meta_fields()
{
    $fields = [
        'event_location' => ['type' => 'string'],
        'event_start_date' => ['type' => 'string'],
        'event_end_date' => ['type' => 'string'],
        'event_sub_title' => ['type' => 'string'],
        'event_cta_link' => ['type' => 'string'],
        'event_cta_title' => ['type' => 'string'],
        'event_description' => ['type' => 'string'],
        'event_objective_title' => ['type' => 'string'],
        'event_objective_description' => ['type' => 'string'],
        'event_objective_cta_link' => ['type' => 'string'],
        'event_objective_cta_title' => ['type' => 'string'],
        'event_overview_title' => ['type' => 'string'],
        'event_overview_description' => ['type' => 'string'],
        'event_schedule_title' => ['type' => 'string'],
        'event_schedule_description' => ['type' => 'string'],
        'event_sponsorship_title' => ['type' => 'string'],
        'event_sponsorship_description' => ['type' => 'string'],
        'event_venue_title' => ['type' => 'string'],
        'event_venue_description' => ['type' => 'string'],
        'event_venue_map' => ['type' => 'string'],
        'event_organizing_committee_title' => ['type' => 'string'],
        'event_sponsors_title' => ['type' => 'string'],
        'event_banner_title' => ['type' => 'string'],
        'event_banner_description' => ['type' => 'string'],
        'event_banner_cta_link' => ['type' => 'string'],
        'event_banner_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_meta('post', $key, array_merge($args, [
            'object_subtype' => 'events',
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_meta('post', 'event_schedule_dates', [
        'object_subtype' => 'events',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'date' => ['type' => 'string'],
                'sessions' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'start_time' => ['type' => 'string'],
                            'end_time' => ['type' => 'string'],
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_meta('post', 'event_sponsorships', [
        'object_subtype' => 'events',
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

    register_meta('post', 'event_venue_details', [
        'object_subtype' => 'events',
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

    register_meta('post', 'event_organizing_committee', [
        'object_subtype' => 'events',
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

    register_meta('post', 'event_sponsors', [
        'object_subtype' => 'events',
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

    register_meta('post', 'event_partners', [
        'object_subtype' => 'events',
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'category' => ['type' => 'string'],
                'logo' => ['type' => 'string'],
                'name' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Image Gallery
    register_meta('post', 'event_image_gallery', [
        'object_subtype' => 'events',
        'type' => 'array',
        'items' => [
            'type' => 'string', // URL of the image
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);

    // Video Gallery
    register_meta('post', 'event_video_gallery', [
        'object_subtype' => 'events',
        'type' => 'array',
        'items' => [
            'type' => 'string', // YouTube URL
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_events_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_events_rest($response, $post, $request)
{
    if ($post->post_type !== 'events') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'event_location',
        'event_start_date',
        'event_end_date',
        'event_sub_title',
        'event_cta_link',
        'event_cta_title',
        'event_description',
        'event_objective_title',
        'event_objective_description',
        'event_objective_cta_link',
        'event_objective_cta_title',
        'event_overview_title',
        'event_overview_description',
        'event_schedule_title',
        'event_schedule_description',
        'event_sponsorship_title',
        'event_sponsorship_description',
        'event_venue_title',
        'event_venue_description',
        'event_venue_map',
        'event_organizing_committee_title',
        'event_sponsors_title',
        'event_banner_title',
        'event_banner_description',
        'event_banner_cta_link',
        'event_banner_cta_title',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['event_schedule_dates'] = get_post_meta($post->ID, 'event_schedule_dates', true);
    $data['event_sponsorships'] = get_post_meta($post->ID, 'event_sponsorships', true);
    $data['event_venue_details'] = get_post_meta($post->ID, 'event_venue_details', true);
    $data['event_organizing_committee'] = get_post_meta($post->ID, 'event_organizing_committee', true);
    $data['event_sponsors'] = get_post_meta($post->ID, 'event_sponsors', true);
    $data['event_partners'] = get_post_meta($post->ID, 'event_partners', true);
    $data['event_image_gallery'] = get_post_meta($post->ID, 'event_image_gallery', true);
    $data['event_video_gallery'] = get_post_meta($post->ID, 'event_video_gallery', true);

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_events', 'nrna_prepare_events_rest', 10, 3);

// Register event_downloads REST field with file metadata
function nrna_register_event_downloads_rest_field()
{
    register_rest_field('events', 'event_downloads', array(
        'get_callback' => function ($post) {
            $downloads = get_post_meta($post['id'], 'event_downloads', true);
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
            'description' => 'Event downloads',
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
add_action('rest_api_init', 'nrna_register_event_downloads_rest_field');
