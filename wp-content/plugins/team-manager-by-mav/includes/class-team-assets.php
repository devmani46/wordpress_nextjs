<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Mav_Team_Assets {

    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
    }

    public function admin_scripts( $hook ) {
        global $post;
        if ( $hook === 'post.php' || $hook === 'post-new.php' ) {
            if ( isset($post->post_type) && $post->post_type === 'team' ) {
                wp_enqueue_style( 'mav-team-admin', MAV_TEAM_PLUGIN_URL . 'assets/admin.css' );
                wp_enqueue_script( 'mav-team-admin-js', MAV_TEAM_PLUGIN_URL . 'assets/admin.js', array('jquery'), '1.0.0', true );
                wp_enqueue_media(); // For media uploader
            }
        }
    }
}
