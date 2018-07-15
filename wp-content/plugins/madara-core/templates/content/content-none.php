<div class="no-results not-found">
    <div class="results_content">
        <div class="icon-not-found">
            <i class="ion-android-sad"></i>
        </div>
        <div class="not-found-content">
            <p>
				<?php
					if ( is_tax() ) {
						$tax = get_queried_object();
						echo sprintf( __( 'There is no Manga in this %s - %s', WP_MANGA_TEXTDOMAIN ), $tax->name, get_taxonomy( $tax->taxonomy )->label );
					} elseif ( $GLOBALS['wp_manga_functions']->is_manga_posttype_archive() && ! is_search() ) {
						esc_html_e( 'There is no Manga yet', WP_MANGA_TEXTDOMAIN );
					} elseif ( is_search() ) {
						esc_html_e( 'No matches found. Try a different search...', WP_MANGA_TEXTDOMAIN );
					}
				?>
            </p>
        </div>
    </div>
</div>
