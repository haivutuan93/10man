<?php

    namespace App\Plugins\Widgets;

    use App\Madara;
	use App\Models\Database;

    class MangaHistory extends \WP_Widget {

        public function __construct() {

            $widget_options = array(
                'classname' => 'manga-history-widget',
                'description' => esc_html__( 'Show User Manga History', 'madara' ),
            );

            parent::__construct( 'manga-history-id', esc_html__( 'WP Manga : Manga History', 'madara' ), $widget_options );

        }

        function widget( $args, $instance ) {

            $user_id = get_current_user_id();

            if( $user_id == 0 ) {
                return;
            }

            $manga_history = get_user_meta( $user_id, '_wp_manga_history', true );
            $reading_style = $GLOBALS['wp_manga_functions']->get_reading_style();
            $count = isset( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 3;

            if( !empty( $manga_history ) ) {

                extract( $args );

                echo wp_kses_post( $before_widget );

                    if( !empty( $instance['title'] ) ) {
                        echo wp_kses_post( $before_title );
                            echo esc_html( $instance['title'] );
                        echo wp_kses_post( $after_title );
                    }

                    ?>
                    <div class="my-history">
                        <?php
                                //sort by latest manga
                                $manga_history = array_combine( array_column( $manga_history, 't' ), $manga_history );
                                krsort( $manga_history );

                                $current_manga_chapter_url_h = '';
                                foreach( $manga_history as $manga ) {

                                    if( $count == 0 ) {
                                        break;
                                    }

                                    $post = get_post( intval( $manga['id'] ) );

                                    if( $post == null || $post->post_status !== 'publish' ) {
                                        continue;
                                    }

                                    $count--;

                                    $post_id = $manga['id'];

                                    $url = '';

                        			//get chapter
                        			if ( class_exists( 'WP_MANGA' ) && !empty( $manga['c'] ) && !is_array( $manga['c'] ) ) {

                        				$wp_manga_chapter = madara_get_global_wp_manga_chapter();
                        				$chapter_slug = $wp_manga_chapter->get_chapter_slug_by_id( $post_id, $manga['c'] );
                                                        
                                                        $chapter_name_full = explode("-", $chapter_slug);
                                                        $chapter_name_h = ucwords($chapter_name_full[0] . " " . $chapter_name_full[1]);
                                                        
                                        $reading_style = !empty( $reading_style ) ? $reading_style : 'paged';
                                        $page = !empty( $manga['p'] ) ? $manga['p'] : '1';

                                        global $wp_manga_functions;
                                        $url = $wp_manga_functions->build_chapter_url( $post_id, $chapter_slug, $reading_style, null, $page );

                                        if($current_manga_chapter_url_h != ''){
                                            if(strcmp($current_manga_chapter_url_h, $url) == 0){
                                                continue;
                                            }else{
                                                $current_manga_chapter_url_h = $url;
                                            }
                                        }else{
                                            $current_manga_chapter_url_h = $url;
                                        }
                        			}else{
                                        continue;
                                    }

                                    if( !empty( $manga['i'] ) && $reading_style == 'list' ){
                                        $url .= '#image-' . $manga['i'];
                                    }

                                    ?>
                                    <div class="my-history-item-wrap">
                                        <div class="my-history-title">
                                            <a title="<?php echo esc_attr( get_the_title( $post_id ) ); ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( get_the_title( $post_id ) ); ?></a>
                                        </div>
                                        <a href="<?php echo esc_url ( $url ); ?>">
                                            <div class="my-history-page">
                                                <span><?php echo  $chapter_name_h; ?></span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php

                                    //reset for the next loop
                                    $chapter_slug = '';

                                }

                        ?>
                    </div>
                    <?php

                echo wp_kses_post( $after_widget );
            }
        }

        function form( $instance ) {

            $title = isset( $instance['title'] ) ? $instance['title'] : '';
            $number_of_posts = isset( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 3;

            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                    <?php esc_html_e( 'Title: ', 'madara' ); ?>
                    <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title ') ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" />
                </label>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('number_of_posts' ) ); ?>">
                    <?php esc_html_e( 'Number of manga: ', 'madara' ); ?>
                    <input type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number_of_posts' ) ); ?>" value="<?php echo esc_attr( $number_of_posts ) ?>" max="12" />
                </label>
            </p>
            <?php

        }

        function update( $new_instance, $old_instance ) {

            $instance = $old_instance;

            $instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
            $instance['number_of_posts'] = isset( $new_instance['number_of_posts'] ) ? $new_instance['number_of_posts'] : 3;

            return $instance;

        }

    }

    add_action( 'widgets_init', function(){
        register_widget( 'App\Plugins\Widgets\MangaHistory' );
    });
