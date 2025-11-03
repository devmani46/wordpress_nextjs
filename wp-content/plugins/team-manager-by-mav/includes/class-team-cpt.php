<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Mav_Team_CPT {

    public function __construct() {
        add_action( 'init', [ $this, 'register_team_cpt' ] );
        add_action( 'init', [ $this, 'register_team_taxonomies' ] );
        add_filter( 'manage_team_posts_columns', [ $this, 'add_team_columns' ] );
        add_action( 'manage_team_posts_custom_column', [ $this, 'fill_team_columns' ], 10, 2 );
    }

    public function register_team_cpt() {

        $labels = [
            'name' => 'Team',
            'singular_name' => 'Team Member',
            'menu_name' => 'Team Members'
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'supports' => [ 'title', 'editor', 'thumbnail', 'comments' ],
            'menu_icon' => 'dashicons-groups',
            'has_archive' => true
        ];

        register_post_type( 'team', $args );
    }

    public function register_team_taxonomies() {

        register_taxonomy( 'team_category', 'team', [
            'label' => 'Team Category',
            'hierarchical' => true,
            'show_in_rest' => true
        ]);

        register_taxonomy( 'team_type', 'team', [
            'label' => 'Team Type',
            'hierarchical' => true,
            'show_in_rest' => true
        ]);
    }

    public function add_team_columns( $columns ) {
        $new_columns = [];
        foreach ( $columns as $key => $value ) {
            $new_columns[$key] = $value;
            if ( $key === 'title' ) {
                $new_columns['team_category'] = 'Category';
                $new_columns['team_type'] = 'Type';
                $new_columns['thumbnail'] = 'Photo';
            }
        }
        return $new_columns;
    }

    public function fill_team_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'team_category':
                $terms = get_the_terms( $post_id, 'team_category' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $term_names = wp_list_pluck( $terms, 'name' );
                    echo esc_html( implode( ', ', $term_names ) );
                } else {
                    echo '—';
                }
                break;
            case 'team_type':
                $terms = get_the_terms( $post_id, 'team_type' );
                if ( $terms && ! is_wp_error( $terms ) ) {
                    $term_names = wp_list_pluck( $terms, 'name' );
                    echo esc_html( implode( ', ', $term_names ) );
                } else {
                    echo '—';
                }
                break;
            case 'thumbnail':
                if ( has_post_thumbnail( $post_id ) ) {
                    echo get_the_post_thumbnail( $post_id, array( 50, 50 ) );
                } else {
                    echo '—';
                }
                break;
        }
    }

}
