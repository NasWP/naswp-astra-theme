<?php

	/* ------------------------------ ASSETS ------------------------------ */

	// adds extra/custom styles for administration
	add_action( 'admin_enqueue_scripts', 'webpunk_admin_theme_style' );

	function webpunk_admin_theme_style() 
	{
		wp_enqueue_style( 'webpunk-admin', get_stylesheet_directory_uri() . '/assets/css/admin.css', null, null, 'all' );
	}

	/* ------------------------------ CLEARING ------------------------------ */

	// disables theme/plugins editor
	define( 'DISALLOW_FILE_EDIT', true );

	// removes emojis from admin styles
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// disables some admin menu items
	add_action( 'admin_menu', 'webpunk_remove_menus' );

	function webpunk_remove_menus () {
		//remove_menu_page( 'edit.php' );
		//remove_menu_page( 'edit-comments.php' );
		//remove_submenu_page( 'themes.php', 'widgets.php' );
		//remove_submenu_page( 'options-general.php', 'options-writing.php' );
		//remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		//remove_submenu_page( 'options-general.php', 'options-media.php' );
	}

	// removes some parts of post types
	add_action( 'init', 'webpunk_remove_post_parts' );

	function webpunk_remove_post_parts() {
		// removes tags and categories for posts
		//unregister_taxonomy_for_object_type( 'post_tag', 'post' );
		//unregister_taxonomy_for_object_type( 'category', 'post' );

		// removes post-formats type
		remove_theme_support( 'post-formats' );
	}

	// removes comments from pages and posts
	//remove_post_type_support( 'page', 'comments' );
	//remove_post_type_support( 'post', 'comments' );

	/* ------------------------------ ADDITIONS ------------------------------ */

	// adds support for post thumbnails
	add_theme_support( 'post-thumbnails', array( 'post' ) );

	// adds support for post excerpt in pages
	//add_post_type_support( 'page', 'excerpt' );

	/* ------------------------------ MEDIA ------------------------------ */

	// prefers Imagick editor library over GD (Imagick can keeps exif data for resized images and creates thumbnails for PDF documents)
	add_filter( 'wp_image_editors', 'webpunk_media_editors' );
 
	function webpunk_media_editors( $editors ) {
		return array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' );
	}

	// updates media sizes (only once after switch theme)
	add_action( 'after_switch_theme', 'webpunk_media_sizes' );

	function webpunk_media_sizes() {
		// sets thumbnail size for media library images
		update_option( 'thumbnail_size_w', 192 );
		update_option( 'thumbnail_size_h', 192 );
		update_option( 'thumbnail_crop', 1 );
		// removes medium and large size
		update_option( 'medium_size_w', 0 );
		update_option( 'medium_size_h', 0 );
		update_option( 'large_size_w', 0 );
		update_option( 'large_size_h', 0 );
	}

	// disables extra WP media sizes
	add_filter( 'intermediate_image_sizes', 'webpunk_remove_extra_sizes' );

	function webpunk_remove_extra_sizes( $sizes ) {
		return array_diff( $sizes, ['medium_large', '1536x1536', '2048x2048'] );
	}

	// removes size limit for images
	add_filter( 'big_image_size_threshold', '__return_false' );

	// sanitize filenames for media upload
	add_filter( 'sanitize_file_name', 'webpunk_sanitize_file_name', 10, 1 );

	function webpunk_sanitize_file_name( $filename ) {
		$sanitized_filename = remove_accents( $filename );
		$invalid = array( ' ' => '-', '%20' => '-', '_' => '-' );
		$sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
		$sanitized_filename = preg_replace( '/[^A-Za-z0-9-\. ]/', '', $sanitized_filename );
		$sanitized_filename = preg_replace( '/\.(?=.*\.)/', '', $sanitized_filename );
		$sanitized_filename = preg_replace( '/-+/', '-', $sanitized_filename );
		$sanitized_filename = str_replace( '-.', '.', $sanitized_filename );
		$sanitized_filename = strtolower( $sanitized_filename );
		return $sanitized_filename;
	}

	// edits image name and sets defined format for name and alt attribute
	add_action( 'add_attachment', 'webpunk_edit_image_meta_data' );

	function webpunk_edit_image_meta_data( $post_ID ) {
		
		// checks if the uploaded file is an image
		if ( wp_attachment_is_image( $post_ID ) ) {
			
			// gets the name of the image
			$image_name = get_post( $post_ID )->post_title;

			// lowercase
			$image_name = mb_strtolower( $image_name );

			// capitalize first letter
			$image_name = mb_strtoupper( mb_substr( $image_name, 0, 1 ) ) . mb_substr( $image_name, 1 );

			$image_meta = array(

				// updates image ID
				'ID' => $post_ID,
				
				// sets formatted image name
				'post_title' => $image_name,
			);

			// sets image meta data
			wp_update_post( $image_meta );

			// sets image alt
			update_post_meta( $post_ID, '_wp_attachment_image_alt', $image_name );
		}
	}

	/* ------------------------------ REDIRECTS ------------------------------ */

	// redirects attachment from template to URL 
	add_action( 'template_redirect', 'webpunk_attachment_redirect' );

	function webpunk_attachment_redirect() {
		if ( is_attachment() ) {
			wp_redirect( wp_get_attachment_url(), 301 );
		}
	}

	/* ------------------------------ E-MAILS ------------------------------ */

	// removes emojis from e-mails
	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

	// disables security check for admin e-mail
	add_filter( 'admin_email_check_interval', '__return_false' );