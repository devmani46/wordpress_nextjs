<?php
// Register Activities custom post type
function nrna_register_activities_cpt() {
    $labels = [
        'name'               => _x('Activities', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Activity', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Activities', 'nrna'),
        'name_admin_bar'     => __('Activity', 'nrna'),
        'add_new_item'       => __('Add New Activity', 'nrna'),
        'edit_item'          => __('Edit Activity', 'nrna'),
        'view_item'          => __('View Activity', 'nrna'),
        'all_items'          => __('All Activities', 'nrna'),
        'search_items'       => __('Search Activities', 'nrna'),
        'not_found'          => __('No activities found.', 'nrna'),
        'not_found_in_trash' => __('No activities found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title', 'thumbnail'],
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 22,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'activities'],
    ];

    register_post_type('activities', $args);
}
add_action('init', 'nrna_register_activities_cpt');

// Register meta fields for REST API
function nrna_register_activities_meta_rest() {
    register_meta('post', 'activity_content', array(
        'object_subtype' => 'activities',
        'type' => 'string',
        'description' => 'Activity Content',
        'single' => true,
        'show_in_rest' => true,
    ));

    register_meta('post', 'activity_related_activities', array(
        'object_subtype' => 'activities',
        'type' => 'array',
        'description' => 'Related Activities',
        'single' => true,
        'show_in_rest' => array(
            'schema' => array(
                'type' => 'array',
                'items' => array(
                    'type' => 'integer',
                ),
            ),
        ),
    ));
}
add_action('init', 'nrna_register_activities_meta_rest');

// Prepare REST API response to include meta fields
function nrna_prepare_activities_rest($response, $post, $request) {
    $response->data['activity_content'] = get_post_meta($post->ID, 'activity_content', true);
    $response->data['activity_related_activities'] = get_post_meta($post->ID, 'activity_related_activities', false);
    return $response;
}
add_filter('rest_prepare_activities', 'nrna_prepare_activities_rest', 10, 3);
