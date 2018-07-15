<?php

    if( !class_exists('Disqus_Public') ){
        return;
    }

    class MadaraDisqusComments{

        private $disqus_public;

        private $disqus;

        private $version;

        private $shortname;

        function __construct(){
            add_filter('dsq_can_load', array( $this, 'disqus_override' ) );
            add_action('wp_enqueue_scripts', array( $this, 'disqus_enqueue_comment_embed' ) );

            $plugin = new Disqus( '3.0.12' );

            $this->disqus = $plugin->get_disqus_name();
            $this->version = $plugin->get_version();
            $this->shortname = $plugin->get_shortname();

            $this->disqus_public = new Disqus_Public( $this->disqus, $this->version, $this->shortname );
        }

        function disqus_override( $script_name, $is_manga = false ){

            if( $script_name !== 'embed' || $is_manga ){
                return $script_name;
            }

            $backtrace = debug_backtrace();
            $called_functions = array_column( $backtrace, 'function' );
            if( in_array( 'enqueue_comment_embed', $called_functions
             ) ){
                return false;
            }

            return $script_name;

        }

        function disqus_enqueue_comment_embed(){

            global $post;

            if ( $this->disqus_override( 'embded', true ) ) {

                $embed_vars = $this->embed_vars_for_post( $post );

                $js_file_path = WP_PLUGIN_DIR . '/disqus-comment-system/public/js/comment_embed.js';
                $js_file_url = WP_PLUGIN_URL . '/disqus-comment-system/public/js/comment_embed.js';

                if( !file_exists( $js_file_path ) ){
                    return;
                }

                wp_enqueue_script( 'manga_disqus_embed', $js_file_url, array(), '', true );
                wp_localize_script( 'manga_disqus_embed', 'embedVars', $embed_vars );
            }

        }

        function embed_vars_for_post( $post ) {

            $embed_vars = array(
                'disqusConfig' => array(
                    'integration' => 'wordpress ' . $this->version,
                ),
                'disqusIdentifier' => $this->dsq_identifier_for_post( $post ),
                'disqusShortname' => $this->shortname,
                'disqusTitle' => $this->dsq_title_for_post( $post ),
                'disqusUrl' => $this->get_post_url(),
                'postId' => $post->ID,
            );

            $public_key = get_option( 'disqus_public_key' );
            $secret_key = get_option( 'disqus_secret_key' );
            $can_enable_sso = $public_key && $secret_key && get_option( 'disqus_sso_enabled' );
            if ( $can_enable_sso ) {
                $user = wp_get_current_user();
                $login_redirect = get_admin_url( null, 'profile.php?opener=dsq-sso-login' );
                $embed_vars['disqusConfig']['sso'] = array(
                    'name' => esc_js( get_bloginfo( 'name' ) ),
                    'button' => esc_js( get_option( 'disqus_sso_button' ) ),
                    'url' => wp_login_url( $login_redirect ),
                    'logout' => wp_logout_url(),
                    'width' => '800',
                    'height' => '700',
                );
                $embed_vars['disqusConfig']['api_key'] = $public_key;
                $embed_vars['disqusConfig']['remote_auth_s3'] = $this->remote_auth_s3_for_user( $user, $secret_key );
            }

            return $embed_vars;
        }

        function dsq_identifier_for_post( $post ) {

            global $post, $wp_manga_functions;

            if( $wp_manga_functions->is_manga_reading_page() ){
                $post->guid .= '&chapter=' . get_query_var('chapter');
            }

            return $post->ID . ' ' . $post->guid;
        }

        function dsq_title_for_post( $post ) {
            $title = get_the_title( $post );
            $title = strip_tags( $title, '<b><u><i><h1><h2><h3><code><blockquote><br><hr>' );
            return $title;
        }

        function remote_auth_s3_for_user( $user, $secret_key ) {
            $payload_user = array();
            if ( $user->ID ) {
                $payload_user['id'] = $user->ID;
                $payload_user['username'] = $user->display_name;
                $payload_user['avatar'] = get_avatar( $user->ID, 92 );
                $payload_user['email'] = $user->user_email;
                $payload_user['url'] = $user->user_url;
            }
            $payload_user = base64_encode( json_encode( $payload_user ) );
            $time = time();
            $hmac = hash_hmac( 'sha1', $payload_user . ' ' . $time, $secret_key );

            return $payload_user . ' ' . $hmac . ' ' . $time;
        }

        function get_post_url(){

            global $wp_manga_functions;

            if( $wp_manga_functions->is_manga_reading_page() ){
                return $wp_manga_functions->build_chapter_url( get_the_ID(), get_query_var('chapter') );
            }

            return get_permalink();

        }
    }

    $GLOBALS['madara_disqus_comments'] = new MadaraDisqusComments();
