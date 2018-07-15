<h3 class="head text-center"><?php esc_html_e( 'Manga Post Type Settings', WP_MANGA_TEXTDOMAIN ); ?></h3>

<p class="narrow text-center">
    <?php _e('The default slug for Manga Post type is <code>manga</code>, but you can change the slug of Manga Post Type to different slug. ', WP_MANGA_TEXTDOMAIN ); ?>
</p>

<p class="narrow text-center">
    <?php $url = add_query_arg(
        array(
            'post_type' => 'wp-manga',
            'page'      => 'wp-manga-settings',
        ), get_admin_url( '', 'edit.php' )
    ); ?>
    <?php esc_html_e(' If you want to skip this setting, then you can get it back in ', WP_MANGA_TEXTDOMAIN ); ?> <strong><a href="<?php echo esc_url( $url ); ?>" target="_blank">Manga > WP Manga settings</a></strong>
</p>

<table class="manga-settings">
    <tr>
        <td><strong><?php esc_html_e( 'Manga Slug', WP_MANGA_TEXTDOMAIN ) ?></strong></td>
        <td>
            <p>
                <?php
                global $wp_manga_setting;
                $manga_slug = $wp_manga_setting->get_manga_option('manga_slug', 'manga');
                ?>
                <input type="text" name="manga-slug" value="<?php echo esc_attr( $manga_slug ); ?>">
                <br />
                <span class="description"> <?php _e( 'Change slug for Manga, default slug is <code>manga</code>. Manga slug will be used for Manga Single and Manga Archive page.', WP_MANGA_TEXTDOMAIN); ?> </span>
            </p>
        </td>
    </tr>
</table>

<p class="submit-line text-center">
    <a href="<?php echo esc_url( get_admin_url() ); ?>" class="btn cancel-button"> <?php esc_html_e('Maybe later', WP_MANGA_TEXTDOMAIN ); ?> </a>
    <a href="#step-4" data-toggle="tab" class="btn main-button" data-save="manga-post-type"> <?php esc_html_e('Next', WP_MANGA_TEXTDOMAIN ); ?> </a>
</p>
