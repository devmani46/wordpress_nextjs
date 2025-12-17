<?php
// Add Activities meta boxes
function nrna_add_activities_meta_boxes()
{
    add_meta_box(
        'activities_content_box',
        __('Activity Content', 'nrna'),
        'nrna_render_activities_content_meta_box',
        'activities',
        'normal',
        'high'
    );
    add_meta_box(
        'activities_related_activities_box',
        __('Related Activities', 'nrna'),
        'nrna_render_activities_related_activities_meta_box',
        'activities',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_activities_meta_boxes');



// Render Activity Content meta box
function nrna_render_activities_content_meta_box($post)
{
    $content = get_post_meta($post->ID, 'activity_content', true);
    echo '<label for="activity_content" style="display:block; font-weight:bold; margin-bottom:8px;">Content:</label>';
    wp_editor($content, 'activity_content', [
        'textarea_name' => 'activity_content',
        'media_buttons' => false,
        'textarea_rows' => 3,
        'teeny' => false,
        'quicktags' => true,
    ]);
}

// Render Related Activities meta box
function nrna_render_activities_related_activities_meta_box($post)
{
    $related_activities = get_post_meta($post->ID, 'activity_related_activities', false);
    if (!is_array($related_activities)) {
        $related_activities = [];
    }

    $activities_query = new WP_Query([
        'post_type' => 'activities',
        'posts_per_page' => -1,
        'post__not_in' => [$post->ID], // Exclude current post
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    echo '<label style="display:block; font-weight:bold; margin-bottom:8px;">Select Related Activities:</label>';
    echo '<select id="activity_related_activities" name="activity_related_activities[]" multiple style="width:100%; height:150px;">';
    if ($activities_query->have_posts()) {
        while ($activities_query->have_posts()) {
            $activities_query->the_post();
            $selected = in_array(get_the_ID(), $related_activities) ? 'selected' : '';
            echo '<option value="' . esc_attr(get_the_ID()) . '" ' . $selected . '>' . esc_html(get_the_title()) . '</option>';
        }
        wp_reset_postdata();
    }
    echo '</select>';
    echo '<p class="description">Hold Ctrl (or Cmd on Mac) to select multiple activities.</p>';
}



// Save Activities meta
function nrna_save_activities_meta_boxes($post_id)
{
    if (array_key_exists('activity_content', $_POST)) {
        update_post_meta($post_id, 'activity_content', wp_kses_post($_POST['activity_content']));
    }
    // Save related activities
    if (array_key_exists('activity_related_activities', $_POST)) {
        $related = array_map('intval', $_POST['activity_related_activities']);
        delete_post_meta($post_id, 'activity_related_activities');
        foreach ($related as $rel_id) {
            add_post_meta($post_id, 'activity_related_activities', $rel_id);
        }
    }
}
add_action('save_post', 'nrna_save_activities_meta_boxes');



// Clean up Activities admin screen
function nrna_remove_activities_meta_boxes()
{
    remove_meta_box('slugdiv', 'activities', 'normal');
    remove_meta_box('authordiv', 'activities', 'normal');
    remove_meta_box('commentsdiv', 'activities', 'normal');
    remove_meta_box('revisionsdiv', 'activities', 'normal');
}
add_action('admin_menu', 'nrna_remove_activities_meta_boxes');
