<?php
	/**
	 * The template for displaying the footer.
	 *
	 * Contains the closing of the #content div and all content after
	 *
	 * @package madara
	 */

	use App\Madara;

	$madara_copyright = Madara::getOption( 'copyright', '' );

	$madara_ParseSocials = new App\Views\ParseSocials();

	$madara_social_accounts = $madara_ParseSocials->renderSocialAccounts( false );

	$manga_hover_details = Madara::getOption( 'manga_hover_details', 'off' );

	if ( ! is_404() ) {

		?>
<div id="footer-sidebar" class="">
<div id="footer-sidebar1" class="col-lg-10 col-lg-offset-1 col-md-12 col-md-offset-0 col-xs-12 col-xs-offset-0 col-sm-12 col-sm-offset-0" style="padding-top: 30px; padding-bottom: 15px; margin-bottom: 10px; margin-top: 10px;">
<?php
if(is_active_sidebar('footer-sidebar-1')){
dynamic_sidebar('footer-sidebar-1');
}
?>
</div>
<div id="footer-sidebar2">
<?php
if(is_active_sidebar('footer-sidebar-2')){
dynamic_sidebar('footer-sidebar-2');
}
?>
</div>
<div id="footer-sidebar3">
<?php
if(is_active_sidebar('footer-sidebar-3')){
dynamic_sidebar('footer-sidebar-3');
}
?>
</div>
</div>
        </div><!-- <div class="site-content"> -->

		<?php get_template_part( 'html/main-bottom' ); ?>

        <footer class="site-footer">

			<?php madara_ads_position( 'ads_footer', 'footer-ads col-md-12' ); ?>

			<?php if ( $madara_social_accounts && $madara_social_accounts != '' ) { ?>
                <div class="top-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="wrap_social_account">
									<?php echo wp_kses_post( $madara_social_accounts ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php } ?>

            <div class="bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

							<?php
								if ( has_nav_menu( 'footer_menu' ) ) {
									echo '<div class="nav-footer"><ul class="list-inline font-nav">';
									wp_nav_menu( array(
										'theme_location' => 'footer_menu',
										'container'      => false,
										'items_wrap'     => '%3$s',
										'depth'          => '1',
									) );
									echo '</ul></div>';
								}
							?>

                            <div class="copyright">
								<?php
									$madara_copyright = Madara::getOption( 'copyright', '' );
									if ( $madara_copyright != '' ) {
										echo '<p>' . wp_kses_post( $madara_copyright ) . '</p>';
									} else {
										echo '<p>' . esc_html__( '&copy; 2018. Bản quyền thuộc về 10manga.com', 'madara' ) . '</p>';
									}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </footer>
        <?php
        madara_ads_position( 'ads_wall_left', 'wall-ads-control wall-ads-left' );
        madara_ads_position( 'ads_wall_right', 'wall-ads-control wall-ads-right' );
        ?>

		<?php if ( $manga_hover_details == 'on' ) { ?>
            <div id="hover-infor"></div>
		<?php } ?>

        </div> <!-- class="wrap" --></div> <!-- class="body-wrap" -->

	<?php } ?>


<?php wp_footer(); ?>

</body>
<!-- <script>
            //var proxyServer = "http://images2-focus-opensocial.googleusercontent.com/gadgets/proxy?container=focus&gadget=a&no_expand=1&resize_h=0&rewriteMime=image/*&url=";
            window.onload = function () {
                jQuery(".chapter-video-frame img").each(function () {
                    var imgSrc = jQuery(this).attr('src');
                    if (imgSrc.search("focus-opensocial.googleusercontent") > 0) {
                        var imageUrl = imgSrc.split('&url=').pop();
                        imgSrc = decodeURIComponent(imageUrl);
                    } 
                    jQuery(this).attr("src", imgSrc);
                });
            };
        </script> -->
</html>