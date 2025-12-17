<?php
// Add Projects meta boxes
function nrna_add_projects_meta_boxes()
{
    add_meta_box(
        'projects_meta_box',
        __('Project Content', 'nrna'),
        'nrna_render_projects_meta_box',
        'projects',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_projects_meta_boxes');

// Render Projects meta box with tabs
function nrna_render_projects_meta_box($post)
{
    wp_nonce_field('nrna_projects_meta_box', 'nrna_projects_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero Section',
        'objectives' => 'Objectives',
        'locations' => 'Project Location',
        'banner' => 'Banner',
        'downloads' => 'Downloads',
        'image-gallery' => 'Image Gallery',
    ];

    echo '<div class="projects-meta-tabs">';
    echo '<div class="tab-buttons">';
    foreach ($tabs as $key => $label) {
        echo '<button type="button" class="tab-button" data-tab="' . esc_attr($key) . '">' . esc_html($label) . '</button>';
    }
    echo '</div>';

    echo '<div class="tab-content">';

    foreach ($tabs as $key => $label) {
        echo '<div class="tab-pane" id="tab-' . esc_attr($key) . '">';

        switch ($key) {
            case 'hero':
                $hero_title = get_post_meta($post->ID, 'project_hero_title', true);
                $date = get_post_meta($post->ID, 'project_date', true);
                $sub_title = get_post_meta($post->ID, 'project_sub_title', true);
                $cta_link_1 = get_post_meta($post->ID, 'project_cta_link_1', true);
                $cta_title_1 = get_post_meta($post->ID, 'project_cta_title_1', true);
                $cta_link_2 = get_post_meta($post->ID, 'project_cta_link_2', true);
                $cta_title_2 = get_post_meta($post->ID, 'project_cta_title_2', true);
                $description = get_post_meta($post->ID, 'project_description', true);

?>
                <p><label>Title:</label><br>
                    <?php wp_editor($hero_title, 'project_hero_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'project_hero_title',
                    ]); ?>
                </p>
                <p><label>Date:</label><br><input type="date" name="project_date" value="<?php echo esc_attr($date); ?>" class="wide-input"></p>
                <p><label>Sub Title:</label><br><input type="text" name="project_sub_title" value="<?php echo esc_attr($sub_title); ?>" class="wide-input"></p>
                <p><label>CTA Title 1:</label><br><input type="text" name="project_cta_title_1" value="<?php echo esc_attr($cta_title_1); ?>" class="wide-input"></p>
                <p><label>CTA Link 1:</label><br><input type="url" name="project_cta_link_1" value="<?php echo esc_attr($cta_link_1); ?>" class="wide-input"></p>
                <p><label>CTA Title 2:</label><br><input type="text" name="project_cta_title_2" value="<?php echo esc_attr($cta_title_2); ?>" class="wide-input"></p>
                <p><label>CTA Link 2:</label><br><input type="url" name="project_cta_link_2" value="<?php echo esc_attr($cta_link_2); ?>" class="wide-input"></p>


                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'project_description', true), 'project_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'project_description',
                                                    ));
                                                    ?></p>
            <?php
                break;

            case 'objectives':
                $obj_title = get_post_meta($post->ID, 'project_objective_title', true);
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($obj_title, 'project_objective_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'project_objective_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'project_objective_description', true), 'project_objective_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'project_objective_description',
                                                    ));
                                                    ?></p>
            <?php
                break;

            case 'locations':
                $locations = get_post_meta($post->ID, 'project_locations', true);
                if (!is_array($locations)) $locations = [];
            ?>
                <div class="locations-container">
                    <?php foreach ($locations as $index => $location): ?>
                        <div class="location-item">
                            <p><label>Place:</label><br><input type="text" name="project_locations[<?php echo $index; ?>][place]" value="<?php echo esc_attr($location['place'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Date:</label><br><input type="text" name="project_locations[<?php echo $index; ?>][date]" value="<?php echo esc_attr($location['date'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Description:</label><br>
                                <?php
                                wp_editor($location['description'] ?? '', "project_locations_{$index}_description", array(
                                    'media_buttons' => false,
                                    'textarea_rows' => 5,
                                    'teeny' => false,
                                    'quicktags' => true,
                                    'textarea_name' => "project_locations[{$index}][description]",
                                ));
                                ?>
                            </p>
                            <p><label>CTA Link:</label><br><input type="url" name="project_locations[<?php echo $index; ?>][cta_link]" value="<?php echo esc_attr($location['cta_link'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>CTA Title:</label><br><input type="text" name="project_locations[<?php echo $index; ?>][cta_title]" value="<?php echo esc_attr($location['cta_title'] ?? ''); ?>" class="wide-input"></p>
                            <button type="button" class="remove-location button">Remove Location</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-location button">Add Location</button>
            <?php
                break;

            case 'banner':
                $banner_title = get_post_meta($post->ID, 'project_banner_title', true);
                $banner_cta_link = get_post_meta($post->ID, 'project_banner_cta_link', true);
                $banner_cta_title = get_post_meta($post->ID, 'project_banner_cta_title', true);
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($banner_title, 'project_banner_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'project_banner_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'project_banner_description', true), 'project_banner_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'project_banner_description',
                                                    ));
                                                    ?></p>
                <p><label>CTA Link:</label><br><input type="url" name="project_banner_cta_link" value="<?php echo esc_attr($banner_cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="project_banner_cta_title" value="<?php echo esc_attr($banner_cta_title); ?>" class="wide-input"></p>
            <?php
                break;

            case 'downloads':
                $downloads = get_post_meta($post->ID, 'project_downloads', true);
                if (!is_array($downloads)) $downloads = [];
            ?>
                <div class="downloads-table-container">
                    <table class="downloads-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>File</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($downloads as $index => $download): ?>
                                <tr class="download-row">
                                    <td><input type="text" name="project_downloads[<?php echo $index; ?>][title]" value="<?php echo esc_attr($download['title'] ?? ''); ?>" class="wide-input"></td>
                                    <td>
                                        <div class="file-preview-container">
                                            <input type="hidden" name="project_downloads[<?php echo $index; ?>][file]" value="<?php echo esc_attr($download['file'] ?? ''); ?>" class="download-file-id">
                                            <?php if (!empty($download['file'])): ?>
                                                <?php
                                                $file_url = wp_get_attachment_url($download['file']);
                                                $file_name = basename($file_url);
                                                ?>
                                                <a href="<?php echo esc_url($file_url); ?>" target="_blank" class="file-link"><?php echo esc_html($file_name); ?></a>
                                            <?php endif; ?>
                                            <button type="button" class="select-download-file button"><?php echo empty($download['file']) ? 'Select File' : 'Change File'; ?></button>
                                        </div>
                                    </td>
                                    <td><button type="button" class="remove-download button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-download button">Add Download</button>
                </div>
            <?php
                break;

            case 'image-gallery':
                $image_gallery = get_post_meta($post->ID, 'project_image_gallery', true);
                if (!is_array($image_gallery)) $image_gallery = [];
            ?>
                <div class="image-gallery-container">
                    <div class="gallery-items">
                        <?php foreach ($image_gallery as $index => $image_url): ?>
                            <div class="gallery-item">
                                <input type="hidden" name="project_image_gallery[]" value="<?php echo esc_attr($image_url); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="Gallery Image" style="max-width: 100px; max-height: 100px;">
                                <button type="button" class="remove-gallery-image button">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-gallery-image button">Add Images</button>
                </div>
