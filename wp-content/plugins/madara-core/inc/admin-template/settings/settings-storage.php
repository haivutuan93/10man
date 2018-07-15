<?php

    $options = get_option( 'wp_manga', array() );
    // imgur
    $imgur_client_id     = isset( $options['imgur_client_id'] ) ? $options['imgur_client_id'] : '';
    $imgur_client_secret = isset( $options['imgur_client_secret'] ) ? $options['imgur_client_secret'] : '';

    $google_client_id     = isset( $options['google_client_id'] ) ? $options['google_client_id'] : '';
    $google_client_secret = isset( $options['google_client_secret'] ) ? $options['google_client_secret'] : '';
    $google_redirect      = isset( $options['google_redirect'] ) ? $options['google_redirect'] : '';
    $google_refreshtoken  = get_option('wp_manga_google_refreshToken');

    $amazon_s3_access_key    = isset( $options['amazon_s3_access_key'] ) ? $options['amazon_s3_access_key'] : '';
    $amazon_s3_access_secret = isset( $options['amazon_s3_access_secret'] ) ? $options['amazon_s3_access_secret'] : '';
    $amazon_s3_region        = isset( $options['amazon_s3_region'] ) ? $options['amazon_s3_region'] : '';
    ?>
    <div class="wrap wp-manga-wrap">
        <h2>
            <?php echo get_admin_page_title(); ?>
        </h2>
        <form method="post">
            <h2>
                <?php esc_html_e( 'Blogspot Settings', WP_MANGA_TEXTDOMAIN ) ?>
                <span class="wp-manga-tooltip dashicons dashicons-editor-help"><span class="wp-manga-tooltip-text"><?php esc_html_e( ' - You can start using this upload feature when you see the Authorizing success display.', WP_MANGA_TEXTDOMAIN ) ?>
                        <br><?php esc_html_e( ' - Allow to upload only Image file type at this time.', WP_MANGA_TEXTDOMAIN ) ?></span></span>
            </h2>
            <p class="blogspot-setup">
                <strong><?php esc_html_e( '* For Blogspot Api register :', WP_MANGA_TEXTDOMAIN ); ?></strong>
                <br>
                <?php esc_html_e( ' - You need to create a Oauth Client ID Credential and when setting, remember to put the redirect Url to your website. ', WP_MANGA_TEXTDOMAIN ); ?>
            </p>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Client ID', WP_MANGA_TEXTDOMAIN ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[google_client_id]" type="text" class="large-text" value="<?php echo esc_attr( $google_client_id ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'You can register the client ID at', WP_MANGA_TEXTDOMAIN ); ?>
                            <a href="https://console.developers.google.com/" target="_blank">
                                <?php esc_html_e( 'developers.google.com', WP_MANGA_TEXTDOMAIN ); ?>
                            </a>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Client Secret', WP_MANGA_TEXTDOMAIN ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[google_client_secret]" type="text" class="large-text" value="<?php echo esc_attr( $google_client_secret ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'You will need Client Secret to Authorize and Upload to blogspot function', WP_MANGA_TEXTDOMAIN ) ?>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Redirect URL', WP_MANGA_TEXTDOMAIN ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[google_redirect]" type="text" class="large-text" value="<?php echo esc_url( $google_redirect ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'Redirect URL need to match Credential\'s redirect URL when creating API Credential ', WP_MANGA_TEXTDOMAIN ) ?>
                        <p></p>
                    </td>
                </tr>
                <?php if( !empty( $google_refreshtoken )  ) { ?>
                    <tr>
                        <th scope="row">
                            <?php esc_html_e( 'Refresh Token', WP_MANGA_TEXTDOMAIN ) ?>
                        </th>
                        <td>
                            <p>
                                <input name="google_refreshtoken" type="text" class="large-text" value="<?php echo esc_attr( $google_refreshtoken ); ?>">
                            <p class="description">
                                <strong><?php esc_html_e( 'Google Refresh Token only being provided at the first time Authorizing the App, so you should remember or save the token to use the client ID again.', WP_MANGA_TEXTDOMAIN ) ?></strong>
                            <p>
                            <p class="description">
                                <strong><?php esc_html_e( 'Also if you have saved the Refresh Token along with Client ID and Secret, you can just fill all the details and don\'t need to authorize again. ', WP_MANGA_TEXTDOMAIN ) ?></strong>
                            <p>
                            <p class="description">
                                <?php esc_html_e( 'This will be auto generate when Authorize Process success.', WP_MANGA_TEXTDOMAIN ) ?>
                            <p></p>
                        </td>
                    </tr>
                <?php } ?>
                <?php
                    $scope   = 'https://picasaweb.google.com/data/';
                    $secure  = false;
                    $session = true;
                ?>
                <tr>
                    <th scope="row">
                        <a id="google-authorize" href="https://accounts.google.com/o/oauth2/v2/auth?scope=<?php echo esc_attr( $scope ); ?>&amp;client_id=<?php echo esc_attr( $google_client_id ); ?>&amp;redirect_uri=<?php echo esc_url( $google_redirect ); ?>&amp;response_type=code&amp;access_type=offline&amp;state=picasa&amp;include_granted_scopes=true&amp;prompt=consent">
                            <?php esc_html_e( 'Authorize', WP_MANGA_TEXTDOMAIN ) ?>
                        </a>
                    </th>
                    <td>
                        <p>
                            <?php
                            if( !empty( $google_refreshtoken )  ){
                                if ( get_transient('google_authorized') ) { ?>
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php esc_html_e( 'Authorizing Success', WP_MANGA_TEXTDOMAIN );
                                }else{
                                    $error_msg = get_transient( 'google_authorization_error' );
                                    ?>
                                    <span class="dashicons dashicons-dismiss"></span>
                                    <?php esc_html_e( 'Authorizing Failed. ', WP_MANGA_TEXTDOMAIN );

                                    if( $error_msg ){
                                        echo esc_html( $error_msg );
                                    }
                                }
                            }
                            ?>
                        </p>
                    </td>
                </tr>
                <?php if( !get_option('_wp_manga_is_blogspot_replaced' ) ){ ?>
                    <tr>
                        <th scope="row">
                            <?php esc_html_e( 'Replace Blogspot URL', WP_MANGA_TEXTDOMAIN ) ?>
                        </th>
                        <td>
                            <div class="replace-blogspot-wrapper">
                                <button class="button" id="replace-blogspot-url">Replace all Images URL</button>
                                <div class="spinner"></div>
                            </div>
                            <p class="description">
                                <?php esc_html_e('From Madara version 1.3, Madara-Core plugin use Blogspot URL for images instead of Picasa URL as before. This button would help you to replace all images Picasa URL with Blogspot URL.'); ?>
                            </p>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            <h2>
                <?php esc_html_e( 'Imgur Settings', WP_MANGA_TEXTDOMAIN ) ?>
                <span class="wp-manga-tooltip dashicons dashicons-editor-help"><span class="wp-manga-tooltip-text"><?php esc_html_e( ' - API setting for storage in Imgur.com', WP_MANGA_TEXTDOMAIN ) ?></span></span>
            </h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Client ID', WP_MANGA_TEXTDOMAIN ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[imgur_client_id]" class="large-text" value="<?php echo esc_attr( $imgur_client_id ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'You can register the client ID at ', WP_MANGA_TEXTDOMAIN ) ?>
                            <a href="https://api.imgur.com/oauth2/addclient" target="_blank">
                                <?php esc_html_e( 'Imgur.com', WP_MANGA_TEXTDOMAIN ) ?>
                            </a>
                        <p>
                        <p class="description">
                            <?php esc_html_e( 'Note that you need to create a credential with callback to your website, also the the Redirect URL should be your website ', WP_MANGA_TEXTDOMAIN ) ?>
                            <span class="dashicons dashicons-arrow-down"></span> <span class="eg-detail">
                                    <img src="<?php echo WP_MANGA_URI . 'assets/img/imgur-guide.png' ?>">
                                </span>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Client Secret', WP_MANGA_TEXTDOMAIN ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[imgur_client_secret]" class="large-text" value="<?php echo esc_attr( $imgur_client_secret ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'You will need Client Secret to Authorize and Upload to imgur function', WP_MANGA_TEXTDOMAIN ) ?>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <a id="imgur-authorize" href="https://api.imgur.com/oauth2/authorize?response_type=code&amp;state=imgur&amp;client_id=<?php echo esc_attr( $imgur_client_id ); ?>" id="imgur-authorize">
                            <?php esc_html_e( 'Authorize', WP_MANGA_TEXTDOMAIN ) ?>
                        </a>
                    </th>
                    <td>
                        <p>
                            <?php
                                $imgur_refreshtoken = get_option( 'wp_manga_imgur_refreshToken', null );
                                if ( $imgur_refreshtoken && $imgur_client_id ) { ?>
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php esc_html_e( 'Authorizing Success', WP_MANGA_TEXTDOMAIN ) ?><?php }

                            ?>
                        </p>
                    </td>
                </tr>


            </table>

            <h2>
                <?php esc_html_e( 'Amazon S3 settings', 'wp-manga' ) ?>
                <span class="wp-manga-tooltip dashicons dashicons-editor-help"><span class="wp-manga-tooltip-text"><?php esc_html_e( ' - If your upload not success, please check your region when create account need to be same as your bucket region.', 'wp-manga' ); ?>
                        <br><?php esc_html_e( ' - Allow to upload all files.', 'wp-manga' ) ?></span></span>
            </h2>
            <p class="amazone-setup">
                <strong><?php esc_html_e( '* For Amazon S3 set up :', 'wp-manga' ); ?></strong> <br>
                <?php esc_html_e( ' - You need to login to your account at ', 'wp-manga' ); ?>
                <a href="https://console.aws.amazon.com/">
                    <?php esc_html_e( 'https://console.aws.amazon.com/', 'wp-manga' ); ?>
                </a>

                <br>
                <?php esc_html_e( ' - First thing to do is create an user to have the Access key ID and Secret access key ( register with Access Type of Programmatic access then push them to a group that have permission: "AmazonS3FullAcess" to get the keys ) at ', 'wp-manga' ); ?>
                <a href="https://console.aws.amazon.com/iam">
                    <?php esc_html_e( 'https://console.aws.amazon.com/iam', 'wp-manga' ); ?>
                </a>
                <?php esc_html_e( '. Then provide the user the permission to read and write.', 'wp-manga' ); ?>

                <br>
                <?php esc_html_e( ' - After having the Key and Secret, note it and get to ', 'wp-manga' ); ?>
                <a href="https://console.aws.amazon.com/s3/">
                    <?php esc_html_e( 'https://console.aws.amazon.com/s3/', 'wp-manga' ); ?>
                </a>
                <?php esc_html_e( '. ( *Note : you need to create the bucket in the same region as your account when register the account at ', 'wp-manga' ); ?>
                <a href="https://console.aws.amazon.com/">
                    <?php esc_html_e( 'https://console.aws.amazon.com/', 'wp-manga' ); ?>
                </a>
                <?php esc_html_e( 'And Also provide the permission for Any Authenticated AWS User can read and write ', 'wp-manga' ); ?>

                <br>
                <?php esc_html_e( ' - Then go back to your website >> dashboard >> Settings >> WP Cloud Media Settings >> Input your Key ID and Secret and also the Region of your account and your Bucket then save changes and you can start to upload. ', 'wp-manga' ); ?>
            </p>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Access key ID', 'wp-manga' ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[amazon_s3_access_key]" type="text" class="large-text" value="<?php echo esc_attr( $amazon_s3_access_key ); ?>">
                        <p class="description">
                            <?php esc_html_e( 'You can create the key ID at', 'wp-manga' ) ?>
                            <a href="https://console.aws.amazon.com/iam/home" target="_blank">
                                <?php esc_html_e( 'console.aws.amazon.com/iam', 'wp-manga' ) ?>
                            </a>
                        <p></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Secret access key', 'wp-manga' ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[amazon_s3_access_secret]" type="text" class="large-text" value="<?php echo esc_attr( $amazon_s3_access_secret ); ?>">
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <?php esc_html_e( 'Region', 'wp-manga' ) ?>
                    </th>
                    <td>
                        <p>
                            <input name="wp_manga[amazon_s3_region]" type="text" class="large-text" value="<?php echo esc_attr( $amazon_s3_region ); ?>">
                        </p>
                    </td>
                </tr>
                <?php global $wp_manga_amazon_upload;
                    $buckets = $wp_manga_amazon_upload->buckets;
                    if ( $buckets ) :
                        $current_bucket = $wp_manga_amazon_upload->get_upload_bucket( $buckets );
                        ?>
                        <tr>
                            <th scope="row">
                                <?php esc_html_e( 'Bucket', 'wp-manga' ) ?>
                            </th>
                            <td>
                                <select name="wp_manga[amazon_s3_bucket]">
                                    <?php foreach ( $buckets as $key => $value ) { ?>
                                        <option value="<?php echo esc_attr( $value['Name'] ); ?>" <?php selected( $current_bucket, $value['Name'], true ); ?>><?php echo esc_html( $value['Name'] ); ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <span>
                                    <?php esc_html_e( 'Authorize', WP_MANGA_TEXTDOMAIN ) ?>
                                </span>
                            </th>
                            <td>
                                <p>
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php esc_html_e( 'Authorizing Success', WP_MANGA_TEXTDOMAIN ) ?>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
            </table>

            <button type="submit" class="button button-primary"><?php esc_attr_e( 'Save Changes', WP_MANGA_TEXTDOMAIN ) ?></button>
        </form>
    </div>
