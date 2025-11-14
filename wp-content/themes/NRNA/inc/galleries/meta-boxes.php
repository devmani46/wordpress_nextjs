<?php
// Add Galleries meta boxes
function nrna_add_galleries_meta_boxes() {
    add_meta_box(
        'galleries_images_box',
        __('Gallery Images', 'nrna'),
        'nrna_render_galleries_images_meta_box',
        'galleries',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_galleries_meta_boxes');

// Render Gallery Images meta box
function nrna_render_galleries_images_meta_box($post) {
    $gallery_images = get_post_meta($post->ID, 'gallery_images', true);
    if (!is_array($gallery_images)) {
        $gallery_images = [];
    }

    wp_nonce_field('nrna_galleries_meta_box', 'nrna_galleries_meta_box_nonce');

    echo '<label for="gallery_images" style="display:block; font-weight:bold; margin-bottom:8px;">Select Gallery Images:</label>';
    echo '<div id="gallery_images_container" style="margin-bottom:10px;">';

    if (!empty($gallery_images)) {
        foreach ($gallery_images as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            if ($image_url) {
                echo '<div class="gallery-image-item" style="display:inline-block; margin:5px; position:relative;">';
                echo '<img src="' . esc_url($image_url) . '" style="max-width:100px; max-height:100px; object-fit:cover;" />';
                echo '<input type="hidden" name="gallery_images[]" value="' . esc_attr($image_id) . '" />';
                echo '<button type="button" class="remove-image" style="position:absolute; top:0; right:0; background:red; color:white; border:none; cursor:pointer;">&times;</button>';
                echo '</div>';
            }
        }
    }

    echo '</div>';
    echo '<button type="button" id="add_gallery_images" class="button">Add Images</button>';
    echo '<p class="description">Click "Add Images" to select multiple images for the gallery.</p>';

    // Enqueue media scripts
    wp_enqueue_media();
    ?>
    <script>
    jQuery(document).ready(function($) {
        var frame;
        $('#add_gallery_images').on('click', function(e) {
            e.preventDefault();
            if (frame) {
                frame.open();
                return;
            }
            frame = wp.media({
                title: 'Select Gallery Images',
                button: {
                    text: 'Add to Gallery'
                },
                multiple: true
            });
            frame.on('select', function() {
                var attachments = frame.state().get('selection').toJSON();
                attachments.forEach(function(attachment) {
                    var imageUrl = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                    var imageItem = '<div class="gallery-image-item" style="display:inline-block; margin:5px; position:relative;">' +
                        '<img src="' + imageUrl + '" style="max-width:100px; max-height:100px; object-fit:cover;" />' +
                        '<input type="hidden" name="gallery_images[]" value="' + attachment.id + '" />' +
                        '<button type="button" class="remove-image" style="position:absolute; top:0; right:0; background:red; color:white; border:none; cursor:pointer;">&times;</button>' +
                        '</div>';
                    $('#gallery_images_container').append(imageItem);
                });
            });
            frame.open();
        });

        $(document).on('click', '.remove-image', function() {
            $(this).parent('.gallery-image-item').remove();
        });
    });
    </script>
    <?php
}

// Save Galleries meta
function nrna_save_galleries_meta_boxes($post_id) {
    if (!isset($_POST['nrna_galleries_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_galleries_meta_box_nonce'], 'nrna_galleries_meta_box')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (array_key_exists('gallery_images', $_POST)) {
        $images = array_map('intval', $_POST['gallery_images']);
        update_post_meta($post_id, 'gallery_images', $images);
    } else {
        delete_post_meta($post_id, 'gallery_images');
    }
}
add_action('save_post', 'nrna_save_galleries_meta_boxes');

// Clean up Galleries admin screen
function nrna_remove_galleries_meta_boxes() {
    remove_meta_box('slugdiv', 'galleries', 'normal');
    remove_meta_box('authordiv', 'galleries', 'normal');
    remove_meta_box('commentsdiv', 'galleries', 'normal');
    remove_meta_box('revisionsdiv', 'galleries', 'normal');
}
add_action('admin_menu', 'nrna_remove_galleries_meta_boxes');
