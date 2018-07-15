<?php
	/**
	 * The Template for displaying product archives, including the main shop page which is a post type archive
	 *
	 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
	 * @version     3.3.0
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	use App\Madara;

	$madara_page_sidebar = Madara::getOption( 'woocommerce_sidebar_position', 'right' );

	get_header( 'shop' );

	/**
	 * Hook: woocommerce_before_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
	 * @hooked woocommerce_breadcrumb - 20
	 * @hooked WC_Structured_Data::generate_website_data() - 30
	 */
	do_action( 'woocommerce_before_main_content' );

?>

    <div class="c-page-content style-1">
        <div class="content-area">
            <div class="container">
                <div class="row <?php echo ( $madara_page_sidebar == 'left' ) ? 'sidebar-left' : ''; ?>">

                    <div class="main-col <?php echo ( $madara_page_sidebar != 'full' && ( is_active_sidebar( 'woo_sidebar' ) ) ) ? 'col-md-8 col-sm-8' : 'sidebar-hidden col-md-12 col-sm-12' ?>">

						<?php get_template_part( 'html/main-bodytop' ); ?>


                        <header class="woocommerce-products-header">

							<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                                <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
							<?php endif; ?>

							<?php
								/**
								 * Hook: woocommerce_archive_description.
								 *
								 * @hooked woocommerce_taxonomy_archive_description - 10
								 * @hooked woocommerce_product_archive_description - 10
								 */
								do_action( 'woocommerce_archive_description' );
							?>
                        </header>
						<?php

							if ( have_posts() ) {

								/**
								 * Hook: woocommerce_before_shop_loop.
								 *
								 * @hooked wc_print_notices - 10
								 * @hooked woocommerce_result_count - 20
								 * @hooked woocommerce_catalog_ordering - 30
								 */
								do_action( 'woocommerce_before_shop_loop' );

								woocommerce_product_loop_start();

								if ( wc_get_loop_prop( 'total' ) ) {
									while ( have_posts() ) {
										the_post();

										/**
										 * Hook: woocommerce_shop_loop.
										 *
										 * @hooked WC_Structured_Data::generate_product_data() - 10
										 */
										do_action( 'woocommerce_shop_loop' );

										wc_get_template_part( 'content', 'product' );
									}
								}

								woocommerce_product_loop_end();

								/**
								 * Hook: woocommerce_after_shop_loop.
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								do_action( 'woocommerce_after_shop_loop' );
							} else {
								/**
								 * Hook: woocommerce_no_products_found.
								 *
								 * @hooked wc_no_products_found - 10
								 */
								do_action( 'woocommerce_no_products_found' );
							}


						?>

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
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 */
	do_action( 'woocommerce_after_main_content' );


	get_footer( 'shop' );
