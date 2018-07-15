<?php global $wp_manga_functions; ?>
<div class="slider__item">
    <div class="slider__thumb">
        <div class="slider__thumb_item">
            <a href="<?php echo get_the_permalink() ?>">
				<?php the_post_thumbnail( 'manga-thumb-1' ) ?>
                <div class="slider-overlay"></div>
            </a>
        </div>
    </div>
    <div class="slider__content">
        <div class="slider__content_item">
            <div class="post-title font-title">
                <h4>
                    <a href="<?php echo get_the_permalink() ?>"><?php echo get_the_title() ?></a>
                </h4>
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
							foreach ( $chapters as $c_key => $chapter ) {
								$style = $wp_manga_functions->get_reading_style();

								$manga_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $c_key, $style );
                                
								?>

								<?php if ( isset( $chapter['chapter_name'] ) ) { ?>
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
