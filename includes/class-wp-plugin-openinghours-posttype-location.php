<?php

class WP_Plugin_OpeningHours_Posttype_Location {

	public function register() {

		register_post_type( 'event_location', $this->posttype_arguments() );

	}

	/**
	 * Get all posts locations
	 *
	 * @since    1.0.0
	 * @access   public static
	 *
	 * @return   array      array of posts
	 */
	public static function locations( $wanted_location, $limit = -1 ) {

		if( $wanted_location !== 'all' ) {

			return array( get_post( $wanted_location ) );

		}

		return get_posts(
			array(
				'posts_per_page' => $limit,
				'post_type' => 'event_location',
				'orderby' => 'date',
				'order' => 'asc'
			)
		);

	}


	/**
	 * The labels used for the post type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   array
	 */
	private function posttype_labels() {

		return array(
			'name'               => _x( 'Anläggningar', 'post type general name', 'wp-plugin-dictionary' ),
			'singular_name'      => _x( 'Anläggning', 'post type singular name', 'wp-plugin-dictionary' ),
			'menu_name'          => _x( 'Anläggningar', 'admin menu', 'wp-plugin-dictionary' ),
			'name_admin_bar'     => _x( 'Anläggning', 'add new on admin bar', 'wp-plugin-dictionary' ),
			'add_new'            => _x( 'Ny anläggning', 'material', 'wp-plugin-dictionary' ),
			'add_new_item'       => __( 'Lägg till ny anläggning', 'wp-plugin-dictionary' ),
			'new_item'           => __( 'Ny anläggning', 'wp-plugin-dictionary' ),
			'edit_item'          => __( 'Ändra anläggning', 'wp-plugin-dictionary' ),
			'view_item'          => __( 'Visa anläggning', 'wp-plugin-dictionary' ),
			'all_items'          => __( 'Alla anläggningar', 'wp-plugin-dictionary' ),
			'search_items'       => __( 'Sök anläggning', 'wp-plugin-dictionary' ),
			'parent_item_colon'  => __( 'Nuvarande anläggning:', 'wp-plugin-dictionary' ),
			'not_found'          => __( 'Inga anläggningar funna.', 'wp-plugin-dictionary' ),
			'not_found_in_trash' => __( 'Inga anläggningar funna i papperskorgen.', 'wp-plugin-dictionary' )
		);

	}


	/**
	 * The argumets used for registering the post type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @return   array
	 */
	private function posttype_arguments() {

		$options = get_option( 'wp_plugin_openinghours_options' );
		$permalink = !empty( $options['wp_plugin_openinghours_field_permalink'] ) ? $options['wp_plugin_openinghours_field_permalink'] : 'anlaggning';

		return array(
			'labels'             => $this->posttype_labels(),
			'description'        => __( 'Description.', 'wp-plugin-dictionary' ),
			'public'             => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'rewrite'            => array( 'slug' => $permalink ),
			'supports'           => array( 'title', 'editor', 'thumbnail' )
		);

	}




}