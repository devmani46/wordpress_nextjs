<?php
// Register Videos custom post type
function nrna_register_videos_cpt() {
    $labels = [
        'name'               => _x('Videos', 'Post Type General Name', 'nrna'),
        'singular_name'      => _x('Video', 'Post Type Singular Name', 'nrna'),
        'menu_name'          => __('Videos', 'nrna'),
        'name_admin_bar'     => __('Video', 'nrna'),
        'add_new_item'       => __('Add New Video', 'nrna'),
        'edit_item'          => __('Edit Video', 'nrna'),
        'view_item'          => __('View Video', 'nrna'),
        'all_items'          => __('All Videos', 'nrna'),
        'search_items'       => __('Search Videos', 'nrna'),
        'not_found'          => __('No videos found.', 'nrna'),
        'not_found_in_trash' => __('No videos found in Trash.', 'nrna'),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => ['title'],
        'menu_icon'          => 'dashicons-video-alt3',
        'menu_position'      => 26,
        'has_archive'        => true,
        'rewrite'            => ['slug' => 'videos'],
    ];

    register_post_type('videos', $args);
}
add_action('init', 'nrna_register_videos_cpt');

// Register meta fields for REST API
function nrna_register_videos_meta_rest() {
    register_meta('post', 'video_youtube_url', array(
        'object_subtype' => 'videos',
        'type' => 'string',
        'description' => 'YouTube Video URL',
        'single' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'nrna_register_videos_meta_rest');

// Prepare REST API response to include meta fields
function nrna_prepare_videos_rest($response, $post, $request) {
    $response->data['video_youtube_url'] = get_post_meta($post->ID, 'video_youtube_url', true);
    return $response;
}
add_filter('rest_prepare_videos', 'nrna_prepare_videos_rest', 10, 3);
