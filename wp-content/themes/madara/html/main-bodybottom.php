<?php

	/**
	 * The Template for displaying Body Bottom Sidebar
	 *
	 * @package madara
	 */

	madara_ads_position( 'ads_after_content', 'body-bottom-ads' );

?>

<?php

	if ( is_active_sidebar( 'body_bottom_sidebar' ) ): ?>
        <div class="c-sidebar">
            <div class="body-bottom-sidebar">
                <div class="row c-row">
					<?php dynamic_sidebar( 'body_bottom_sidebar' ); ?>
                </div>
            </div>
        </div>
	<?php endif;