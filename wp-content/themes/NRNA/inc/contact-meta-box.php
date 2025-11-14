<?php
function nrna_register_contact_meta_fields() {
    $fields = [
        'hero_title' => ['type' => 'string'],
        'hero_description' => ['type' => 'string'],
        'hero_email' => ['type' => 'string'],
        'hero_location' => ['type' => 'string'],
        'hero_cta_link' => ['type' => 'string'],
        'hero_cta_title' => ['type' => 'string'],
        'map_embed' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }

    // Array fields
    register_post_meta('page', 'hero_phone_numbers', [
        'type' => 'array',
        'items' => ['type' => 'string'],
        'show_in_rest' => true,
        'single' => true,
    ]);

    register_post_meta('page', 'information_descriptions', [
        'type' => 'array',
        'items' => [
            'type' => 'object',
            'properties' => [
                'title' => ['type' => 'string'],
                'description' => ['type' => 'string'],
            ],
        ],
        'show_in_rest' => true,
        'single' => true,
    ]);
}
add_action('init', 'nrna_register_contact_meta_fields');

function nrna_add_contact_meta_box() {
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'template-contact.php') {
        add_meta_box(
            'contact_page_meta_box',
            __('Contact Us Page Content', 'nrna'),
            'nrna_render_contact_meta_box',
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
add_action('add_meta_boxes', 'nrna_add_contact_meta_box');

function nrna_render_contact_meta_box($post) {
    wp_nonce_field('nrna_contact_meta_box', 'nrna_contact_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero',
        'information' => 'Information',
        'map' => 'Map'
    ];

    echo '<div class="contact-meta-tabs">';
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
                $email = get_post_meta($post->ID, 'hero_email', true);
                $phone_numbers = get_post_meta($post->ID, 'hero_phone_numbers', true);
                if (!is_array($phone_numbers)) $phone_numbers = [];
                $location = get_post_meta($post->ID, 'hero_location', true);
                $cta_link = get_post_meta($post->ID, 'hero_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'hero_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="hero_title" value="<?php echo esc_attr($title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php wp_editor($description, 'hero_description', [
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                    'textarea_name' => 'hero_description'
                ]); ?></p>
                <p><label>Email:</label><br><input type="email" name="hero_email" value="<?php echo esc_attr($email); ?>" class="wide-input"></p>
                <div class="repeater-container" data-repeater="hero_phone_numbers">
                    <?php foreach ($phone_numbers as $index => $phone) : ?>
                        <div class="repeater-item">
                            <p><label>Phone Number:</label><br><input type="text" name="hero_phone_numbers[<?php echo $index; ?>]" value="<?php echo esc_attr($phone); ?>" class="wide-input"></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="hero_phone_numbers">Add Phone Number</button>
                <p><label>Location:</label><br><input type="text" name="hero_location" value="<?php echo esc_attr($location); ?>" class="wide-input"></p>
                <p><label>CTA Link:</label><br><input type="url" name="hero_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="hero_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <?php
                break;

            case 'information':
                $descriptions = get_post_meta($post->ID, 'information_descriptions', true);
                if (!is_array($descriptions)) $descriptions = [];
                ?>
                <div class="repeater-container" data-repeater="information_descriptions">
                    <?php foreach ($descriptions as $index => $info) : ?>
                        <div class="repeater-item">
                            <p><label>Information Title:</label><br><input type="text" name="information_descriptions[<?php echo $index; ?>][title]" value="<?php echo esc_attr($info['title'] ?? ''); ?>" class="wide-input"></p>
                            <p><label>Description:</label><br><?php wp_editor($info['description'] ?? '', 'information_descriptions_' . $index, [
                                'media_buttons' => true,
                                'textarea_rows' => 10,
                                'teeny' => false,
                                'quicktags' => true,
                                'textarea_name' => 'information_descriptions[' . $index . '][description]'
                            ]); ?></p>
                            <button type="button" class="remove-item button">Remove</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-item button" data-repeater="information_descriptions">Add Information</button>
                <?php
                break;

            case 'map':
                $map_embed = get_post_meta($post->ID, 'map_embed', true);
                ?>
                <p><label>Map Embed Code:</label><br><textarea name="map_embed" rows="10" class="wide-textarea"><?php echo esc_textarea($map_embed); ?></textarea></p>
                <?php
                break;
        }
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

function nrna_save_contact_meta_box($post_id) {
    if (!isset($_POST['nrna_contact_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_contact_meta_box_nonce'], 'nrna_contact_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'hero_title' => 'sanitize_text_field',
        'hero_description' => 'wp_kses_post',
        'hero_email' => 'sanitize_email',
        'hero_location' => 'sanitize_text_field',
        'hero_cta_link' => 'esc_url_raw',
        'hero_cta_title' => 'sanitize_text_field',
        'map_embed' => 'nrna_sanitize_map_embed',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    $array_fields = ['hero_phone_numbers', 'information_descriptions'];

    foreach ($array_fields as $field) {
        if (!isset($_POST[$field])) {
            delete_post_meta($post_id, $field);
            continue;
        }

        $data = $_POST[$field];
        $sanitized = [];

        foreach ((array)$data as $item) {
            if ($field === 'hero_phone_numbers') {
                $sanitized[] = sanitize_text_field($item);
            } elseif ($field === 'information_descriptions') {
                $sanitized[] = [
                    'title' => sanitize_text_field($item['title'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                ];
            }
        }

        update_post_meta($post_id, $field, $sanitized);
    }
}
function nrna_sanitize_map_embed($value) {
    return wp_kses($value, array(
        'iframe' => array(
            'src' => array(),
            'width' => array(),
            'height' => array(),
            'frameborder' => array(),
            'style' => array(),
            'allowfullscreen' => array(),
            'loading' => array(),
        ),
    ));
}

add_action('save_post', 'nrna_save_contact_meta_box');

function nrna_prepare_contact_page_rest_response($response, $post, $request) {
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-contact.php') {
        return $response;
    }

    $data = $response->get_data();

    // List of contact-related meta keys
    $contact_meta_keys = [
        'hero_title',
        'hero_description',
        'hero_email',
        'hero_phone_numbers',
        'hero_location',
        'hero_cta_link',
        'hero_cta_title',
        'information_descriptions',
        'map_embed'
    ];

    // Include all contact-related meta fields, even if empty
    $filtered_meta = [];
    foreach ($contact_meta_keys as $key) {
        $value = get_post_meta($post->ID, $key, true);
        $filtered_meta[$key] = $value;
    }
    $data['meta'] = $filtered_meta;

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_contact_page_rest_response', 10, 3);
