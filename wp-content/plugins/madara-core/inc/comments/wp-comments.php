<?php

    class WP_MANGA_COMMENTS{

        function __construct(){
            add_action('pre_get_comments', array( $this, 'filter_get_comments' ) );
            add_action('comment_post', array( $this, 'filter_save_comments' ) );
            add_action('comment_form', array( $this, 'chapter_comment_field' ) );

            add_filter('comment_post_redirect', array( $this, 'redirect_after_comment' ) );
            add_filter('get_comments_number', array( $this, 'manga_get_comments_number' ) );
        }

        function comment_get_chapter_id(){

            global $wp_manga_functions, $wp_manga_chapter;

            if( $wp_manga_functions->is_manga_single() ){

                return $chapter_id = '0';

            }elseif( $wp_manga_functions->is_manga_reading_page() ){

                $chapter_slug = get_query_var('chapter');

                if( empty( $chapter_slug ) ){
                    return false;
                }

                return $chapter_id = $wp_manga_chapter->get_chapter_id_by_slug( get_the_ID(), $chapter_slug );

            }

            return false;

        }

        function filter_get_comments( $comments_query ){

            $chapter_id = $this->comment_get_chapter_id();

            if( $chapter_id === false ){
                return;
            }

            $meta_query = array(
                'relation'    => 'OR',
                array(
                    'key'     => 'chapter_id',
                    'value'   => $chapter_id,
                )
            );

            if( $chapter_id == '0' ){
                $meta_query = array_merge( $meta_query, array(
                    array(
                        'key'     => 'chapter_id',
                        'compare' => 'NOT EXISTS'
                    )
                ) );
            }

            $comments_query->query_vars['meta_query'] = $meta_query;

        }

        function filter_save_comments( $comment_id ){

            $chapter_id = isset( $_REQUEST['wp_manga_chapter_id'] ) ? $_REQUEST['wp_manga_chapter_id'] : null;

            if( $chapter_id === null ){
                return;
            }

            update_comment_meta( $comment_id, 'chapter_id', $chapter_id );

        }

        function chapter_comment_field(){

            $chapter_id = $this->comment_get_chapter_id();

            if( $chapter_id === false ){
                return;
            }

            ?>
                <input type="hidden" name="wp_manga_chapter_id" value="<?php echo esc_attr( $chapter_id ); ?>">
            <?php
        }

        function redirect_after_comment($location){
            return $_SERVER["HTTP_REFERER"];
        }

        function manga_get_comments_number(){

            $comments = get_comments( array( 'post_id' => get_the_ID() ) );

            return count( $comments );
        }

        

    }

    $GLOBALS['wp_manga_comments'] = new WP_MANGA_COMMENTS();
