<?php

/**
 * Register REST API endpoint for regional meeting registration submissions
 */
function nrna_register_rm_registration_endpoint()
{
    register_rest_route('nrna/v1', '/regional-meeting-registration', [
        'methods' => 'POST',
        'callback' => 'nrna_handle_rm_registration_submission',
        'permission_callback' => '__return_true', // Allow public access
    ]);
}
add_action('rest_api_init', 'nrna_register_rm_registration_endpoint');

/**
 * Handle regional meeting registration submission
 */
function nrna_handle_rm_registration_submission($request)
{
    // Get parameters from request
    $meeting_name = sanitize_text_field($request->get_param('meeting_name'));
    $first_name = sanitize_text_field($request->get_param('first_name'));
    $last_name = sanitize_text_field($request->get_param('last_name'));
    $email = sanitize_email($request->get_param('email'));
    $phone = sanitize_text_field($request->get_param('phone'));
    $location = sanitize_text_field($request->get_param('location'));

    // Validate required fields
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone)) {
        return new WP_Error(
            'missing_fields',
            'Please fill in all required fields.',
            ['status' => 400]
        );
    }

    // Validate email format
    if (!is_email($email)) {
        return new WP_Error(
            'invalid_email',
            'Please enter a valid email address.',
            ['status' => 400]
        );
    }

    // Prepare email content
    $to = get_option('admin_email'); // Send to site admin email
    $subject = 'New Regional Meeting Registration: ' . $meeting_name . ' - ' . $first_name . ' ' . $last_name;

    $email_message = "You have received a new regional meeting registration:\n\n";
    $email_message .= "Meeting: " . ($meeting_name ? $meeting_name : 'N/A') . "\n";
    $email_message .= "Name: " . $first_name . " " . $last_name . "\n";
    $email_message .= "Email: " . $email . "\n";
    $email_message .= "Phone: " . $phone . "\n";
    $email_message .= "Location: " . ($location ? $location : 'Not provided') . "\n";

    $headers = ['Content-Type: text/plain; charset=UTF-8'];

    // Send email
    wp_mail($to, $subject, $email_message, $headers);

    // Store submission in database
    global $wpdb;
    $table_name = $wpdb->prefix . 'regional_meeting_registrations';

    // Create table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        meeting_name varchar(255) NOT NULL,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        location text,
        status varchar(20) DEFAULT 'new' NOT NULL,
        submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Insert submission
    $wpdb->insert(
        $table_name,
        [
            'meeting_name' => $meeting_name,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'location' => $location,
            'status' => 'new'
        ],
        ['%s', '%s', '%s', '%s', '%s', '%s', '%s']
    );

    // Check if database insert was successful
    if ($wpdb->insert_id) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Registration successful! We have received your details.',
        ], 200);
    } else {
        return new WP_Error(
            'submission_failed',
            'Failed to save registration. DB Error: ' . $wpdb->last_error,
            ['status' => 500]
        );
    }
}

/**
 * Add Regional Meeting Registrations admin menu page
 */
function nrna_add_rm_registrations_menu()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'regional_meeting_registrations';

    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'new'");
    } else {
        $count = 0;
    }

    $menu_title = 'Regional Meeting Registrations';
    if ($count > 0) {
        $menu_title .= ' <span class="awaiting-mod count-' . esc_attr($count) . '"><span class="pending-count" aria-hidden="true">' . esc_html($count) . '</span></span>';
    }

    add_menu_page(
        'Regional Meeting Registrations',           // Page title
        $menu_title,                                // Menu title
        'manage_options',                           // Capability
        'regional-meeting-registrations',           // Menu slug
        'nrna_rm_registrations_page',               // Callback function
        'dashicons-groups',                         // Icon
        28                                          // Position
    );
}
add_action('admin_menu', 'nrna_add_rm_registrations_menu');

/**
 * Display regional meeting registrations admin page
 */
