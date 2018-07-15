<?php

	/**
	 * The Template for displaying Body Top Sidebar
	 *
	 * @package madara
	 */

	madara_ads_position( 'ads_before_content', 'body-top-ads' );

?>

<?php if ( is_active_sidebar( 'body_top_sidebar' ) ): ?>
    <div class="c-sidebar">
        <div class="body-top-sidebar">
            <div class="row c-row">
				<?php dynamic_sidebar( 'body_top_sidebar' ); ?>
            </div>
        </div>
    </div>
<?php endif;