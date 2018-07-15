<?php

require_once( WP_MANGA_DIR . 'inc/database/database.php' );

class WP_DB_VOLUME extends WP_MANGA_DATABASE{

    public $volume_table;

    public function __construct(){

        $this->volume_table = $this->get_wpdb()->prefix . 'manga_volumes';

    }

    function insert_volume( $args ){

        //post_id require, volume_name, date, date_gmt

        if( empty( $args['post_id'] ) ){
            return false;
        }

        $args['date'] = current_time( 'mysql' );
        $args['date_gmt'] = current_time( 'mysql', true );

        return $this->insert( $this->volume_table, $args );

    }

    function get_volumes( $args ){

        if( empty( $args ) ){
            return false;
        }

        $conditions = array();

        foreach( $args as $name => $value ){

            if( $name == 'orderby' ){
                continue;
            }

            $conditions[] = "$name = '$value'";
        }

        $where = implode( ' AND ', $conditions );

        $orderby = isset( $args['orderby'] ) ? $args['orderby'] : '';
        $order   = isset( $args['order'] ) ? $args['order'] : '';

        return $this->get( $this->volume_table, $where, $orderby, $order );

    }


    function delete_volume( $args ){

        return $this->delete( $this->volume_table, $args );

    }

    function update_volume( $update, $args ){

        return $this->update( $this->volume_table, $update, $args );

    }

    function get_volume_by_id( $post_id, $volume_id ) {

        $volume = $this->get_volumes(
            array(
                'post_id' => $post_id,
                'volume_id' => $volume_id,
            )
        );

        if( isset( $volume[0] ) ){
            return $volume[0];
        }

        return false;
    }

    function get_manga_volumes( $post_id ){

        return $this->get_volumes(
            array(
                'post_id' => $post_id
            )
        );
    }

    function get_volume_chapters( $post_id, $volume_id, $orderby = '', $order = '' ){

        global $wp_manga_chapter;

        $chapters = $wp_manga_chapter->get_chapters(
            array(
                'post_id'   => $post_id,
                'volume_id' => $volume_id
            ),
            $is_search = false,
            $orderby,
            $order
        );

        return $chapters;

    }

}

$GLOBALS['wp_manga_volume'] = new WP_DB_VOLUME();
