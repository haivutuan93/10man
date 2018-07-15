<?php
	/**
	 * The Template for displaying all single products
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
	 *
	 * HOWEVER, on occasion WooCommerce will need to update template files and you
	 * (the theme developer) will need to copy the new files to your theme to
	 * maintain compatibility. We try to do this as little as possible, but it does
	 * happen. When this occurs the version of the template file will be bumped and
	 * the readme will list any important changes.
	 *
	 * @see        https://docs.woocommerce.com/document/template-structure/
	 * @author        WooThemes
	 * @package    WooCommerce/Templates
	 * @version     1.6.4
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}

	use App\Madara;

	$madara_page_sidebar = Madara::getOption( 'woocommerce_sidebar_position', 'right' );

	get_header( 'shop' ); ?>

<?php
	/**
	 * woocommerce_before_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 */
	do_action( 'woocommerce_before_main_content' );
?>

    <div class="c-page-content style-1">
        <div class="content-area">
            <div class="container">
                <div class="row <?php echo ( $madara_page_sidebar == 'left' ) ? 'sidebar-left' : ''; ?>">

                    <div class="main-col <?php echo ( $madara_page_sidebar != 'full' && ( is_active_sidebar( 'woo_sidebar' ) ) ) ? 'col-md-8 col-sm-8' : 'sidebar-hidden col-md-12 col-sm-12' ?>">

						<?php get_template_part( 'html/main-bodytop' ); ?>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php wc_get_template_part( 'content', 'single-product' ); ?>

						<?php endwhile; // end of the loop. ?>

                        <?php get_template_part( 'html/main-bodybottom' ); ?>

                    </div>

					<?php
						if ( $madara_page_sidebar != 'full' && ( is_active_sidebar( 'woo_sidebar' ) ) ) {
							?>
                            <div class="sidebar-col col-md-4 col-sm-4">
								<?php get_sidebar( 'woocommerce' ); ?>
                            </div>
							<?php
						}
					?>

                </div>

            </div>
        </div>
    </div>

<?php
	/**
	 * woocommerce_after_main_content hook.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );
?>

<?php get_footer( 'shop' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
