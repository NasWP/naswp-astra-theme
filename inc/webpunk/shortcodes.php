<?php

	/* ------------------------------ SHORTCODES ------------------------------ */

	// adds shortcode for newest posts
	add_shortcode( 'newest-posts', 'newest_posts_shortcode' );

	function newest_posts_shortcode( $atts ) {

		$atts = shortcode_atts(
			array(
				'count' => '10'
			),
			$atts
		);

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => $atts['count'],
			'post_status' => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows' => true
		);

		$the_query = new WP_Query( $args );

		ob_start();

		?>
			<ul class="naswp-newest-posts">
				<?php
					if ( $the_query->have_posts() ) {
						while( $the_query->have_posts() ) {
							$the_query->the_post();
							?>
								<li>
									<h3>
										<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h3>

									<time datetime="<?php the_time( 'Y-m-d' ); ?>"><?php the_time( 'd. m. Y' ); ?></time>
								</li>
							<?php
						}
					}

					wp_reset_postdata();
				?>
			</ul>
		<?php

		return ob_get_clean();
	}

	// adds shortcode for top posts
	add_shortcode( 'top-posts', 'top_posts_shortcode' );

	function top_posts_shortcode() {
		
		ob_start();

		?>
			<div class="naswp-top-posts">
				<?php
					$top_posts = get_field( 'naswp_home_newest_posts' );

					if ( $top_posts ) {
						foreach ( $top_posts as $item ) {
							$image_id = get_post_thumbnail_id( $item->ID );
							$image_url = wp_get_attachment_image_src( $image_id, 'thumbnail', false );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true ); 
							?>
								<a class="naswp-top-post" href="<?php echo esc_url( get_permalink( $item->ID ) ); ?>">
									<img src="<?php echo esc_url( $image_url[0] ); ?>" lazy="loading" alt="<?php echo esc_attr( $image_alt ); ?>" width="<?php echo esc_url( $image_url[1] ); ?>" height="<?php echo esc_url( $image_url[2] ); ?>">

									<div class="naswp-top-post__content">
										<h3><?php echo esc_html( get_the_title( $item->ID ) ); ?></h3>

										<p><?php echo esc_html( get_the_excerpt( $item->ID ) ); ?></p>
									</div>
								</a>
							<?php
						}
					}
				?>
			</div>
		<?php

		return ob_get_clean();
	}
	
?>