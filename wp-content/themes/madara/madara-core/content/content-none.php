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
						echo sprintf( esc_html__( 'There is no Manga in this %s - %s', 'madara' ), $tax->name, get_taxonomy( $tax->taxonomy )->label );
					} elseif ( is_manga_posttype_archive() && ! is_search() ) {
						esc_html_e( 'There is no Manga yet', 'madara' );
					} elseif ( is_search() ) {
						esc_html_e( 'No matches found. Try a different search...', 'madara' );
					}
				?>
            </p>
        </div>
    </div>
</div>
