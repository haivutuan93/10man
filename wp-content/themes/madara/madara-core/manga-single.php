<?php

	/** Single template of Manga **/
    function getTagName() {
        //tags
        $tag_name = '';
        $manga_tags = get_the_terms(get_the_ID(), 'wp-manga-tag');
        $manga_tags = isset($manga_tags) && !empty($manga_tags) ? $manga_tags : array();
        $tag_count = count($manga_tags);
        $tag_flag = 0;
        $separate_char = ', ';

        if ($manga_tags == false || is_wp_error($manga_tags)) {

        }else{
            foreach ($manga_tags as $tag) {
                $tag_flag ++;
                if ($tag->name == 'Truyện tranh') {
                    $tag_name = ' tranh';
                    break;
                }
                if ($tag->name == 'Truyện chữ') {
                    $tag_name = ' chữ';
                    break;
                }
            }
        }
        return $tag_name;
    }
add_filter('pre_get_document_title', function( $title ) {
//tags
    $tag_name = getTagName();
    $title = get_the_title() . ' - Truyện';
    if($tag_name != ''){
        $title = $title. $tag_name;
    }
    return $title;
}, 999, 1);

function custom_add_meta_description_tag_manga() {
    $tag_name = getTagName();
    if($tag_name != ''){
        $title = 'Truyện'. $tag_name;
        $title = $title . ' - ' .get_the_title();
    }else{
        $title = get_the_title();
    }
    $description = $title . '. Nội dung truyện: ' . strip_tags(get_post()->post_content);   
    ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta http-equiv="content-language" content="vi" />
    <?php
}
add_action('wp_head', 'custom_add_meta_description_tag_manga', 999, 1);

	get_header();

	use App\Madara;

	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$thumb_size         = array( 193, 278 );

	$alternative = $wp_manga_functions->get_manga_alternative( get_the_ID() );
	$rank        = $wp_manga_functions->get_manga_rank( get_the_ID() );
	$views       = $wp_manga_functions->get_manga_monthly_views( get_the_ID() );
	$authors     = $wp_manga_functions->get_manga_authors( get_the_ID() );
	$rate        = $wp_manga_functions->get_total_review( get_the_ID() );
	$vote        = $wp_manga_functions->get_total_vote( get_the_ID() );
	$artists     = $wp_manga_functions->get_manga_artists( get_the_ID() );
	$genres      = $wp_manga_functions->get_manga_genres( get_the_ID() );

	$madara_single_sidebar    = madara_get_theme_sidebar_setting();
	$madara_breadcrumb        = Madara::getOption( 'manga_single_breadcrumb', 'on' );
	$manga_profile_background = madara_output_background_options( 'manga_profile_background' );
	$chapters_order           = Madara::getOption( 'manga_chapters_order', 'desc' );

	$wp_manga_settings = get_option( 'wp_manga_settings' );
	$related_manga     = isset( $wp_manga_settings['related_manga']['state'] ) ? $wp_manga_settings['related_manga']['state'] : null;

?>


