<?php

	class WP_MANGA_DATABASE {

		public $wpdb;

		public function __construct() {

			register_activation_hook( WP_MANGA_FILE, array( $this, 'wp_manga_create_db' ) );

		}

		function get_wpdb() {

			global $wpdb;

			return $wpdb;

		}

		function wp_manga_create_db() {

			$args = array(
				'volume_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'post_id bigint(20) UNSIGNED NOT NULL',
				'volume_name text NOT NULL',
				'date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
				'date_gmt datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
			);

			$this->create_table( 'manga_volumes', $args );

			$args = array(
				'chapter_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'post_id bigint(20) UNSIGNED NOT NULL',
				'volume_id  bigint(20) UNSIGNED NULL',
				'chapter_name text NOT NULL',
				'chapter_name_extend text NOT NULL',
				'chapter_slug text NOT NULL',
				'date datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
				'date_gmt datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
			);

			$this->create_table( 'manga_chapters', $args );

		}

		function create_table( $name, $args ) {

			if ( ! is_array( $args ) || empty( $args ) ) {
				return false;
			}

			$charset_collate = $this->get_wpdb()
			                        ->get_charset_collate();
			$table_name      = $this->get_wpdb()->prefix . $name;

			$sql = "CREATE TABLE $table_name (
	            " . implode( ', ', $args ) . "
	        ) $charset_collate;";

			$this->maybe_create_table( $table_name, $sql );

		}

		function maybe_create_table( $table_name, $create_ddl ) {

		    global $wpdb;

		    $query = $wpdb->prepare( "SHOW TABLES LIKE %s", $wpdb->esc_like( $table_name ) );

		    if ( $wpdb->get_var( $query ) == $table_name ) {
		        return true;
		    }

		    // Didn't find it try to create it..
		    $wpdb->query($create_ddl);

		    // We cannot directly tell that whether this succeeded!
		    if ( $wpdb->get_var( $query ) == $table_name ) {
		        return true;
		    }
		    return false;
		}

		function insert( $table, $args ) {

			$this->get_wpdb()
			     ->insert( $table, $args );

			if ( isset( $this->get_wpdb()->insert_id ) ) {
				return $this->get_wpdb()->insert_id;
			}

			return false;

		}

		function get( $table, $where, $orderBy, $order, $num_limit = '' ) {

			$sort_setting = $this->get_sort_setting();

			$sort_by    = $sort_setting['sortBy'];
			$sort_order = $sort_setting['sort'];

			if( !empty( $orderBy ) ){
				$sort_by = $orderBy;
				$sort_order = !empty( $order ) ? $order : 'desc';
			}

			if( $sort_by == 'date' ){
				$sql = "
							SELECT SQL_CACHE *
							FROM $table
						";

				if( !empty( $where ) ){
					$sql .= "WHERE $where";
				}

				$sql .= "
							ORDER BY $sort_by $sort_order
						";
				if($num_limit){
					$sql .= " LIMIT $num_limit" ;                                      
                }
			}else{
				$sql = "
							SELECT SQL_CACHE *
							FROM $table
						";

				if( !empty( $where ) ){
					$sql .= "WHERE $where";
				}
				if($num_limit){
					$sql .= " LIMIT $num_limit" ;                                      
                }
			}

			$results = $this
			->get_wpdb()
			->get_results( $sql, 'ARRAY_A' );

			if( $results && $sort_by == 'name' ){

				if( strpos( $table, 'chapters' ) !== false ){
					$column = 'chapter_name';
				}elseif( strpos( $table, 'volumes' ) !== false ){
					$column = 'volume_name';
				}

				if( isset( $column ) ){

					//bring column name to be key of results array
					$results = array_combine( array_column( $results, $column ), $results );

					//get column values as an separated array and sort name with natcasesort
					$results_order = array_column( $results, $column );
					natcasesort( $results_order );

					//put appropiate values to sorted position
					$output_results = array();
					foreach( $results_order as $result ){
						array_push( $output_results, $results[$result] );
					}

					if( !empty( $sort_order ) && $sort_order == 'desc' ){
						$results = array_reverse( $output_results );
					}else{
						$results = $output_results;
					}

				}
			}

			return $results;

		}

		function update( $table, $data, $where ) {

			return $this->get_wpdb()
			            ->update( $table, $data, $where );

		}

		function delete( $table, $where ) {

			return $this->get_wpdb()
			            ->delete( $table, $where );

		}

		function get_sort_setting(){

			//get sort option
			if( class_exists( 'App\Madara' ) ){
				$sort_option = App\Madara::getOption('manga_chapters_order', 'name_desc');
			}else{
				$sort_option = 'name_desc';
			}

			if( in_array( $sort_option, array( 'name_desc', 'name_asc' ) ) ){
				$sort_option = array(
					'sortBy' => 'name',
					'sort'   => $sort_option == 'name_desc' ? 'desc' : 'asc'
				);
			}else{
				$sort_option = array(
					'sortBy' => 'date',
					'sort'   => $sort_option == 'date_desc' ? 'desc' : 'asc',
				);
			}

			return $sort_option;

		}
	}

	$GLOBALS['wp_manga_database'] = new WP_MANGA_DATABASE();
