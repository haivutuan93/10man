<?php

	if ( ! is_404() ) {

		if ( class_exists( 'WP_MANGA' ) && is_manga() ) {


			$manga_main_top_sidebar_container_class = madara_output_sidebar_container_classes( 'manga_main_top_sidebar_container', 'container' );
			$manga_main_top_sidebar_background      = madara_output_background_options( 'manga_main_top_sidebar_background' );
			$manga_main_top_sidebar_spacing         = madara_output_spacing_options( 'manga_main_top_sidebar_spacing' );

			$manga_main_top_second_sidebar_container_class = madara_output_sidebar_container_classes( 'manga_main_top_second_sidebar_container', 'container' );
			$manga_main_top_second_sidebar_background      = madara_output_background_options( 'manga_main_top_second_sidebar_background' );
			$manga_main_top_second_sidebar_spacing         = madara_output_spacing_options( 'manga_main_top_second_sidebar_spacing' );

			?>

			<?php if ( is_active_sidebar( 'manga_main_top_sidebar' ) ) { ?>
                <div class="c-sidebar c-top-sidebar wp-manga" <?php echo ($manga_main_top_sidebar_background != '' || $manga_main_top_sidebar_spacing != '') ? 'style="' . $manga_main_top_sidebar_background . $manga_main_top_sidebar_spacing . '"' : ''; ?> >
                    <div class="<?php echo esc_attr( $manga_main_top_sidebar_container_class ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'manga_main_top_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php if ( is_active_sidebar( 'manga_main_top_second_sidebar' ) ) { ?>
                <div class="c-sidebar c-top-second-sidebar wp-manga" <?php echo ($manga_main_top_second_sidebar_background != '' || $manga_main_top_second_sidebar_spacing != '') ? 'style="' . $manga_main_top_second_sidebar_background . $manga_main_top_second_sidebar_spacing . '"' : ''; ?> >
                    <div class="<?php echo esc_attr( $manga_main_top_second_sidebar_container_class ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'manga_main_top_second_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

		<?php } else {


			$main_top_sidebar_container_class = madara_output_sidebar_container_classes( 'main_top_sidebar_container', 'container' );
			$main_top_sidebar_background      = madara_output_background_options( 'main_top_sidebar_background' );
			$main_top_sidebar_spacing         = madara_output_spacing_options( 'main_top_sidebar_spacing' );

			$main_top_second_sidebar_container_class = madara_output_sidebar_container_classes( 'main_top_second_sidebar_container', 'container' );
			$main_top_second_sidebar_background      = madara_output_background_options( 'main_top_second_sidebar_background' );
			$main_top_second_sidebar_spacing         = madara_output_spacing_options( 'main_top_second_sidebar_spacing' );

			?>

			<?php if ( is_active_sidebar( 'top_sidebar' ) ) { ?>
                <div class="c-sidebar c-top-sidebar" <?php echo( $main_top_sidebar_background != '' || $main_top_sidebar_spacing != '') ? 'style="' . $main_top_sidebar_background . $main_top_sidebar_spacing . '"' : ''; ?> >
                    <div class="<?php echo esc_attr( $main_top_sidebar_container_class ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'top_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php if ( is_active_sidebar( 'top_second_sidebar' ) ) { ?>
                <div class="c-sidebar c-top-second-sidebar" <?php echo ($main_top_second_sidebar_background != '' || $main_top_second_sidebar_spacing != '') ? 'style="' . $main_top_second_sidebar_background . $main_top_second_sidebar_spacing . '"' : ''; ?> >
                    <div class="<?php echo esc_attr( $main_top_second_sidebar_container_class ); ?>">
                        <div class="row c-row">
							<?php dynamic_sidebar( 'top_second_sidebar' ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php

		}
	}
