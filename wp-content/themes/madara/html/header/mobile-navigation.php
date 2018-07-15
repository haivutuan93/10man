<?php
	/**
	 * Mobile Navigation Template
	 * @package madara
	 */

?>

<div class="mobile-menu menu-collapse off-canvas">
    <div class="close-nav">
        <button class="menu_icon__close">
            <span></span> <span></span>
        </button>
    </div>
    <nav class="off-menu">
		<?php
			if ( has_nav_menu( 'mobile_menu' ) ) {

				wp_nav_menu( array(
					'theme_location' => 'mobile_menu',
					'container'      => false,
					'menu_class'     => 'nav navbar-nav main-navbar',
					'walker'         => new App\Plugins\Walker_Nav_Menu\Custom_Walker_Nav_Menu()
				) );

			} else {

				wp_nav_menu( array(
					'theme_location' => 'primary_menu',
					'container'      => false,
					'menu_class'     => 'nav navbar-nav main-navbar',
					'walker'         => new App\Plugins\Walker_Nav_Menu\Custom_Walker_Nav_Menu()
				) );

			}
		?>
    </nav>
</div>