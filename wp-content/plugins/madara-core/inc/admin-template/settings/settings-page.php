<?php

    $options             = get_option( 'wp_manga_settings', array() );
    $paging_style        = isset( $options['paging_style'] ) ? $options['paging_style'] : 'load-more';
    $user_page           = isset( $options['user_page'] ) ? $options['user_page'] : '';
    $manga_archive_page  = isset( $options['manga_archive_page'] ) ? $options['manga_archive_page'] : 0;
    $enable_comment      = isset( $options['enable_comment'] ) ? $options['enable_comment'] : '';
    $related_manga       = isset( $options['related_manga']['state'] ) ? $options['related_manga']['state'] : '1';
    $single_manga_seo    = isset( $options['single_manga_seo'] ) ? $options['single_manga_seo'] : '1';
    $related_by          = isset( $options['related_manga']['related_by'] ) ? $options['related_manga']['related_by'] : 'related_genre';
    $manga_slug          = isset( $options['manga_slug'] ) ? $options['manga_slug'] : 'manga';
    $manga_post_type_archive_slug = isset( $options['manga_post_type_archive_slug'] ) ? $options['manga_post_type_archive_slug'] : $manga_slug;
    $loading_bootstrap   = isset( $options['loading_bootstrap'] ) ? $options['loading_bootstrap'] : '1';
    $loading_slick       = isset( $options['loading_slick'] ) ? $options['loading_slick'] : '1';
    $loading_fontawesome = isset( $options['loading_fontawesome'] ) ? $options['loading_fontawesome'] : '1';
    $loading_ionicon     = isset( $options['loading_ionicon'] ) ? $options['loading_ionicon'] : '1';
    $admin_hide_bar      = isset( $options['admin_hide_bar'] ) ? $options['admin_hide_bar'] : '0';
    $default_storage 	 = isset( $options['default_storage'] ) ? $options['default_storage'] : 'local';
    $hosting_selection = isset( $options['hosting_selection'] ) ? $options['hosting_selection'] : '1';
    ?>
    <div class="wrap wp-manga-wrap">
        <h2><?php echo get_admin_page_title(); ?></h2>
        <form method="post">
            <h2 class="title"><?php esc_html_e( 'Manga Page Settings', WP_MANGA_TEXTDOMAIN ); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Paging Style', WP_MANGA_TEXTDOMAIN ) ?></th>
                    <td>
                        <p>
                            <select name="wp_manga_settings[paging_style]" type="text" class="large-text">
                                <option value="default" <?php selected( 'default', $paging_style, true ) ?>><?php esc_html_e( 'Default', WP_MANGA_TEXTDOMAIN ) ?></option>
                                <option value="load-more" <?php selected( 'load-more', $paging_style, true ) ?>><?php esc_html_e( 'Load More Button', WP_MANGA_TEXTDOMAIN ) ?></option>
                            </select>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Manga Archive Page', WP_MANGA_TEXTDOMAIN ) ?></th>
                    <td>
                        <p>
                            <?php
                                wp_dropdown_pages( array(
                                    'name'              => 'wp_manga_settings[manga_archive_page]',
                                    'show_option_none'  => __( 'Default', WP_MANGA_TEXTDOMAIN ),
                                    'option_none_value' => 0,
                                    'selected'          => $manga_archive_page,
                                ) );
                            ?><br><span class="description"><?php _e( 'Choose page for Manga archive to show all manga.', WP_MANGA_TEXTDOMAIN ) ?></span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'User Page', WP_MANGA_TEXTDOMAIN ) ?></th>
                    <td>
                        <p>
                            <?php
                                wp_dropdown_pages( array(
                                    'name'              => 'wp_manga_settings[user_page]',
                                    'show_option_none'  => __( 'Select User Page', WP_MANGA_TEXTDOMAIN ),
                                    'option_none_value' => 0,
                                    'selected'          => $user_page,
                                ) );
                            ?><br><span class="description"><?php _e( 'A page display user\'s bookmark, history and settings. The <code>[manga-user-page]</code> short code must be on this page.','aw-twitch-press' ) ?></span>
                        </p>
                    </td>
                </tr>
            </table>
            <h2 class="title"><?php esc_html_e( 'Single Manga Settings', WP_MANGA_TEXTDOMAIN ); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e( 'Hosting Selection', WP_MANGA_TEXTDOMAIN ) ?></th>
                    <td>
                        <p>
                            <label for="hosting_selection">
                                <input type="checkbox" id="hosting_selection" name="wp_manga_settings[hosting_selection]" value="1" <?php checked( 1, $hosting_selection, true ); ?>>
                                <?php esc_html_e( 'Show Hosting Selection for Manga', WP_MANGA_TEXTDOMAIN ) ?>
                            </label>
                            <br />
                            <span class="description"> <?php _e( 'Uncheck to hide Hosting Selection. Hosting Selection should be hide if you only use one hosting for your Manga', WP_MANGA_TEXTDOMAIN); ?> </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Manga Comment', WP_MANGA_TEXTDOMAIN ) ?></th>
                    <td>
                        <p>
                            <label for="enable_comment">
                                <input type="checkbox" id="enable_comment" name="wp_manga_settings[enable_comment]" value="1" <?php checked( 1, $enable_comment, true ); ?>>
                                <?php esc_html_e( 'Enable Comment for manga', WP_MANGA_TEXTDOMAIN ) ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Related Manga', WP_MANGA_TEXTDOMAIN ); ?></th>
                    <td>
                        <p>
                            <label for="related-manga">
                                <input type="checkbox" name="wp_manga_settings[related_manga][state]" value="1" <?php checked( 1, $related_manga, true ); ?> id="related-manga">
                                <?php esc_html_e( 'Enable Related Manga', WP_MANGA_TEXTDOMAIN ); ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'Related by', WP_MANGA_TEXTDOMAIN ); ?></th>
                    <td>
                        <p>
                            <select name="wp_manga_settings[related_manga][related_by]">
                                <option value="related_author" <?php selected( $related_by, 'related_author' ); ?> ><?php esc_html_e( 'Author', WP_MANGA_TEXTDOMAIN );?></option>
                                <option value="related_year" <?php selected( $related_by, 'related_year' ); ?>><?php esc_html_e( 'Release Year', WP_MANGA_TEXTDOMAIN ); ?></option>
                                <option value="related_artist" <?php selected( $related_by, 'related_artist' ); ?>><?php esc_html_e( 'Artists', WP_MANGA_TEXTDOMAIN ); ?></option>
                                <option value="related_genre" <?php selected( $related_by, 'related_genre' ); ?>><?php esc_html_e( 'Genres', WP_MANGA_TEXTDOMAIN ); ?></option>
                            </select>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e( 'SEO', WP_MANGA_TEXTDOMAIN ); ?></th>
                    <td>
                        <p>
                            <label for="related-manga">
                                <input type="checkbox" name="wp_manga_settings[single_manga_seo]" value="1" <?php checked( 1, $single_manga_seo, true ); ?> id="related-manga">
                                <?php esc_html_e( 'Add website name to meta title tag & meta keywords tag', WP_MANGA_TEXTDOMAIN ); ?>
                            </label>
                        </p>
                    </td>
                </tr>
            </table>
            <h2 class="title"><?php esc_html_e( 'Manga Post Type Settings', WP_MANGA_TEXTDOMAIN ); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Manga Single Slug', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <input type="text" name="wp_manga_settings[manga_slug]" value="<?php echo esc_attr( $manga_slug ); ?>">
                            <br />
                            <span class="description"> <?php _e( 'Change slug for Single Manga, default slug is <strong> manga </strong>.', WP_MANGA_TEXTDOMAIN); ?> </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Manga Post Type Archive Slug', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <input type="text" name="wp_manga_settings[manga_post_type_archive_slug]" value="<?php echo esc_attr( $manga_post_type_archive_slug ); ?>">
                            <br />
                            <span class="description"> <?php _e( 'Change slug for Post Type Archive Manga Page, default slug is <strong> manga </strong>.', WP_MANGA_TEXTDOMAIN); ?> </span>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Manga Default Storage', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <select name="wp_manga_settings[default_storage]">
                                <?php
                                    $available_host = $GLOBALS['wp_manga']->get_available_host();
                                    foreach( $available_host as $host ) {
                                        ?>
                                <option value="<?php echo esc_attr( $host['value'] ); ?>" <?php selected( $default_storage, $host['value'] ); ?>><?php echo esc_attr( $host['text'] ); ?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                            <br />
                            <span class="description"> <?php _e( 'Change default storage to upload Manga', WP_MANGA_TEXTDOMAIN); ?> </span>
                        </p>
                    </td>
                </tr>
            </table>
            <h2 class="title"><?php esc_html_e( 'Manga General Settings', WP_MANGA_TEXTDOMAIN ); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Loading Bootstrap', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <label for="loading-bootstrap">
                                <input type="checkbox" name="wp_manga_settings[loading_bootstrap]" <?php checked( $loading_bootstrap, '1' ); ?> value="1" id="loading-bootstrap">
                                <?php _e( 'Option to turn off loading Bootstrap', WP_MANGA_TEXTDOMAIN); ?>
                                <br />
                                <span class="description"> <?php _e( 'Turn off loading Bootstrap might break Manga pages layout. However, in some cases, your theme already has Bootstrap, then you can switch this off to avoid conflicts.', WP_MANGA_TEXTDOMAIN); ?> </span>
                                <br />
                                <span class="description"> <?php _e( 'By default, plugin would check if your theme already has Bootstrap and turn off this.', WP_MANGA_TEXTDOMAIN); ?> </span>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Loading Slick', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <label for="loading-slick">
                                <input type="checkbox" name="wp_manga_settings[loading_slick]" <?php checked( $loading_slick, '1' ); ?> value="1" id="loading-slick">
                                <?php _e( 'Option to turn off loading slick', WP_MANGA_TEXTDOMAIN); ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Loading FontAwesome', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <label for="loading-fontawesome">
                                <input type="checkbox" name="wp_manga_settings[loading_fontawesome]" <?php checked( $loading_fontawesome, '1' ); ?> value="1" id="loading-fontawesome">
                                <?php _e( 'Option to turn off loading FontAwesome', WP_MANGA_TEXTDOMAIN); ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Loading IonIcons', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <label for="loading-ionicon">
                                <input type="checkbox" name="wp_manga_settings[loading_ionicon]" <?php checked( $loading_ionicon, '1' ); ?> value="1" id="loading-ionicon">
                                <?php _e( 'Option to turn off loading IonIcons', WP_MANGA_TEXTDOMAIN); ?>
                            </label>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Admin Bar', WP_MANGA_TEXTDOMAIN ); ?>
                    </th>
                    <td>
                        <p>
                            <label for="loading-ionicon">
                                <input type="checkbox" name="wp_manga_settings[admin_hide_bar]" <?php checked( $admin_hide_bar, '1' ); ?> value="1" id="admin-hide-bar">
                                <?php _e( 'Hide Admin Bar for Administrator', WP_MANGA_TEXTDOMAIN); ?>
                            </label>
                            <br />
                            <span class="description"> <?php _e( 'By default, Admin Bar would be hidden for all user roles but Administrator. This option will let you hide Admin Bar for Administrator role.', WP_MANGA_TEXTDOMAIN); ?> </span>
                        </p>
                    </td>
                </tr>
            </table>
            <?php do_action('after_madara_settings_page'); ?>
            <button type="submit" class="button button-primary"><?php esc_attr_e( 'Save Changes', WP_MANGA_TEXTDOMAIN ) ?></button>
        </form>
    </div>
