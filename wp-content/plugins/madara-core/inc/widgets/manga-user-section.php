<?php


class MANGA_USER_SECTION extends WP_Widget{

    public function __construct(){

        $widget_opts = array(
            'classname' => 'wp-manga-section wp-manga-user-section',
            'description' => esc_html__('Show Login or Current Manga User', WP_MANGA_TEXTDOMAIN )
        );

        parent::__construct( 'wp-manga-user-section-id', esc_html__('WP Manga : User section', WP_MANGA_TEXTDOMAIN ), $widget_opts );

    }

    function form( $instance ){

        $title = isset( $instance['title'] ) ? $instance['title'] : '';

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title:', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </label>
        </p>
        <?php

    }

    function widget( $args, $instance ){

        extract( $args );

        $title = isset( $instance['title'] ) ? $instance['title'] : '';

        echo $before_widget;

            if( !empty( $title ) ){
                echo $before_title;
                    echo $title;
                echo $after_title;
            }

            global $wp_manga_user_actions;

            echo $wp_manga_user_actions->wp_manga_user_section();

        echo $after_widget;

    }

    function update( $new_instance, $old_instance ){

        $instance = $old_instance;

        $instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';

        return $instance;

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'MANGA_USER_SECTION' );
});


?>
