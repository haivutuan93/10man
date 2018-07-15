<?php

	/**
	 * Hook to wrap Main Header div
	 */
	do_action( 'madara_main_header_before', 1 );

?>
    <div class="main-menu">
		<?php get_template_part( 'html/header/main-nav' ); ?>
    </div>

    <div class="search-navigation search-sidebar">

		<?php if ( is_active_sidebar( 'search_sidebar' ) ) { ?>

			<?php dynamic_sidebar( 'search_sidebar' ); ?>


		<?php } else { ?>

            <div class="search-navigation__wrap">
                <ul class="main-menu-search nav-menu">
                    <li class="menu-search">
                        <a href="javascript:;" class="open-search-main-menu"> <i class="ion-ios-search-strong"></i>
                            <i class="ion-android-close"></i> </a>

                    </li>
                </ul>
            </div>
		<?php } ?>

    </div>
    <div class="c-togle__menu">
        <button type="button" class="menu_icon__open">
            <span></span> <span></span> <span></span>
        </button>
    </div>
<?php

	/**
	 * Hook to wrap Main Header div
	 */
	do_action( 'madara_main_header_after', 1 );