<?php do_action( 'before_manga_single' ); ?>
<div class="profile-manga" style="<?php echo esc_attr( $manga_profile_background != '' ? $manga_profile_background : 'background-image: url(' . get_parent_theme_file_uri( '/images/bg-search.jpg' ) . ');' ); ?>">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
				<?php
					if ( $madara_breadcrumb == 'on' ) {
						get_template_part( 'madara-core/manga', 'breadcrumb' );
					}
				?>
                <div class="post-title">
                    <h1 style='text-align: left; font-size: 18px;'><?php echo esc_html( get_the_title() ); ?></h1>
                </div>
                <div class="tab-summary <?php echo has_post_thumbnail() ? '' : esc_attr( 'no-thumb' ); ?>">

					<?php if ( has_post_thumbnail() ) { ?>
                        <div class="summary_image">
                            <a href="<?php echo get_the_permalink(); ?>">
								<?php 
								    $temp_badges = get_post_meta( get_the_ID(), 'manga_title_badges', true );
                                            if($temp_badges == "custom"){
                                                echo madara_thumbnail( 'manga_wg_post_2');
                                            }else{
                                                echo madara_thumbnail( $thumb_size );
                                            }
								?>
                            </a>
                        </div>
					<?php } ?>
                    <div class="summary_content_wrap">
                        <div class="summary_content">
                            <div class="post-content">
								<?php get_template_part( 'html/ajax-loading/ball-pulse' ); ?>
                                <div class="post-rating">
									<?php
										$wp_manga_functions->manga_rating_display( get_the_ID(), true );
									?>
                                </div>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5><?php echo esc_attr__( 'Đánh giá', 'madara' ); ?></h5>
                                    </div>
                                    <div class="summary-content vote-details">
										<?php echo sprintf( _n( 'Trung bình %1s / %2s điểm trong tất cả đánh giá.', 'Trung bình %1s / %2s điểm trong tất cả đánh giá.', $vote, 'madara' ), $rate, '5', $vote ); ?>
                                    </div>
                                </div>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Xếp hạng', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo sprintf( _n( ' %1s', ' %1s', $views, 'madara' ), $rank, $views ); ?>
                                    </div>
                                </div>

                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Tên khác', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $alternative ); ?>
                                    </div>
                                </div>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Tác giả', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="author-content">
											<?php echo wp_kses_post( $authors ); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Họa sĩ', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="artist-content">
											<?php echo wp_kses_post( $artists ); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Thể loại', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="genres-content">
											<?php echo wp_kses_post( $genres ); ?>
                                        </div>
                                    </div>
                                </div>

								<?php $type = $wp_manga_functions->get_manga_type( get_the_ID() ); ?>
                            </div>
                            <div class="post-status">
                                
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Xuất bản', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php
											echo wp_kses_post( $wp_manga_functions->get_manga_release( get_the_ID() ) );
										?>
                                    </div>
                                </div>
                                
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Tình trạng', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php
											echo wp_kses_post( $wp_manga_functions->get_manga_status( get_the_ID() ) );
										?>
                                    </div>
                                </div>

                                <div class="manga-action">
                                    <div class="count-comment">
                                        <div class="action_icon">
                                            <a href="#manga-discission"><i class="ion-chatbubble-working"></i></a>
                                        </div>
                                        <div class="action_detail">
											<?php $comments_count = wp_count_comments( get_the_ID() ); ?>
                                            <span><?php printf( _n( '%s bình luận', '%s bình luận', $comments_count->approved, 'madara' ), $comments_count->approved ); ?></span>
                                        </div>
                                    </div>
                                    <div class="add-bookmark">
										<?php
											$wp_manga_functions->bookmark_link_e();
										?>
                                    </div>
									<?php do_action( 'madara_single_manga_action' ); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="c-page-content style-1">
    <div class="content-area">
        <div class="container">
            <div class="row <?php echo esc_attr( $madara_single_sidebar == 'left' ? 'sidebar-left' : '' ) ?>">
                <div class="main-col <?php echo esc_attr( $madara_single_sidebar !== 'full' && ( is_active_sidebar( 'manga_single_sidebar' ) || is_active_sidebar( 'main_sidebar' ) ) ? ' col-md-8 col-sm-8' : 'col-md-12 col-sm-12 sidebar-hidden' ) ?>">
                    <!-- container & no-sidebar-->
                    <div class="main-col-inner">
                        <div class="c-page">
                            <!-- <div class="c-page__inner"> -->
                            <div class="c-page__content">

								<?php if ( get_the_content() != '' ) { ?>

                                    <div class="c-blog__heading style-2 font-heading">

                                        <h4>
                                            <i class="<?php madara_default_heading_icon(); ?>"></i>
											<?php echo esc_attr__( 'Giới thiệu', 'madara' ); ?>
                                        </h4>
                                    </div>

                                    <div class="description-summary">
                                        <div class="summary__content">
											<?php echo get_the_content() ?>
                                        </div>

										<?php echo '<div class="c-content-readmore"><span class="btn btn-link content-readmore">' . esc_html__( 'Xem thêm  ', 'madara' ) . '</span></div>' ?>
                                    </div>

								<?php } ?>

                                <div class="c-blog__heading style-2 font-heading">

                                    <h4>
                                        <i class="<?php madara_default_heading_icon(); ?>"></i>
										<?php echo esc_attr__( "CHƯƠNG MỚI", 'madara' ); ?>
                                    </h4>
                                </div>
                                <div class="page-content-listing single-page">
                                    <div class="listing-chapters_wrap">
										<?php $manga = $wp_manga_functions->get_all_chapters( get_the_ID() ); ?>
										<?php if ( $manga ) : ?>

											<?php do_action( 'madara_before_chapter_listing' ) ?>

                                            <ul class="main version-chap">
												<?php
													$single   = isset( $manga['0']['chapters'] ) ? $manga['0']['chapters'] : null;
													if ( $single ) : ?><?php foreach ( $single as $chapter ) :
														$style = $wp_manga_functions->get_reading_style();
														//$link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter['chapter_slug'], $style );
														$link = $wp_manga_functions->build_chapter_url_not_by_slug( get_the_ID(), $chapter, $chapter['chapter_slug'], $style );
														?>
                                                        <li class="wp-manga-chapter">
                                                            <a href="<?php echo esc_url( $link ); ?>">
																<?php echo isset( $chapter['chapter_name'] ) ? esc_attr( $chapter['chapter_name'] . $wp_manga_functions->filter_extend_name( $chapter['chapter_name_extend'] ) ) : ''; ?>
                                                            </a><span class="chapter-release-date"><i><?php echo isset( $chapter['date'] ) ? $wp_manga_functions->get_time_diff( $chapter['date'] ) : ''; ?></i></span>
                                                        </li>
													<?php endforeach; ?><?php unset( $manga['0'] ); endif;
												?>

												<?php

													if ( ! empty( $manga ) ) {

														if ( $chapters_order == 'desc' ) {
															$manga = array_reverse( $manga );
														}

														foreach ( $manga as $vol_id => $vol ) {

															$chapters = isset( $vol['chapters'] ) ? $vol['chapters'] : null;

															$chapters_parent_class = $chapters ? 'parent has-child' : 'parent no-child';
															$chapters_child_class  = $chapters ? 'has-child' : 'no-child';
															$first_volume_class    = isset( $first_volume ) ? '' : ' active';
															?>

                                                            <li class="<?php echo esc_attr( $chapters_parent_class . ' ' . $first_volume_class ); ?>">

																<?php echo isset( $vol['volume_name'] ) ? '<a href="javascript:void(0)" class="' . $chapters_child_class . '">' . $vol['volume_name'] . '</a>' : ''; ?>
																<?php

																	if ( $chapters ) { ?>
                                                                        <ul class="sub-chap list-chap" <?php echo isset( $first_volume ) ? '' : ' style="display: block;"'; ?> >

																			<?php if ( $chapters_order == 'desc' ) {
																				$chapters = array_reverse( $chapters );
																			} ?>

																			<?php foreach ( $chapters as $chapter ) {
																				$style = $wp_manga_functions->get_reading_style();

																				//$link          = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter['chapter_slug'], $style );
																				$link		   = $wp_manga_functions->build_chapter_url_not_by_slug( get_the_ID(), $chapter, $chapter['chapter_slug'], $style );
																				$c_extend_name = madara_get_global_wp_manga_functions()->filter_extend_name( $chapter['chapter_name_extend'] );
																				?>
                                                                                <li class="wp-manga-chapter">
                                                                                    <a href="<?php echo esc_url( $link ); ?>">
																						<?php echo esc_attr( $chapter['chapter_name'] . $c_extend_name ) ?>
                                                                                    </a>
                                                                                    <span class="chapter-release-date">
																						<i>
																							<?php
																								echo wp_kses_post( $wp_manga_functions->get_time_diff( $chapter['date'] ) );
																							?>
																						</i>
																					</span>
                                                                                </li>
																			<?php } ?>
                                                                        </ul>
																	<?php } else { ?>
                                                                        <span class="no-chapter"><?php echo esc_html__( 'Không tìm thấy chương nào', 'madara' ); ?></span>
																	<?php } ?>
                                                            </li>
															<?php $first_volume = false; ?>

														<?php } //endforeach; ?>

													<?php } //endif-empty( $volume);
												?>
                                            </ul>

											<?php do_action( 'madara_after_chapter_listing' ) ?>

										<?php else : ?>

											<?php echo esc_html__( 'Không tìm thấy chương nào.', 'madara' ); ?>

										<?php endif; ?>

										<?php echo '<div class="c-chapter-readmore"><span class="btn btn-link chapter-readmore">' . esc_html__( 'Xem thêm ', 'madara' ) . '</span></div>' ?>
                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
						<div style="float: right">
<?php
if (function_exists("kk_star_ratings")) : echo kk_star_ratings($pid);
endif;
?>
                        </div>
                        <!-- comments-area -->
						<?php do_action( 'wp_manga_discusion' ); ?>
                        <!-- END comments-area -->

						<?php

							if ( $related_manga == 1 ) {
								get_template_part( '/madara-core/manga', 'related' );
							}

							if ( class_exists( 'WP_Manga' ) ) {
								$GLOBALS['wp_manga']->wp_manga_get_tags();
							}
						?>

                    </div>
                </div>

				<?php
					if ( $madara_single_sidebar != 'full' && ( is_active_sidebar( 'main_sidebar' ) || is_active_sidebar( 'manga_single_sidebar' ) ) ) {
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

<?php do_action( 'after_manga_single' ); ?><?php get_footer(); ?>
