<?php

	if ( ! is_404() ) {

		if ( class_exists( 'WP_MANGA' ) && is_manga() ) {

			$manga_main_bottom_sidebar_background = madara_output_background_options( 'manga_main_bottom_sidebar_background' );

			$manga_main_bottom_sidebar_container = madara_output_sidebar_container_classes( 'manga_main_bottom_sidebar_container', 'container' );

			$manga_main_bottom_sidebar_spacing = madara_output_spacing_options( 'manga_main_bottom_sidebar_spacing' );

			?>

			<?php if ( is_active_sidebar( 'manga_main_bottom_sidebar' ) ) { ?>
                <div class="c-sidebar c-bottom-sidebar wp-manga" <?php echo ( $manga_main_bottom_sidebar_background != '' || $manga_main_bottom_sidebar_spacing != '' ) ? 'style="' . $manga_main_bottom_sidebar_background . $manga_main_bottom_sidebar_spacing . '"' : ''; ?> >
                    <div class="<?php echo esc_attr( $manga_main_bottom_sidebar_container ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'manga_main_bottom_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

		<?php } else {

			$main_bottom_sidebar_background = madara_output_background_options( 'main_bottom_sidebar_background' );

			$main_bottom_sidebar_container = madara_output_sidebar_container_classes( 'main_bottom_sidebar_container', 'container' );

			$main_bottom_sidebar_spacing = madara_output_spacing_options( 'main_bottom_sidebar_spacing' );

			?>

			<?php if ( is_active_sidebar( 'bottom_sidebar' ) ) { ?>
                <div class="c-sidebar c-bottom-sidebar" <?php echo ( $main_bottom_sidebar_background != '' || $main_bottom_sidebar_spacing != '' ) ? 'style="' . $main_bottom_sidebar_background . $main_bottom_sidebar_spacing . '"' : ''; ?>>
                    <div class="<?php echo esc_attr( $main_bottom_sidebar_container ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'bottom_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php

		}
	}
