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

// Add Notice meta boxes
function nrna_add_notice_meta_boxes() {
    add_meta_box(
        'notice_video_box',
        __('Notice Video (YouTube URL)', 'nrna'),
        'nrna_render_notice_video_meta_box',
        'notices',
        'normal',
        'high'
    );
    add_meta_box(
        'notice_related_box',
        __('Related Notices', 'nrna'),
        'nrna_render_notice_related_meta_box',
        'notices',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_notice_meta_boxes');

// Render Video meta box
function nrna_render_notice_video_meta_box($post) {
    $video_url = get_post_meta($post->ID, 'notice_video_url', true);
    echo '<label for="notice_video_url" style="display:block; font-weight:bold; margin-bottom:8px;">YouTube Video URL:</label>';
    echo '<input type="url" id="notice_video_url" name="notice_video_url" value="' . esc_attr($video_url) . '" style="width:100%;" placeholder="https://www.youtube.com/watch?v=..." />';
}

// Render Related Notices meta box
function nrna_render_notice_related_meta_box($post) {
    $related_notices = get_post_meta($post->ID, 'notice_related', false);
    if (!is_array($related_notices)) {
        $related_notices = [];
    }

    $notices_query = new WP_Query([
        'post_type' => 'notices',
        'posts_per_page' => -1,
        'post__not_in' => [$post->ID], // Exclude current post
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    echo '<label style="display:block; font-weight:bold; margin-bottom:8px;">Select Related Notices:</label>';
    echo '<select id="notice_related" name="notice_related[]" multiple style="width:100%; height:150px;">';
    if ($notices_query->have_posts()) {
        while ($notices_query->have_posts()) {
            $notices_query->the_post();
            $selected = in_array(get_the_ID(), $related_notices) ? 'selected' : '';
            echo '<option value="' . esc_attr(get_the_ID()) . '" ' . $selected . '>' . esc_html(get_the_title()) . '</option>';
        }
        wp_reset_postdata();
    }
    echo '</select>';
    echo '<p class="description">Hold Ctrl (or Cmd on Mac) to select multiple notices.</p>';
}

// Save Notice meta
function nrna_save_notice_meta_boxes($post_id) {
    if (array_key_exists('notice_video_url', $_POST)) {
        update_post_meta($post_id, 'notice_video_url', sanitize_url($_POST['notice_video_url']));
    }
    if (array_key_exists('notice_related', $_POST)) {
        $related = array_map('intval', $_POST['notice_related']);
        delete_post_meta($post_id, 'notice_related');
        foreach ($related as $rel_id) {
            add_post_meta($post_id, 'notice_related', $rel_id);
        }
    }
}
add_action('save_post', 'nrna_save_notice_meta_boxes');

// Clean up Notice admin screen
function nrna_remove_notice_meta_boxes() {
    remove_meta_box('slugdiv', 'notices', 'normal');
    remove_meta_box('authordiv', 'notices', 'normal');
    remove_meta_box('commentsdiv', 'notices', 'normal');
    remove_meta_box('revisionsdiv', 'notices', 'normal');
}
add_action('admin_menu', 'nrna_remove_notice_meta_boxes');

?>

