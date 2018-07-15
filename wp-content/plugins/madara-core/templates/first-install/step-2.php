<h3 class="head text-center"><?php esc_html_e( 'Manga Page Settings', WP_MANGA_TEXTDOMAIN ); ?></h3>

<p class="narrow text-center">
	<?php esc_html_e( ' By default, WP Manga plugin would generate Manga Archive Page and User Page automatically. However, you can be able to set a Single Page to be Manga Archive Page and User Page. ', WP_MANGA_TEXTDOMAIN ); ?>
</p>

<p class="narrow text-center">
	<?php $url = add_query_arg( array(
		'post_type' => 'wp-manga',
		'page'      => 'wp-manga-settings',
	), get_admin_url( '', 'edit.php' ) ); ?>
	<?php esc_html_e( ' If you want to skip this setting, then you can get it back in ', WP_MANGA_TEXTDOMAIN ); ?>
    <strong><a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php esc_html_e( 'Manga > WP Manga settings', WP_MANGA_TEXTDOMAIN ); ?></a></strong>
</p>

<table class="manga-settings">
    <tr>
        <td><strong><?php esc_html_e( 'Manga Archive Page', WP_MANGA_TEXTDOMAIN ) ?></strong></td>
        <td>
            <p>
				<?php
					$options = get_option( 'wp_manga_settings', array() );

					wp_dropdown_pages( array(
						'name'              => 'manga_archive_page',
						'show_option_none'  => __( 'Default', WP_MANGA_TEXTDOMAIN ),
						'option_none_value' => 0,
						'selected'          => isset( $options['manga_archive_page'] ) ? $options['manga_archive_page'] : 0,
					) );
					
				?>
                <br><span class="description"><?php esc_html_e( 'Choose page for Manga archive to show all manga.', WP_MANGA_TEXTDOMAIN ) ?></span>
            </p>
        </td>
    </tr>
    <tr>
        <td><strong><?php esc_html_e( 'User Page', WP_MANGA_TEXTDOMAIN ) ?></strong></td>
        <td>
            <p>
				<?php

					wp_dropdown_pages( array(
						'name'              => 'user_page',
						'show_option_none'  => __( 'Select User Page', WP_MANGA_TEXTDOMAIN ),
						'option_none_value' => 0,
						'selected'          => isset( $options['user_page'] ) ? $options['user_page'] : 0,
					) );
				?><br><span class="description">

                    <?php printf( wp_kses( __( 'A page display user\'s bookmark, history and settings. The <code>[manga-user-page]</code> short code must be on this page.', WP_MANGA_TEXTDOMAIN ), array( 'code' => array() ) ) ); ?></span>
            </p>
        </td>
    </tr>
</table>

<p class="submit-line text-center">
    <a href="<?php echo esc_url( get_admin_url() ); ?>" class="btn cancel-button"> <?php esc_html_e( 'Maybe later', WP_MANGA_TEXTDOMAIN ); ?> </a>
    <a href="#step-3" data-toggle="tab" class="btn main-button" data-save="manga-page"> <?php esc_html_e( 'Next', WP_MANGA_TEXTDOMAIN ); ?> </a>
</p>
