<?php
// Add Events meta boxes
function nrna_add_events_meta_boxes() {
    add_meta_box(
        'events_meta_box',
        __('Event Content', 'nrna'),
        'nrna_render_events_meta_box',
        'events',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nrna_add_events_meta_boxes');

// Render Events meta box with tabs
function nrna_render_events_meta_box($post) {
    wp_nonce_field('nrna_events_meta_box', 'nrna_events_meta_box_nonce');

    $tabs = [
        'hero' => 'Hero Section',
        'objective' => 'Objective Section',
        'schedule' => 'Event Schedule',
        'sponsorship' => 'Sponsorship',
        'venue' => 'Venue',
        'organizing-committee' => 'Organizing Committee',
        'sponsors' => 'Our Sponsors',
        'partners' => 'Partners',
        'banner' => 'Banner',
    ];

    echo '<div class="events-meta-tabs">';
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
                $hero_title = get_post_meta($post->ID, 'event_hero_title', true);
                $location = get_post_meta($post->ID, 'event_location', true);
                $start_date = get_post_meta($post->ID, 'event_start_date', true);
                $end_date = get_post_meta($post->ID, 'event_end_date', true);
                $sub_title = get_post_meta($post->ID, 'event_sub_title', true);
                $cta_link = get_post_meta($post->ID, 'event_cta_link', true);
                $cta_title = get_post_meta($post->ID, 'event_cta_title', true);
                $description = get_post_meta($post->ID, 'event_description', true);
                ?>
                <p><label>Hero Title:</label><br><input type="text" name="event_hero_title" value="<?php echo esc_attr($hero_title); ?>" class="wide-input"></p>
                <p><label>Location:</label><br><input type="text" name="event_location" value="<?php echo esc_attr($location); ?>" class="wide-input"></p>
                <p><label>Start Date:</label><br><input type="date" name="event_start_date" value="<?php echo esc_attr($start_date); ?>" class="wide-input"></p>
                <p><label>End Date:</label><br><input type="date" name="event_end_date" value="<?php echo esc_attr($end_date); ?>" class="wide-input"></p>
                <p><label>Sub Title:</label><br><input type="text" name="event_sub_title" value="<?php echo esc_attr($sub_title); ?>" class="wide-input"></p>
                <p><label>CTA Link:</label><br><input type="url" name="event_cta_link" value="<?php echo esc_attr($cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="event_cta_title" value="<?php echo esc_attr($cta_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_description', true), 'event_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ));
                ?></p>
                <?php
                break;

            case 'objective':
                $obj_title = get_post_meta($post->ID, 'event_objective_title', true);
                $obj_description = get_post_meta($post->ID, 'event_objective_description', true);
                $obj_cta_link = get_post_meta($post->ID, 'event_objective_cta_link', true);
                $obj_cta_title = get_post_meta($post->ID, 'event_objective_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="event_objective_title" value="<?php echo esc_attr($obj_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_objective_description', true), 'event_objective_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ));
                ?></p>
                <p><label>CTA Link:</label><br><input type="url" name="event_objective_cta_link" value="<?php echo esc_attr($obj_cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="event_objective_cta_title" value="<?php echo esc_attr($obj_cta_title); ?>" class="wide-input"></p>
                <?php
                break;

            case 'schedule':
                $schedule_title = get_post_meta($post->ID, 'event_schedule_title', true);
                $schedule_description = get_post_meta($post->ID, 'event_schedule_description', true);
                $schedule_dates = get_post_meta($post->ID, 'event_schedule_dates', true);
                if (!is_array($schedule_dates)) $schedule_dates = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="event_schedule_title" value="<?php echo esc_attr($schedule_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_schedule_description', true), 'event_schedule_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ));
                ?></p>
                <div class="repeater-container" data-repeater="event_schedule_dates">
                    <?php foreach ($schedule_dates as $date_index => $date_item): ?>
                        <div class="date-item">
                            <h4>Date <?php echo $date_index + 1; ?></h4>
                            <p><label>Date:</label><br><input type="date" name="event_schedule_dates[<?php echo $date_index; ?>][date]" value="<?php echo esc_attr($date_item['date'] ?? ''); ?>" class="wide-input"></p>
                            <div class="sessions-container" data-repeater="sessions">
                                <?php if (isset($date_item['sessions']) && is_array($date_item['sessions'])): ?>
                                    <?php foreach ($date_item['sessions'] as $session_index => $session): ?>
                                        <div class="session-item">
                                            <h5>Session <?php echo $session_index + 1; ?></h5>
                                            <p><label>Start Time:</label><br><input type="time" name="event_schedule_dates[<?php echo $date_index; ?>][sessions][<?php echo $session_index; ?>][start_time]" value="<?php echo esc_attr($session['start_time'] ?? ''); ?>" class="wide-input"></p>
                                            <p><label>End Time:</label><br><input type="time" name="event_schedule_dates[<?php echo $date_index; ?>][sessions][<?php echo $session_index; ?>][end_time]" value="<?php echo esc_attr($session['end_time'] ?? ''); ?>" class="wide-input"></p>
                                            <p><label>Title:</label><br><input type="text" name="event_schedule_dates[<?php echo $date_index; ?>][sessions][<?php echo $session_index; ?>][title]" value="<?php echo esc_attr($session['title'] ?? ''); ?>" class="wide-input"></p>
                                            <p><label>Description:</label><br><?php
                                            wp_editor($session['description'] ?? '', "event_schedule_dates_{$date_index}_sessions_{$session_index}_description", array(
                                                'media_buttons' => true,
                                                'textarea_rows' => 10,
                                                'teeny' => false,
                                                'quicktags' => true,
                                            ));
                                            ?></p>
                                            <button type="button" class="remove-session button">Remove Session</button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="add-session button" data-date-index="<?php echo $date_index; ?>">Add Session</button>
                            <button type="button" class="remove-date button">Remove Date</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-date button" data-repeater="event_schedule_dates">Add Date</button>
                <?php
                break;

            case 'sponsorship':
                $sponsorship_title = get_post_meta($post->ID, 'event_sponsorship_title', true);
                $sponsorship_description = get_post_meta($post->ID, 'event_sponsorship_description', true);
                $sponsorships = get_post_meta($post->ID, 'event_sponsorships', true);
                if (!is_array($sponsorships)) $sponsorships = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="event_sponsorship_title" value="<?php echo esc_attr($sponsorship_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_sponsorship_description', true), 'event_sponsorship_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
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
                                    <td><input type="text" name="event_sponsorships[<?php echo $index; ?>][category]" value="<?php echo esc_attr($sponsorship['category'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_sponsorships[<?php echo $index; ?>][amount]" value="<?php echo esc_attr($sponsorship['amount'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-sponsorship button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="button" class="add-sponsorship button">Add Sponsorship Category</button>
                </div>
                <?php
                break;

            case 'venue':
                $venue_title = get_post_meta($post->ID, 'event_venue_title', true);
                $venue_description = get_post_meta($post->ID, 'event_venue_description', true);
                $venue_map = get_post_meta($post->ID, 'event_venue_map', true);
                $venue_details = get_post_meta($post->ID, 'event_venue_details', true);
                if (!is_array($venue_details)) $venue_details = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="event_venue_title" value="<?php echo esc_attr($venue_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_venue_description', true), 'event_venue_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ));
                ?></p>
                <p><label>Map Embed Code:</label><br><textarea name="event_venue_map" rows="4" class="wide-textarea"><?php echo esc_textarea($venue_map); ?></textarea></p>
                <div class="venue-details-container">
                    <h4>Venue Details</h4>
                    <div class="venue-details-list">
                        <?php foreach ($venue_details as $index => $detail): ?>
                            <div class="venue-detail-item">
                                <p><label>Title:</label><br><input type="text" name="event_venue_details[<?php echo $index; ?>][title]" value="<?php echo esc_attr($detail['title'] ?? ''); ?>" class="wide-input"></p>
                                <p><label>Description:</label><br><?php
                                wp_editor($detail['description'] ?? '', "event_venue_details_{$index}_description", array(
                                    'media_buttons' => true,
                                    'textarea_rows' => 5,
                                    'teeny' => false,
                                    'quicktags' => true,
                                ));
                                ?></p>
                                <button type="button" class="remove-venue-detail button">Remove Detail</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-venue-detail button">Add Venue Detail</button>
                </div>
                <?php
                break;

            case 'organizing-committee':
                $organizing_committee_title = get_post_meta($post->ID, 'event_organizing_committee_title', true);
                $organizing_committee = get_post_meta($post->ID, 'event_organizing_committee', true);
                if (!is_array($organizing_committee)) $organizing_committee = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="event_organizing_committee_title" value="<?php echo esc_attr($organizing_committee_title); ?>" class="wide-input"></p>
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
                                        <input type="hidden" name="event_organizing_committee[<?php echo $index; ?>][photo]" value="<?php echo esc_attr($member['photo'] ?? ''); ?>" class="committee-photo-url">
                                        <div class="image-preview-container">
                                            <img src="<?php echo esc_url($member['photo'] ?? ''); ?>" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($member['photo']) ? 'display: none;' : ''; ?>">
                                            <button type="button" class="select-image button"><?php echo empty($member['photo']) ? 'Select Image' : 'Change Image'; ?></button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="event_organizing_committee[<?php echo $index; ?>][name]" value="<?php echo esc_attr($member['name'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_organizing_committee[<?php echo $index; ?>][role]" value="<?php echo esc_attr($member['role'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_organizing_committee[<?php echo $index; ?>][service]" value="<?php echo esc_attr($member['service'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_organizing_committee[<?php echo $index; ?>][country]" value="<?php echo esc_attr($member['country'] ?? ''); ?>" class="wide-input"></td>
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
                $sponsors_title = get_post_meta($post->ID, 'event_sponsors_title', true);
                $sponsors = get_post_meta($post->ID, 'event_sponsors', true);
                if (!is_array($sponsors)) $sponsors = [];
                ?>
                <p><label>Title:</label><br><input type="text" name="event_sponsors_title" value="<?php echo esc_attr($sponsors_title); ?>" class="wide-input"></p>
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
                                        <input type="hidden" name="event_sponsors[<?php echo $index; ?>][photo]" value="<?php echo esc_attr($sponsor['photo'] ?? ''); ?>" class="committee-photo-url">
                                        <div class="image-preview-container">
                                            <img src="<?php echo esc_url($sponsor['photo'] ?? ''); ?>" alt="Photo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($sponsor['photo']) ? 'display: none;' : ''; ?>">
                                            <button type="button" class="select-image button"><?php echo empty($sponsor['photo']) ? 'Select Image' : 'Change Image'; ?></button>
                                        </div>
                                    </td>
                                    <td><input type="text" name="event_sponsors[<?php echo $index; ?>][name]" value="<?php echo esc_attr($sponsor['name'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_sponsors[<?php echo $index; ?>][role]" value="<?php echo esc_attr($sponsor['role'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_sponsors[<?php echo $index; ?>][service]" value="<?php echo esc_attr($sponsor['service'] ?? ''); ?>" class="wide-input"></td>
                                    <td><input type="text" name="event_sponsors[<?php echo $index; ?>][country]" value="<?php echo esc_attr($sponsor['country'] ?? ''); ?>" class="wide-input"></td>
                                    <td><button type="button" class="remove-sponsor button">Remove</button></td>
                                </tr>
                            <?php endforeach; ?>
                    </table>
                    <button type="button" class="add-sponsor button">Add Sponsor</button>
                </div>
                <?php
                break;

            case 'partners':
                $partners = get_post_meta($post->ID, 'event_partners', true);
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
                                $gold_partners = array_filter($partners, function($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'gold';
                                });
                                foreach ($gold_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                            <div class="image-preview-container">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="event_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][category]" value="gold">
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
                                $silver_partners = array_filter($partners, function($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'silver';
                                });
                                foreach ($silver_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                            <div class="image-preview-container">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="event_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][category]" value="silver">
                                            <button type="button" class="remove-partner button">Remove</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button type="button" class="add-partner button" data-category="silver">Add Silver Partner</button>
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
                                $airlines_partners = array_filter($partners, function($partner) {
                                    return isset($partner['category']) && $partner['category'] === 'airlines';
                                });
                                foreach ($airlines_partners as $index => $partner): ?>
                                    <tr class="partner-row">
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][logo]" value="<?php echo esc_attr($partner['logo'] ?? ''); ?>" class="committee-photo-url">
                                            <div class="image-preview-container">
                                                <img src="<?php echo esc_url($partner['logo'] ?? ''); ?>" alt="Logo Preview" class="committee-photo-preview" style="max-width: 50px; max-height: 50px; <?php echo empty($partner['logo']) ? 'display: none;' : ''; ?>">
                                                <button type="button" class="select-image button"><?php echo empty($partner['logo']) ? 'Select Image' : 'Change Image'; ?></button>
                                            </div>
                                        </td>
                                        <td><input type="text" name="event_partners[<?php echo $index; ?>][name]" value="<?php echo esc_attr($partner['name'] ?? ''); ?>" class="wide-input"></td>
                                        <td>
                                            <input type="hidden" name="event_partners[<?php echo $index; ?>][category]" value="airlines">
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

            case 'banner':
                $banner_title = get_post_meta($post->ID, 'event_banner_title', true);
                $banner_description = get_post_meta($post->ID, 'event_banner_description', true);
                $banner_cta_link = get_post_meta($post->ID, 'event_banner_cta_link', true);
                $banner_cta_title = get_post_meta($post->ID, 'event_banner_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="event_banner_title" value="<?php echo esc_attr($banner_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><?php
                wp_editor(get_post_meta($post->ID, 'event_banner_description', true), 'event_banner_description', array(
                    'media_buttons' => true,
                    'textarea_rows' => 10,
                    'teeny' => false,
                    'quicktags' => true,
                ));
                ?></p>
                <p><label>CTA Link:</label><br><input type="url" name="event_banner_cta_link" value="<?php echo esc_attr($banner_cta_link); ?>" class="wide-input"></p>
                <p><label>CTA Title:</label><br><input type="text" name="event_banner_cta_title" value="<?php echo esc_attr($banner_cta_title); ?>" class="wide-input"></p>
                <?php
                break;
        }

        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
}

// Save Events meta box
function nrna_save_events_meta_box($post_id) {
    if (!isset($_POST['nrna_events_meta_box_nonce']) || !wp_verify_nonce($_POST['nrna_events_meta_box_nonce'], 'nrna_events_meta_box')) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = [
        'event_hero_title' => 'sanitize_text_field',
        'event_location' => 'sanitize_text_field',
        'event_start_date' => 'sanitize_text_field', 
        'event_end_date' => 'sanitize_text_field', 
        'event_sub_title' => 'sanitize_text_field',
        'event_cta_link' => 'esc_url_raw',
        'event_cta_title' => 'sanitize_text_field',
        'event_description' => 'wp_kses_post',
        'event_objective_title' => 'sanitize_text_field',
        'event_objective_description' => 'wp_kses_post',
        'event_objective_cta_link' => 'esc_url_raw',
        'event_objective_cta_title' => 'sanitize_text_field',
        'event_schedule_title' => 'sanitize_text_field',
        'event_schedule_description' => 'wp_kses_post',
        'event_sponsorship_title' => 'sanitize_text_field',
        'event_sponsorship_description' => 'wp_kses_post',
        'event_venue_title' => 'sanitize_text_field',
        'event_venue_description' => 'wp_kses_post',
        'event_venue_map' => 'wp_kses_post',
        'event_organizing_committee_title' => 'sanitize_text_field',
        'event_sponsors_title' => 'sanitize_text_field',
        'event_banner_title' => 'sanitize_text_field',
        'event_banner_description' => 'wp_kses_post',
        'event_banner_cta_link' => 'esc_url_raw',
        'event_banner_cta_title' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, call_user_func($sanitize, $_POST[$field]));
        }
    }

    // Save schedule dates array
    if (isset($_POST['event_schedule_dates'])) {
        $schedule_data = $_POST['event_schedule_dates'];
        $sanitized_schedule = [];

        foreach ((array)$schedule_data as $date_item) {
            $clean_date = [];
            if (isset($date_item['date'])) $clean_date['date'] = sanitize_text_field($date_item['date']);

            $clean_sessions = [];
            if (isset($date_item['sessions']) && is_array($date_item['sessions'])) {
                foreach ($date_item['sessions'] as $session) {
                    $clean_session = [];
                    if (isset($session['start_time'])) $clean_session['start_time'] = sanitize_text_field($session['start_time']);
                    if (isset($session['end_time'])) $clean_session['end_time'] = sanitize_text_field($session['end_time']);
                    if (isset($session['title'])) $clean_session['title'] = sanitize_text_field($session['title']);
                    // Handle wp_editor content for session descriptions
                    $session_desc_key = 'event_schedule_dates_' . array_search($date_item, $schedule_data) . '_sessions_' . array_search($session, $date_item['sessions']) . '_description';
                    if (isset($_POST[$session_desc_key])) {
                        $clean_session['description'] = wp_kses_post($_POST[$session_desc_key]);
                    }
                    if (!empty($clean_session)) $clean_sessions[] = $clean_session;
                }
            }
            $clean_date['sessions'] = $clean_sessions;
            if (!empty($clean_date)) $sanitized_schedule[] = $clean_date;
        }

        update_post_meta($post_id, 'event_schedule_dates', $sanitized_schedule);
    } else {
        delete_post_meta($post_id, 'event_schedule_dates');
    }

    // Save sponsorships array
    if (isset($_POST['event_sponsorships'])) {
        $sponsorship_data = $_POST['event_sponsorships'];
        $sanitized_sponsorships = [];

        foreach ((array)$sponsorship_data as $sponsorship) {
            $clean_sponsorship = [];
            if (isset($sponsorship['category'])) $clean_sponsorship['category'] = sanitize_text_field($sponsorship['category']);
            if (isset($sponsorship['amount'])) $clean_sponsorship['amount'] = sanitize_text_field($sponsorship['amount']);
            if (!empty($clean_sponsorship)) $sanitized_sponsorships[] = $clean_sponsorship;
        }

        update_post_meta($post_id, 'event_sponsorships', $sanitized_sponsorships);
    } else {
        delete_post_meta($post_id, 'event_sponsorships');
    }

    // Save venue details array
    if (isset($_POST['event_venue_details'])) {
        $venue_data = $_POST['event_venue_details'];
        $sanitized_venue_details = [];

        foreach ((array)$venue_data as $detail) {
            $clean_detail = [];
            if (isset($detail['title'])) $clean_detail['title'] = sanitize_text_field($detail['title']);
            // Handle wp_editor content for venue detail descriptions
            $detail_desc_key = 'event_venue_details_' . array_search($detail, $venue_data) . '_description';
            if (isset($_POST[$detail_desc_key])) {
                $clean_detail['description'] = wp_kses_post($_POST[$detail_desc_key]);
            }
            if (!empty($clean_detail)) $sanitized_venue_details[] = $clean_detail;
        }

        update_post_meta($post_id, 'event_venue_details', $sanitized_venue_details);
    } else {
        delete_post_meta($post_id, 'event_venue_details');
    }

    // Save organizing committee array
    if (isset($_POST['event_organizing_committee'])) {
        $committee_data = $_POST['event_organizing_committee'];
        $sanitized_committee = [];

        foreach ((array)$committee_data as $member) {
            $clean_member = [];
            if (isset($member['photo'])) $clean_member['photo'] = sanitize_text_field($member['photo']);
            if (isset($member['name'])) $clean_member['name'] = sanitize_text_field($member['name']);
            if (isset($member['role'])) $clean_member['role'] = sanitize_text_field($member['role']);
            if (isset($member['service'])) $clean_member['service'] = sanitize_text_field($member['service']);
            if (isset($member['country'])) $clean_member['country'] = sanitize_text_field($member['country']);
            if (!empty($clean_member)) $sanitized_committee[] = $clean_member;
        }

        update_post_meta($post_id, 'event_organizing_committee', $sanitized_committee);
    } else {
        delete_post_meta($post_id, 'event_organizing_committee');
    }

    // Save sponsors array
    if (isset($_POST['event_sponsors'])) {
        $sponsors_data = $_POST['event_sponsors'];
        $sanitized_sponsors = [];

        foreach ((array)$sponsors_data as $sponsor) {
            $clean_sponsor = [];
            if (isset($sponsor['photo'])) $clean_sponsor['photo'] = sanitize_text_field($sponsor['photo']);
            if (isset($sponsor['name'])) $clean_sponsor['name'] = sanitize_text_field($sponsor['name']);
            if (isset($sponsor['role'])) $clean_sponsor['role'] = sanitize_text_field($sponsor['role']);
            if (isset($sponsor['service'])) $clean_sponsor['service'] = sanitize_text_field($sponsor['service']);
            if (isset($sponsor['country'])) $clean_sponsor['country'] = sanitize_text_field($sponsor['country']);
            if (!empty($clean_sponsor)) $sanitized_sponsors[] = $clean_sponsor;
        }

        update_post_meta($post_id, 'event_sponsors', $sanitized_sponsors);
    } else {
        delete_post_meta($post_id, 'event_sponsors');
    }

    // Save partners array
    if (isset($_POST['event_partners'])) {
        $partners_data = $_POST['event_partners'];
        $sanitized_partners = [];

        foreach ((array)$partners_data as $partner) {
            $clean_partner = [];
            if (isset($partner['category'])) $clean_partner['category'] = sanitize_text_field($partner['category']);
            if (isset($partner['logo'])) $clean_partner['logo'] = sanitize_text_field($partner['logo']);
            if (isset($partner['name'])) $clean_partner['name'] = sanitize_text_field($partner['name']);
            if (!empty($clean_partner)) $sanitized_partners[] = $clean_partner;
        }

        update_post_meta($post_id, 'event_partners', $sanitized_partners);
    } else {
        delete_post_meta($post_id, 'event_partners');
    }
}
add_action('save_post', 'nrna_save_events_meta_box');

// Clean up Events admin screen
function nrna_remove_events_meta_boxes() {
    remove_meta_box('slugdiv', 'events', 'normal');
    remove_meta_box('authordiv', 'events', 'normal');
    remove_meta_box('commentsdiv', 'events', 'normal');
    remove_meta_box('revisionsdiv', 'events', 'normal');
}
add_action('admin_menu', 'nrna_remove_events_meta_boxes');
