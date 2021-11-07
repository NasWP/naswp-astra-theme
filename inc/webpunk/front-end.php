<?php

	/* ------------------------------ STYLES & SCRIPTS ------------------------------ */

	// removes media parameter from stylesheets
	add_filter( 'style_loader_tag', 'webpunk_remove_css_media' );

	function webpunk_remove_css_media( $tag ) {
		return preg_replace( '~\s+media=["\'][^"\']++["\']~i', '', $tag );
	}

	/* ------------------------------ WORDPRESS FEATURES ------------------------------ */

	// removes automatically generated links (mostly needless elements)
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rel_canonical' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_resource_hints', 2 );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );

	// removes the WordPress version from RSS feeds
	add_filter( 'the_generator', '__return_false' );

	// removes all smilies functionality
	add_filter( 'option_use_smilies', '__return_false' );

	// removes default gallery styles
	add_filter( 'use_default_gallery_style', '__return_false' );

	// removes recentcomments inline styles from head tag
	add_filter( 'show_recent_comments_widget_style', '__return_false' );

	// removes paragraphs from term descriptions
	remove_filter( 'term_description','wpautop' );

	// removes default admin bar styles
	add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

	// adds RSS feed links to <head> tag
	add_theme_support( 'automatic-feed-links' );

	// switch default core markup for listed elements to output valid HTML5
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );

	// removes self-closing tags generated from WordPress
	add_filter( 'get_avatar', 'webpunk_remove_self_closing_tags' );
	add_filter( 'comment_id_fields', 'webpunk_remove_self_closing_tags' );
	add_filter( 'post_thumbnail_html', 'webpunk_remove_self_closing_tags' );

	function webpunk_remove_self_closing_tags( $input ) {
		return str_replace( ' />', '>', $input );
	}