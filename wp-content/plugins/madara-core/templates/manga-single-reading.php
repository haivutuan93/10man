<?php

	/** Manga paged **/

	$name = get_query_var('chapter');

	if ( $name == '' ) {
		get_404_template();
		exit();
	}

	get_header();

	global $wp_manga, $wp_manga_template;

	$post_id  = get_the_ID();
	$paged    = ! empty( $_GET['manga-paged'] ) ? $_GET['manga-paged'] : 1;
	$style    = ! empty( $_GET['style'] ) ? $_GET['style'] : 'paged';

	$chapters = $wp_manga->get_chapter( get_the_ID() );
	$cur_chap = get_query_var('chapter');

	$wp_manga_settings = get_option( 'wp_manga_settings' );
	$related_manga     = isset( $wp_manga_settings['related_manga']['state'] ) ? $wp_manga_settings['related_manga']['state'] : null;

?>

    <div class="wp-manga-section">
		<div class="c-page-content style-1">
	        <div class="content-area">
	            <div class="container">
	                <div class="row">
	                    <div class="main-col col-md-12 col-sm-12 sidebar-hidden">
	                        <!-- container & no-sidebar-->
	                        <div class="main-col-inner">
	                            <div class="c-blog-post">
	                                <div class="entry-header">
										<?php $wp_manga->manga_nav( 'header' ); ?>
	                                </div>
	                                <div class="entry-content">
	                                    <div class="entry-content_wrap">
	                                        <div class="read-container">
												<?php $wp_manga_template->load_template( 'reading-content/content', 'reading-' . $style, true ); ?>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="c-select-bottom">
									<?php $wp_manga->manga_nav( 'footer' ); ?>
	                            </div>

	                            <div class="row">
	                                <div class="main-col col-xs-12 col-sm-8 col-md-8 col-lg-8">
	                                    <!-- comments-area -->
										<?php do_action( 'wp_manga_discusion' ); ?>
	                                    <!-- END comments-area -->
	                                </div>
									<div class="sidebar-col col-md-4 col-sm-4">
										<?php dynamic_sidebar( 'manga_reading_sidebar' ); ?>
			                        </div>
	                            </div>
								<?php
									if ( $related_manga == 1 ) {
										get_template_part( '/wp-manga/manga', 'related' );
									}

									$wp_manga->wp_manga_get_tags();

								?>

	                        </div>
	                    </div>

	                </div>
	            </div>
	        </div>
	    </div>
	</div>

<?php

	get_footer();