function nrna_rm_registrations_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'regional_meeting_registrations';

    // Check for actions
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Handle Delete Action
    if ($action === 'delete' && $id > 0) {
        check_admin_referer('delete_rm_registration_' . $id);
        $wpdb->delete($table_name, ['id' => $id]);
        echo '<script>window.location.href="admin.php?page=regional-meeting-registrations&message=deleted";</script>';
        exit;
    }

    // Handle Mark as Read Action
    if ($action === 'mark_read' && $id > 0) {
        check_admin_referer('mark_read_rm_registration_' . $id);
        $wpdb->update($table_name, ['status' => 'read'], ['id' => $id]);
        echo '<script>window.location.href="admin.php?page=regional-meeting-registrations&message=marked_read";</script>';
        exit;
    }

    // Handle Mark ALL as Read Action
    if ($action === 'mark_all_read') {
        check_admin_referer('mark_all_read_rm_registrations');
        $wpdb->update($table_name, ['status' => 'read'], ['status' => 'new']);
        echo '<script>window.location.href="admin.php?page=regional-meeting-registrations&message=all_marked_read";</script>';
        exit;
    }

    // View Details
    if ($action === 'view' && $id > 0) {
        // Mark as read when viewing
        $wpdb->update($table_name, ['status' => 'read'], ['id' => $id]);

        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
        if (!$submission) {
            echo '<div class="wrap"><p>Registration not found.</p></div>';
            return;
        }
?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Registration Details</h1>
            <a href="admin.php?page=regional-meeting-registrations" class="page-title-action">Back to List</a>
            <hr class="wp-header-end">

            <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
                <h2 style="margin-top: 0;"><?php echo esc_html($submission->first_name . ' ' . $submission->last_name); ?></h2>
                <p>
                    <strong>Meeting:</strong> <?php echo esc_html($submission->meeting_name ?: 'N/A'); ?><br>
                    <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($submission->email); ?>"><?php echo esc_html($submission->email); ?></a><br>
                    <strong>Phone:</strong> <?php echo esc_html($submission->phone); ?><br>
                    <strong>Location:</strong> <?php echo esc_html($submission->location ?: 'N/A'); ?><br>
                    <strong>Date:</strong> <?php echo esc_html(date('F j, Y g:i A', strtotime($submission->submitted_at))); ?>
                </p>
                <hr>
                <div style="margin-top: 20px;">
                    <a href="<?php echo wp_nonce_url('admin.php?page=regional-meeting-registrations&action=delete&id=' . $submission->id, 'delete_rm_registration_' . $submission->id); ?>"
                        class="button button-link-delete"
                        onclick="return confirm('Are you sure you want to delete this registration?')">
                        Delete Registration
                    </a>
                </div>
            </div>
        </div>
    <?php
        return;
    }

    // List View
    $submissions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY submitted_at DESC");
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Regional Meeting Registrations</h1>
        <a href="<?php echo wp_nonce_url('admin.php?page=regional-meeting-registrations&action=mark_all_read', 'mark_all_read_rm_registrations'); ?>" class="page-title-action">Mark All as Read</a>
        <hr class="wp-header-end">

        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] === 'deleted') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Registration deleted successfully.</p>
                </div>
            <?php elseif ($_GET['message'] === 'all_marked_read') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>All new registrations marked as read.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($submissions)) : ?>
            <div class="card" style="margin-top: 20px; padding: 20px;">
                <p>No registrations yet.</p>
            </div>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 5%;">S.N.</th>
                        <th style="width: 20%;">Meeting Name</th>
                        <th style="width: 20%;">Name</th>
                        <th style="width: 20%;">Email</th>
                        <th style="width: 15%;">Phone</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1;
                    foreach ($submissions as $sub) :
                        $is_new = $sub->status === 'new';
                        $view_url = 'admin.php?page=regional-meeting-registrations&action=view&id=' . $sub->id;
                    ?>
                        <tr class="<?php echo $is_new ? 'updated' : ''; ?>">
                            <td><?php echo $sn++; ?></td>
                            <td><strong><?php echo esc_html($sub->meeting_name ?: 'N/A'); ?></strong></td>
                            <td>
                                <strong><a class="row-title" href="<?php echo $view_url; ?>"><?php echo esc_html($sub->first_name . ' ' . $sub->last_name); ?></a></strong>
                                <div class="row-actions">
                                    <span class="view"><a href="<?php echo $view_url; ?>">View</a> | </span>
                                    <span class="delete"><a href="<?php echo wp_nonce_url('admin.php?page=regional-meeting-registrations&action=delete&id=' . $sub->id, 'delete_rm_registration_' . $sub->id); ?>" class="submitdelete" onclick="return confirm('Are you sure?')">Delete</a></span>
                                </div>
                            </td>
                            <td><a href="mailto:<?php echo esc_attr($sub->email); ?>"><?php echo esc_html($sub->email); ?></a></td>
                            <td><?php echo esc_html($sub->phone); ?></td>
                            <td>
                                <?php if ($is_new) : ?>
                                    <span class="dashicons dashicons-marker" style="color: #ca4a1f;"></span> <strong>New</strong>
                                <?php else : ?>
                                    <span class="dashicons dashicons-yes" style="color: #999;"></span> Read
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(date('M j, Y', strtotime($sub->submitted_at))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <style>
        .updated {
            background-color: #f0f6fc !important;
        }

        .updated th,
        .updated td {
            box-shadow: inset 2px 0 0 #3571b1;
        }
    </style>
<?php
}
