<?php

	require_once( WP_MANGA_DIR . 'inc/database/database.php' );

	class WP_DB_CHAPTER extends WP_MANGA_DATABASE {

		public $chapter_table;

		public function __construct() {

			$this->chapter_table = $this->get_wpdb()->prefix . 'manga_chapters';

		}

		function insert_chapter( $args ) {
			//post_id require, volume id, chapter name, chapter extend name, chapter slug

			if ( empty( $args['post_id'] ) ) {
				return false;
			}

			if( empty( $args['volume_id'] ) ){
				$args['volume_id'] = 0;
			}
                        
                        update_post_meta( $args['post_id'], '_latest_update', current_time( 'timestamp', false ) );

			//check if chapter slug is unique
			$search = $this->get_chapter_by_slug( $args['post_id'], $args['chapter_slug'] );

			if( $search ){
				global $wp_manga_functions;
				$args['chapter_slug'] = $wp_manga_functions->unique_slug( $args['post_id'], $args['chapter_name'] );
			}

			$args['date'] = current_time( 'mysql' );
			$args['date_gmt'] = current_time( 'mysql', true );

			return $this->insert( $this->chapter_table, $args );

		}

		function get_chapters( $args, $search = false, $orderby = '', $order = '', $num_limit = '' ) {

			$conditions = array();
			foreach ( $args as $name => $value ) {
				$conditions[] = "$name = '$value'";
			}

			if ( $search ) {
				$conditions[] = "chapter_name LIKE '%$search%' OR chapter_name_extend LIKE '%$search%'";
			}

			$where = implode( ' AND ', $conditions );

			return $this->get( $this->chapter_table, $where, $orderby, $order , $num_limit );

		}

		function get_latest_chapters( $post_id, $q, $num, $all_meta = 0, $orderby = 'name', $order = 'desc' ) {

			$chapters = $this->get_chapters( array(
				'post_id' => $post_id
			), $q, $orderby, $order , $num);

			if ( $chapters && $all_meta == 0 ) {
				return array_slice( $chapters, 0, $num );
			}

			return $chapters;

		}

		function delete_chapter( $args ) {

			return $this->delete( $this->chapter_table, $args );

		}

		function update_chapter( $update, $args ) {

			$update['date']     = current_time( 'mysql' );
			$update['date_gmt'] = current_time( 'mysql', true );

			return $this->update( $this->chapter_table, $update, $args );

		}

		function get_manga_chapters( $post_id ) {

			return $this->get_chapters( array(
				'post_id' => $post_id
			) );
		}

		function get_chapter_by_id( $post_id, $chapter_id ) {

			$chapter = $this->get_chapters( array(
				'post_id'    => $post_id,
				'chapter_id' => $chapter_id
			) );

			if ( isset( $chapter[0] ) ) {
				return $chapter[0];
			}

			return false;
		}

		function get_chapter_volume( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter == false ) {
				return false;
			}

			if ( $chapter['volume_id'] == 0 ) {
				return false;
			}

			$volume = $GLOBALS['wp_manga_volume']->get_volumes( array(
				'post_id'   => $post_id,
				'volume_id' => $chapter['volume_id']
			) );

			if ( isset( $volume[0] ) ) {
				return $volume[0];
			}

			return false;
		}

		function get_chapter_by_slug( $post_id, $chapter_slug ) {

			$chapter = $this->get_chapters( array(
				'post_id'      => $post_id,
				'chapter_slug' => $chapter_slug
			) );

			if ( isset( $chapter[0] ) ) {
				return $chapter[0];
			}

			return false;

		}

		function get_chapter_id_by_slug( $post_id, $chapter_slug ) {

			$chapter = $this->get_chapter_by_slug( $post_id, $chapter_slug );

			if ( $chapter ) {
				return $chapter['chapter_id'];
			}

			return false;

		}

		function get_chapter_slug_by_id( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter ) {
				return $chapter['chapter_slug'];
			}

			return false;
		}

		function get_chapter_data( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter ) {

				$chapter['volume'] = $this->get_chapter_volume( $post_id, $chapter_id );

				if ( $chapter['volume'] == false ) {
					unset( $chapter['volume'] );
				}

			}

			return $chapter;

		}

	}

	$GLOBALS['wp_manga_chapter'] = new WP_DB_CHAPTER();
