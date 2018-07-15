<?php
	/*
	*  Manga Archive
	*/

	use App\Cactus;

	get_header();

	global $wp_query, $wp_manga, $wp_manga_template;

	//set args
	if ( ! empty( get_query_var( 'paged' ) ) ) {
		$paged = get_query_var( 'paged' );
	} elseif ( ! empty( get_query_var( 'page' ) ) ) {
		$paged = get_query_var( 'page' );
	} else {
		$paged = 1;
	}

	$orderby = isset( $_GET['m_orderby'] ) ? $_GET['m_orderby'] : 'latest';

	$manga_args = array(
		'paged'    => $paged,
		'orderby'  => $orderby,
		'template' => 'archive'
	);

	foreach ( $manga_args as $key => $value ) {
		$wp_query->set( $key, $value );
	}

	if ( is_home() || is_front_page() ) {
		$manga_query =  $wp_manga->mangabooth_manga_query( $manga_args );
	} else {
		$manga_query =  $wp_manga->mangabooth_manga_query( $wp_query->query_vars );
	}

?>
<div class="wp-manga-section">
	<div class="c-page-content style-1">
	    <div class="content-area">
	        <div class="container">
	            <div class="row">
	                <div class="main-col col-md-8 col-sm-8">
	                    <!-- container & no-sidebar-->
	                    <div class="main-col-inner">
	                        <div class="c-page">
								<?php if( is_tax() ){ ?>
									<div class="entry-header">
								        <div class="entry-header_wrap">
								            <div class="entry-title">
								                <h2 class="item-title"><?php echo isset( get_queried_object()->name ) ? get_queried_object()->name : ''; ?></h2>
								            </div>
								        </div>
								    </div>
								<?php } ?>
	                            <!-- <div class="c-page__inner"> -->
	                            <div class="c-page__content">
	                                <div class="tab-wrap">
	                                    <div class="c-blog__heading style-2 font-heading">

	                                        <h4>
	                                            <i class="ion-ios-star"></i>
												<?php echo sprintf( _n( '%s result', '%s results', $manga_query->post_count, WP_MANGA_TEXTDOMAIN ), $manga_query->found_posts ); ?>
	                                        </h4>
											<?php $wp_manga_template->load_template( 'manga-archive-filter' ); ?>
	                                    </div>
	                                </div>
	                                <!-- Tab panes -->
	                                <div class="tab-content-wrap">
	                                    <div role="tabpanel" class="c-tabs-item">
	                                        <div class="page-content-listing">
												<?php
													if ( $manga_query->have_posts() ) {

														$manga_args['max_num_pages'] = $manga_query->max_num_pages;
														$wp_manga->wp_manga_query_vars_js( $manga_args );

														$index = 0;
														// echo '<pre>'; var_dump( $manga_query ); echo '</pre>';

														set_query_var( 'wp_manga_posts_per_page', $manga_query->post_count );
														set_query_var( 'wp_manga_paged', $paged );

														while ( $manga_query->have_posts() ) {

															$index++;
															set_query_var( 'wp_manga_post_index', $index );

															$manga_query->the_post();
															$wp_manga_template->load_template( 'content/content', 'archive' );
														}


													}else{
														$wp_manga_template->load_template( 'content/content-none' );
													}

													wp_reset_postdata();

												?>
	                                        </div>
											<?php
												echo $wp_manga->wp_manga_pagination( $manga_query, '.page-content-listing', 'archive' );
 											?>
	                                    </div>
	                                </div>
	                            </div>
	                            <!-- </div> -->
	                        </div>
	                        <!-- paging -->
	                    </div>
	                </div>
					<div class="sidebar-col col-md-4 col-sm-4">
						<?php dynamic_sidebar( 'manga_archive_sidebar' ); ?>
					</div>
	            </div>
	        </div>
	    </div>
	</div>
</div>
<?php get_footer(); ?>
