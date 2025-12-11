<?php

/**
 * Register REST API endpoint for newsletter subscriptions
 */
function nrna_register_subscribe_endpoint()
{
    register_rest_route('nrna/v1', '/subscribe', [
        'methods' => 'POST',
        'callback' => 'nrna_handle_subscribe_submission',
        'permission_callback' => '__return_true', // Allow public access
    ]);
}
add_action('rest_api_init', 'nrna_register_subscribe_endpoint');

/**
 * Handle newsletter subscription submission
 */
function nrna_handle_subscribe_submission($request)
{
    // Get email parameter from request
    $email = sanitize_email($request->get_param('email'));

    // Validate required field
    if (empty($email)) {
        return new WP_Error(
            'missing_email',
            'Please provide an email address.',
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

    // Store subscription in database
    global $wpdb;
    $table_name = $wpdb->prefix . 'subscribed_emails';

    // Create table if it doesn't exist
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        status varchar(20) DEFAULT 'active' NOT NULL,
        is_read tinyint(1) DEFAULT 0 NOT NULL,
        subscribed_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Check if email already exists
    $existing = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s",
        $email
    ));

    if ($existing) {
        return new WP_Error(
            'duplicate_email',
            'This email is already subscribed to our newsletter.',
            ['status' => 409]
        );
    }

    // Insert subscription
    $inserted = $wpdb->insert(
        $table_name,
        [
            'email' => $email,
            'status' => 'active'
        ],
        ['%s', '%s']
    );

    // Debug logging
    error_log('Subscribe - Insert ID: ' . $wpdb->insert_id);
    error_log('Subscribe - WP DB Error: ' . $wpdb->last_error);

    // Check if database insert was successful
    if ($wpdb->insert_id) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Thank you for subscribing to our newsletter!',
        ], 200);
    } else {
        return new WP_Error(
            'subscription_failed',
            'Failed to subscribe. Please try again later.',
            ['status' => 500]
        );
    }
}

/**
 * Add Subscribed Emails admin menu page
 */
function nrna_add_subscribed_emails_menu()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'subscribed_emails';

    // Count active subscriptions
    // Check if table exists first to avoid errors on fresh install
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        // Count unread
        // Check if is_read column exists first (to avoid errors on existing installs before migration)
        $col_exists = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'is_read'");
        if (!empty($col_exists)) {
            $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_read = 0");
        } else {
            // Fallback to active count or 0 if column missing
            $count = 0;
        }
    } else {
        $count = 0;
    }

    $menu_title = 'Subscribed Emails';
    if ($count > 0) {
        $menu_title .= ' <span class="awaiting-mod count-' . esc_attr($count) . '"><span class="pending-count" aria-hidden="true">' . esc_html($count) . '</span></span>';
    }

    add_menu_page(
        'Subscribed Emails',              // Page title
        $menu_title,                      // Menu title (with badge)
        'manage_options',                 // Capability
        'subscribed-emails',              // Menu slug
        'nrna_subscribed_emails_page',    // Callback function
        'dashicons-email-alt',            // Icon
        27                                // Position
    );
}
add_action('admin_menu', 'nrna_add_subscribed_emails_menu');

/**
 * Display subscribed emails admin page
 */
function nrna_subscribed_emails_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'subscribed_emails';

    // Check for actions
    $action = isset($_GET['action']) ? $_GET['action'] : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Schema Migration: Check if is_read column exists
    $col_check = $wpdb->get_results("SHOW COLUMNS FROM $table_name LIKE 'is_read'");
    if (empty($col_check)) {
        $sql = "ALTER TABLE $table_name ADD COLUMN is_read tinyint(1) DEFAULT 0 NOT NULL";
        $wpdb->query($sql);
        // Mark existing as read to avoid badge explosion
        $wpdb->query("UPDATE $table_name SET is_read = 1");
    }

    // Handle Mark as Read Action
    if ($action === 'mark_read' && $id > 0) {
        check_admin_referer('mark_read_subscription_' . $id);
        $wpdb->update($table_name, ['is_read' => 1], ['id' => $id]);
        echo '<script>window.location.href="admin.php?page=subscribed-emails&message=marked_read";</script>';
        exit;
    }

    // Handle Mark ALL as Read Action
    if ($action === 'mark_all_read') {
        check_admin_referer('mark_all_read_subscriptions');
        $wpdb->query("UPDATE $table_name SET is_read = 1 WHERE is_read = 0");
        echo '<script>window.location.href="admin.php?page=subscribed-emails&message=all_marked_read";</script>';
        exit;
    }

    // Handle Delete Action
    if ($action === 'delete' && $id > 0) {
        check_admin_referer('delete_subscription_' . $id);

        $wpdb->delete($table_name, ['id' => $id]);

        // Redirect to list to avoid resubmission
        echo '<script>window.location.href="admin.php?page=subscribed-emails&message=deleted";</script>';
        exit;
    }

    // List View
    $subscriptions = $wpdb->get_results(
        "SELECT * FROM $table_name ORDER BY subscribed_at DESC"
    );

