<div class="manga-search">
    <div class="c-search-header__wrapper">
        <div class="container">
            <div class="search-content">
                <form role="search" method="get" class="manga-search-form search-form" action="<?php echo site_url('/'); ?>">
                    <label>
                        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', WP_MANGA_TEXTDOMAIN ); ?></span>
                        <input type="search" class="manga-search-field search-field" placeholder="<?php esc_html_e( 'Search ...', WP_MANGA_TEXTDOMAIN ); ?>" value="" name="s">
                        <input type="hidden" value="wp-manga" name="post_type">
                    </label>
                    <input type="submit" class="search-submit" value="<?php esc_html_e( 'Search', WP_MANGA_TEXTDOMAIN ); ?>">
                </form>
                <a href="<?php echo add_query_arg( array( 's' => '', 'post_type' => 'wp-manga' ), site_url('/') ); ?>" class="btn-search-adv collapsed"><?php esc_html_e( 'Advanced', WP_MANGA_TEXTDOMAIN ); ?></a>
            </div>
        </div>
    </div>
</div>