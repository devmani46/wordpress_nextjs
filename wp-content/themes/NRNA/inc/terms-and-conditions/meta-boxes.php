<?php
// Register Terms and Conditions meta fields for REST API
function nrna_register_terms_and_conditions_meta_fields() {
    register_post_meta('terms-and-conditions', 'description', [
        'type' => 'string',
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_terms_and_conditions_meta_fields');

// Add Terms and Conditions meta boxes
function nrna_add_terms_and_conditions_meta_boxes() {
    add_meta_box(
        'terms_and_conditions_description_box',
        __('Terms and Conditions Description', 'nrna'),
        'nrna_render_terms_and_conditions_description_meta_box',
        'terms-and-conditions',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_terms_and_conditions_meta_boxes');

// Render Terms and Conditions Description meta box
function nrna_render_terms_and_conditions_description_meta_box($post) {
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

// Save Terms and Conditions meta
function nrna_save_terms_and_conditions_meta_boxes($post_id) {
    if (array_key_exists('description', $_POST)) {
        update_post_meta($post_id, 'description', wp_kses_post($_POST['description']));
    }
}
add_action('save_post', 'nrna_save_terms_and_conditions_meta_boxes');

// Clean up Terms and Conditions admin screen
function nrna_remove_terms_and_conditions_meta_boxes() {
    remove_meta_box('slugdiv', 'terms-and-conditions', 'normal');
    remove_meta_box('authordiv', 'terms-and-conditions', 'normal');
    remove_meta_box('commentsdiv', 'terms-and-conditions', 'normal');
    remove_meta_box('revisionsdiv', 'terms-and-conditions', 'normal');
}
add_action('admin_menu', 'nrna_remove_terms_and_conditions_meta_boxes');

// Prepare Terms and Conditions REST response to include description
function nrna_prepare_terms_and_conditions_rest_response($response, $post, $request) {
    $data = $response->get_data();
    $data['description'] = get_post_meta($post->ID, 'description', true);
    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_terms-and-conditions', 'nrna_prepare_terms_and_conditions_rest_response', 10, 3);
