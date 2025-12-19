<?php
// Add Regional Meetings meta boxes
function nrna_add_regional_meetings_meta_boxes()
{
    add_meta_box(
        'regional_meetings_meta_box',
        __('Regional Meeting Content', 'nrna'),
        'nrna_render_regional_meetings_meta_box',
        'regional_meetings',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_regional_meetings_meta_boxes');

// Render Regional Meetings meta box with tabs
function nrna_render_regional_meetings_meta_box($post)
{
    wp_nonce_field('nrna_regional_meetings_meta_box', 'nrna_regional_meetings_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero Section',
        'agenda' => 'Agenda',
        'contact' => 'Contact Information',
        'sponsorship' => 'Sponsorship Opportunities',
        'organizing-committee' => 'Organizing Committee',
        'sponsors' => 'Our Sponsors',
        'partners' => 'Partners',
        'image-gallery' => 'Image Gallery',
        'video-gallery' => 'Video Gallery',
        'downloads' => 'Downloads',
    ];

    echo '<div class="regional-meetings-meta-tabs">';
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
                $location = get_post_meta($post->ID, 'rm_location', true);
                $start_date = get_post_meta($post->ID, 'rm_start_date', true);
                $end_date = get_post_meta($post->ID, 'rm_end_date', true);
                $cta_link = get_post_meta($post->ID, 'rm_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'rm_cta_title', true);
                $description = get_post_meta($post->ID, 'rm_description', true);
?>
                <p><label>Location:</label><br><input type="text" name="rm_location" value="<?php echo esc_attr($location); ?>" class="wide-input"></p>
                <p><label>Start Date:</label><br><input type="date" name="rm_start_date" value="<?php echo esc_attr($start_date); ?>" class="wide-input"></p>
                <p><label>End Date:</label><br><input type="date" name="rm_end_date" value="<?php echo esc_attr($end_date); ?>" class="wide-input"></p>
                <p><label>CTA Link:</label><br><input type="url" name="rm_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="rm_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'rm_description', true), 'rm_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'rm_description',
                                                    ));
                                                    ?></p>
            <?php
                break;

            case 'agenda':
                $agenda_title = get_post_meta($post->ID, 'rm_agenda_title', true);
                $agenda_description = get_post_meta($post->ID, 'rm_agenda_description', true);
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($agenda_title, 'rm_agenda_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'rm_agenda_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'rm_agenda_description', true), 'rm_agenda_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'rm_agenda_description',
                                                    ));
                                                    ?></p>
            <?php
                break;

            case 'contact':
                $contact_title = get_post_meta($post->ID, 'rm_contact_title', true);
                $contact_description = get_post_meta($post->ID, 'rm_contact_description', true);
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($contact_title, 'rm_contact_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'rm_contact_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'rm_contact_description', true), 'rm_contact_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'rm_contact_description',
                                                    ));
                                                    ?></p>
            <?php
                break;

            case 'sponsorship':
                $sponsorship_title = get_post_meta($post->ID, 'rm_sponsorship_title', true);
                $sponsorship_description = get_post_meta($post->ID, 'rm_sponsorship_description', true);
                $sponsorships = get_post_meta($post->ID, 'rm_sponsorships', true);
                if (!is_array($sponsorships)) $sponsorships = [];
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($sponsorship_title, 'rm_sponsorship_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 2,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'rm_sponsorship_title',
                    ]); ?>
                </p>
                <p><label>Description:</label><br><?php
                                                    wp_editor(get_post_meta($post->ID, 'rm_sponsorship_description', true), 'rm_sponsorship_description', array(
                                                        'media_buttons' => false,
                                                        'textarea_rows' => 5,
                                                        'teeny' => false,
                                                        'quicktags' => true,
                                                        'textarea_name' => 'rm_sponsorship_description',
                                                    ));
                                                    ?></p>
                <div class="sponsorship-table-container">
                    <table class="sponsorship-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Amount to be Contributed</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sponsorships as $index => $sponsorship): ?>
                                <tr class="sponsorship-row">
                                    <td><input type="text" name="rm_sponsorships[<?php echo $index; ?>][category]" value="<?php echo esc_attr($sponsorship['category'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_sponsorships[<?php echo $index; ?>][amount]" value="<?php echo esc_attr($sponsorship['amount'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-sponsorship button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-sponsorship button">Add Sponsorship Category</button>
                </div>
            <?php
                break;

            case 'organizing-committee':
                $organizing_committee_title = get_post_meta($post->ID, 'rm_organizing_committee_title', true);
                $organizing_committee = get_post_meta($post->ID, 'rm_organizing_committee', true);
                if (!is_array($organizing_committee)) $organizing_committee = [];
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($organizing_committee_title, 'rm_organizing_committee_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 5,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'rm_organizing_committee_title',
                    ]); ?>
                </p>
                <div class="committee-table-container">
                    <table class="committee-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Service</th>
                                <th>Country</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizing_committee as $index => $member): ?>
                                <tr class="committee-row">
                                    <td>
                                        <div class="image-preview-container">
                                            <input type="hidden" name="rm_organizing_committee[<?php echo $index; ?>][photo]" value="<?php echo esc_attr($member['photo'] ?? ''); ?>" class="committee-photo-url">
                                            <img src="<?php echo esc_url($member['photo'] ?? ''); ?>" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($member['photo']) ? 'display: none;' : ''; ?>">
                                            <button type="button" class="select-image button"><?php echo empty($member['photo']) ? 'Select Image' : 'Change Image'; ?></button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="rm_organizing_committee[<?php echo $index; ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_organizing_committee[<?php echo $index; ?>][role]" value="<?php echo esc_attr($member['role'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_organizing_committee[<?php echo $index; ?>][service]" value="<?php echo esc_attr($member['service'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_organizing_committee[<?php echo $index; ?>][country]" value="<?php echo esc_attr($member['country'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-committee button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-committee button">Add Committee Member</button>
                </div>
            <?php
                break;

            case 'sponsors':
                $sponsors_title = get_post_meta($post->ID, 'rm_sponsors_title', true);
                $sponsors = get_post_meta($post->ID, 'rm_sponsors', true);
                if (!is_array($sponsors)) $sponsors = [];
            ?>
                <p><label>Title:</label><br>
                    <?php wp_editor($sponsors_title, 'rm_sponsors_title', [
                        'media_buttons' => false,
                        'textarea_rows' => 5,
                        'teeny' => false,
                        'quicktags' => true,
                        'textarea_name' => 'rm_sponsors_title',
                    ]); ?>
                </p>
                <div class="sponsors-table-container">
                    <table class="sponsors-table">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Service</th>
                                <th>Country</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sponsors as $index => $sponsor): ?>
                                <tr class="sponsor-row">
                                    <td>
                                        <div class="image-preview-container">
                                            <input type="hidden" name="rm_sponsors[<?php echo $index; ?>][photo]" value="<?php echo esc_attr($sponsor['photo'] ?? ''); ?>" class="committee-photo-url">
                                            <img src="<?php echo esc_url($sponsor['photo'] ?? ''); ?>" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($sponsor['photo']) ? 'display: none;' : ''; ?>">
                                            <button type="button" class="select-image button"><?php echo empty($sponsor['photo']) ? 'Select Image' : 'Change Image'; ?></button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="rm_sponsors[<?php echo $index; ?>][name]" value="<?php echo esc_attr($sponsor['name'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_sponsors[<?php echo $index; ?>][role]" value="<?php echo esc_attr($sponsor['role'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_sponsors[<?php echo $index; ?>][service]" value="<?php echo esc_attr($sponsor['service'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="rm_sponsors[<?php echo $index; ?>][country]" value="<?php echo esc_attr($sponsor['country'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-sponsor button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-sponsor button">Add Sponsor</button>
                </div>
            <?php
                break;

            case 'partners':
                $partners = get_post_meta($post->ID, 'rm_partners', true);
                if (!is_array($partners)) $partners = [];
            ?>
                <div class="partners-container">
                    <h4>Gold Partners</h4>
                    <div class="partners-section" data-category="gold">
                        <table class="partners-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $gold_partners = array_filter($partners, function ($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'gold';
                                });
                                foreach ($gold_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <div class="image-preview-container">
                                                <input type="hidden" name="rm_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="rm_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="rm_partners[<?php echo $index; ?>][category]" value="gold">
                                            <button type="button" class="remove-partner button">Remove</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="add-partner button" data-category="gold">Add Gold Partner</button>
                    </div>

                    <h4>Silver Partners</h4>
                    <div class="partners-section" data-category="silver">
                        <table class="partners-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $silver_partners = array_filter($partners, function ($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'silver';
                                });
                                foreach ($silver_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <div class="image-preview-container">
                                                <input type="hidden" name="rm_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="rm_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="rm_partners[<?php echo $index; ?>][category]" value="silver">
                                            <button type="button" class="remove-partner button">Remove</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="add-partner button" data-category="silver">Add Silver Partner</button>
                    </div>

                    <h4>Event Partners</h4>
                    <div class="partners-section" data-category="event">
                        <table class="partners-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $event_partners = array_filter($partners, function ($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'event';
                                });
                                foreach ($event_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <div class="image-preview-container">
                                                <input type="hidden" name="rm_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="rm_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="rm_partners[<?php echo $index; ?>][category]" value="event">
                                            <button type="button" class="remove-partner button">Remove</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="add-partner button" data-category="event">Add Event Partner</button>
                    </div>

                    <h4>Airlines Partners</h4>
                    <div class="partners-section" data-category="airlines">
                        <table class="partners-table">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $airlines_partners = array_filter($partners, function ($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'airlines';
                                });
                                foreach ($airlines_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <div class="image-preview-container">
                                                <input type="hidden" name="rm_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="rm_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="rm_partners[<?php echo $index; ?>][category]" value="airlines">
                                            <button type="button" class="remove-partner button">Remove</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="add-partner button" data-category="airlines">Add Airlines Partner</button>
                    </div>
                </div>
            <?php
                break;

            case 'image-gallery':
                $image_gallery = get_post_meta($post->ID, 'rm_image_gallery', true);
                if (!is_array($image_gallery)) $image_gallery = [];
            ?>
                <div class="image-gallery-container">
                    <div class="gallery-items">
                        <?php foreach ($image_gallery as $index => $image_url): ?>
                            <div class="gallery-item">
                                <input type="hidden" name="rm_image_gallery[]" value="<?php echo esc_attr($image_url); ?>">
                                <img src="<?php echo esc_url($image_url); ?>" alt="Gallery Image" style="max-width: 100px; max-height: 100px;">
                                <button type="button" class="remove-gallery-image button">Remove</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-gallery-image button">Add Images</button>
                </div>
            <?php
                break;

            case 'video-gallery':
                $video_gallery = get_post_meta($post->ID, 'rm_video_gallery', true);
                if (!is_array($video_gallery)) $video_gallery = [];
            ?>
                <div class="video-gallery-container">
                    <div class="video-items">
                        <?php foreach ($video_gallery as $index => $video_url): ?>
                            <div class="video-item">
                                <p><label>YouTube URL:</label><br>
                                    <input type="url" name="rm_video_gallery[]" value="<?php echo esc_attr($video_url); ?>" class="wide-input">
                                    <button type="button" class="remove-video-link button">Remove</button>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-video-link button">Add Video Link</button>
                </div>
            <?php
                break;
            case 'downloads':
                $downloads = get_post_meta($post->ID, 'rm_downloads', true);
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
                                    <td><input type="text" name="rm_downloads[<?php echo $index; ?>][title]" value="<?php echo esc_attr($download['title'] ?? ''); ?>" class="wide-input"></td>
                                    <td>
                                        <div class="file-preview-container">
                                            <input type="hidden" name="rm_downloads[<?php echo $index; ?>][file]" value="<?php echo esc_attr($download['file'] ?? ''); ?>" class="download-file-id">
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
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

// Save Regional Meetings meta box
function nrna_save_regional_meetings_meta_box($post_id)
{
    if (!isset($_POST['nrna_regional_meetings_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_regional_meetings_meta_box_nonce'], 'nrna_regional_meetings_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [ 
        'rm_location' => 'sanitize_text_field',
        'rm_start_date' => 'sanitize_text_field',
        'rm_end_date' => 'sanitize_text_field',
        'rm_cta_link' => 'esc_url_raw',
        'rm_cta_title' => 'sanitize_text_field',
        'rm_description' => 'wp_kses_post',
        'rm_agenda_title' => 'wp_kses_post',
        'rm_agenda_description' => 'wp_kses_post',
        'rm_contact_title' => 'wp_kses_post',
        'rm_contact_description' => 'wp_kses_post',
        'rm_sponsorship_title' => 'wp_kses_post',
        'rm_sponsorship_description' => 'wp_kses_post',
        'rm_organizing_committee_title' => 'wp_kses_post',
        'rm_sponsors_title' => 'wp_kses_post',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    // Save sponsorships array
    if (isset($_POST['rm_sponsorships'])) {
        $sponsorship_data = $_POST['rm_sponsorships'];
        $sanitized_sponsorships = [];

        foreach ((array)$sponsorship_data as $sponsorship) {
            $clean_sponsorship = [];
            if (isset($sponsorship['category'])) $clean_sponsorship['category'] = sanitize_text_field($sponsorship['category']);
            if (isset($sponsorship['amount'])) $clean_sponsorship['amount'] = sanitize_text_field($sponsorship['amount']);
            if (!empty($clean_sponsorship)) $sanitized_sponsorships[] = $clean_sponsorship;
        }

        update_post_meta($post_id, 'rm_sponsorships', $sanitized_sponsorships);
    } else {
        delete_post_meta($post_id, 'rm_sponsorships');
    }

    // Save organizing committee array
    if (isset($_POST['rm_organizing_committee'])) {
        $committee_data = $_POST['rm_organizing_committee'];
        $sanitized_committee = [];

        foreach ((array)$committee_data as $member) {
            $clean_member = [];
            if (isset($member['photo'])) $clean_member['photo'] = esc_url_raw($member['photo']);
            if (isset($member['name'])) $clean_member['name'] = sanitize_text_field($member['name']);
            if (isset($member['role'])) $clean_member['role'] = sanitize_text_field($member['role']);
            if (isset($member['service'])) $clean_member['service'] = sanitize_text_field($member['service']);
            if (isset($member['country'])) $clean_member['country'] = sanitize_text_field($member['country']);
            if (!empty($clean_member)) $sanitized_committee[] = $clean_member;
        }

        update_post_meta($post_id, 'rm_organizing_committee', $sanitized_committee);
    } else {
        delete_post_meta($post_id, 'rm_organizing_committee');
    }

    // Save sponsors array
    if (isset($_POST['rm_sponsors'])) {
        $sponsors_data = $_POST['rm_sponsors'];
        $sanitized_sponsors = [];

        foreach ((array)$sponsors_data as $sponsor) {
            $clean_sponsor = [];
            if (isset($sponsor['photo'])) $clean_sponsor['photo'] = esc_url_raw($sponsor['photo']);
            if (isset($sponsor['name'])) $clean_sponsor['name'] = sanitize_text_field($sponsor['name']);
            if (isset($sponsor['role'])) $clean_sponsor['role'] = sanitize_text_field($sponsor['role']);
            if (isset($sponsor['service'])) $clean_sponsor['service'] = sanitize_text_field($sponsor['service']);
            if (isset($sponsor['country'])) $clean_sponsor['country'] = sanitize_text_field($sponsor['country']);
            if (!empty($clean_sponsor)) $sanitized_sponsors[] = $clean_sponsor;
        }

        update_post_meta($post_id, 'rm_sponsors', $sanitized_sponsors);
    } else {
        delete_post_meta($post_id, 'rm_sponsors');
    }

    // Save partners array
    if (isset($_POST['rm_partners'])) {
        $partners_data = $_POST['rm_partners'];
        $sanitized_partners = [];

        foreach ((array)$partners_data as $partner) {
            $clean_partner = [];
            if (isset($partner['category'])) $clean_partner['category'] = sanitize_text_field($partner['category']);
            if (isset($partner['logo'])) $clean_partner['logo'] = esc_url_raw($partner['logo']);
            if (isset($partner['name'])) $clean_partner['name'] = sanitize_text_field($partner['name']);
            if (!empty($clean_partner)) $sanitized_partners[] = $clean_partner;
        }

        update_post_meta($post_id, 'rm_partners', $sanitized_partners);
    } else {
        delete_post_meta($post_id, 'rm_partners');
    }

    // Save image gallery
    if (isset($_POST['rm_image_gallery'])) {
        $image_gallery = array_map('esc_url_raw', $_POST['rm_image_gallery']);
        update_post_meta($post_id, 'rm_image_gallery', $image_gallery);
    } else {
        delete_post_meta($post_id, 'rm_image_gallery');
    }

    // Save video gallery
    if (isset($_POST['rm_video_gallery'])) {
        $video_gallery = array_map('esc_url_raw', $_POST['rm_video_gallery']);
        update_post_meta($post_id, 'rm_video_gallery', $video_gallery);
    } else {
        delete_post_meta($post_id, 'rm_video_gallery');
    }

    // Save downloads array
    if (isset($_POST['rm_downloads'])) {
        $downloads_data = $_POST['rm_downloads'];
        $sanitized_downloads = [];

        foreach ((array)$downloads_data as $download) {
            $clean_download = [];
            if (isset($download['title'])) $clean_download['title'] = sanitize_text_field($download['title']);
            if (isset($download['file'])) $clean_download['file'] = sanitize_text_field($download['file']);
            if (!empty($clean_download)) $sanitized_downloads[] = $clean_download;
        }

        update_post_meta($post_id, 'rm_downloads', $sanitized_downloads);
    } else {
        delete_post_meta($post_id, 'rm_downloads');
    }
}
add_action('save_post', 'nrna_save_regional_meetings_meta_box');

// Clean up Regional Meetings admin screen
function nrna_remove_regional_meetings_meta_boxes()
{
    remove_meta_box('slugdiv', 'regional_meetings', 'normal');
    remove_meta_box('authordiv', 'regional_meetings', 'normal');
    remove_meta_box('commentsdiv', 'regional_meetings', 'normal');
    remove_meta_box('revisionsdiv', 'regional_meetings', 'normal');
}
add_action('admin_menu', 'nrna_remove_regional_meetings_meta_boxes');
