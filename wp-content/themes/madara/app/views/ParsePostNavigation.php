<?php

	/**
	 * ParsePostNavigation Class
	 */

	namespace App\Views;

	class ParsePostNavigation {
		public function __construct() {

		}

		public function render( $echo = 1 ) {

			$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );

			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}

			$get_previous_post = get_previous_post();
			$get_next_post     = get_next_post();
			$thumb_size        = array( 254, 140 );

			?>

            <nav class="navigation paging-navigation">
                <div class="nav-links">
					<?php if ( is_object( $get_next_post ) ) { ?>
                        <div class="nav-next nav-button">
                            <a href="<?php echo get_permalink( $get_next_post->ID ) ?>"><?php echo esc_html__( 'NEXT POST', 'madara' ); ?></a>
                            <div class="c-blog__thumbnail c-image-hover">
                                <a href="<?php echo get_permalink( $get_next_post->ID ) ?>"> <?php echo get_the_post_thumbnail( $get_next_post->ID, $thumb_size, array( 'class' => 'img-responsive' ) ) ?> </a>
                            </div>
                            <div class="c-blog__summary">
                                <div class="post-title font-title">
                                    <h6>
                                        <a href="<?php echo get_permalink( $get_next_post->ID ) ?>"><?php echo get_the_title( $get_next_post->ID ); ?></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
					<?php } ?>

					<?php if ( is_object( $get_previous_post ) ) { ?>
                        <div class="nav-previous nav-button">
                            <a href="<?php echo get_permalink( $get_previous_post->ID ) ?>"><?php echo esc_html__( 'PREV POST', 'madara' ); ?></a>
                            <div class="c-blog__thumbnail c-image-hover">
                                <a href="<?php echo get_permalink( $get_previous_post->ID ) ?>"> <?php echo get_the_post_thumbnail( $get_previous_post->ID, $thumb_size, array( 'class' => 'img-responsive' ) ) ?> </a>
                            </div>
                            <div class="c-blog__summary">
                                <div class="post-title font-title">
                                    <h6>
                                        <a href="<?php echo get_permalink( $get_previous_post->ID ) ?>"><?php echo get_the_title( $get_previous_post->ID ); ?></a>
                                    </h6>
                                </div>
                            </div>
                        </div>
					<?php } ?>
                </div>
                <!-- .nav-links -->
            </nav>

			<?php
		}
	}