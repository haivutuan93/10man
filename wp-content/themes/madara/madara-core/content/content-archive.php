<?php

	use App\Madara;

	$wp_query           = madara_get_global_wp_query();
	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_setting   = madara_get_global_wp_manga_setting();
	$wp_manga_functions = madara_get_global_wp_manga_functions();


	//get ready
	$thumb_size              = array( 110, 150 );
	$madara_loop_index   = get_query_var( 'madara_loop_index' );
	$madara_total_posts  = get_query_var( 'madara_post_count' );
	$madara_page_sidebar = get_query_var( 'sidebar' );

	$manga_hover_details     = Madara::getOption( 'manga_hover_details', 'off' );

	$alternative             = $wp_manga_functions->get_manga_alternative( get_the_ID() );

	$authors                 = $wp_manga_functions->get_manga_authors( get_the_ID() );


	if ( $madara_page_sidebar == 'full' ) {
		$main_col_class = 'col-xs-12 col-md-4';
	} else {
		$main_col_class = 'col-xs-12 col-md-6';
	}

	if ( ( $madara_page_sidebar == 'full' && $madara_loop_index % 3 == 1 ) || ( $madara_page_sidebar !== 'full' && $madara_loop_index % 2 == 1 ) ) {
?>
<div class="page-listing-item">
    <div class="row">
		<?php
			}

		?>

        <div class="<?php echo esc_attr( $main_col_class ); ?>">
            <div class="page-item-detail">

                <div id="manga-item-<?php echo esc_attr( get_the_ID() ); ?>" class="item-thumb <?php echo ($manga_hover_details == 'off') ? '' : 'hover-details'; ?> c-image-hover" data-post-id="<?php echo get_the_ID(); ?>">
					<?php
						if ( has_post_thumbnail() ) {
							?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php 
								    $temp_badges = get_post_meta( get_the_ID(), 'manga_title_badges', true );
                                            if($temp_badges == "custom"){
                                                echo madara_thumbnail( 'manga_wg_post_2');
                                            }else{
                                                echo madara_thumbnail( $thumb_size );
                                            }
								?>
                            </a>
							<?php
						}
					?>
                </div>
                <div class="item-summary">
                    <div class="post-title font-title">
                        <h5>
							<?php madara_manga_title_badges_html( get_the_ID(), 1 ); ?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                    </div>
                    <div class="meta-item rating">
						<?php
							$wp_manga_functions->manga_rating_display( get_the_ID() );
						?>
                    </div>
                    <div class="list-chapter">
						<?php
							$wp_manga_functions->manga_meta( get_the_ID() );
						?>
                    </div>
                </div>
            </div>

        </div>
		<?php
			if ( ( $madara_page_sidebar == 'full' && $madara_loop_index % 3 == 0 ) || ( $madara_page_sidebar !== 'full' && $madara_loop_index % 2 == 0 ) || ( $madara_loop_index == $madara_total_posts ) ) {
		?>
    </div>
</div>
<?php
	}

?>
