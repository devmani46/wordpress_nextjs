<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Mav_Team_Metabox {

    public function __construct() {
        add_action( 'add_meta_boxes_team', [ $this, 'add_meta_boxes' ] );
        add_action( 'save_post_team', [ $this, 'save_meta_data' ] );
    }

    public function add_meta_boxes() {
        add_meta_box('mav_team_details', 'Team Details', [ $this, 'render_fields' ], 'team', 'normal');
    }

    public function render_fields( $post ) {

        $testimonial = get_post_meta( $post->ID, '_team_testimonial', true );
        $skills = get_post_meta( $post->ID, '_team_skills', true );
        $badges = get_post_meta( $post->ID, '_team_badges', true );

        if ( ! is_array( $skills ) ) $skills = [];
        if ( ! is_array( $badges ) ) $badges = [];

        wp_nonce_field( 'mav_team_nonce_action', 'mav_team_nonce' );

        // Testimonial WYSIWYG
        echo '<label><strong>Testimonial:</strong></label><br>';
        wp_editor( $testimonial, 'team_testimonial', array(
            'textarea_name' => 'team_testimonial',
            'media_buttons' => false,
            'textarea_rows' => 5,
            'teeny' => true
        ) );
        echo '<br><br>';

        // Skills Repeater
        echo '<label><strong>Skills:</strong></label><br>';
        echo '<div id="skills-container" data-existing-skills="' . esc_attr( json_encode( $skills ) ) . '"></div>';
        echo '<button type="button" id="add-skill" class="button">Add Skill</button><br><br>';

        // Badges Multiple Images
        echo '<label><strong>Badges:</strong></label><br>';
        echo '<div id="badges-container" data-existing-badges="' . esc_attr( json_encode( array_map( function($badge_id) {
            return array(
                'id' => $badge_id,
                'url' => wp_get_attachment_url( $badge_id )
            );
        }, $badges ) ) ) . '"></div>';
        echo '<button type="button" id="add-badge" class="button">Add Badge</button>';
    }

    public function save_meta_data( $post_id ) {

        if ( ! isset( $_POST['mav_team_nonce'] ) ||
             ! wp_verify_nonce( $_POST['mav_team_nonce'], 'mav_team_nonce_action' ) ) return;

        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        update_post_meta( $post_id, '_team_testimonial', wp_kses_post( $_POST['team_testimonial'] ) );

        $skills = isset( $_POST['team_skills'] ) ? array_filter( array_map( 'sanitize_text_field', $_POST['team_skills'] ) ) : [];
        update_post_meta( $post_id, '_team_skills', $skills );

        $badges = isset( $_POST['team_badges'] ) ? array_filter( array_map( 'intval', $_POST['team_badges'] ) ) : [];
        update_post_meta( $post_id, '_team_badges', $badges );
    }
}
