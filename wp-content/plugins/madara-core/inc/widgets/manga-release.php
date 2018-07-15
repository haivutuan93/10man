<?php

class Manga_Release_Widget extends WP_Widget {

    public function __construct(){

        $widget_options = array(
            'classname' => 'wp_manga_wg_year_release c-released',
            'description' => esc_html__( 'Show Manga Release Years' , WP_MANGA_TEXTDOMAIN )
        );

        parent::__construct( 'wp_manga_release_id', esc_html__( 'WP Manga : Manga Release Years', WP_MANGA_TEXTDOMAIN ), $widget_options );

    }

    function widget( $args, $instance ) {

        extract( $args );

        $title      = isset( $instance['title'] ) ? $instance['title'] : '';
        $exclude    = isset( $instance['exclude'] ) ? $instance['exclude'] : '';
        $number     = isset( $instance['number'] ) ? $instance['number'] : '20';
        $go_release = isset( $instance['go_release'] ) ? $instance['go_release'] : 'true';

        if( !empty( $exclude ) ) {
            $exclude_years = explode( ',', $exclude );
            $exclude = array();

            foreach( $exclude_years as $year ) {
                $year_obj = get_term_by( 'slug', $year, 'wp-manga-release' );
                if( $year_obj != false ) {
                    $exclude[] = $year_obj->term_id;
                }
            }

            $exclude = array_merge( $exclude, $exclude_years );
        }

        $release_years = get_terms(
            array(
                'taxonomy' => 'wp-manga-release',
                'hide_empty' => true,
                'exclude' => $exclude,
                'number' => $number,
            )
        );

        if( is_wp_error( $release_years ) ) {
            return;
        }

        echo $before_widget;

            if( !empty( $title ) ) {
                echo $before_title;
                    echo $title;
                echo $after_title;
            }

            ?>
            <div class="c-released_content">
                <div class="released-item-wrap">
                    <ul class="list-released">

                        <?php
                            $release_years = array_reverse( $release_years );
                            $flag = 0;
                            foreach( $release_years as $year ) {
                                $flag ++;
                                if( $flag % 4 == 1 ) {
                                    echo '<li>';
                                }
                                ?>
                                    <a href="<?php echo esc_url( get_term_link( $year ) ); ?>"><?php echo esc_html( $year->name ); ?></a>
                                <?php
                                if( $flag % 4 == 0 ) {
                                    echo '</li>';
                                }
                            }
                        ?>

                    </ul>
                </div>
                <?php if( $go_release == 'true' ) { ?>
                    <div class="released-search">
                        <form action="<?php echo esc_url( home_url() ); ?>" method="get">
                            <input type="text" placeholder="Other..." name="wp-manga-release" value="">
                            <input type="submit" value="Go">
                        </form>
                    </div>
                <?php } ?>
            </div>

        <?php
        echo $after_widget;
    }

    function form( $instance ) {

        $title      = isset( $instance['title'] ) ? $instance['title'] : '';
        $exclude    = isset( $instance['exclude'] ) ? $instance['exclude'] : '';
        $number     = isset( $instance['number'] ) ? $instance['number'] : '20';
        $go_release = isset( $instance['go_release'] ) ? $instance['go_release'] : 'true';

        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title:', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'title' ); ?>" id="<?php echo $this->get_field_id( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'exclude' ); ?>">
                <?php esc_html_e( 'Exclude Years:', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="text" class="widefat" name="<?php echo $this->get_field_name('exclude'); ?>" id="<?php echo $this->get_field_id( 'exclude' ); ?>" value="<?php echo esc_attr( $exclude ); ?>" />
                <span class="description"> <?php esc_html_e( 'Use Release Years Term ID or slug, separated by comma', WP_MANGA_TEXTDOMAIN ); ?> </span>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'number' ); ?>">
                <?php esc_html_e( 'Number of years', WP_MANGA_TEXTDOMAIN ); ?>
                <input type="number" class="widefat" name="<?php echo $this->get_field_name( 'number' ); ?>" id="<?php echo $this->get_field_id( 'number' ); ?>" value="<?php echo esc_attr( $number ); ?>" />
                <span class="description"> <?php esc_html_e( 'Fill-in 0 to list all Release Years', WP_MANGA_TEXTDOMAIN ); ?> </span>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'go_release' ); ?>">
                <input type="checkbox" class="widefat" name="<?php echo $this->get_field_name( 'go_release' ); ?>" id="<?php echo $this->get_field_id( 'go_release' ); ?>" value="true" <?php echo checked( $go_release, 'true' ); ?> />
                <?php esc_html_e( 'Enable Go Release', WP_MANGA_TEXTDOMAIN ); ?>
            </label>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {

        $instance               = $old_instance;
        $instance['title']      = isset( $new_instance['title'] ) ? $new_instance['title'] : '';
        $instance['exclude']    = isset( $new_instance['exclude'] ) ? $new_instance['exclude'] : '';
        $instance['number']     = isset( $new_instance['number'] ) ? $new_instance['number'] : '20';
        $instance['go_release'] = isset( $new_instance['go_release'] ) ? $new_instance['go_release'] : 'false';

        return $instance;
    }

}

add_action( 'widgets_init', function(){
    register_widget( 'Manga_Release_Widget' );
});
