<?php

    namespace App\Plugins\Widgets;

    use App\Madara;
	use App\Models\Database;

    class MangaBookmark extends \WP_Widget {

        public function __construct() {

            $widget_options = array(
                'classname' => 'c-popular manga-bookmark-widget',
                'description' => esc_html__( 'Show User Manga Bookmark', 'madara' ),
            );

            parent::__construct( 'manga-bookmark-id', esc_html__( 'WP Manga : Manga Bookmark', 'madara' ), $widget_options );

        }

        function widget( $args, $instance ) {

            global $wp_manga_template;

            $user_id = get_current_user_id();

            if( $user_id == 0 ) {
                return;
            }

            $manga_bookmark = get_user_meta( $user_id, '_wp_manga_bookmark', true );
        	$reading_style     = $GLOBALS['wp_manga_functions']->get_reading_style();
        	$reading_style     = ! empty( $reading_style ) ? $reading_style : 'paged';
            $count          = isset( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 3;
            $style          = ! empty( $instance['style'] ) ? $instance['style'] : 'style-1';

            if( !empty( $manga_bookmark ) ) {

                extract( $args );

                echo wp_kses_post( $before_widget );

                    ?> <div class="c-widget-content <?php echo $style; ?>"> <?php

                    if( !empty( $instance['title'] ) ) {
                        echo wp_kses_post( $before_title );
                            echo esc_html( $instance['title'] );
                        echo wp_kses_post( $after_title );
                    }

                    foreach( $manga_bookmark as $manga ) {

                        if( $count == 0 ) {
                            break;
                        }

                        $manga_post = get_post( intval( $manga['id'] ) );

                        if( $manga_post == null || $manga_post->post_status !== 'publish' ) {
                            continue;
                        }

                        $count--;

                        global $post;

                        $post = $manga_post;
                        ?>
                            <div class="popular-item-wrap">

                                <?php if ( $style == 'style-1' ) {
                                    $wp_manga_template->load_template( 'widgets/recent-manga/content-1', false );
                                } else {
                                    $wp_manga_template->load_template( 'widgets/recent-manga/content-2', false );
                                } ?>

                            </div>
                        <?php
                        //reset for the next loop
                        $chapter_slug = '';
                    }
                    ?></div><?php

                wp_reset_postdata();

                echo wp_kses_post( $after_widget );
            }
        }

        function form( $instance ) {

            $title = isset( $instance['title'] ) ? $instance['title'] : '';
            $number_of_posts = isset( $instance['number_of_posts'] ) ? $instance['number_of_posts'] : 3;
            $style = ! empty( $instance['style'] ) ? $instance['style'] : 'style-1';

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
            <p>
                <label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php echo esc_html__( 'Style', 'madara' ); ?>
                    : </label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'style' ); ?>" name="<?php echo $this->get_field_name( 'style' ); ?>">
                    <option value="style-1" <?php echo $style == 'style-1' ? 'selected' : '' ?>><?php echo esc_html__( 'Style 1', 'madara' ); ?></option>
                    <option value="style-2" <?php echo $style == 'style-2' ? 'selected' : '' ?>><?php echo esc_html__( 'Style 2', 'madara' ); ?></option>
                </select>
            </p>
            <?php

        }

        function update( $new_instance, $old_instance ) {

            $instance = $old_instance;

            $instance['title']           = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
            $instance['number_of_posts'] = isset( $new_instance['number_of_posts'] ) ? $new_instance['number_of_posts'] : 3;
            $instance['style']           = isset( $new_instance['style'] ) ? $new_instance['style'] : 'style-1';

            return $instance;

        }

    }

    add_action( 'widgets_init', function(){
        register_widget( 'App\Plugins\Widgets\MangaBookmark' );
    });
