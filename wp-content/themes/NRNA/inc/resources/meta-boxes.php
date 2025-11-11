<?php
// Add Resources meta boxes
function nrna_add_resources_meta_boxes() {
    add_meta_box(
        'resource_files_box',
        __('Resource Files', 'nrna'),
        'nrna_render_resource_files_meta_box',
        'resources',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_resources_meta_boxes');

// Render Resource Files meta box (multiple file uploads)
function nrna_render_resource_files_meta_box($post) {
    $files = get_post_meta($post->ID, 'resource_files', true);
    if (!is_array($files)) {
        $files = [];
    }

    echo '<div id="resource-files-container">';
    echo '<p class="description">Upload multiple files for this resource.</p>';

    if (!empty($files)) {
        foreach ($files as $index => $file_id) {
            $file_url = wp_get_attachment_url($file_id);
            $file_name = basename($file_url);
            echo '<div class="file-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">';
            echo '<input type="hidden" name="resource_files[]" value="' . esc_attr($file_id) . '" />';
            echo '<a href="' . esc_url($file_url) . '" target="_blank">' . esc_html($file_name) . '</a>';
            echo '<button type="button" class="upload-file-button button" data-index="' . $index . '" style="margin-left: 10px;">Change File</button>';
            echo '<button type="button" class="remove-file-button button" style="margin-left: 5px;">Remove</button>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<button type="button" id="add-file-button" class="button">Add File</button>';

    wp_enqueue_media();
    ?>
    <script>
    jQuery(document).ready(function($) {
        var fileIndex = <?php echo count($files); ?>;

        $('#add-file-button').on('click', function() {
            var html = '<div class="file-item" style="margin-bottom: 15px; padding: 10px; border: 1px solid #ddd;">' +
                '<input type="hidden" name="resource_files[]" />' +
                '<span class="file-link" style="display: none;"><a href="" target="_blank"></a></span>' +
                '<button type="button" class="upload-file-button button" data-index="' + fileIndex + '">Upload File</button>' +
                '<button type="button" class="remove-file-button button" style="margin-left: 5px;">Remove</button>' +
                '</div>';
            $('#resource-files-container').append(html);
            fileIndex++;
        });

        $(document).on('click', '.upload-file-button', function() {
            var button = $(this);
            var index = button.data('index');
            var fileIdInput = button.siblings('input[type="hidden"]');
            var fileLink = button.siblings('.file-link');
            var link = fileLink.find('a');

            var mediaUploader = wp.media({
                title: 'Choose File',
                button: { text: 'Choose File' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                fileIdInput.val(attachment.id);
                link.attr('href', attachment.url).text(attachment.filename);
                fileLink.show();
            });

            mediaUploader.open();
        });

        $(document).on('click', '.remove-file-button', function() {
            $(this).closest('.file-item').remove();
        });
    });
    </script>
    <?php
}

// Save Resources meta
function nrna_save_resources_meta_boxes($post_id) {
    if (array_key_exists('resource_files', $_POST)) {
        $files = [];
        foreach ($_POST['resource_files'] as $file_id) {
            if (!empty($file_id)) {
                $files[] = intval($file_id);
            }
        }
        update_post_meta($post_id, 'resource_files', $files);
    } else {
        delete_post_meta($post_id, 'resource_files');
    }
}
add_action('save_post', 'nrna_save_resources_meta_boxes');

// Clean up Resources admin screen
function nrna_remove_resources_meta_boxes() {
    remove_meta_box('slugdiv', 'resources', 'normal');
    remove_meta_box('authordiv', 'resources', 'normal');
    remove_meta_box('commentsdiv', 'resources', 'normal');
    remove_meta_box('revisionsdiv', 'resources', 'normal');
}
add_action('admin_menu', 'nrna_remove_resources_meta_boxes');