<?php
                break;
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

// Save Projects meta box
function nrna_save_projects_meta_box($post_id)
{
    if (!isset($_POST['nrna_projects_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_projects_meta_box_nonce'], 'nrna_projects_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'project_hero_title' => 'wp_kses_post',
        'project_date' => 'sanitize_text_field',
        'project_sub_title' => 'sanitize_text_field',
        'project_cta_link_1' => 'esc_url_raw',
        'project_cta_title_1' => 'sanitize_text_field',
        'project_cta_link_2' => 'esc_url_raw',
        'project_cta_title_2' => 'sanitize_text_field',
        'project_description' => 'wp_kses_post',
        'project_objective_title' => 'wp_kses_post',
        'project_objective_description' => 'wp_kses_post',
        'project_banner_title' => 'wp_kses_post',
        'project_banner_description' => 'wp_kses_post',
        'project_banner_cta_link' => 'esc_url_raw',
        'project_banner_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    // Save Locations
    if (isset($_POST['project_locations'])) {
        $locations = $_POST['project_locations'];
        $sanitized_locations = [];
        foreach ((array)$locations as $index => $location) {
            $clean = [];
            if (isset($location['place'])) $clean['place'] = sanitize_text_field($location['place']);
            if (isset($location['date'])) $clean['date'] = sanitize_text_field($location['date']);
            if (isset($location['date'])) $clean['date'] = sanitize_text_field($location['date']);

            // Handle wp_editor content for description
            $location_desc_key = 'project_locations_' . $index . '_description';
            if (isset($_POST[$location_desc_key])) {
                $clean['description'] = wp_kses_post($_POST[$location_desc_key]);
            } elseif (isset($location['description'])) {
                $clean['description'] = wp_kses_post($location['description']);
            }
            if (isset($location['cta_link'])) $clean['cta_link'] = esc_url_raw($location['cta_link']);
            if (isset($location['cta_title'])) $clean['cta_title'] = sanitize_text_field($location['cta_title']);
            if (!empty($clean)) $sanitized_locations[] = $clean;
        }
        update_post_meta($post_id, 'project_locations', $sanitized_locations);
    } else {
        delete_post_meta($post_id, 'project_locations');
    }

    // Save Downloads
    if (isset($_POST['project_downloads'])) {
        $downloads = $_POST['project_downloads'];
        $sanitized_downloads = [];
        foreach ((array)$downloads as $dl) {
            $clean = [];
            if (isset($dl['title'])) $clean['title'] = sanitize_text_field($dl['title']);
            if (isset($dl['file'])) $clean['file'] = intval($dl['file']);
            if (!empty($clean)) $sanitized_downloads[] = $clean;
        }
        update_post_meta($post_id, 'project_downloads', $sanitized_downloads);
    } else {
        delete_post_meta($post_id, 'project_downloads');
    }

    // Save Image Gallery
    if (isset($_POST['project_image_gallery'])) {
        $gallery = array_map('esc_url_raw', $_POST['project_image_gallery']);
        update_post_meta($post_id, 'project_image_gallery', $gallery);
    } else {
        delete_post_meta($post_id, 'project_image_gallery');
    }
}
add_action('save_post', 'nrna_save_projects_meta_box');

// Clean up Projects admin screen (remove standard boxes if needed, currently only removing generic ones)
function nrna_remove_projects_extra_boxes()
{
    // remove_meta_box('slugdiv', 'projects', 'normal'); // slugs are useful
    // remove_meta_box('postcustom', 'projects', 'normal');
}
// add_action('admin_menu', 'nrna_remove_projects_extra_boxes');
