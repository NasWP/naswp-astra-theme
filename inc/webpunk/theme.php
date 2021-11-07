<?php

	/* ------------------------------ CUSTOM FUNCTION FOR VALUES CHECK ------------------------------ */

	// checks if the value is not empty, even if it's just space
	function webpunk_not_empty( &$value ) {
		return empty( $value ) ? false : ( is_string( $value ) ? !empty( trim( $value ) ) : !empty ( $value ) );
	}

	/* ------------------------------ CUSTOM FUNCTION FOR NAVIGATION CLASSES ------------------------------ */

	class webpunk_NavClasses
	{

		public $tax_to_archives = [];
		public $mark_items = [];
		public $posts_page_id = null;

		public function __construct()
		{
			add_action( 'nav_menu_css_class', [$this, "getClasses"], 10, 2 );
		}

		public function prepare()
		{
			global $post;
			$mark_items = [];

			$q_tax = get_query_var( 'taxonomy' );
			$q_term = get_query_var( 'term' );
			$cat = get_query_var( 'cat' );

			$is_single = is_single();
			$is_tax = is_tax();
			$is_cat = is_category();
			$is_tag = is_tag();

			if($is_cat) {
				$q_tax = "category";
			}

			if($is_tag) {
				$q_tax = "post_tag";
			}

			if ( $is_tax || $is_cat || $is_tag) {

				$term = get_queried_object();
				$parent = $term->parent;
				if ( $parent ) {
					while ( $parent ) {
						$mark_items[$parent] = "term::" . $parent;
						$term = get_term( $parent, $q_tax );
						$parent = $term->parent;
					}
				}

				if ( !isset( $this->tax_to_archives[$q_tax] ) ) {
					$tax = get_taxonomy( $q_tax );
					$post_types = $tax->object_type;
				} else {
					$post_types = $this->tax_to_archives[$q_tax];
				}

				if ( !empty( $post_types ) ) {
					foreach ( $post_types as $post_type ) {
						$mark_items[] = "archive::" . $post_type;
					}
				}
			}

			if ( $is_single ) {
				$taxonomies = get_taxonomies();
				$all_taxs = [];
				if ( !empty( $taxonomies ) ) {
					foreach ( $taxonomies as $tax ) {
						$all_taxs[] = $tax;
					}
				}
				$terms = wp_get_post_terms( $post->ID, $all_taxs );
				$terms_ids = $this->getParentTerms( $terms );
				if ( !empty( $terms_ids ) ) {
					foreach ( $terms_ids as $term_id ) {
						$mark_items[] = "term::" . $term_id;
					}
				}
				$mark_items[] = "archive::" . $post->post_type;
				if ( $post->post_parent ) {
					$item = $post;
					$parent = $item->post_parent;
					while ( $parent ) {
						$mark_items[] = "post::" . $parent;
						$item = get_post( $parent );
						$parent = $item->post_parent;
					}
				}
			}

			$this->mark_items = $mark_items;

			$page_for_posts = get_option( 'page_for_posts' );
			if($page_for_posts)
				$this->posts_page_id = $page_for_posts;

		}

		public function getParentTerms( $terms )
		{
			$out = [];
			if ( !empty( $terms ) ) {
				foreach ( $terms as $term ) {
					$tid = $term->term_id;
					$out[$tid] = $tid;
					$parent = $term->parent;
					if ( $parent ) {
						while ( $parent ) {
							$out[$parent] = $parent;
							$t = get_term( $parent, $term->taxonomy );
							$parent = $t->parent;
						}
					}
				}
			}
			return $out;
		}

		public function getClasses( $classes, $item )
		{
			global $post;
			$mark_items = $this->mark_items;

			$object = $item->object;
			$type = $item->type;
			$object_id = $item->object_id;

			$id = null;
			$item_type = null;
			$class = null;

			if ( $type == "post_type_archive" ) {
				$id = "archive::" . $object;
				$item_type = "archive";
				$class = "active-item_archive-ancestor";
			}

			if ( $type == "post_type" ) {

				if($object_id == $this->posts_page_id) {
					$id = "archive::post";
					$item_type = "archive";
					$class = "active-item_archive-ancestor";
				} else {
					$id = "post::" . $item->object_id;
					$item_type = "post";
					$class = "active-item_post-ancestor";
				}

			}

			if ( $type == "taxonomy" ) {
				$id = "term::" . $item->object_id;
				$item_type = "term";
				$class = "active-item_term-ancestor";
			}

			if ( in_array( $id, $mark_items ) ) {
				$classes[] = $class;
			}

			if ( $item->current ) {
				$classes[] = "active-item";

				$classes[] = "active-item_" . $item_type;
			}

			return $classes;
		}

	}

	add_action( "wp", function () {
		$webpunk_nav_classes = new webpunk_NavClasses();
		$webpunk_nav_classes->prepare();
	} );