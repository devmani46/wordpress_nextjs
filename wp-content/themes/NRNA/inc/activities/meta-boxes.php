<?php
// Add Activities meta boxes
function nrna_add_activities_meta_boxes() {
    add_meta_box(
        'activities_description_box',
        __('Activity Description', 'nrna'),
        'nrna_render_activities_description_meta_box',
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

// Render Activity Description meta box
function nrna_render_activities_description_meta_box($post) {
    $description = get_post_meta($post->ID, 'activity_description', true);
    echo '<label for="activity_description" style="display:block; font-weight:bold; margin-bottom:8px;">Description:</label>';
    wp_editor($description, 'activity_description', [
        'textarea_name' => 'activity_description',
        'media_buttons' => false,
        'textarea_rows' => 8,
        'teeny' => true,
        'quicktags' => false,
    ]);
}

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
    echo '<p class="description">Add photos for this activity.</p>';

    if (!empty($photos)) {
        foreach ($photos as $index => $image_id) {
            echo '<div class="photo-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">';
            echo '<input type="hidden" name="activity_photos[]" value="' . esc_attr($image_id) . '" />';
            echo '<img src="' . esc_url(wp_get_attachment_image_url($image_id, 'medium')) . '" style="max-width: 150px; max-height: 150px; margin-bottom: 5px;" />';
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
                '<input type="hidden" name="activity_photos[]" />' +
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
    if (array_key_exists('activity_description', $_POST)) {
        update_post_meta($post_id, 'activity_description', wp_kses_post($_POST['activity_description']));
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

// Save Activity Photos meta
function nrna_save_activities_photos_meta_box($post_id) {
    if (array_key_exists('activity_photos', $_POST)) {
        $photos = [];
        foreach ($_POST['activity_photos'] as $image_id) {
            if (!empty($image_id)) {
                $photos[] = intval($image_id);
            }
        }
        update_post_meta($post_id, 'activity_photos', $photos);
    } else {
        delete_post_meta($post_id, 'activity_photos');
    }
}
add_action('save_post', 'nrna_save_activities_photos_meta_box');

// Clean up Activities admin screen
function nrna_remove_activities_meta_boxes() {
    remove_meta_box('slugdiv', 'activities', 'normal');
    remove_meta_box('authordiv', 'activities', 'normal');
    remove_meta_box('commentsdiv', 'activities', 'normal');
    remove_meta_box('revisionsdiv', 'activities', 'normal');
}
add_action('admin_menu', 'nrna_remove_activities_meta_boxes');

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
            foreach ($photos as $image_id) {
                $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                if ($image_url) {
                    echo '<img src="' . esc_url($image_url) . '" style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ddd;" />';
                }
            }
            echo '</div>';
        } else {
            echo __('No photos', 'nrna');
        }
    }
}
add_action('manage_activities_posts_custom_column', 'nrna_populate_activities_photos_column', 10, 2);
