<?php
/*
*   Manga Genres Widget
*/

class Manga_Genres_Widget extends WP_Widget {

    public function __construct() {

        $widget_options = array(
            'classname' => 'manga-genres-class-name',
            'description' => __( 'Show Manga Genres', WP_MANGA_TEXTDOMAIN )
        );

        parent::__construct( 'manga-genres-id', __( 'WP Manga: Manga Genres', WP_MANGA_TEXTDOMAIN ), $widget_options );

    }

    function widget( $args, $instance ) {

        extract( $args );

        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $exclude_genre = isset( $instance['exclude_genre'] ) ? $instance['exclude_genre'] : array();
        $show_manga_counts = isset( $instance['show_manga_counts'] ) ? $instance['show_manga_counts'] : 'true';
        $layout = isset( $instance['layout'] ) ? $instance['layout'] : 'layout-1';

        echo $before_widget;

        ?>
        <div class="genres_wrap">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    if( !empty( $title ) ) {
                        echo $before_title . esc_html( $title ) . $after_title;
                    }

                    if( !empty( $exclude_genre ) ){

                        $genres = explode( ',', $exclude_genre );
                        $exclude_genre = array();

                        //get genre id from genre slug
                        foreach( $genres as $genre ){
                            $genre = trim( $genre );
                            $genre_obj = get_term_by( 'slug', $genre, 'wp-manga-genre' );

                            if( $genre_obj != false ) {
                                $exclude_genre[] = $genre_obj->term_id;
                            }
                        }

                        $exclude_genre = array_merge( $exclude_genre, $genres );

                    }

                    //genre query
                    $genre_args = array(
                        'taxonomy' => 'wp-manga-genre',
                        'hide_empty' => false,
                        'exclude' => $exclude_genre
                    );
                    $genres = get_terms( $genre_args );

                    if( !empty( $genres ) && !is_wp_error( $genres ) ) {
                        ?>
                        <div class="genres__collapse" style="display:block;">
                            <div class="row genres">
                                <ul class="list-unstyled">
                                    <?php
                                    foreach( $genres as $genre ) {
                                        ?>
                                        <li class="<?php echo $layout == 'layout-2' ? 'col-xs-6 col-sm-4 col-md-2' : 'col-xs-6 col-sm-6'; ?>">
                                            <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>">
                                                <?php echo esc_html( $genre->name ); ?>
                                                <?php
                                                if( $show_manga_counts == 'true' ) {
                                                    ?>
                                                    <span class="count">
                                                        (<?php echo esc_html( $genre->count ); ?>)
                                                    </span>
                                                    <?php
                                                }
                                                ?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>

        <?php
        echo $after_widget;

    }

    function form( $instance ) {

        $title = isset( $instance['title'] ) ? $instance['title'] : '';
        $show_manga_counts = isset( $instance['show_manga_counts'] ) ? $instance['show_manga_counts'] : 'true';
        $exclude_genre = isset( $instance['exclude_genre'] ) ? $instance['exclude_genre'] : '';
        $layout = isset( $instance['layout'] ) ? $instance['layout'] : "layout-1";

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title: ', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>">
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'exclude_genre' ); ?>">
                <?php esc_html_e( 'Exclude Genres: ', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'exclude_genre' ); ?>" name="<?php echo $this->get_field_name( 'exclude_genre' ); ?>" value="<?php echo esc_attr( $exclude_genre ); ?>">
                <span class="description"> <?php esc_html_e( 'Use WP Manga Genres ID or slug, separated by comma', WP_MANGA_TEXTDOMAIN ); ?> </span>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'layout' ); ?>">
                <?php esc_html_e( 'Layout: ', WP_MANGA_TEXTDOMAIN ); ?>
                <select type="text" class="widefat" id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo $this->get_field_name( 'layout' ); ?>">
                    <option value="layout-1" <?php selected( $layout, 'layout-1' ); ?>>
                        <?php esc_html_e( 'Layout 1 - 2 columns', WP_MANGA_TEXTDOMAIN ); ?>
                    </option>
                    <option value="layout-2" <?php selected( $layout, 'layout-2' ); ?>>
                        <?php esc_html_e( 'Layout 2 - 6 columns', WP_MANGA_TEXTDOMAIN ); ?>
                    </option>
                </select>
            </label>
        </p>
        <p>
            <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'show_manga_counts' ); ?>" name="<?php echo $this->get_field_name( 'show_manga_counts' ); ?>" <?php checked( $show_manga_counts, 'true' ); ?> value="true">
            <label for="<?php echo $this->get_field_id( 'show_manga_counts' ); ?>">
                <?php esc_html_e( 'Show manga counts', WP_MANGA_TEXTDOMAIN ); ?>
            </label>
        </p>

        <?php

    }

    function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance['title'] = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
        $instance['exclude_genre'] = isset( $new_instance['exclude_genre'] ) ? $new_instance['exclude_genre'] : '';
        $instance['show_manga_counts'] = isset( $new_instance['show_manga_counts'] ) ? $new_instance['show_manga_counts'] : 'false';
        $instance['layout'] = isset( $new_instance['layout'] ) ? $new_instance['layout'] : 'layout-1';

        return $instance;

    }

}

add_action( 'widgets_init', function(){
    register_widget( 'Manga_Genres_Widget' );
});

?>
