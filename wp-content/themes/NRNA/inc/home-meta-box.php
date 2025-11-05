<?php
function nrna_add_home_meta_box() {
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-home.php') {
        add_meta_box(
            'home_page_meta_box',
            __('Home Page Content', 'nrna'),
            'nrna_render_home_meta_box',
            'page',
            'normal',
            'high'
        );
        remove_meta_box('postdivrich', 'page', 'normal');
        remove_meta_box('slugdiv', 'page', 'normal');
        remove_meta_box('authordiv', 'page', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
        remove_meta_box('revisionsdiv', 'page', 'normal');
    }
}
add_action('add_meta_boxes', 'nrna_add_home_meta_box');

function nrna_render_home_meta_box($post) {
    wp_nonce_field('nrna_home_meta_box', 'nrna_home_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero',
        'slider' => 'Slider',
        'about-us' => 'About Us',
        'why-choose-us' => 'Why Choose Us',
        'get-involved' => 'Get Involved',
        'stay-updated' => 'Stay Updated',
        'latest-news' => 'Latest News and Update',
        'our-initiatives' => 'Our Initiatives',
        'join-the-journey' => 'Join the Journey'
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
        $title = get_post_meta($post->ID, 'hero_title', true);
        $description = get_post_meta($post->ID, 'hero_description', true);
        $cta_link = get_post_meta($post->ID, 'hero_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'hero_cta_title', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="hero_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="hero_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <p><label>CTA Link:</label><br><input type="url" name="hero_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="hero_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <?php
        break;

    case 'slider':
        $slider_items = get_post_meta($post->ID, 'slider_items', true);
        if (!is_array($slider_items)) $slider_items = [];
        ?>
        <div class="repeater-container" data-repeater="slider_items">
            <?php foreach ($slider_items as $index => $item): ?>
                <div class="repeater-item">
                    <p><label>Title:</label><br><input type="text" name="slider_items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Image:</label><br>
                    <input type="hidden" name="slider_items[<?php echo $index; ?>][image]" value="<?php echo esc_attr($item['image'] ?? ''); ?>" class="image-id">
                    <img src="<?php echo ($item['image'] ?? '') ? esc_url(wp_get_attachment_image_url($item['image'], 'medium')) : ''; ?>" class="image-preview <?php echo ($item['image'] ?? '') ? 'has-image' : ''; ?>">
                    <button type="button" class="upload-image button">Upload Image</button></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="slider_items">Add Slider</button>
        <?php
        break;

    case 'about-us':
        $title = get_post_meta($post->ID, 'about_title', true);
        $image = get_post_meta($post->ID, 'about_image', true);
        $stats = get_post_meta($post->ID, 'about_stats', true);
        if (!is_array($stats)) $stats = [];
        ?>
        <p><label>Title:</label><br><input type="text" name="about_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Image:</label><br>
        <input type="hidden" name="about_image" value="<?php echo esc_attr($image); ?>" class="image-id">
        <img src="<?php echo $image ? esc_url(wp_get_attachment_image_url($image, 'medium')) : ''; ?>" class="image-preview <?php echo $image ? 'has-image' : ''; ?>">
        <button type="button" class="upload-image button">Upload Image</button></p>
        <div class="repeater-container" data-repeater="about_stats">
            <?php foreach ($stats as $index => $stat): ?>
                <div class="repeater-item">
                    <p><label>Stat Title:</label><br><input type="text" name="about_stats[<?php echo $index; ?>][title]" value="<?php echo esc_attr($stat['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Stat Description:</label><br><textarea name="about_stats[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($stat['description'] ?? ''); ?></textarea></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="about_stats">Add Stat</button>
        <?php
        break;

    case 'why-choose-us':
        $title = get_post_meta($post->ID, 'why_title', true);
        $description = get_post_meta($post->ID, 'why_description', true);
        $cta_link = get_post_meta($post->ID, 'why_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'why_cta_title', true);
        $images = get_post_meta($post->ID, 'why_images', true);
        if (!is_array($images)) $images = array_fill(0, 5, '');
        $features = get_post_meta($post->ID, 'why_features', true);
        if (!is_array($features)) $features = [];
        $video_link = get_post_meta($post->ID, 'why_video_link', true);
        $years = get_post_meta($post->ID, 'why_years_of_services', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="why_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="why_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <p><label>CTA Link:</label><br><input type="url" name="why_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="why_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <h4>Images (exactly 5):</h4>
        <?php for ($i = 0; $i < 5; $i++): ?>
            <p><label>Image <?php echo $i+1; ?>:</label><br>
            <input type="hidden" name="why_images[<?php echo $i; ?>]" value="<?php echo esc_attr($images[$i]); ?>" class="image-id">
            <img src="<?php echo $images[$i] ? esc_url(wp_get_attachment_image_url($images[$i], 'medium')) : ''; ?>" class="image-preview <?php echo $images[$i] ? 'has-image' : ''; ?>">
            <button type="button" class="upload-image button">Upload Image</button></p>
        <?php endfor; ?>
        <div class="repeater-container" data-repeater="why_features">
            <?php foreach ($features as $index => $feature): ?>
                <div class="repeater-item">
                    <p><label>Feature Title:</label><br><input type="text" name="why_features[<?php echo $index; ?>][title]" value="<?php echo esc_attr($feature['title'] ?? ''); ?>" class="wide-input"></p>
                    <p><label>Feature Description:</label><br><textarea name="why_features[<?php echo $index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($feature['description'] ?? ''); ?></textarea></p>
                    <button type="button" class="remove-item button">Remove</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="add-item button" data-repeater="why_features">Add Feature</button>
        <p><label>Video Link:</label><br><input type="url" name="why_video_link" value="<?php echo esc_attr($video_link); ?>" class="wide-input"></p>
        <p><label>Years of Services:</label><br><input type="number" name="why_years_of_services" value="<?php echo esc_attr($years); ?>" class="wide-input"></p>
        <?php
        break;

    case 'get-involved':
        $title = get_post_meta($post->ID, 'involved_title', true);
        $description = get_post_meta($post->ID, 'involved_description', true);
        $actions = get_post_meta($post->ID, 'involved_actions', true);
        if (!is_array($actions)) $actions = array_fill(0, 3, ['title' => '', 'description' => '', 'cta_link' => '', 'cta_title' => '']);
        ?>
        <p><label>Title:</label><br><input type="text" name="involved_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="involved_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <h4>Actions (3 fixed):</h4>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <div class="action-item">
                <p><label>Action <?php echo $i+1; ?> Title:</label><br><input type="text" name="involved_actions[<?php echo $i; ?>][title]" value="<?php echo esc_attr($actions[$i]['title']); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="involved_actions[<?php echo $i; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($actions[$i]['description']); ?></textarea></p>
                <p><label>CTA Link:</label><br><input type="url" name="involved_actions[<?php echo $i; ?>][cta_link]" value="<?php echo esc_attr($actions[$i]['cta_link']); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="involved_actions[<?php echo $i; ?>][cta_title]" value="<?php echo esc_attr($actions[$i]['cta_title']); ?>" class="wide-input"></p>
            </div>
        <?php endfor; ?>
        <?php
        break;

    case 'stay-updated':
    case 'latest-news':
    case 'our-initiatives':
        $prefix = str_replace('-', '_', $key);
        $title = get_post_meta($post->ID, $prefix . '_title', true);
        $description = get_post_meta($post->ID, $prefix . '_description', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="<?php echo $prefix; ?>_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="<?php echo $prefix; ?>_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <?php
        break;

    case 'join-the-journey':
        $title = get_post_meta($post->ID, 'journey_title', true);
        $description = get_post_meta($post->ID, 'journey_description', true);
        $cta_link = get_post_meta($post->ID, 'journey_cta_link', true);
        $cta_title = get_post_meta($post->ID, 'journey_cta_title', true);
        ?>
        <p><label>Title:</label><br><input type="text" name="journey_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
        <p><label>Description:</label><br><textarea name="journey_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
        <p><label>CTA Link:</label><br><input type="url" name="journey_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
        <p><label>CTA Title:</label><br><input type="text" name="journey_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
        <?php
        break;
}
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_home_meta_box($post_id) {
    if (!isset($_POST['nrna_home_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_home_meta_box_nonce'], 'nrna_home_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'hero_title' => 'sanitize_text_field',
        'hero_description' => 'wp_kses_post',
        'hero_cta_link' => 'esc_url_raw',
        'hero_cta_title' => 'sanitize_text_field',

        'about_title' => 'sanitize_text_field',
        'about_image' => 'intval',

        'why_title' => 'sanitize_text_field',
        'why_description' => 'wp_kses_post',
        'why_cta_link' => 'esc_url_raw',
        'why_cta_title' => 'sanitize_text_field',
        'why_video_link' => 'esc_url_raw',
        'why_years_of_services' => 'intval',

        'involved_title' => 'sanitize_text_field',
        'involved_description' => 'wp_kses_post',

        'stay_updated_title' => 'sanitize_text_field',
        'stay_updated_description' => 'wp_kses_post',

        'latest_news_title' => 'sanitize_text_field',
        'latest_news_description' => 'wp_kses_post',

        'our_initiatives_title' => 'sanitize_text_field',
        'our_initiatives_description' => 'wp_kses_post',

        'journey_title' => 'sanitize_text_field',
        'journey_description' => 'wp_kses_post',
        'journey_cta_link' => 'esc_url_raw',
        'journey_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['slider_items', 'about_stats', 'why_features', 'involved_actions', 'why_images'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        if ($field === 'why_images') {
            for ($i = 0; $i < 5; $i++) {
                $sanitized[$i] = intval($data[$i] ?? 0);
            }

        } elseif ($field === 'involved_actions') {
            for ($i = 0; $i < 3; $i++) {
                $item = $data[$i] ?? [];
                $sanitized[$i] = [
                    'title' => sanitize_text_field($item['title'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'cta_link' => esc_url_raw($item['cta_link'] ?? ''),
                    'cta_title' => sanitize_text_field($item['cta_title'] ?? ''),
                ];
            }

        } else {
            foreach ((array)$data as $item) {
                $clean = [];
                if (isset($item['title'])) $clean['title'] = sanitize_text_field($item['title']);
                if (isset($item['description'])) $clean['description'] = wp_kses_post($item['description']);
                if (isset($item['image'])) $clean['image'] = intval($item['image']);
                if (!empty($clean)) $sanitized[] = $clean;
            }
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
add_action('save_post', 'nrna_save_home_meta_box');

