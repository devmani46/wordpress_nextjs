<?php
// Register Privacy Policy meta fields for REST API
function nrna_register_privacy_policy_meta_fields() {
    register_post_meta('privacy-policy', 'description', [
        'type' => 'string',
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_privacy_policy_meta_fields');

// Add Privacy Policy meta boxes
function nrna_add_privacy_policy_meta_boxes() {
    add_meta_box(
        'privacy_policy_description_box',
        __('Privacy Policy Description', 'nrna'),
        'nrna_render_privacy_policy_description_meta_box',
        'privacy-policy',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_privacy_policy_meta_boxes');

// Render Privacy Policy Description meta box
function nrna_render_privacy_policy_description_meta_box($post) {
    $description = get_post_meta($post->ID, 'description', true);
    echo '<label for="description" style="display:block; font-weight:bold; margin-bottom:8px;">Description:</label>';
    wp_editor($description, 'description', [
        'textarea_name' => 'description',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Save Privacy Policy meta
function nrna_save_privacy_policy_meta_boxes($post_id) {
    if (array_key_exists('description', $_POST)) {
        update_post_meta($post_id, 'description', wp_kses_post($_POST['description']));
    }
}
add_action('save_post', 'nrna_save_privacy_policy_meta_boxes');

// Clean up Privacy Policy admin screen
function nrna_remove_privacy_policy_meta_boxes() {
    remove_meta_box('slugdiv', 'privacy-policy', 'normal');
    remove_meta_box('authordiv', 'privacy-policy', 'normal');
    remove_meta_box('commentsdiv', 'privacy-policy', 'normal');
    remove_meta_box('revisionsdiv', 'privacy-policy', 'normal');
}
add_action('admin_menu', 'nrna_remove_privacy_policy_meta_boxes');

// Prepare Privacy Policy REST response to include description
function nrna_prepare_privacy_policy_rest_response($response, $post, $request) {
    $data = $response->get_data();
    $data['description'] = get_post_meta($post->ID, 'description', true);
    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_privacy-policy', 'nrna_prepare_privacy_policy_rest_response', 10, 3);
