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
                $countdown_days = get_post_meta($post->ID, 'event_countdown_days', true);
                $countdown_hours = get_post_meta($post->ID, 'event_countdown_hours', true);
                $countdown_minutes = get_post_meta($post->ID, 'event_countdown_minutes', true);
                $countdown_seconds = get_post_meta($post->ID, 'event_countdown_seconds', true);
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
                <p><label>Countdown:</label><br>
                    <input type="number" name="event_countdown_days" value="<?php echo esc_attr($countdown_days); ?>" placeholder="Days" min="0" style="width:80px;"> Days
                    <input type="number" name="event_countdown_hours" value="<?php echo esc_attr($countdown_hours); ?>" placeholder="Hours" min="0" max="23" style="width:80px;"> Hours
                    <input type="number" name="event_countdown_minutes" value="<?php echo esc_attr($countdown_minutes); ?>" placeholder="Minutes" min="0" max="59" style="width:80px;"> Minutes
                    <input type="number" name="event_countdown_seconds" value="<?php echo esc_attr($countdown_seconds); ?>" placeholder="Seconds" min="0" max="59" style="width:80px;"> Seconds
                </p>
                <p><label>Description:</label><br><textarea name="event_description" rows="4" class="wide-textarea"><?php echo esc_textarea($description); ?></textarea></p>
                <?php
                break;

            case 'objective':
                $obj_title = get_post_meta($post->ID, 'event_objective_title', true);
                $obj_description = get_post_meta($post->ID, 'event_objective_description', true);
                $obj_cta_link = get_post_meta($post->ID, 'event_objective_cta_link', true);
                $obj_cta_title = get_post_meta($post->ID, 'event_objective_cta_title', true);
                ?>
                <p><label>Title:</label><br><input type="text" name="event_objective_title" value="<?php echo esc_attr($obj_title); ?>" class="wide-input"></p>
                <p><label>Description:</label><br><textarea name="event_objective_description" rows="4" class="wide-textarea"><?php echo esc_textarea($obj_description); ?></textarea></p>
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
                <p><label>Description:</label><br><textarea name="event_schedule_description" rows="4" class="wide-textarea"><?php echo esc_textarea($schedule_description); ?></textarea></p>
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
                                            <p><label>Description:</label><br><textarea name="event_schedule_dates[<?php echo $date_index; ?>][sessions][<?php echo $session_index; ?>][description]" rows="3" class="wide-textarea"><?php echo esc_textarea($session['description'] ?? ''); ?></textarea></p>
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
        'event_start_date' => 'sanitize_text_field', // Keep as string for date
        'event_end_date' => 'sanitize_text_field', // Keep as string for date
        'event_countdown_days' => 'sanitize_text_field',
        'event_countdown_hours' => 'sanitize_text_field',
        'event_countdown_minutes' => 'sanitize_text_field',
        'event_countdown_seconds' => 'sanitize_text_field',
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
                    if (isset($session['description'])) $clean_session['description'] = wp_kses_post($session['description']);
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
