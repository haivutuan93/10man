<?php

	/**
	 * MadaraShortcodePostSlider
	 */
	class MadaraShortcodePostSlider extends MadaraShortcode {
		public function __construct( $params = null, $content = '' ) {
			parent::__construct( 'manga_post_slider', $params, $content );
		}

		/**
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function renderShortcode( $atts, $content ) {

			$id        = 'c-post-slider-' . rand( 1, 999 );
			$style     = isset( $atts['style'] ) && $atts['style'] != '' ? $atts['style'] : 1;
			$cats      = isset( $atts['cats'] ) && $atts['cats'] != '' ? $atts['cats'] : '';
			$tags      = isset( $atts['tags'] ) && $atts['tags'] != '' ? $atts['tags'] : '';
			$orderby   = isset( $atts['orderby'] ) && $atts['orderby'] != '' ? $atts['orderby'] : 'latest';
			$count     = isset( $atts['count'] ) && $atts['count'] != '' ? $atts['count'] : '5';
			$order     = isset( $atts['order'] ) && $atts['order'] != '' ? $atts['order'] : 'DESC';
			$ids       = isset( $atts['ids'] ) && $atts['ids'] != '' ? $atts['ids'] : '';
			$post_type = isset( $atts['post_type'] ) && $atts['post_type'] != '' ? $atts['post_type'] : 'post';
			$number    = isset( $atts['number'] ) && $atts['number'] != '' ? $atts['number'] : '2';
			$time      = isset( $atts['time'] ) && $atts['time'] != '' ? $atts['time'] : 'all';

			$thumb_size = array( 642, 320 );

			if ( $orderby == 'view' ) {
				$orderby = 'most_viewed';
			} else if ( $orderby == 'random' ) {
				$orderby = 'rand';
			} else if ( $orderby == 'comment' ) {
				$orderby = 'most_commented';
			} else if ( $orderby == 'title' ) {
				$orderby = 'title';
			} else if ( $orderby == 'input' ) {
				$orderby = 'post__in';
			} else {
				$orderby = 'date';
			}

			$shortcode_query = App\Models\Database::getPosts( $count, $order, 1, $orderby, array(
				'categories' => $cats,
				'tags'       => $tags,
				'ids'        => $ids,
				'post_type'  => $post_type,
				'timerange'  => $time
			) );

			switch ( $style ) {
				case '1':
					$wrap_class  = 'manga-slider';
					$inner_class = 'slider__container';
					$data_style  = 'style-1';
					$classes     = 'style-1 no-padding';
					break;
				case '2':
					$wrap_class  = 'manga-slider';
					$inner_class = 'slider__container';
					$data_style  = 'style-2';
					$classes     = 'style-2';
					break;
				case '3':
					$wrap_class  = 'manga-slider';
					$inner_class = 'slider__container';
					$data_style  = 'style-1';
					$classes     = 'style-1 no-full-width style-3';
					break;
				case '4':
					$wrap_class  = 'related-post manga-slider';
					$inner_class = 'related__container slider__container row c-row';
					$data_style  = 'style-4';
					$classes     = 'style-4';
					break;
			}

			ob_start();

			if ( $shortcode_query->have_posts() ) {
				?>


                <div id="<?php echo esc_attr( $id ); ?>" class="<?php echo esc_attr( $wrap_class ) . ' ' . esc_attr( $classes ); ?>" data-style="<?php echo esc_attr( $data_style ); ?>" data-count="<?php echo esc_attr( $number ); ?>">

                    <div class="<?php echo esc_attr( $inner_class ); ?>" role="toolbar">

						<?php
							while ( $shortcode_query->have_posts() ) {

								$shortcode_query->the_post(); ?>

								<?php if ( $style != 4 ) { ?>

                                    <div class="slider__item <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

										<?php if ( has_post_thumbnail() ) { ?>
                                            <div class="slider__thumb">
                                                <div class="slider__thumb_item">
                                                    <a href="<?php echo get_the_permalink() ?>">
														<?php echo madara_thumbnail( $thumb_size ); ?>
                                                        <div class="slider-overlay"></div>
                                                    </a>
                                                </div>
                                            </div>
										<?php } ?>

                                        <div class="slider__content">
                                            <div class="slider__content_item">
                                                <div class="post-title font-title">
                                                    <h4>
                                                        <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

								<?php } else { ?>

                                    <div class="col-sm-6 col-md-3 related__item <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

										<?php if ( has_post_thumbnail() ) { ?>
                                            <div class="related__thumb">
                                                <div class="related__thumb_item">
                                                    <a href="<?php echo get_the_permalink() ?>">
														<?php echo madara_thumbnail( $thumb_size ); ?>
                                                        <div class="related-overlay"></div>
                                                    </a>
                                                </div>
                                            </div>
										<?php } ?>

                                        <div class="related__content">
                                            <div class="related__content_item">
                                                <div class="post-title font-title">
                                                    <h5>
                                                        <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

								<?php } ?>

							<?php }//end while
							wp_reset_postdata();
						?>

                    </div>
                </div>


				<?php
			}
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

	}

	$madara_button = new MadaraShortcodePostSlider();

	/**
	 * add button to visual composer
	 */
	add_action( 'after_setup_theme', 'reg_manga_post_slider' );

	function reg_manga_post_slider() {
		if ( function_exists( 'vc_map' ) ) {
			$params = array(
				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Style", "madara" ),
					"param_name"  => "style",
					"std"         => 1,
					"value"       => array(
						esc_html__( "Style 1", "madara" ) => 1,
						esc_html__( "Style 2", "madara" ) => 2,
						esc_html__( "Style 3", "madara" ) => 3,
						esc_html__( "Style 4", "madara" ) => 4,
					),
					"description" => esc_html__( "condition to query items", "madara" )
				),
				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Count", "madara" ),
					"param_name"  => "count",
					"value"       => "",
					"description" => esc_html__( 'number of items to query', "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Number of Post to show", "madara" ),
					"param_name"  => "number",
					"std"         => '2',
					"description" => esc_html__( 'Number of Post to show', "madara" ),
					"value"       => array(
						esc_html__( "1", "madara" ) => "1",
						esc_html__( "2", "madara" ) => "2",
						esc_html__( "3", "madara" ) => "3",
						esc_html__( "4", "madara" ) => "4",
					),
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Oder By", "madara" ),
					"param_name"  => "orderby",
					"value"       => array(
						esc_html__( "Latest", "madara" )                                         => 'latest',
						esc_html__( "Most viewed", "madara" )                                    => 'view',
						esc_html__( "Most commented", "madara" )                                 => 'comment',
						esc_html__( "Title", "madara" )                                          => "title",
						esc_html__( "Input(only available when using ids parameter)", "madara" ) => "input",
						esc_html__( "Random", "madara" )                                         => "random"
					),
					"description" => esc_html__( "condition to query items", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Time Range to query posts", "madara" ),
					"param_name"  => "time",
					"std"         => 'all',
					"description" => esc_html__( 'Time Range to query posts', "madara" ),
					"value"       => array(
						esc_html__( "All Time", "madara" ) => "all",
						esc_html__( "1 Day", "madara" )    => "day",
						esc_html__( "1 Week", "madara" )   => "week",
						esc_html__( "1 Month", "madara" )  => "month",
						esc_html__( "1 Year", "madara" )   => "year",
					),
					'dependency'  => array(
						'element' => 'orderby',
						'value'   => array( 'view' ),
					),
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Order", "madara" ),
					"param_name"  => "order",
					"value"       => array(
						esc_html__( "Descending", "madara" ) => "DESC",
						esc_html__( "Ascending", "madara" )  => "ASC"
					),
				),

				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Categories", "madara" ),
					"param_name"  => "cats",
					"description" => esc_html__( "list of categories (ID) to query items from, separated by a comma.", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Tags", "madara" ),
					"param_name"  => "tags",
					"value"       => "",
					"description" => esc_html__( 'list of tags slug to query items from, separated by a comma. For example: tag-1, tag-2, tag-3', "madara" )
				),
				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "IDs", "madara" ),
					"param_name"  => "ids",
					"value"       => "",
					"description" => esc_html__( 'list of post IDs to query, separated by a comma. If this value is not empty, cats, tags and featured are omitted', "madara" )
				),
			);
			vc_map( array(
				'name'     => esc_html__( 'Madara Post Slider', 'madara' ),
				'base'     => 'manga_post_slider',
				'icon'     => CT_SHORTCODE_PLUGIN_URL . '/shortcodes/img/c_post_slider.png',
				'category' => esc_html__( 'Madara Shortcodes', 'madara' ),
				'params'   => $params,
			) );
		}
	}
