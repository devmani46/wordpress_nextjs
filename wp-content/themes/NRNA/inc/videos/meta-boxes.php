<?php
// Add Videos meta boxes
function nrna_add_videos_meta_boxes() {
    add_meta_box(
        'videos_youtube_url_box',
        __('YouTube Video URL', 'nrna'),
        'nrna_render_videos_youtube_url_meta_box',
        'videos',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_videos_meta_boxes');

// Render YouTube URL meta box
function nrna_render_videos_youtube_url_meta_box($post) {
    $youtube_url = get_post_meta($post->ID, 'video_youtube_url', true);
    wp_nonce_field('nrna_videos_meta_box', 'nrna_videos_meta_box_nonce');

    echo '<label for="video_youtube_url" style="display:block; font-weight:bold; margin-bottom:8px;">YouTube Video URL:</label>';
    echo '<input type="url" id="video_youtube_url" name="video_youtube_url" value="' . esc_attr($youtube_url) . '" style="width:100%; padding:8px;" placeholder="https://www.youtube.com/watch?v=VIDEO_ID" />';
    echo '<p class="description">Enter the full YouTube video URL .</p>';
}

// Save Videos meta
function nrna_save_videos_meta_boxes($post_id) {
    if (!isset($_POST['nrna_videos_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_videos_meta_box_nonce'], 'nrna_videos_meta_box')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (array_key_exists('video_youtube_url', $_POST)) {
        $url = sanitize_text_field($_POST['video_youtube_url']);
        // Basic validation for YouTube URL
        if (!empty($url) && (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false)) {
            update_post_meta($post_id, 'video_youtube_url', $url);
        } else {
            delete_post_meta($post_id, 'video_youtube_url');
        }
    }
}
add_action('save_post', 'nrna_save_videos_meta_boxes');

// Clean up Videos admin screen
function nrna_remove_videos_meta_boxes() {
    remove_meta_box('slugdiv', 'videos', 'normal');
    remove_meta_box('authordiv', 'videos', 'normal');
    remove_meta_box('commentsdiv', 'videos', 'normal');
    remove_meta_box('revisionsdiv', 'videos', 'normal');
}
add_action('admin_menu', 'nrna_remove_videos_meta_boxes');
