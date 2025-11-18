<?php
// Add Notice meta boxes
function nrna_add_notice_meta_boxes() {
    add_meta_box(
        'notice_content_box',
        __('Notice Content', 'nrna'),
        'nrna_render_notice_content_meta_box',
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

// Render Notice Content meta box
function nrna_render_notice_content_meta_box($post) {
    $content = get_post_meta($post->ID, 'notice_content', true);
    echo '<label for="notice_content" style="display:block; font-weight:bold; margin-bottom:8px;">Content:</label>';
    wp_editor($content, 'notice_content', [
        'textarea_name' => 'notice_content',
        'media_buttons' => true,
        'textarea_rows' => 10,
        'teeny' => false,
        'quicktags' => true,
    ]);
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
        'post__not_in' => [$post->ID], 
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
    if (array_key_exists('notice_content', $_POST)) {
        update_post_meta($post_id, 'notice_content', wp_kses_post($_POST['notice_content']));
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
