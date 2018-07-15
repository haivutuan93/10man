<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117846139-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117846139-1');
</script>

	<?php 
    $is_home = is_front_page();
    if ($is_home) { ?>
    <meta name="description" content="Kho truyện tranh, truyện chữ lớn nhất Việt Nam. Đọc hững bộ truyện phổ biến: conan, 7 viên ngọc rồng, naruto... Những bộ truyện tranh, truyện chữ chọn lọc mới nhất, hay nhất tại đây. Trải nghiệm đọc truyện tốt nhất tại 10manga.com" />
    <?php } ?>
	<?php
		/**
		 * The Header for our theme.
		 *
		 * Displays all of the <head> section and everything up till <div id="content">
		 *
		 * @package madara
		 */

		use App\Madara;

		$madara_header_style = apply_filters( 'madara_header_style', Madara::getOption( 'header_style', 1 ) );

	?>


	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( ! is_404() ) { ?>

<?php

	/**
	 * madara_before_body hook
	 *
	 * @hooked madara_before_body - 10
	 *
	 * @author
	 * @since 1.0
	 * @code     Madara
	 */
	do_action( 'madara_before_body' );

?>

<div class="wrap">
    <div class="body-wrap">
        <header class="site-header">
            <div class="c-header__top">
                <ul class="search-main-menu">
                    <li>
                        <form id="blog-post-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                            <input type="text" placeholder="<?php echo esc_html__( 'Search...', 'madara' ); ?>" name="s" value="">
                            <input type="submit" value="<?php esc_html_e( 'Search', 'madara' ); ?>">
                            <div class="loader-inner line-scale">
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </form>
                    </li>
                </ul>
                <div class="main-navigation <?php echo esc_attr( $madara_header_style == 3 ? 'style-2' : 'style-1'); ?> ">
                    <div class="container <?php echo esc_attr( $madara_header_style == '2' ? 'custom-width' : '' ); ?>">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="main-navigation_wrap">
                                    <div class="wrap_branding">
                                        <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
											<?php $logo = Madara::getOption( 'logo_image', '' ) == '' ? esc_url( get_parent_theme_file_uri() ) . '/images/logo.png' : Madara::getOption( 'logo_image', '' ); ?>
                                            <img class="img-responsive" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"/>
                                        </a>
                                    </div>

									<?php get_template_part( 'html/header/main-header-1' ); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

			<?php get_template_part( 'html/header/mobile-navigation' ); ?>

			<?php get_template_part( 'html/header/sub-header-nav' ); ?>

        </header>

		<?php get_template_part( 'html/main-top' ); ?>

        <div class="site-content">
<?php }
