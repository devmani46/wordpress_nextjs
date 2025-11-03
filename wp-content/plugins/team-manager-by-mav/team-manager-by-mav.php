<?php
/**
 * Plugin Name: Team Manager By Mav
 * Description: Custom Post Type for Team Members with skills and testimonial meta fields.
 * Version: 2.0
 * Author: Mavrick
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin path
define( 'MAV_TEAM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MAV_TEAM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once MAV_TEAM_PLUGIN_PATH . 'includes/class-team-cpt.php';
require_once MAV_TEAM_PLUGIN_PATH . 'includes/class-team-metabox.php';
require_once MAV_TEAM_PLUGIN_PATH . 'includes/class-team-assets.php';

// Init Classes
function mav_team_plugin_init() {
    new Mav_Team_CPT();
    new Mav_Team_Metabox();
    new Mav_Team_Assets();
}
add_action( 'plugins_loaded', 'mav_team_plugin_init' );
