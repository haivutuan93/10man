<?php

	/**
	 * Class ParseHead
	 *
	 * @since Madara Alpha 1.0
	 * @package madara
	 */

	namespace App\Views;

	class ParseHead {
		public function __construct() {

		}

		/**
		 * Print page meta tags
		 */
		public static function meta_tags() {
			$description = get_bloginfo( 'description' );

			$meta_tags_html = '';
			if ( is_single() ) {
				global $post;

				$post_format = get_post_format( $post->ID ) != '' && get_post_format( $post->ID ) == 'video' ? 'video.movie' : 'article';
				$post_url    = get_permalink( $post->ID );

				$description = $post->post_excerpt;
				if ( $description == '' ) {
					$description = substr( strip_tags( $post->post_content ), 0, 165 );
				}

				$thumbnail_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
                $image_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), array(1200, 630) );

				$meta_tags_html .= '<meta property="og:image" content="' . esc_attr( $image_attributes[0] ) . '"/>';
				$meta_tags_html .= '<meta property="og:title" content="' . esc_attr( get_the_title( $post->ID ) ) . '"/>';
				$meta_tags_html .= '<meta property="og:url" content="' . esc_url( $post_url ) . '"/>';
				$meta_tags_html .= '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '"/>';
				$meta_tags_html .= '<meta property="og:type" content="' . esc_attr( $post_format ) . '"/>';
				$meta_tags_html .= '<meta property="og:description" content="' . esc_attr( strip_shortcodes( $description ) ) . '"/>';
				$meta_tags_html .= '<meta property="fb:app_id" content="' . \App\Madara::getOption( 'facebook_app_id' ) . '" />';

				// Meta for twitter
				$meta_tags_html .= '<meta name="twitter:card" content="summary" />';
				$meta_tags_html .= '<meta name="twitter:site" content="@' . esc_attr( get_bloginfo( 'name' ) ) . '" />';
				$meta_tags_html .= '<meta name="twitter:title" content="' . esc_attr( get_the_title( $post->ID ) ) . '" />';
				$meta_tags_html .= '<meta name="twitter:description" content="' . esc_attr( strip_shortcodes( $description ) ) . '" />';
				$meta_tags_html .= '<meta name="twitter:image" content="' . esc_attr( $thumbnail_url ) . '" />';
				$meta_tags_html .= '<meta name="twitter:url" content="' . esc_url( get_permalink( $post->ID ) ) . '" />';

				global $_wp_additional_image_sizes;

				$width  = 696;
				$height = 391;

				if ( isset( $_wp_additional_image_sizes['thumbnail'] ) ) {
					$width  = $_wp_additional_image_sizes['thumbnail']['width'];
					$height = $_wp_additional_image_sizes['thumbnail']['height'];
				}

				$logo = \App\Madara::getOption( 'logo_image' );

				?>

                <script type="application/ld+json">
				{
				  "@context": "http://schema.org",
				  "@type": "Article",
				  "mainEntityOfPage": {
					"@type": "WebPage",
					"@id": "https://google.com/article"
				  },
				  "headline": "<?php echo esc_attr( get_the_title( $post->ID ) ); ?>",
				  "image": {
					"@type": "ImageObject",
					"url": "<?php echo esc_attr( $thumbnail_url ); ?>",
					"height": <?php echo esc_attr( $height ); ?>,
					"width": <?php echo esc_attr( $width ); ?>
				  },
				  "datePublished": "<?php echo esc_js( $post->post_date_gmt ); ?>",
				  "dateModified": "<?php echo esc_js( $post->post_modified_gmt ); ?>",
				  "author": {
					"@type": "Person",
					"name": "<?php
						$author = get_user_by( 'id', $post->post_author );
						if ( $author ) {
							echo esc_attr( $author->display_name );
						}; ?>"
				  },
				   "publisher": {
					"@type": "Organization",
					"name": "<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>",
					"logo": {
						"@type": "ImageObject",
						"url": "<?php if ( $logo != '' ) {
						echo esc_attr( $logo );
					} else {
						echo esc_attr( $thumbnail_url );
					} ?>"
					}
				  },
				  "description": "<?php echo esc_attr( strip_shortcodes( $description ) ); ?>"
				}

                </script>
				<?php
			}
            global $post, $wp_manga_chapter, $wp_manga_setting;
			$is_manga_chapter = false;
            if (is_single() && isset($post->post_type) && $post->post_type == 'wp-manga' && get_query_var('chapter') != '') {
                $single_manga_seo = $wp_manga_setting->get_manga_option('single_manga_seo', 'manga');
                $site_name = get_bloginfo('name');
                $chapter_slug = get_query_var('chapter');
                $chapter = $wp_manga_chapter->get_chapter_by_slug($post->ID, $chapter_slug);
                $chapter_name = $chapter['chapter_name'];
                $chapter_name_extend = $chapter['chapter_name_extend'];

                if ($chapter_name_extend != '') {
                    $chapter_keywords = $chapter_name . ', ' . $chapter_name_extend;
                } else {
                    $chapter_keywords = $chapter_name;
                }
                if ($single_manga_seo == 1) {
                    $chapter_keywords .= ', ' . $site_name;
                }
                $is_manga_chapter = true;
                $meta_tags_html .= "\n" . '<meta name="description" content="' . $description . '"/>' . "\n";
                $meta_tags_html .= '<meta name="keywords" content="' . $post->post_title . ', ' . $chapter_keywords . '"/>' . "\n";
            }

            if (!$is_manga_chapter) {
                $meta_tags_html .= '<meta name="description" content="' . esc_attr( strip_shortcodes( $description ) ) . '"/>';
            }
			$meta_tags_html .= '<meta name="generator" content="' . esc_attr( esc_html__( 'Powered by Madara - A powerful multi-purpose theme by Madara', 'madara' ) ) . '" />';
			echo apply_filters( 'madara-meta-tags', $meta_tags_html );

			do_action( 'madara-meta-tags' );
		}
	}