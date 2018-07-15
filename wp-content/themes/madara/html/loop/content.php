<?php
	/**
	 * @package madara
	 */

	use App\Madara;

	$madara_postMeta = new App\Views\ParseMeta();
	$madara_sidebar  = madara_get_theme_sidebar_setting();
	$thumb_size      = array( 360, 206 );

	$archive_content_columns = get_query_var( 'archive_content_columns', Madara::getOption( 'archive_content_columns', 3 ) );
	$archive_post_excerpt    = Madara::getOption( 'archive_post_excerpt', 'on' );

	$columns_class = 'col-md-4';
	if ( $archive_content_columns == 2 ) {
		$columns_class = 'col-md-6';
	}

	$madara_loop_index = get_query_var( 'madara_loop_index' );
	$madara_post_count = get_query_var( 'madara_post_count' );

?>


<?php if ( $madara_loop_index % $archive_content_columns == 1 ) { ?>
    <div class="row c-row">
<?php } ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class( 'col-xs-12  ' . $columns_class . ' ' ); ?>>
        <div class="c-blog_item <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

			<?php if ( has_post_thumbnail() ) { ?>
                <div class="c-blog__thumbnail c-image-hover">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php echo madara_thumbnail( $thumb_size ); ?>
                    </a>
                </div>
			<?php } ?>

            <div class="c-blog__summary">
                <div class="post-meta total-count font-meta">
					<?php $madara_postMeta->renderPostViews( 1 ); ?><?php $madara_postMeta->renderPostTotalShareCounter( 1, 1 ); ?>
                </div>
                <div class="post-title font-title">
					<?php if ( get_the_title() != '' ) { ?>

						<?php $madara_postMeta->renderPostTitle( 'h4' ); ?>

					<?php } else { ?>
                        <h4 class="heading">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php echo get_the_time( get_option( 'date_format' ) ); ?>
                            </a>
                        </h4>
					<?php } ?>

                </div>
				<?php if ( $archive_post_excerpt == 'on' ) { ?>
                    <div class="item-summary">
						<?php the_excerpt(); ?>
                    </div>
				<?php } ?>
            </div>
        </div>
    </div>

<?php if ( $madara_loop_index % $archive_content_columns == 0 || $madara_loop_index == $madara_post_count ) { ?>
    </div>
<?php } ?>