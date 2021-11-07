<?php

	/* ------------------------------ GUTENBERG ------------------------------ */

	// sets basic features
	add_action( 'after_setup_theme', 'alprime_setup_gutenberg' );

	function alprime_setup_gutenberg() {
		
		// removes core block patterns
		remove_theme_support( 'core-block-patterns' );

		// changes base color palettes
		add_theme_support( 'editor-color-palette', array(
			// removes custom colors
		) );

		// adds gradient presets
		add_theme_support( '__experimental-editor-gradient-presets', array(
			// removes custom gradients
		) );

		// changes base font sizes
		add_theme_support( 'editor-font-sizes', array(
			// removes variable font sizes
		) );

		// removes custom font sizes
		add_theme_support( 'disable-custom-font-sizes' );

		// removes custom color palettes
		add_theme_support( 'disable-custom-colors' );

		// removes custom gradients palettes
		add_theme_support( 'disable-custom-gradients' );

		// adds responsive behavior for embedded content
		add_theme_support( 'responsive-embeds' );

		// adds wide alignment
		// add_theme_support( 'align-wide' );

	}

	// removes Drop cap from Paragraph block
	add_filter( 'block_editor_settings_all', 'alprime_block_paragraph_remove_dropcap' );

	function alprime_block_paragraph_remove_dropcap( array $editor_settings ) {

		$editor_settings['__experimentalFeatures']['typography']['dropCap'] = false;

		return $editor_settings;
	}

	// allows selected blocks
	add_filter( 'allowed_block_types_all', 'webpunk_allowed_blocks', 10, 2 );

	function webpunk_allowed_blocks( $allowed_block_types, $block_editor_context ) {

		global $pagenow;

		// list of allowed blocks (globally)
		$allowed_block_types = array(
			'core/shortcode',
			'core/columns',
			'core/column',
			'core/group',
			'core/buttons',
			'core/image',
			'core/gallery',
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/quote',
			'core/table',
			'core/html',
			'core/separator',
			'core/spacer',
			'core/block',
			'core/embed', // embed types/variants are in blocks.init.js
			'fluentfom/guten-block'
		);

		// adds specific blocks only for Widgets
		if ( 'widgets.php' === $pagenow || 'customize.php' === $pagenow ) {
			$allowed_block_types = array(
				'core/paragraph',
				'core/html'
			);
		}

		return $allowed_block_types;

	}

	// assets for block editor
	add_action( 'enqueue_block_editor_assets', function() {

		// customizations for core blocks
		wp_enqueue_script( 
            'webpunk_blocks_core', 
            get_stylesheet_directory_uri() . '/assets/js/blocks.init.js', 
            array( 'wp-rich-text', 'wp-data', 'wp-blocks', 'wp-dom-ready', 'wp-i18n' ), 
            null, 
            true 
        );

    });