?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Subscribed Emails</h1>
        <a href="<?php echo wp_nonce_url('admin.php?page=subscribed-emails&action=mark_all_read', 'mark_all_read_subscriptions'); ?>" class="page-title-action">Mark All as Read</a>
        <hr class="wp-header-end">

        <?php if (isset($_GET['message'])) : ?>
            <?php if ($_GET['message'] === 'deleted') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Subscription deleted successfully.</p>
                </div>
            <?php elseif ($_GET['message'] === 'marked_read') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>Subscription marked as read.</p>
                </div>
            <?php elseif ($_GET['message'] === 'all_marked_read') : ?>
                <div class="notice notice-success is-dismissible">
                    <p>All subscriptions marked as read.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($subscriptions)) : ?>
            <div class="card" style="margin-top: 20px; padding: 20px;">
                <p>No subscriptions yet.</p>
            </div>
        <?php else : ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 10%;">S.N.</th>
                        <th style="width: 50%;">Email</th>
                        <th style="width: 20%;">Status</th>
                        <th style="width: 20%;">Subscribed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serial_number = 1;
                    foreach ($subscriptions as $subscription) :
                        $is_active = isset($subscription->status) && $subscription->status === 'active';
                        $is_unread = isset($subscription->is_read) && $subscription->is_read == 0;
                        $row_class = $is_unread ? 'updated' : '';
                        if ($is_active) $row_class .= ' active-subscription'; // optional visual aid for active
                    ?>
                        <tr class="<?php echo esc_attr($row_class); ?>">
                            <td><?php echo $serial_number++; // Sequential Number 
                                ?></td>
                            <td>
                                <strong><?php echo esc_html($subscription->email); ?></strong>
                                <div class="row-actions">
                                    <?php if ($is_unread) : ?>
                                        <span class="mark-read"><a href="<?php echo wp_nonce_url('admin.php?page=subscribed-emails&action=mark_read&id=' . $subscription->id, 'mark_read_subscription_' . $subscription->id); ?>">Mark as Read</a> | </span>
                                    <?php endif; ?>
                                    <span class="delete"><a href="<?php echo wp_nonce_url('admin.php?page=subscribed-emails&action=delete&id=' . $subscription->id, 'delete_subscription_' . $subscription->id); ?>" class="submitdelete" onclick="return confirm('Are you sure you want to delete this subscription?')">Delete</a></span>
                                </div>
                            </td>
                            <td>
                                <?php if ($is_active) : ?>
                                    <span class="dashicons dashicons-yes" style="color: #46b450;"></span> <strong>Active</strong>
                                <?php else : ?>
                                    <span class="dashicons dashicons-no" style="color: #999;"></span> Inactive
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(date('M j, Y g:i A', strtotime($subscription->subscribed_at))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p style="margin-top: 20px;">
                <strong>Total Subscriptions:</strong> <?php echo count($subscriptions); ?>
            </p>
        <?php endif; ?>
    </div>

    <style>
        .wrap table td {
            vertical-align: top;
            padding: 10px;
        }

        .updated {
            background-color: #f0f6fc !important;
            font-weight: bold;
        }

        .updated td {
            box-shadow: inset 2px 0 0 #3571b1;
        }
    </style>
<?php
}
