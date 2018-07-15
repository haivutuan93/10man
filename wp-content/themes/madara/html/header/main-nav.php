<?php
	/*
	 *
	 * Main Navigation
	 *
	 * */

	$menu_location = 'primary_menu';

	if ( has_nav_menu( $menu_location ) ) {
		echo '<ul class="nav navbar-nav main-navbar">';
		wp_nav_menu( array(
			'theme_location' => $menu_location,
			'container'      => false,
			'items_wrap'     => '%3$s'
		) );
		echo '</ul>';
	} else { ?>
        <ul class="nav navbar-nav main-navbar">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'madara' ) ?></a></li>
        </ul>
	<?php }
