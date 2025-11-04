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

// Add Activities meta boxes
function nrna_add_activities_meta_boxes() {
    add_meta_box(
        'activities_related_activities_box',
        __('Related Activities', 'nrna'),
        'nrna_render_activities_related_activities_meta_box',
        'activities',
        'normal',
        'high'
    );
    add_meta_box(
        'activities_photos_box',
        __('Activity Photos', 'nrna'),
        'nrna_render_activities_photos_meta_box',
        'activities',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_activities_meta_boxes');



// Render Related Activities meta box
function nrna_render_activities_related_activities_meta_box($post) {
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

// Render Activity Photos meta box
function nrna_render_activities_photos_meta_box($post) {
    $photos = get_post_meta($post->ID, 'activity_photos', true);
    if (!is_array($photos)) {
        $photos = [];
    }

    echo '<div id="activity-photos-container">';
    echo '<p class="description">Add photos for this activity. Each photo can have a title.</p>';

    if (!empty($photos)) {
        foreach ($photos as $index => $photo) {
            echo '<div class="photo-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">';
            echo '<label>Photo Title:</label><br>';
            echo '<input type="text" name="activity_photos[' . $index . '][title]" value="' . esc_attr($photo['title'] ?? '') . '" style="width: 100%; margin-bottom: 5px;" />';
            echo '<label>Image:</label><br>';
            echo '<input type="hidden" name="activity_photos[' . $index . '][image_id]" value="' . esc_attr($photo['image_id'] ?? '') . '" />';
            echo '<img src="' . esc_url(wp_get_attachment_image_url($photo['image_id'] ?? '', 'medium')) . '" style="max-width: 150px; max-height: 150px; margin-bottom: 5px;" />';
            echo '<button type="button" class="upload-image-button button" data-index="' . $index . '">Change Image</button>';
            echo '<button type="button" class="remove-photo-button button" style="margin-left: 5px;">Remove</button>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<button type="button" id="add-photo-button" class="button">Add Photo</button>';

    // Enqueue media scripts
    wp_enqueue_media();
    ?>
    <script>
    jQuery(document).ready(function($) {
        var photoIndex = <?php echo count($photos); ?>;

        $('#add-photo-button').on('click', function() {
            var html = '<div class="photo-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">' +
                '<label>Photo Title:</label><br>' +
                '<input type="text" name="activity_photos[' + photoIndex + '][title]" style="width: 100%; margin-bottom: 5px;" />' +
                '<label>Image:</label><br>' +
                '<input type="hidden" name="activity_photos[' + photoIndex + '][image_id]" />' +
                '<img src="" style="max-width: 150px; max-height: 150px; margin-bottom: 5px; display: none;" />' +
                '<button type="button" class="upload-image-button button" data-index="' + photoIndex + '">Upload Image</button>' +
                '<button type="button" class="remove-photo-button button" style="margin-left: 5px;">Remove</button>' +
                '</div>';
            $('#activity-photos-container').append(html);
            photoIndex++;
        });

        $(document).on('click', '.upload-image-button', function() {
            var button = $(this);
            var index = button.data('index');
            var imageIdInput = button.siblings('input[type="hidden"]');
            var img = button.siblings('img');

            var mediaUploader = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Choose Image'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                imageIdInput.val(attachment.id);
                img.attr('src', attachment.url).show();
            });

            mediaUploader.open();
        });

        $(document).on('click', '.remove-photo-button', function() {
            $(this).closest('.photo-item').remove();
        });
    });
    </script>
    <?php
}

// Save Activities meta
function nrna_save_activities_meta_boxes($post_id) {
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
function nrna_remove_activities_meta_boxes() {
    remove_meta_box('slugdiv', 'activities', 'normal');
    remove_meta_box('authordiv', 'activities', 'normal');
    remove_meta_box('commentsdiv', 'activities', 'normal');
    remove_meta_box('revisionsdiv', 'activities', 'normal');
}
add_action('admin_menu', 'nrna_remove_activities_meta_boxes');

// Save Activity Photos meta
function nrna_save_activities_photos_meta_box($post_id) {
    if (array_key_exists('activity_photos', $_POST)) {
        $photos = [];
        foreach ($_POST['activity_photos'] as $photo) {
            if (!empty($photo['image_id'])) {
                $photos[] = [
                    'title' => sanitize_text_field($photo['title'] ?? ''),
                    'image_id' => intval($photo['image_id']),
                ];
            }
        }
        update_post_meta($post_id, 'activity_photos', $photos);
    } else {
        delete_post_meta($post_id, 'activity_photos');
    }
}
add_action('save_post', 'nrna_save_activities_photos_meta_box');

// Add custom column for photos in activities admin list
function nrna_add_activities_photos_column($columns) {
    $columns['activity_photos'] = __('Photos', 'nrna');
    return $columns;
}
add_filter('manage_activities_posts_columns', 'nrna_add_activities_photos_column');

// Populate the custom column with photo previews
function nrna_populate_activities_photos_column($column, $post_id) {
    if ($column === 'activity_photos') {
        $photos = get_post_meta($post_id, 'activity_photos', true);
        if (!empty($photos) && is_array($photos)) {
            echo '<div style="display: flex; flex-wrap: wrap; gap: 5px;">';
            foreach ($photos as $photo) {
                $image_url = wp_get_attachment_image_url($photo['image_id'], 'thumbnail');
                if ($image_url) {
                    echo '<img src="' . esc_url($image_url) . '" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;" title="' . esc_attr($photo['title'] ?? '') . '" />';
                }
            }
            echo '</div>';
        } else {
            echo __('No photos', 'nrna');
        }
    }
}
add_action('manage_activities_posts_custom_column', 'nrna_populate_activities_photos_column', 10, 2);

