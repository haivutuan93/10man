
<ul class="main-menu-search nav-menu">
    <li class="menu-search">
        <a href="javascript:;" class="open-search-main-menu"> <i class="ion-ios-search-strong"></i>
            <i class="ion-android-close"></i> </a>
        <ul class="search-main-menu">
            <li>
                <form class="manga-search-form search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
                    <input class="manga-search-field" type="text" placeholder="<?php echo esc_html__( 'Search...', 'madara' ); ?>" name="s" value="">
                    <input type="hidden" name="post_type" value="wp-manga"> <i class="ion-ios-search-strong"></i>
                    <div class="loader-inner ball-clip-rotate-multiple">
                        <div></div>
                        <div></div>
                    </div>
                    <input type="submit" value="<?php esc_html_e( 'Search', 'madara' ); ?>">
                </form>
            </li>
        </ul>
    </li>
</ul>
