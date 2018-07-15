<?php
	/**
	 * Template Name: Front-Page
	 *
	 * @package madara
	 */

	use App\Madara;

	get_header();

	$page_content = Madara::getOption( 'page_content' );
	if ( $page_content == 'page_content' ) {
		get_template_part( 'page' );
		exit;
	}
	if ( ! class_exists( 'WP_MANGA' ) && $page_content == 'manga' ) {
		get_template_part( 'page' );
		exit;
	}

	$madara_archive_heading_text = Madara::getOption( 'archive_heading_text', '' );
	$madara_archive_heading_icon = Madara::getOption( 'archive_heading_icon', '' );
	$archive_content_columns     = Madara::getOption( 'archive_content_columns', 3 );
	$archive_margin_top          = get_post_meta( get_the_ID(), 'archive_margin_top', true );
	$archive_margin_top          = isset( $archive_margin_top ) && $archive_margin_top != '' ? $archive_margin_top : '';
	$madara_sidebar              = madara_get_theme_sidebar_setting();
	$nav_type                    = \App\Madara::getOption( 'archive_navigation', 'default' );

	$template = 'html/loop/content';

	if ( $page_content == 'manga' ) {
		$post_type = 'wp-manga';
		$class_1   = 'c-page';
		$class_2   = 'c-page__content manga_content';
		$template  = 'madara-core/content/content-archive';
	} else {
		$post_type = 'post';
		$class_1   = '';
		$class_2   = '';
	}


	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	$madara_custom_query = madara_get_front_page_query( $post_type, $paged );

	if ( $page_content == 'manga' ) {

		set_query_var( 'sidebar', $madara_sidebar );

		$set_query_var = array(
			'sidebar' => $madara_sidebar,
		);


	} else {

		set_query_var( 'archive_content_columns', $archive_content_columns );

		$set_query_var = array(
			'archive_content_columns' => $archive_content_columns,
		);

	}

	$madara_custom_query->query = array_merge( $madara_custom_query->query, $set_query_var );

	$madara_post_count = $madara_custom_query->post_count;

?>

    <div class="c-page-content style-1">
        <div class="content-area" <?php echo ( $archive_margin_top != '' ) ? 'style="margin-top: ' . $archive_margin_top . 'px"' : ''; ?>>
            <div class="container">
                <div class="row <?php echo ( $madara_sidebar == 'left' ) ? 'sidebar-left' : '' ?>">

                    <div class="main-col <?php echo ( $madara_sidebar != 'full' && ( is_active_sidebar( 'manga_archive_sidebar' ) || is_active_sidebar( 'main_sidebar' ) ) ) ? 'col-md-8 col-sm-8' : 'sidebar-hidden col-md-12 col-sm-12' ?>">

						<?php get_template_part( 'html/main-bodytop' ); ?>


                        <div class="main-col-inner <?php echo esc_attr( $class_1 ); ?>">

							<?php if ( $madara_archive_heading_text != '' ) { ?>
                                <div class="c-blog__heading style-2 font-heading <?php echo ( $madara_archive_heading_icon == '' ) ? 'no-icon' : ''; ?>">

                                    <h4>

										<?php if ( $madara_archive_heading_icon != '' ) { ?>
                                            <i class="<?php echo esc_attr( $madara_archive_heading_icon ); ?>"></i>
										<?php } ?>

										<?php echo "TRUYỆN MỚI CẬP NHẬT"; ?>

                                    </h4>
                                </div>
							<?php } ?>

                            <div class="c-blog-listing <?php echo esc_attr( $class_2 ); ?>">
                                <div class="c-blog__inner">
                                    <div class="c-blog__content">

										<?php if ( $madara_custom_query->have_posts() ) : ?>

                                            <div id="loop-content" class="page-content-listing">


												<?php
													$index = 1;
													set_query_var( 'madara_post_count', $madara_post_count );

												?>

												<?php while ( $madara_custom_query->have_posts() ) : $madara_custom_query->the_post(); ?>

													<?php
													set_query_var( 'madara_loop_index', $index );

													if ( $page_content == 'manga' ) {
														get_template_part( 'madara-core/content/content-archive' );
													} else {
														get_template_part( 'html/loop/content' );
													}

													$index ++;

													?>

												<?php endwhile;
													wp_reset_postdata(); ?>

                                            </div>

										<?php else : ?>

											<?php get_template_part( 'html/loop/content', 'none' ); ?>

										<?php endif; ?>

                                        <script type="text/javascript">
											var manga_args = <?php echo str_replace( '\/', '/', json_encode( $madara_custom_query->query_vars ) ); ?>;
                                        </script>

										<?php
											//Get Pagination
											$madara_pagination = new App\Views\ParsePagination();
											$madara_pagination->renderPageNavigation( '#loop-content', $template, $madara_custom_query, $nav_type );
										?>

                                    </div>
                                </div>
                            </div>

                        </div>


						<?php get_template_part( 'html/main-bodybottom' ); ?>

                    </div>


					<?php
						if ( $madara_sidebar != 'full' && is_active_sidebar( 'main_sidebar' ) ) {
							?>
                            <div class="sidebar-col col-md-4 col-sm-4">
								<?php get_sidebar(); ?>
                            </div>
						<?php }
					?>

                </div>
            </div>
        </div>
    </div>


<?php

	get_footer();
