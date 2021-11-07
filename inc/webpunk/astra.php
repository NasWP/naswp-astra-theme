<?php

	/* ------------------------------ ASTRA THEME ------------------------------ */

	/* --- ASSETS --- */

	// styles & scripts
	add_action( 'wp_enqueue_scripts', 'webpunk_astra_styles_scripts', 15 );

	function webpunk_astra_styles_scripts() {
		wp_enqueue_style( 'webpunk-astra-css', get_stylesheet_directory_uri() . '/assets/css/astra.css', array( 'astra-theme-css' ), null, 'all' );
	}

	/* --- FEATURES --- */

	// removes Astra font
	add_filter( 'astra_enable_default_fonts', '__return_false' );