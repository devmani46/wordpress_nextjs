<?php
// Add Committees page meta boxes
function nrna_add_committees_meta_boxes() {
    add_meta_box(
        'committees_meta_box',
        __('Committees Content', 'nrna'),
        'nrna_render_committees_meta_box',
        'page',
        'normal',
        'high'
    );
}

// Render Committees meta box with tabs
function nrna_render_committees_meta_box($post) {
    wp_nonce_field('nrna_committees_meta_box', 'nrna_committees_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero Section',
        'why' => 'Why Committee Exists',
        'how' => 'How They Work',
        'banner1' => 'Banner_1',
        'teams' => 'Focused Teams, Strong Leadership',
        'banner2' => 'Banner 2',
    ];

    echo '<div class="home-meta-tabs">';
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
                $title = get_post_meta($post->ID, 'committees_hero_title', true);
                $description = get_post_meta($post->ID, 'committees_hero_description', true);
                $images = get_post_meta($post->ID, 'committees_hero_images', true);
                if (!is_array($images)) $images = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_hero_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'committees_hero_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ]); ?></p>
                <div class="repeater-container" data-repeater="committees_hero_images">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="repeater-item">
                            <p><label>Image:</label><br>
                            <input type="hidden" name="committees_hero_images[<?php echo $index; ?>][image]" value="<?php echo esc_attr($image['image'] ?? ''); ?>" class="image-id">
                            <img src="<?php echo ($image['image'] ?? '') ? esc_url(wp_get_attachment_image_url($image['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($image['image'] ?? '') ? 'has-image' : ''; ?>">
                            <button type="button" class="upload-image button">Upload Image</button></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="committees_hero_images">Add Image</button>
                <?php
                break;

            case 'why':
                $title = get_post_meta($post->ID, 'committees_why_title', true);
                $description = get_post_meta($post->ID, 'committees_why_description', true);
                $image = get_post_meta($post->ID, 'committees_why_image', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_why_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'committees_why_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ]); ?></p>
                <p><label>Image:</label><br>
                <input type="hidden" name="committees_why_image" value="<?php echo esc_attr($image); ?>" class="image-id">
                <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
                <button type="button" class="upload-image button">Upload Image</button></p>
                <?php
                break;

            case 'how':
                $title = get_post_meta($post->ID, 'committees_how_title', true);
                $description = get_post_meta($post->ID, 'committees_how_description', true);
                $image = get_post_meta($post->ID, 'committees_how_image', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_how_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'committees_how_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ]); ?></p>
                <p><label>Image:</label><br>
                <input type="hidden" name="committees_how_image" value="<?php echo esc_attr($image); ?>" class="image-id">
                <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
                <button type="button" class="upload-image button">Upload Image</button></p>
                <?php
                break;

            case 'banner1':
                $title = get_post_meta($post->ID, 'committees_banner1_title', true);
                $description = get_post_meta($post->ID, 'committees_banner1_description', true);
                $cta_link = get_post_meta($post->ID, 'committees_banner1_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'committees_banner1_cta_title', true);
                $stats = get_post_meta($post->ID, 'committees_banner1_stats', true);
                if (!is_array($stats)) $stats = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_banner1_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'committees_banner1_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ]); ?></p>
                <p><label>CTA Link:</label><br><input type="url" name="committees_banner1_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="committees_banner1_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <div class="repeater-container" data-repeater="committees_banner1_stats">
                    <?php foreach ($stats as $index => $stat): ?>
                        <div class="repeater-item">
                            <p><label>Title:</label><br><input type="text" name="committees_banner1_stats[<?php echo $index; ?>][title]" value="<?php echo esc_attr($stat['title'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Description:</label><br><?php wp_editor($stat['description'] ?? '', 'committees_banner1_stats_' . $index . '_description', [
                                'media_buttons' => true,
                                'textarea_rows' => 5,
                                'teeny' => false,
                                'quicktags' => true,
                            ]); ?></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="committees_banner1_stats">Add Stat</button>
                <?php
                break;

            case 'teams':
                $title = get_post_meta($post->ID, 'committees_teams_title', true);
                $members = get_post_meta($post->ID, 'committees_teams_members', true);
                if (!is_array($members)) $members = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_teams_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <div class="teams-table-container">
                    <table class="teams-table">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Name</th>
                                <th>Official Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($members as $index => $member): ?>
                                <tr class="teams-row">
                                    <td><input type="text" name="committees_teams_members[<?php echo $index; ?>][project]" value="<?php echo esc_attr($member['project'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="committees_teams_members[<?php echo $index; ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="email" name="committees_teams_members[<?php echo $index; ?>][email]" value="<?php echo esc_attr($member['email'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-teams button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-teams button">Add Team Member</button>
                </div>
                <?php
                break;

            case 'banner2':
                $title = get_post_meta($post->ID, 'committees_banner2_title', true);
                $description = get_post_meta($post->ID, 'committees_banner2_description', true);
                $cta_link = get_post_meta($post->ID, 'committees_banner2_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'committees_banner2_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="committees_banner2_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'committees_banner2_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ]); ?></p>
                <p><label>CTA Link:</label><br><input type="url" name="committees_banner2_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="committees_banner2_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <?php
                break;
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

// Save Committees meta box
function nrna_save_committees_meta_box($post_id) {
    if (!isset($_POST['nrna_committees_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_committees_meta_box_nonce'], 'nrna_committees_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'committees_hero_title' => 'sanitize_text_field',
        'committees_hero_description' => 'wp_kses_post',
        'committees_why_title' => 'sanitize_text_field',
        'committees_why_description' => 'wp_kses_post',
        'committees_why_image' => 'intval',
        'committees_how_title' => 'sanitize_text_field',
        'committees_how_description' => 'wp_kses_post',
        'committees_how_image' => 'intval',
        'committees_banner1_title' => 'sanitize_text_field',
        'committees_banner1_description' => 'wp_kses_post',
        'committees_banner1_cta_link' => 'esc_url_raw',
        'committees_banner1_cta_title' => 'sanitize_text_field',
        'committees_teams_title' => 'sanitize_text_field',
        'committees_banner2_title' => 'sanitize_text_field',
        'committees_banner2_description' => 'wp_kses_post',
        'committees_banner2_cta_link' => 'esc_url_raw',
        'committees_banner2_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['committees_hero_images', 'committees_banner1_stats', 'committees_teams_members'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        if ($field === 'committees_hero_images') {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['image'])) $clean['image'] = intval($item['image']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        } elseif ($field === 'committees_banner1_stats') {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['title'])) $clean['title'] = sanitize_text_field($item['title']);
                if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        } elseif ($field === 'committees_teams_members') {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['project'])) $clean['project'] = sanitize_text_field($item['project']);
                if (isset($item['name'])) $clean['name'] = sanitize_text_field($item['name']);
                if (isset($item['email'])) $clean['email'] = sanitize_email($item['email']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
add_action('save_post', 'nrna_save_committees_meta_box');

// Clean up Committees admin screen
function nrna_remove_committees_meta_boxes() {
    remove_meta_box('slugdiv', 'page', 'normal');
    remove_meta_box('authordiv', 'page', 'normal');
    remove_meta_box('commentsdiv', 'page', 'normal');
    remove_meta_box('revisionsdiv', 'page', 'normal');
}
add_action('admin_menu', 'nrna_remove_committees_meta_boxes');
