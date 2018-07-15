<h3 class="head text-center"><?php esc_html_e( 'Before Getting Started with WP Manga Plugin', WP_MANGA_TEXTDOMAIN ); ?></h3>

<p class="narrow text-center">
    <?php esc_html_e('Since WP Manga Plugin is a plugin to upload and manage Manga, so there are a fews requirements and recommendations on your server settings to make it works properly. ', WP_MANGA_TEXTDOMAIN ); ?>
</p>

<p class="narrow text-center">
    <?php esc_html_e('These are settings you need to notice and the number in right side is the current config in your server.', WP_MANGA_TEXTDOMAIN ); ?>
</p>

<p class="narrow text-center">
    <?php esc_html_e('Please make sure your file size does not exceed the configurations below, and change it to fit your needs. ', WP_MANGA_TEXTDOMAIN ); ?>
</p>

        <?php
        global $wp_manga_functions;

        $post_max_size = ini_get( 'post_max_size' );

        $upload_max_filesize = ini_get( 'upload_max_filesize' );

        $max_execution_time = ini_get( 'max_execution_time' );

        $max_input_time = ini_get( 'max_input_time' );

         ?>


    <table class="server-settings">
        <thead>
            <tr>
                <td>
                    <?php esc_html_e( 'Settings', WP_MANGA_TEXTDOMAIN ); ?>
                </td>
                <td>
                    <?php esc_html_e( 'Require/Recommend', WP_MANGA_TEXTDOMAIN ); ?>
                </td>
                <td>
                    <?php esc_html_e( 'Your Current Settings', WP_MANGA_TEXTDOMAIN ); ?>
                </td>
                <td>
                    <?php esc_html_e( 'Status', WP_MANGA_TEXTDOMAIN ); ?>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    cURL
                </td>
                <td>
                    ON
                </td>
                <td>
                    <?php echo function_exists( 'curl_version' ) ? 'ON' : 'OFF'; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo function_exists( 'curl_version' ) ? 'high' : ''; ?>"></i>

                </td>
            </tr>
            <tr>
                <td>
                    file_uploads
                </td>
                <td>
                    ON
                </td>
                <td>
                    <?php echo ini_get( 'file_uploads' ) ? 'ON' : 'OFF'; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo ini_get( 'file_uploads' ) ? 'high' : ''; ?>"></i>

                </td>
            </tr>
            <tr>
                <td>
                    post_max_size <em>(MB)</em>
                </td>
                <td>
                    64M
                </td>
                <td>
                    <?php echo $post_max_size; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo $wp_manga_functions->validate_size_setting( $post_max_size ); ?>"></i>
                </td>
            </tr>
            <tr>
                <td>
                    upload_max_filesize <em>(MB)</em>
                </td>
                <td>
                    64M
                </td>
                <td>
                    <?php echo $upload_max_filesize; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo $wp_manga_functions->validate_size_setting( $upload_max_filesize ); ?>"></i>

                </td>
            </tr>
            <tr>
                <td>
                    max_execution_time <em>(seconds)</em>
                </td>
                <td>
                    300
                </td>
                <td>
                    <?php echo $max_execution_time; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo $wp_manga_functions->validate_time_setting( $max_execution_time ); ?>"></i>

                </td>
            </tr>
            <tr>
                <td>
                    max_input_time <em>(seconds)</em>
                </td>
                <td>
                    300
                </td>
                <td>
                    <?php echo $max_input_time; ?>
                </td>
                <td>
                    <i class="fa fa-check-square-o <?php echo $wp_manga_functions->validate_time_setting( $max_input_time ); ?>"></i>

                </td>
            </tr>

        </tbody>
    </table>

    <p class="comment">
        <span>
            <i class="fa fa-check-square-o high"></i> <?php esc_html_e( 'high', WP_MANGA_TEXTDOMAIN ); ?>
        </span>
        <span>
            <i class="fa fa-check-square-o medium"></i> <?php esc_html_e( 'medium', WP_MANGA_TEXTDOMAIN ); ?>
        </span>
        <span>
            <i class="fa fa-check-square-o low"></i> <?php esc_html_e( 'low', WP_MANGA_TEXTDOMAIN ); ?>
        </span>
    </p>

<p class="submit-line text-center">
    <a href="<?php echo esc_url( get_admin_url() ); ?>" class="btn cancel-button"> <?php esc_html_e('Maybe later', WP_MANGA_TEXTDOMAIN ); ?> </a>
    <a href="#step-2" data-toggle="tab" class="btn main-button"> <?php esc_html_e('Next', WP_MANGA_TEXTDOMAIN ); ?> </a>
</p>
