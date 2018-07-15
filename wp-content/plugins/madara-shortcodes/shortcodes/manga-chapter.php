<?php

	/**
	 * MadaraShortcodeMangaChapter
	 */
	class MadaraShortcodeMangaChapter extends MadaraShortcode {
		public function __construct( $params = null, $content = '' ) {
			parent::__construct( 'manga_chapter', $params, $content );
		}

		/**
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function renderShortcode( $atts, $content ) {

			$id            = isset( $atts['id'] ) && is_numeric( $atts['id'] ) ? $atts['id'] : null;
			$order         = isset( $atts['order'] ) && $atts['order'] != '' ? $atts['order'] : 'ASC';
			$orderby       = isset( $atts['orderby'] ) && $atts['orderby'] != '' ? $atts['orderby'] : 'name';
			$manga_details = isset( $atts['manga_details'] ) && $atts['manga_details'] != '' ? $atts['manga_details'] : '1';
			$manga_title   = isset( $atts['manga_title'] ) && $atts['manga_title'] != '' ? $atts['manga_title'] : '1';

			$wp_manga_functions = madara_get_global_wp_manga_functions();
			$alternative        = $wp_manga_functions->get_manga_alternative( $id );
			$rank               = $wp_manga_functions->get_manga_rank( $id );
			$views              = $wp_manga_functions->get_manga_monthly_views( $id );
			$authors            = $wp_manga_functions->get_manga_authors( $id );
			$rate               = $wp_manga_functions->get_total_review( $id );
			$vote               = $wp_manga_functions->get_total_vote( $id );
			$artists            = $wp_manga_functions->get_manga_artists( $id );
			$genres             = $wp_manga_functions->get_manga_genres( $id );
			$type               = $wp_manga_functions->get_manga_type( $id );

			$thumb_size = array( 193, 278 );

			ob_start();

			if ( $id ) {

				?>

                <div class="page-listing-item shortcode-manga-chapter">

                    <div class="row">

                        <div class="col-xs-12 col-md-12">

                            <div id="manga-item-<?php echo esc_attr( $id ); ?>" class="manga-detail <?php echo $manga_details == '1' ? '' : 'detail-hide'; ?>">

								<?php if ( $manga_details == '1' ) { ?>

									<?php if ( $manga_title == '1' ) { ?>
                                        <div class="post-title font-title">
                                            <h3>
												<?php madara_manga_title_badges_html( $id, 1 ); ?>
                                                <a href="<?php echo get_the_permalink( $id ); ?>"><?php echo get_the_title( $id ); ?></a>
                                            </h3>
                                        </div>
									<?php } ?>

                                    <div class="manga-info">
                                        <div class="item-thumb">
											<?php
												if ( has_post_thumbnail( $id ) ) {

													$thumb_url  = get_the_post_thumbnail_url( $id );
													$thumb_type = 'gif';
													if ( $thumb_url != '' ) {
														$image_type = substr( $thumb_url, - 3 );
													}

													if ( $thumb_type == $image_type ) {
														$thumb_size = 'full';
													}

													?>
                                                    <a href="<?php get_the_permalink( $id ); ?>" title="<?php get_the_title( $id ); ?>">
														<?php echo madara_thumbnail( $thumb_size, $id ); ?>
                                                    </a>
													<?php
												}
											?>
                                        </div>
                                        <div class="item-summary">

                                            <div class="post-content">
                                                <div class="post-rating">
													<?php echo $wp_manga_functions->manga_rating_display( $id ); ?>
                                                </div>
                                                <div class="post-content_item">
                                                    <div class="summary-heading">
                                                        <h5><?php echo esc_attr__( 'Rating', 'madara' ); ?></h5>
                                                    </div>
                                                    <div class="summary-content vote-details">
														<?php echo sprintf( _n( 'Average %1s / %2s out of %3s total vote.', 'Average %1s / %2s out of %3s total votes.', $vote, 'madara' ), $rate, '5', $vote ); ?>
                                                    </div>
                                                </div>
                                                <div class="post-content_item">
                                                    <div class="summary-heading">
                                                        <h5>
															<?php echo esc_attr__( 'Rank', 'madara' ); ?>
                                                        </h5>
                                                    </div>
                                                    <div class="summary-content">
														<?php echo sprintf( _n( ' %1s, it has %2s monthly view', ' %1s, it has %2s monthly views', $views, 'madara' ), $rank, $views ); ?>
                                                    </div>
                                                </div>
												<?php if ( $alternative ): ?>
                                                    <div class="post-content_item">
                                                        <div class="summary-heading">
                                                            <h5>
																<?php echo esc_attr__( 'Alternative', 'madara' ); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="summary-content">
															<?php echo $alternative; ?>
                                                        </div>
                                                    </div>
												<?php endif ?>

												<?php if ( $authors ): ?>
                                                    <div class="post-content_item">
                                                        <div class="summary-heading">
                                                            <h5>
																<?php echo esc_attr__( 'Author(s)', 'madara' ); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="summary-content">
                                                            <div class="author-content">
																<?php echo $authors; ?>
                                                            </div>
                                                        </div>
                                                    </div>
												<?php endif ?>

												<?php if ( $artists ): ?>
                                                    <div class="post-content_item">
                                                        <div class="summary-heading">
                                                            <h5>
																<?php echo esc_attr__( 'Artist(s)', 'madara' ); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="summary-content">
                                                            <div class="artist-content">
																<?php echo $artists; ?>
                                                            </div>
                                                        </div>
                                                    </div>
												<?php endif ?>

												<?php if ( $genres ): ?>
                                                    <div class="post-content_item">
                                                        <div class="summary-heading">
                                                            <h5>
																<?php echo esc_attr__( 'Genre(s)', 'madara' ); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="summary-content">
                                                            <div class="genres-content">
																<?php echo $genres; ?>
                                                            </div>
                                                        </div>
                                                    </div>
												<?php endif ?>

												<?php if ( $type ): ?>
                                                    <div class="post-content_item">
                                                        <div class="summary-heading">
                                                            <h5>
																<?php echo esc_attr__( 'Type', 'madara' ); ?>
                                                            </h5>
                                                        </div>
                                                        <div class="summary-content">
															<?php echo $type; ?>
                                                        </div>
                                                    </div>
												<?php endif ?>
                                            </div>


                                        </div>
                                    </div>

								<?php } ?>

                                <div class="list-chapter">
									<?php
										$wp_manga_functions->manga_get_all_chapter( $id, 1, $orderby, $order );
									?>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

				<?php
			}
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

	}

	$madara_button = new MadaraShortcodeMangaChapter();

	/**
	 * add button to visual composer
	 */
	add_action( 'after_setup_theme', 'reg_manga_chapter' );

	function reg_manga_chapter() {
		if ( function_exists( 'vc_map' ) ) {
			$params = array(

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Manga ID", "madara" ),
					"param_name"  => "id",
					"value"       => "",
					"description" => esc_html__( 'Enter ID of manga to display.', "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Chapter Order", "madara" ),
					"param_name"  => "order",
					"std"         => 'ASC',
					"value"       => array(
						esc_html__( "Ascending", "madara" )  => "ASC",
						esc_html__( "Descending", "madara" ) => "DESC",
					),
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Enable Manga Details", "madara" ),
					"param_name"  => "manga_details",
					"std"         => 'enable',
					"value"       => array(
						esc_html__( "Enable", "madara" )  => "1",
						esc_html__( "Disable", "madara" ) => "0"
					),
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Enable Manga Title", "madara" ),
					"param_name"  => "manga_title",
					"std"         => 'enable',
					"value"       => array(
						esc_html__( "Enable", "madara" )  => "1",
						esc_html__( "Disable", "madara" ) => "0"
					),
					"dependency"  => array(
						"element" => "manga_details",
						"value"   => array( "1" ),
					),
				),

			);
			vc_map( array(
				'name'     => esc_html__( 'Madara Manga Chapter', 'madara' ),
				'base'     => 'manga_chapter',
				'icon'     => CT_SHORTCODE_PLUGIN_URL . '/shortcodes/img/c_post_slider.png',
				'category' => esc_html__( 'Madara Shortcodes', 'madara' ),
				'params'   => $params,
			) );
		}
	}
