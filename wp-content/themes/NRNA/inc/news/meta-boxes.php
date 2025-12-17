<?php
// Add News meta boxes
function nrna_add_news_meta_boxes()
{
    add_meta_box(
        'news_content_box',
        __('News Content', 'nrna'),
        'nrna_render_news_content_meta_box',
        'news',
        'normal',
        'high'
    );
    add_meta_box(
        'news_related_box',
        __('Related News', 'nrna'),
        'nrna_render_news_related_meta_box',
        'news',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_news_meta_boxes');

// Render News Content meta box
function nrna_render_news_content_meta_box($post)
{
    $content = get_post_meta($post->ID, 'news_content', true);
    echo '<label for="news_content" style="display:block; font-weight:bold; margin-bottom:8px;">Content:</label>';
    wp_editor($content, 'news_content', [
        'textarea_name' => 'news_content',
        'media_buttons' => false,
        'textarea_rows' => 3,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Render Related News meta box
function nrna_render_news_related_meta_box($post)
{
    $related_news = get_post_meta($post->ID, 'news_related', false);
    if (!is_array($related_news)) {
        $related_news = [];
    }

    $news_query = new WP_Query([
        'post_type' => 'news',
        'posts_per_page' => -1,
        'post__not_in' => [$post->ID],
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    echo '<label style="display:block; font-weight:bold; margin-bottom:8px;">Select Related News:</label>';
    echo '<select id="news_related" name="news_related[]" multiple style="width:100%; height:150px;">';
    if ($news_query->have_posts()) {
        while ($news_query->have_posts()) {
            $news_query->the_post();
            $selected = in_array(get_the_ID(), $related_news) ? 'selected' : '';
            echo '<option value="' . esc_attr(get_the_ID()) . '" ' . $selected . '>' . esc_html(get_the_title()) . '</option>';
        }
        wp_reset_postdata();
    }
    echo '</select>';
    echo '<p class="description">Hold Ctrl (or Cmd on Mac) to select multiple news.</p>';
}

// Save News meta
function nrna_save_news_meta_boxes($post_id)
{
    if (array_key_exists('news_content', $_POST)) {
        update_post_meta($post_id, 'news_content', wp_kses_post($_POST['news_content']));
    }
    if (array_key_exists('news_related', $_POST)) {
        $related = array_map('intval', $_POST['news_related']);
        delete_post_meta($post_id, 'news_related');
        foreach ($related as $rel_id) {
            add_post_meta($post_id, 'news_related', $rel_id);
        }
    }
}
add_action('save_post', 'nrna_save_news_meta_boxes');

// Clean up News admin screen
function nrna_remove_news_meta_boxes()
{
    remove_meta_box('slugdiv', 'news', 'normal');
    remove_meta_box('authordiv', 'news', 'normal');
    remove_meta_box('commentsdiv', 'news', 'normal');
    remove_meta_box('revisionsdiv', 'news', 'normal');
}
add_action('admin_menu', 'nrna_remove_news_meta_boxes');
