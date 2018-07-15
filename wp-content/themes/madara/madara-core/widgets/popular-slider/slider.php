<?php

	/** Template of Manga: Overwrite Popular Slider **/

	use App\Madara;

	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$thumb_size         = array( 125, 180 );
	$allow_thumb_gif    = Madara::getOption( 'manga_single_allow_thumb_gif', 'off' );

	$thumb_url = get_the_post_thumbnail_url( get_the_ID() );

	$thumb_type = 'gif';
	if ( $thumb_url != '' ) {
		$type = substr( $thumb_url, - 3 );
	}

	if ( $allow_thumb_gif == 'on' && $thumb_type == $type ) {
		$thumb_size = 'full';
	}

?>
<div class="slider__item">
    <div class="item__wrap <?php echo has_post_thumbnail() ? '' : 'no-thumb'; ?>">

		<?php if ( has_post_thumbnail() ) { ?>
            <div class="slider__thumb">
                <div class="slider__thumb_item c-image-hover">
                    <a href="<?php echo get_the_permalink() ?>">
						<?php if ( $allow_thumb_gif == 'off' ) {
							$temp_badges = get_post_meta( get_the_ID(), 'manga_title_badges', true );
                                            if($temp_badges == "custom"){
                                                echo madara_thumbnail( 'manga_wg_post_2');
                                            }else{
                                                echo madara_thumbnail( $thumb_size );
                                            }
						} else {
							echo get_the_post_thumbnail( get_the_ID(), $thumb_size );
						} ?>
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
				<?php $date = get_post_meta( get_the_ID(), '_latest_update', true ) ?>
				<?php if ( $date && '' != $date ): ?>
                    <div class="post-on font-meta">
                        <span>
                            <?php echo date( get_option( 'date_format' ), $date ); ?>
                        </span>
                    </div>
				<?php endif ?>

                <div class="chapter-item">
					<?php $chapters = $wp_manga_functions->get_latest_chapters( get_the_ID(), null, 2 );
						if ( $chapters ) {
							foreach ( $chapters as $chapter ) {

								$style = $wp_manga_functions->get_reading_style();

								$manga_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter['chapter_slug'], $style );

								?>

								<?php if ( ! empty( $chapter['chapter_name'] ) ) { ?>
                                    <span class="chapter">
                                        <a href="<?php echo esc_url( $manga_link ); ?>"><?php echo esc_attr( $chapter['chapter_name'] ) ?></a>
                                    </span>
								<?php } ?>

							<?php }
						}
					?>
                </div>
            </div>
        </div>

    </div>
</div>
