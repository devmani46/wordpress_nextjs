<?php
// Add FAQ Answer meta box
function nrna_add_faq_meta_box() {
    add_meta_box(
        'faq_answer_box',
        __('FAQ Answer', 'nrna'),
        'nrna_render_faq_meta_box',
        'faqs',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_faq_meta_box');

// Render the Answer field
function nrna_render_faq_meta_box($post) {
    $answer = get_post_meta($post->ID, 'answer', true);
    echo '<label for="faq_answer" style="display:block; font-weight:bold; margin-bottom:8px;">Answer:</label>';
    wp_editor($answer, 'faq_answer', [
        'textarea_name' => 'faq_answer',
        'media_buttons' => false,
        'textarea_rows' => 8,
        'teeny' => true,
        'quicktags' => false,
    ]);
}

// Save the Answer meta
function nrna_save_faq_meta_box($post_id) {
    if (array_key_exists('faq_answer', $_POST)) {
        update_post_meta($post_id, 'answer', wp_kses_post($_POST['faq_answer']));
    }
}
add_action('save_post', 'nrna_save_faq_meta_box');

// Clean up FAQ admin screen
function nrna_remove_faq_meta_boxes() {
    remove_meta_box('slugdiv', 'faqs', 'normal');
    remove_meta_box('authordiv', 'faqs', 'normal');
    remove_meta_box('commentsdiv', 'faqs', 'normal');
    remove_meta_box('revisionsdiv', 'faqs', 'normal');
}
add_action('admin_menu', 'nrna_remove_faq_meta_boxes');

?>

