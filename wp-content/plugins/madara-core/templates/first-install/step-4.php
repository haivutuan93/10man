<h3 class="head text-center"><?php esc_html_e( 'Configure WP Manga Storage', WP_MANGA_TEXTDOMAIN ); ?></h3>

<p class="narrow text-center">
    <?php _e('Beside Local storage, WP Manga integrate 3 popular storage services to store Manga data for your website. Includes <strong>Blogspot (Google Photos), Imgur Settings, Amazon S3 services.</strong>.', WP_MANGA_TEXTDOMAIN ); ?>
</p>

<p class="narrow text-center">
    <?php $url = add_query_arg(
        array(
            'post_type' => 'wp-manga',
            'page'      => 'wp-manga-storage',
        ), get_admin_url( '', 'edit.php' )
    ); ?>
    <?php esc_html_e('You will find the settings for this in ', WP_MANGA_TEXTDOMAIN ); ?> <strong><a href="<?php echo esc_url( $url ); ?>" target="_blank">Manga > WP Manga Storage</a></strong>
</p>

<p class="submit-line text-center">
    <a href="<?php echo esc_url( get_admin_url() ); ?>" class="btn cancel-button"> <?php esc_html_e('Maybe later', WP_MANGA_TEXTDOMAIN ); ?> </a>
    <a href="#step-5" data-toggle="tab" class="btn main-button"> <?php esc_html_e('Next', WP_MANGA_TEXTDOMAIN ); ?> </a>
</p>
