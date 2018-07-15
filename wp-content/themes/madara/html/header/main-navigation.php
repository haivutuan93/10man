<?php
	/**
	 * Main Navigation Template
	 * @package madara
	 */

	use App\Madara;

?>

<section class="c-main-navigation sticky-<?php echo esc_attr(Madara::getOption( 'nav_sticky_schema', 'dark' )); ?>">
    <div class="container c-container">
        <div class="row c-row">
            <div class="c-main-navigation__inner">
                <div class="col-md-3 col-xs-12 c-column">
                    <div class="c-branding">
                        <div id="site-branding" class="site-branding pull-left">
                            <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php

									$madara_logo = Madara::getOption( 'desktop_logo_image', '' );
									if ( $madara_logo == '' ) {
										$madara_logo = get_parent_theme_file_uri( '/images/logo.png' );
									}

									$madara_logo = apply_filters( 'madara_logo_url', $madara_logo );
								?>
                                <img src="<?php echo esc_url( $madara_logo ); ?>" alt="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 col-xs-12 c-column">
                    <div class="c-main-menu pull-right">
                        <nav id="site-navigation" class="navbar navbar-inverse main-menu">
                            <button class="menu-toggle"><?php esc_html_e( 'Primary Menu', 'madara' ); ?></button>
							<?php
								if ( has_nav_menu( 'primary_menu' ) ) {

									wp_nav_menu( array(
										'theme_location' => 'primary_menu',
										'container'      => false,
										'menu_class'     => 'nav navbar-nav',
										'walker'         => new App\Plugins\Walker_Nav_Menu\Custom_Walker_Nav_Menu()
									) );

								} else { ?>

                                    <li>
                                        <a href="<?php echo esc_url( home_url( '/' ) );; ?>"><?php esc_html_e( 'Home', 'madara' ) ?></a>
                                    </li>

									<?php wp_list_pages( 'depth=1&number=4&title_li=' ); ?>

								<?php } ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>