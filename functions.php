<?php

	/* ------------------------------ CONSTANTS ------------------------------ */

	// defines admin login names
	define( 'ADMINS', array( 'digis', 'laita', 'webpunk' ) );

	// defines current user's login name
	$current_user = wp_get_current_user();

	define( 'CURRENT_USER', $current_user->user_login );

	/* ------------------------------ GENERAL ------------------------------ */

	// custom settings for administration
	require_once __DIR__ . '/inc/webpunk/admin.php';

	// custom shortcodes
	require_once __DIR__ . '/inc/webpunk/shortcodes.php';

	// custom theme functions
	require_once __DIR__ . '/inc/webpunk/theme.php';

	// functions for Front-End
	require_once __DIR__ . '/inc/webpunk/front-end.php';

	/* ------------------------------ THEMES & BUILDERS ------------------------------ */

	// general settings for blocks
	//require_once __DIR__ . '/inc/webpunk/gutenberg.php';

	// custom settings for Astra 
	require_once __DIR__ . '/inc/webpunk/astra.php';

	/* ------------------------------ PLUGIN FUNCTIONS ------------------------------ */

	/* --- RANK MATH ---  */

	// changes breadcrumbs template
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		add_filter( 'rank_math/frontend/breadcrumb/args', function( $args ) {
			$args = array(
				'delimiter'   => '&nbsp;&#47;&nbsp;',
				'wrap_before' => '<nav class="breadcrumbs" role="navigation" aria-label="Breadcrumbs navigation">',
				'wrap_after'  => '</nav>',
				'before'      => '',
				'after'       => '',
			);
			
			return $args;
		});
	}

	/* --- WPML --- */

	// checks if WPML plugin exist
	function webpunk_is_wpml_active() 
	{
		$is_wpml_active = class_exists( 'SitePress' );
		$is_wpml_configured = apply_filters( 'wpml_setting', false, 'setup_complete' );
		return ( $is_wpml_active && $is_wpml_configured );
	}

	// edits of WPML properties
	if ( webpunk_is_wpml_active()  ) {

		// adds language code to body class
		add_filter( 'body_class', 'webpunk_append_language_class' );

		function webpunk_append_language_class( $classes ) {
			$classes[] = "web-lang-" . esc_attr( apply_filters( 'wpml_current_language', NULL ) );

			return $classes;
		}

		// removes WPML styles and scripts
		define( 'ICL_DONT_LOAD_NAVIGATION_CSS', true );
		define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
		define( 'ICL_DONT_LOAD_LANGUAGES_JS', true );

		// removes meta generator
		remove_action( 'wp_head', array( $sitepress, 'meta_generator_tag' ) );
	}

	/* --- AUTO CLOUDINARY ---  */

	// checks if the Auto Cloudinary plugin exists
	if ( function_exists( 'cloudinary_url' ) ) {
		// adds specific parameters for cloudinary URLs
		add_filter( 'cloudinary_default_args', function( $args ) {
				
			$args['transform']['quality'] = 'auto:eco';
			$args['transform']['fetch_format'] = 'auto';
				
			return $args;
		} );

		// adds preconnect for cloudinary URL
		add_action( 'wp_head', 'webpunk_dns_preconnect', 0 );

		function webpunk_dns_preconnect() {
			echo '<link href="https://res.cloudinary.com" rel="preconnect" crossorigin>';
		}
	}
    
    /* --- NasWP Kit --- */
    require_once "settings.php";
    $naswp_settings = new NasWP_Settings();


    require_once "classes/class-naswp-helpers.php";
    $naswp_helpers = new NasWP_Helpers();
    //$helpers->gtm('GTM-0');
    $naswp_helpers->mimes($naswp_settings->mimes);
    $naswp_helpers->lightbox();
    $naswp_helpers->auto_async_js();
    $naswp_helpers->file_names();

