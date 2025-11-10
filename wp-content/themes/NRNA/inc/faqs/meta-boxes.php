<?php
// Register FAQ meta fields for REST API
function nrna_register_faq_meta_fields() {
    register_post_meta('faqs', 'answer', [
        'type' => 'string',
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_faq_meta_fields');

// Add FAQ meta boxes
function nrna_add_faq_meta_boxes() {
    add_meta_box(
        'faq_answer_box',
        __('FAQ Answer', 'nrna'),
        'nrna_render_faq_answer_meta_box',
        'faqs',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_faq_meta_boxes');

// Render FAQ Answer meta box
function nrna_render_faq_answer_meta_box($post) {
    $answer = get_post_meta($post->ID, 'answer', true);
    echo '<label for="answer" style="display:block; font-weight:bold; margin-bottom:8px;">Answer:</label>';
    wp_editor($answer, 'answer', [
        'textarea_name' => 'answer',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Save FAQ meta
function nrna_save_faq_meta_boxes($post_id) {
    if (array_key_exists('answer', $_POST)) {
        update_post_meta($post_id, 'answer', wp_kses_post($_POST['answer']));
    }
}
add_action('save_post', 'nrna_save_faq_meta_boxes');

// Clean up FAQ admin screen
function nrna_remove_faq_meta_boxes() {
    remove_meta_box('slugdiv', 'faqs', 'normal');
    remove_meta_box('authordiv', 'faqs', 'normal');
    remove_meta_box('commentsdiv', 'faqs', 'normal');
    remove_meta_box('revisionsdiv', 'faqs', 'normal');
}
add_action('admin_menu', 'nrna_remove_faq_meta_boxes');

// Prepare FAQ REST response to include answer
function nrna_prepare_faq_rest_response($response, $post, $request) {
    $data = $response->get_data();
    $data['answer'] = get_post_meta($post->ID, 'answer', true);
    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_faqs', 'nrna_prepare_faq_rest_response', 10, 3);
