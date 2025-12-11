<?php
function nrna_register_our_ncc_page_meta_fields()
{
    $fields = [
        'our_ncc_banner_title' => ['type' => 'string'],
        'our_ncc_banner_cta_link' => ['type' => 'string'],
        'our_ncc_banner_cta_title' => ['type' => 'string'],
    ];

    foreach ($fields as $key => $args) {
        register_post_meta('page', $key, array_merge($args, [
            'show_in_rest' => true,
            'single' => true,
        ]));
    }
}
add_action('init', 'nrna_register_our_ncc_page_meta_fields');

function nrna_add_our_ncc_page_meta_box()
{
    global $post;
    if ($post && $post->ID && get_page_template_slug($post->ID) !== 'template-our-ncc.php') return;

    add_meta_box(
        'our_ncc_page_meta_box',
        __('Our NCC Page Content', 'nrna'),
        'nrna_render_our_ncc_page_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_our_ncc_page_meta_box');

function nrna_render_our_ncc_page_meta_box($post)
{
    if ($post->ID != 0 && get_page_template_slug($post->ID) !== 'template-our-ncc.php') {
        echo '<p>Please select the "Our NCC" template to edit these fields.</p>';
        return;
    }

    wp_nonce_field('nrna_our_ncc_page_meta_box', 'nrna_our_ncc_page_meta_box_nonce');

    $banner_title = get_post_meta($post->ID, 'our_ncc_banner_title', true);
    $banner_cta_link = get_post_meta($post->ID, 'our_ncc_banner_cta_link', true);
    $banner_cta_title = get_post_meta($post->ID, 'our_ncc_banner_cta_title', true);
?>
    <h3 style="margin-top: 20px; padding: 10px; background: #f0f0f1; border-left: 4px solid #2271b1;">Banner Section</h3>
    <p><label>Banner Title:</label><br>
        <?php wp_editor($banner_title, 'our_ncc_banner_title', [
            'media_buttons' => false,
            'textarea_rows' => 3,
            'teeny' => false,
            'quicktags' => true,
        ]); ?>
    </p>
    <p><label>Banner CTA Link:</label><br><input type="url" name="our_ncc_banner_cta_link" value="<?php echo esc_attr($banner_cta_link); ?>" class="wide-input"></p>
    <p><label>Banner CTA Title:</label><br><input type="text" name="our_ncc_banner_cta_title" value="<?php echo esc_attr($banner_cta_title); ?>" class="wide-input"></p>
<?php
}

function nrna_save_our_ncc_page_meta_box($post_id)
{
    if (!isset($_POST['nrna_our_ncc_page_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_our_ncc_page_meta_box_nonce'], 'nrna_our_ncc_page_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'our_ncc_banner_title' => 'wp_kses_post',
        'our_ncc_banner_cta_link' => 'esc_url_raw',
        'our_ncc_banner_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }
}
add_action('save_post', 'nrna_save_our_ncc_page_meta_box');

function nrna_prepare_our_ncc_page_rest_response($response, $post, $request)
{
    if ($post->post_type !== 'page' || get_page_template_slug($post->ID) !== 'template-our-ncc.php') {
        return $response;
    }

    $data = $response->get_data();

    // Filter meta fields to only include our_ncc-related fields
    $filtered_meta = [];
    foreach ($data['meta'] as $key => $value) {
        if (strpos($key, 'our_ncc_') === 0) {
            $filtered_meta[$key] = $value;
        }
    }
    $data['meta'] = $filtered_meta;

    $response->set_data($data);
    return $response;
}
add_filter('rest_prepare_page', 'nrna_prepare_our_ncc_page_rest_response', 10, 3);
?>