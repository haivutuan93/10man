<?php

	/**
	 * Class MadaraShortcode_Blog
	 */
	class MadaraShortcode_Blog extends MadaraShortcode {
		public function __construct( $attrs = null, $content = '' ) {
			parent::__construct( 'manga_blog', $attrs, $content );
		}

		/**
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function renderShortcode( $atts, $content ) {
			$id            = isset( $atts['id'] ) ? $atts['id'] : 'c-blog-' . rand( 0, 999 );
			$count         = isset( $atts['count'] ) && $atts['count'] != '' ? $atts['count'] : 6;
			$cats          = isset( $atts['cats'] ) ? $atts['cats'] : '';
			$tags          = isset( $atts['tags'] ) ? $atts['tags'] : '';
			$ids           = isset( $atts['ids'] ) ? $atts['ids'] : '';
			$order         = isset( $atts['order'] ) ? $atts['order'] : 'DESC';
			$orderby       = isset( $atts['orderby'] ) ? $atts['orderby'] : 'date';
			$items_per_row = isset( $atts['items_per_row'] ) && $atts['items_per_row'] != '' ? $atts['items_per_row'] : 3;

			$shortcode_query = App\Models\Database::getPosts( $count, $order, 1, $orderby, array(
				'categories' => $cats,
				'tags'       => $tags,
				'ids'        => $ids
			) );

			$count = $shortcode_query->post_count;

			$madara_postMeta = new App\Views\ParseMeta();
			$thumb_size      = array( 360, 206 );
            $counter = 0;
			$columns_class = 'col-md-4';
			if($items_per_row == '2'){
			    $columns_class = 'col-md-6';
            }

			//display
			ob_start();

			if ( $shortcode_query->have_posts() ) {
				?>

                <div id="<?php echo esc_attr( $id ); ?>" class="c-blog-listing shortcode-blog">
                    <div class="c-blog__inner">
                        <div class="c-blog__content">

                            <div class="row">

								<?php while ( $shortcode_query->have_posts() ) {
                                    $counter ++;
									$shortcode_query->the_post(); ?>

                                    <div id="post-<?php the_ID(); ?>" <?php post_class( ' col-xs-12 col-sm-6 '.$columns_class.' ' ); ?>>

                                        <div class="c-blog_item <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

											<?php if ( has_post_thumbnail() ) { ?>
                                                <div class="c-blog__thumbnail c-image-hover">
                                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
														<?php
															if ( function_exists( 'madara_thumbnail' ) ) {
																echo madara_thumbnail( $thumb_size );
															} else {
																the_post_thumbnail( $thumb_size );
															}

														?>
                                                    </a>
                                                </div>
											<?php } ?>

                                            <div class="c-blog__summary">
                                                <div class="post-meta total-count font-meta">
                                                    <?php $madara_postMeta->renderPostViews(1); ?> <?php $madara_postMeta->renderPostTotalShareCounter(1, 1); ?>
                                                </div>
                                                <div class="post-title font-title">
													<?php $madara_postMeta->renderPostTitle( 'h4' ); ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <?php if ( $counter < $count ) {
                                        if ( ( $counter % $items_per_row ) == 0 && $counter >= $items_per_row ) {
                                            echo '</div><div class="row">';
                                        }
                                    }
                                    ?>

									<?php
								}//while have_posts
									wp_reset_postdata();
								?>
                            </div>
                        </div>
                    </div>
                </div>


				<?php
			}//if have_posts
			//end
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}
	}

	$blog = new MadaraShortcode_Blog();

	/**
	 * Register to Visual Composer
	 */

	add_action( 'after_setup_theme', 'reg_manga_blog' );
	function reg_manga_blog() {
		if ( function_exists( 'vc_map' ) ) {
			$params = array(
				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Number of posts to show", "madara" ),
					"description" => esc_html__( "Default value is 6", "madara" ),
					"param_name"  => "count",
					"value"       => "",
					"description" => "",
				),
				array(
					"type"       => "dropdown",
					"class"      => "",
					"heading"    => esc_html__( "Items per row", 'madara' ),
					"param_name" => "items_per_row",
					"value"      => array(
						esc_html__( '3 Items', 'madara' ) => 3,
						esc_html__( '2 Items', 'madara' ) => 2,
					),
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Category", "madara" ),
					"param_name"  => "cats",
					"value"       => "",
					"description" => esc_html__( "List of category (slug), separated by a comma", "madara" ),
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Tags", "madara" ),
					"param_name"  => "tags",
					"value"       => "",
					"description" => esc_html__( "List of tag (slug), separated by a comma", "madara" ),
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "IDs", "madara" ),
					"param_name"  => "ids",
					"value"       => "",
					"description" => esc_html__( "Specify post IDs to retrieve", "madara" ),
				),
				array(
					"type"        => "dropdown",
					"admin_label" => true,
					"class"       => "",
					"heading"     => esc_html__( "Order", 'madara' ),
					"param_name"  => "order",
					"value"       => array(
						esc_html__( 'DESC', 'madara' ) => 'DESC',
						esc_html__( 'ASC', 'madara' )  => 'ASC',
					),
					"description" => ''
				),
				array(
					"type"        => "dropdown",
					"admin_label" => true,
					"class"       => "",
					"heading"     => esc_html__( "Order by", 'madara' ),
					"param_name"  => "orderby",
					"value"       => array(
						esc_html__( 'Date', 'madara' )          => 'date',
						esc_html__( 'ID', 'madara' )            => 'ID',
						esc_html__( 'Author', 'madara' )        => 'author',
						esc_html__( 'Title', 'madara' )         => 'title',
						esc_html__( 'Name', 'madara' )          => 'name',
						esc_html__( 'Modified', 'madara' )      => 'modified',
						esc_html__( 'Random', 'madara' )        => 'rand',
						esc_html__( 'Comment count', 'madara' ) => 'comment_count',
						esc_html__( 'Post__in', 'madara' )      => 'post__in',
					),
					"description" => ''
				),
			);

			vc_map( array(
				"name"     => esc_html__( "Madara Blog" ),
				"base"     => "manga_blog",
				"class"    => "",
				"icon"     => CT_SHORTCODE_PLUGIN_URL . "/shortcodes/img/c_blog.png",
				"controls" => "full",
				"category" => esc_html__( "Madara Shortcodes", "madara" ),
				"params"   => $params
			) );
		}
	}