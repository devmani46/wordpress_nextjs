<?php
// Register Events custom post type
function nrna_register_events_cpt() {
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
    ];

    register_post_type('events', $args);
}
add_action('init', 'nrna_register_events_cpt');

// Register meta fields for Events
function nrna_register_events_meta_fields() {
    $fields = [
        'event_hero_title' => ['type' => 'string'],
        'event_location' => ['type' => 'string'],
        'event_start_date' => ['type' => 'string'], // Store as YYYY-MM-DD
        'event_end_date' => ['type' => 'string'], // Store as YYYY-MM-DD
        'event_countdown_days' => ['type' => 'string'],
        'event_countdown_hours' => ['type' => 'string'],
        'event_countdown_minutes' => ['type' => 'string'],
        'event_countdown_seconds' => ['type' => 'string'],
        'event_sub_title' => ['type' => 'string'],
        'event_cta_link' => ['type' => 'string'],
        'event_cta_title' => ['type' => 'string'],
        'event_description' => ['type' => 'string'],
        'event_objective_title' => ['type' => 'string'],
        'event_objective_description' => ['type' => 'string'],
        'event_objective_cta_link' => ['type' => 'string'],
        'event_objective_cta_title' => ['type' => 'string'],
        'event_schedule_title' => ['type' => 'string'],
        'event_schedule_description' => ['type' => 'string'],
        'event_sponsorship_title' => ['type' => 'string'],
        'event_sponsorship_description' => ['type' => 'string'],
        'event_venue_title' => ['type' => 'string'],
        'event_venue_description' => ['type' => 'string'],
        'event_venue_map' => ['type' => 'string'],
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
}
add_action('init', 'nrna_register_events_meta_fields');

// Prepare REST API response to include meta fields
function nrna_prepare_events_rest($response, $post, $request) {
    if ($post->post_type !== 'events') {
        return $response;
    }

    $data = $response->get_data();

    $meta_fields = [
        'event_hero_title',
        'event_location',
        'event_start_date',
        'event_end_date',
        'event_countdown_days',
        'event_countdown_hours',
        'event_countdown_minutes',
        'event_countdown_seconds',
        'event_sub_title',
        'event_cta_link',
        'event_cta_title',
        'event_description',
        'event_objective_title',
        'event_objective_description',
        'event_objective_cta_link',
        'event_objective_cta_title',
        'event_schedule_title',
        'event_schedule_description',
        'event_sponsorship_title',
        'event_sponsorship_description',
        'event_venue_title',
        'event_venue_description',
        'event_venue_map',
    ];

    foreach ($meta_fields as $field) {
        $data[$field] = get_post_meta($post->ID, $field, true);
    }

    $data['event_schedule_dates'] = get_post_meta($post->ID, 'event_schedule_dates', true);
    $data['event_sponsorships'] = get_post_meta($post->ID, 'event_sponsorships', true);
    $data['event_venue_details'] = get_post_meta($post->ID, 'event_venue_details', true);

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_events', 'nrna_prepare_events_rest', 10, 3);
