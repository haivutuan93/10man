<?php

class WP_MANGA_FIRST_INSTALL{

    function __construct(){

        add_action( 'admin_notices', array( $this, 'first_install_notice' ) );
        add_action( 'admin_init', array( $this, 'first_install_page' ) );
        add_action( 'admin_init', array( $this, 'first_install_redirect') );
        add_action( 'admin_menu', array( $this, 'first_install_submenu' ) );

    }

    function first_install_notice(){

        $manga_notice = get_option( 'wp_manga_notice' );

        if( $manga_notice == true ) {
            return;
        }

        ?>

        <div id="message" class="notice notice-info is-dismissible wp-manga-first-install-notice">
        	<p><?php _e( '<strong>WP Manga Plugin</strong> &#8211; Get started with WP Manga Plugin!', WP_MANGA_TEXTDOMAIN ); ?></p>
        	<p class="submit">
                <a href="<?php echo esc_url( add_query_arg( array( 'wp-manga' => 'first-install' ), admin_url() ) ); ?>" class="button-primary"><?php _e( 'Let\'s go!', WP_MANGA_TEXTDOMAIN ); ?></a>
                <a class="button-secondary wp-manga-skip" href="javascript:void(0)"><?php _e( 'Skip this', WP_MANGA_TEXTDOMAIN ); ?></a></p>
        </div>

        <?php
    }

    function first_install_submenu(){

        add_submenu_page(
            'edit.php?post_type=wp-manga',
            esc_html__( 'WP Manga Welcome', WP_MANGA_TEXTDOMAIN ),
            esc_html__( 'WP Manga Welcome', WP_MANGA_TEXTDOMAIN ),
            'manage_options',
            '?wp-manga=first-install'
        );

    }

    function first_install_redirect(){

        $welcome_redirect = get_transient( 'wp_manga_welcome_redirect' );
        if( $welcome_redirect == true ){            
            wp_redirect(
                add_query_arg( array( 'wp-manga' => 'first-install' ), admin_url() )
            );
        }

    }

    function first_install_page(){

        if( isset( $_GET['wp-manga'] ) && $_GET['wp-manga'] == 'first-install' ) {

            wp_enqueue_style( 'manga_first_install_css', WP_MANGA_URI . 'assets/css/first-install.css', array(), '' );
            wp_enqueue_style( 'manga_bootstrap_css', WP_MANGA_URI . 'assets/css/bootstrap.min.css', array(), '' );
            wp_enqueue_style( 'manga_fontawesome', WP_MANGA_URI . 'assets/css/font-awesome/css/font-awesome.min.css', array(), '' );
            wp_enqueue_script( 'manga_bootstrap_js', WP_MANGA_URI . 'assets/js/bootstrap.min.js', array( 'jquery' ), '' );
            wp_enqueue_script( 'manga_first_install_js', WP_MANGA_URI . 'assets/js/first-install.js', array( 'jquery' ), '' );
            wp_localize_script( 'manga_first_install_js', 'manga_ajax_url', array( 'admin_ajax' => get_admin_url( '', 'admin-ajax.php' ) ) );

            $GLOBALS['wp_manga_template']->load_template( 'first-install/first-install', 'html', true );
            exit();
        }

    }

}

$GLOBALS['wp_manga_first_install'] = new WP_MANGA_FIRST_INSTALL();
