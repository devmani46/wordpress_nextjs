<?php
/**
 * Twenty Twenty-Four functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Twenty Twenty-Four
 * @since Twenty Twenty-Four 1.0
 */

/**
 * Register block styles.
 */

if ( ! function_exists( 'twentytwentyfour_block_styles' ) ) :
	/**
	 * Register custom block styles
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'twentytwentyfour' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);
		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'twentytwentyfour' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'twentytwentyfour' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name'         => 'arrow-link',
				'label'        => __( 'With arrow', 'twentytwentyfour' ),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'         => 'asterisk',
				'label'        => __( 'With asterisk', 'twentytwentyfour' ),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_styles' );

/**
 * Enqueue block stylesheets.
 */

if ( ! function_exists( 'twentytwentyfour_block_stylesheets' ) ) :
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_block_stylesheets() {
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'twentytwentyfour-button-style-outline',
				'src'    => get_parent_theme_file_uri( 'assets/css/button-outline.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => get_parent_theme_file_path( 'assets/css/button-outline.css' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_block_stylesheets' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'twentytwentyfour_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since Twenty Twenty-Four 1.0
	 * @return void
	 */
	function twentytwentyfour_pattern_categories() {

		register_block_pattern_category(
			'twentytwentyfour_page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category', 'twentytwentyfour' ),
				'description' => __( 'A collection of full page layouts.', 'twentytwentyfour' ),
			)
		);
	}
endif;

add_action( 'init', 'twentytwentyfour_pattern_categories' );

// Register Team Custom Post Type
function register_team_post_type() {
    $labels = array(
        'name'                  => _x( 'Team', 'Post type general name', 'twentytwentyfour' ),
        'singular_name'         => _x( 'Team Member', 'Post type singular name', 'twentytwentyfour' ),
        'menu_name'             => _x( 'Team', 'Admin Menu text', 'twentytwentyfour' ),
        'name_admin_bar'        => _x( 'Team Member', 'Add New on Toolbar', 'twentytwentyfour' ),
        'add_new'               => __( 'Add New', 'twentytwentyfour' ),
        'add_new_item'          => __( 'Add New Team Member', 'twentytwentyfour' ),
        'new_item'              => __( 'New Team Member', 'twentytwentyfour' ),
        'edit_item'             => __( 'Edit Team Member', 'twentytwentyfour' ),
        'view_item'             => __( 'View Team Member', 'twentytwentyfour' ),
        'all_items'             => __( 'All Team Members', 'twentytwentyfour' ),
        'search_items'          => __( 'Search Team Members', 'twentytwentyfour' ),
        'parent_item_colon'     => __( 'Parent Team Members:', 'twentytwentyfour' ),
        'not_found'             => __( 'No team members found.', 'twentytwentyfour' ),
        'not_found_in_trash'    => __( 'No team members found in Trash.', 'twentytwentyfour' ),
        'featured_image'        => _x( 'Team Member Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'twentytwentyfour' ),
        'set_featured_image'    => _x( 'Set team member image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'twentytwentyfour' ),
        'remove_featured_image' => _x( 'Remove team member image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'twentytwentyfour' ),
        'use_featured_image'    => _x( 'Use as team member image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'twentytwentyfour' ),
        'archives'              => _x( 'Team member archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'twentytwentyfour' ),
        'insert_into_item'      => _x( 'Insert into team member', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'twentytwentyfour' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this team member', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'twentytwentyfour' ),
        'filter_items_list'     => _x( 'Filter team members list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'twentytwentyfour' ),
        'items_list_navigation' => _x( 'Team members list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'twentytwentyfour' ),
        'items_list'            => _x( 'Team members list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'twentytwentyfour' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'team' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-groups',
    );

    register_post_type( 'team', $args );
}
add_action( 'init', 'register_team_post_type' );

// Register Team Category Taxonomy
function register_team_category_taxonomy() {
    $labels = array(
        'name'              => _x( 'Team Categories', 'taxonomy general name', 'twentytwentyfour' ),
        'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'twentytwentyfour' ),
        'search_items'      => __( 'Search Team Categories', 'twentytwentyfour' ),
        'all_items'         => __( 'All Team Categories', 'twentytwentyfour' ),
        'parent_item'       => __( 'Parent Team Category', 'twentytwentyfour' ),
        'parent_item_colon' => __( 'Parent Team Category:', 'twentytwentyfour' ),
        'edit_item'         => __( 'Edit Team Category', 'twentytwentyfour' ),
        'update_item'       => __( 'Update Team Category', 'twentytwentyfour' ),
        'add_new_item'      => __( 'Add New Team Category', 'twentytwentyfour' ),
        'new_item_name'     => __( 'New Team Category Name', 'twentytwentyfour' ),
        'menu_name'         => __( 'Team Category', 'twentytwentyfour' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'team-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'team_category', array( 'team' ), $args );
}
add_action( 'init', 'register_team_category_taxonomy' );

// Register Team Type Taxonomy
function register_team_type_taxonomy() {
    $labels = array(
        'name'              => _x( 'Team Types', 'taxonomy general name', 'twentytwentyfour' ),
        'singular_name'     => _x( 'Team Type', 'taxonomy singular name', 'twentytwentyfour' ),
        'search_items'      => __( 'Search Team Types', 'twentytwentyfour' ),
        'all_items'         => __( 'All Team Types', 'twentytwentyfour' ),
        'parent_item'       => __( 'Parent Team Type', 'twentytwentyfour' ),
        'parent_item_colon' => __( 'Parent Team Type:', 'twentytwentyfour' ),
        'edit_item'         => __( 'Edit Team Type', 'twentytwentyfour' ),
        'update_item'       => __( 'Update Team Type', 'twentytwentyfour' ),
        'add_new_item'      => __( 'Add New Team Type', 'twentytwentyfour' ),
        'new_item_name'     => __( 'New Team Type Name', 'twentytwentyfour' ),
        'menu_name'         => __( 'Team Type', 'twentytwentyfour' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'team-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'team_type', array( 'team' ), $args );
}
add_action( 'init', 'register_team_type_taxonomy' );

// Add default terms for team_type
function add_default_team_types() {
    if ( ! term_exists( 'employee', 'team_type' ) ) {
        wp_insert_term( 'Employee', 'team_type', array( 'slug' => 'employee' ) );
    }
    if ( ! term_exists( 'staff', 'team_type' ) ) {
        wp_insert_term( 'Staff', 'team_type', array( 'slug' => 'staff' ) );
    }
}
add_action( 'init', 'add_default_team_types' );
