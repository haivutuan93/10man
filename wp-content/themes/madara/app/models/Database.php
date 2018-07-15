<?php

	namespace App\Models;

	use App\Madara;

	class Database extends Data {
		/**
		 * Query Posts based on various conditions
		 *
		 * @param $conditions - select
		 * 'featured' - Only posts marked as featured (having metadata '_ct_featured'). You can change this metadata name using $extend_args['featured_meta_key']
		 * 'most_liked' - Used with WTI Like Posts plugin. You can set $extend_args['timerange'] (day, week, month, year, all) to narrow the query
		 * 'most_viewed' - Used with BAW Post Views Count or Top 10 plugin. You can set $extend_args['timerange'] (day, week, month, year, all) to narrow the query
		 * 'most_commented' - You can set $extend_args['timerange'] (day, week, month, year, all) to narrow the query
		 * 'latest' - Latest post (default)
		 * 'random', 'rand' - Get posts randomly
		 * 'title' - Alpha-beta order
		 * 'modified' - Modified Date
		 * @param $number - int - count
		 * @param $sort - ASC/DESC
		 * @param $paged - int - Current page, start at 1
		 * @param $extend_args - array - more arguments (optional)
		 * 'categories' - array of category IDs or slugs
		 * 'category_taxonomy' - name of custom taxonomy for category, if different than default
		 * 'tags' - array of tags
		 * 'tag_taxonomy' - name of custom taxonomy for tag, if different than default
		 * 'post_type' - string - Post Type to query, default 'post'
		 * 'timerange' - select - Time Range to query. Possible values: day, week, month, year, all
		 * 'ids' - array - To query specific posts by their ids
		 * 'featured_meta_key' - string - name of meta_key used to mark a post Featured. Used with condition 'featured'
		 * 'viewed_meta_key' - string - name of meta_key used to store Post Views Count. Used with condition 'most_viewed' (if BAW or Top 10 is not installed)
		 * 'post_status' - string - Post Status
		 * 'meta_query_value' - array - array meta value
		 * 'key' - string - meta key
		 * 'meta_query_posttype' - string - in case 'meta_query_value' is string/array of string, which is post slug, 'meta_query_posttype' helps to convert slug into ID
		 *
		 * @return WP_Query
		 */
		public static function getPosts(
			$number = - 1,
			$sort = 'DESC',
			$paged = 1,
			$conditions = 'latest',
			$extend_args = array()
		) {
			extract( $extend_args );
			if ( ! isset( $post_type ) ) {
				$post_type = 'post';
			}
			if ( ! isset( $timerange ) ) {
				$timerange = 'all';
			}
			if ( ! isset( $post_status ) ) {
				$post_status = 'publish';
			}
			if ( ! isset( $ids ) ) {
				$ids = '';
			}
			if ( ! isset( $categories ) ) {
				$categories = '';
			}
			if ( ! isset( $tags ) ) {
				$tags = '';
			}
			if ( ! isset( $meta_query ) ) {
				$meta_query = '';
			}
			if ( ! isset( $meta_query_value ) ) {
				$meta_query_value = '';
			}
			if ( ! isset( $key ) ) {
				$key = '';
			}
			if ( ! isset( $meta_query_posttype ) ) {
				$meta_query_posttype = '';
			}


			$args = array(
				'post_type'           => $post_type,
				'posts_per_page'      => $number,
				'post_status'         => $post_status,
				'ignore_sticky_posts' => 1,
				'order'               => $sort,
				'paged'               => $paged,
			);

			if ( $conditions == 'featured' ) {

				$args['meta_query'] = array(
					array(
						'key'   => isset( $extend_args['featured_meta_key'] ) ? $extend_args['featured_meta_key'] : '_ct_featured',
						'value' => 'yes',
					),
					array(
						'key'   => isset( $extend_args['featured_meta_key'] ) ? $extend_args['featured_meta_key'] : '_ct_featured',
						'value' => '1',
					),
					'relation' => 'OR'
				);

			} elseif ( $conditions == 'most_liked' ) {

				global $wpdb;
				if ( $timerange == 'day' ) {
					$time_range = '1';
				} else if ( $timerange == 'week' ) {
					$time_range = '7';
				} else if ( $timerange == 'month' ) {
					$time_range = '1m';
				} else if ( $timerange == 'year' ) {
					$time_range = '1y';
				}

				$order_by = 'ORDER BY like_count DESC, post_title';

				$limit = $where = '';

				if ( $number > 0 ) {
					$limit = "LIMIT " . $number;
				}

				$show_excluded_posts = get_option( 'wti_like_post_show_on_widget' );
				$excluded_post_ids   = explode( ',', get_option( 'wti_like_post_excluded_posts' ) );

				if ( ! $show_excluded_posts && count( $excluded_post_ids ) > 0 ) {
					$where .= "AND post_id NOT IN (" . get_option( 'wti_like_post_excluded_posts' ) . ")";
				}

				if ( $timerange != 'all' ) {
					if ( function_exists( 'GetWtiLastDate' ) ) {
						$last_date = GetWtiLastDate( $time_range );
						$where     .= " AND date_time >= '$last_date'";
					}
				}

				$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";

				$query .= $wpdb->prepare( "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > 0 %s GROUP BY post_id %s %s", $where, $order_by, $limit );

				$posts  = $wpdb->get_results( $query );
				$p_data = array();
				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$p_data[] = $post->post_id;
					}
				}

				$args = array_merge( $args, array(
					'orderby'  => 'post__in',
					'order'    => 'DESC',
					'post__in' => $p_data
				) );

			} elseif ( $conditions == 'most_viewed' ) {

				// check if BAW or Top 10 is integrated
				if ( function_exists( 'get_tptn_pop_posts' ) ) {
					$ids = '';

					$offset = 0;

					if ( $paged && $paged > 0 && $number > - 1 ) {
						$offset = ( $paged - 1 ) * $number;
					}

					if ( $timerange == 'day' ) {

						$settings = array(
							'daily'        => true,
							'daily_range'  => 1,
							'post_types'   => 'post',
							'limit'        => $number,
							'offset'       => $offset,
							'strick_limit' => false,
						);

						$ids = get_tptn_pop_posts( $settings );
						$ids = wp_list_pluck( $ids, 'postnumber' );

					} elseif ( $timerange == 'week' ) {

						$settings = array(
							'daily'        => true,
							'daily_range'  => 7,
							'post_types'   => 'post',
							'limit'        => $number,
							'offset'       => $offset,
							'strick_limit' => false,
						);
						$ids      = get_tptn_pop_posts( $settings );
						$ids      = wp_list_pluck( $ids, 'ID' );

					} elseif ( $timerange == 'month' ) {

						$settings = array(
							'daily'        => true,
							'daily_range'  => 30,
							'post_types'   => 'post',
							'limit'        => $number,
							'offset'       => $offset,
							'strick_limit' => false,
						);
						$ids      = get_tptn_pop_posts( $settings );
						$ids      = wp_list_pluck( $ids, 'postnumber' );

					} elseif ( $timerange == 'year' ) {

						$settings = array(
							'daily'        => true,
							'daily_range'  => 365,
							'post_types'   => 'post',
							'limit'        => $number,
							'offset'       => $offset,
							'strick_limit' => false,
						);
						$ids      = get_tptn_pop_posts( $settings );
						$ids      = wp_list_pluck( $ids, 'postnumber' );

					} else {

						$settings = array(
							'daily'        => 0,
							'post_types'   => 'post',
							'limit'        => $number,
							'offset'       => $offset,
							'strick_limit' => false,
						);
						$ids      = get_tptn_pop_posts( $settings );
						$ids      = wp_list_pluck( $ids, 'postnumber' );
					}

					if ( isset( $ids ) ) {
						$args = array_merge( $args, array(
							'post__in' => $ids,
							'orderby'  => 'post__in'
						) );
					}

				} elseif ( function_exists( 'bawpvc_main' ) ) {

					if ( $timerange == 'day' ) {
						$meta_key = '_count-views_day-' . date( "Ymd" );
					} else if ( $timerange == 'week' ) {
						$meta_key = '_count-views_week-' . date( "YW" );
					} else if ( $timerange == 'month' ) {
						$meta_key = '_count-views_month-' . date( "Ym" );
					} else if ( $timerange == 'year' ) {
						$meta_key = '_count-views_year-' . date( "Y" );
					} else {
						$meta_key = '_count-views_all';
					}

					$args = array_merge( $args, array(
						'meta_key' => $meta_key,
						'orderby'  => 'meta_value_num',
						'order'    => 'DESC',
					) );

				} elseif ( isset( $extend_args['viewed_meta_key'] ) ) {
					$args = array_merge( $args, array(
						'meta_key' => $extend_args['viewed_meta_key'],
						'orderby'  => 'meta_value_num',
						'order'    => 'DESC',
					) );
				}

			} else if ( $conditions == 'most_commented' ) {
				if ( $timerange == 'all' ) {
					$args = array_merge( $args, array(
						'orderby' => 'comment_count'
					) );
				} else {
					if ( $timerange == 'day' ) {
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 day ago',
								),
							),
						) );
					} else if ( $timerange == 'week' ) {
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 week ago',
								),
							),
						) );
					} else if ( $timerange == 'month' ) {
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 month ago',
								),
							),
						) );
					} else if ( $timerange == 'year' ) {
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 year ago',
								),
							),
						) );
					}

					$arr_id = array();

					foreach ( $some_comments as $comment ) {
						$arr_id[] = $comment->comment_post_ID;
					}

					$arr_id = array_unique( $arr_id, SORT_REGULAR );

					if ( count( $arr_id ) > 0 ) {
						$args = array_merge( $args, array(
							'post__in' => $arr_id
						) );
					}
				}
			} else if ( $meta_query_value != '' ) {

				$meta_query_value = explode( ",", $meta_query_value );

				$args['meta_query'] = array(
					'relation' => 'OR',
				);

				foreach ( $meta_query_value as $value ) {

					if ( $meta_query_posttype != '' ) {
						if ( ! is_numeric( $value ) ) {
							$obj = get_page_by_path( $value, OBJECT, $meta_query_posttype );

							if ( $obj ) {
								$value = $obj->ID;
							} else {
								$value = '';
							}

						}
					}

					if ( $value != '' ) {

						$args['meta_query'][] = array(
							'key'     => $key,
							'value'   => $value,
							'compare' => 'LIKE'
						);
					}

				}

			} else if ( isset( $meta_query ) && $meta_query ) {

				$args['meta_query'] = $meta_query;

			} else {
				// other conditions (random, modified, title)

				if ( $conditions == 'random' ) {
					$conditions = 'rand';
				}

				if ( $conditions != 'latest' ) {

					if ( $args['post_type'] == 'wp-manga' && $conditions == 'modified' ) {
						$args = array_merge( $args, array(
							'orderby'  => 'meta_value_num',
							'meta_key' => '_latest_update',
						) );
					} else {
						$args = array_merge( $args, array(
							'orderby' => $conditions // rand, modified, title

						) );
					}

				}


				if ( $timerange != 'all' ) {
					if ( $timerange == 'week' ) {
						$number_day = '7';
					} elseif ( $timerange == 'day' ) {
						$number_day = '1';
					} elseif ( $timerange == 'month' ) {
						$number_day = '30';
					} elseif ( $timerange == 'year' ) {
						$number_day = '365';
					}

					$limit_date = date( 'Y-m-d', strtotime( '-' . $number_day . ' day' ) );

					$args = array_merge( $args, array(
						'date_query' => array(
							'after' => $limit_date
						)
					) );
				}
			}

			if ( isset( $ids ) && $ids != '' ) {
				if ( is_array( $ids ) ) {
					$id_arr = $ids;
				} else {
					$id_arr = explode( ",", $ids );
				}

				if ( count( $id_arr ) > 0 ) {
					// make sure array contains all integer. Safe-test by checking first item
					if ( is_numeric( $id_arr[0] ) ) {
						$args = array_merge( $args, array(
							'post__in' => $id_arr,
							'orderby'  => 'post__in'
						) );
					}
				}
			}

			$tax_query = null;

			if ( isset( $categories ) && $categories != '' ) {
				if ( ! is_array( $categories ) ) {
					$cats = explode( ',', $categories );
				} else {
					$cats = $categories;
				}

				if ( isset( $category_taxonomy ) && $category_taxonomy != '' ) {
					// custom taxonomy
					$tax_query = array(
						'relation' => 'AND',
						array(
							'taxonomy' => $category_taxonomy,
							'field'    => is_numeric( $cats[0] ) ? 'term_id' : 'slug',
							'terms'    => $cats,
							'operator' => 'IN'
						)
					);

				} else {
					// default category taxonomy
					if ( is_numeric( $cats[0] ) ) {
						$args['category__in'] = $cats;
					} else {
						$args['category_name'] = $categories;
					}
				}
			}

			if ( isset( $tags ) && $tags != '' ) {
				if ( ! is_array( $tags ) ) {
					$tags = explode( ',', $tags );
				}

				if ( isset( $tag_taxonomy ) && $tag_taxonomy != '' ) {
					// custom tag taxonomy
					if ( ! $tax_query ) {
						$tax_query = array();
					}

					$tax_query = array_push( $tax_query, array(
						'taxonomy' => $tag_taxonomy,
						'field'    => 'slug',
						'terms'    => $tags,
						'operator' => 'IN'
					) );
				} else {
					// default tag taxonomy
					$args = array_merge( $args, array( 'tag' => $tags ) );
				}

			}

			if ( $tax_query ) {
				$args['tax_query'] = $tax_query;
			}

			$query = new \WP_Query( $args );

			// make sure query has posts by removing date_query
			if ( ! $query->have_posts() ) {
				unset( $args['date_query'] );
				$query = new \WP_Query( $args );
			}

			// make sure query has posts by removing meta_query
			if ( ! $query->have_posts() ) {
				unset( $args['meta_query'] );
				$query = new \WP_Query( $args );
			}

			return $query;

		}

		/**
		 * @param integer $post_id
		 *
		 * @return void|\WP_Query
		 */
		public static function getRelatedPosts( $post_id = null ) {

			if ( ! $post_id ) {
				global $post;
				if ( $post ) {
					$post_id = $post->ID;
				} else {
					return;
				}
			}

			$number              = Madara::getOption( 'related_posts_count', 3 );
			$relatedPostsOrderBy = Madara::getOption( 'related_posts_order_by', 'date' ); // date or rand

			$args = array(
				'post_status'         => 'publish',
				'posts_per_page'      => $number,
				'orderby'             => $relatedPostsOrderBy,
				'ignore_sticky_posts' => 1,
				'post__not_in'        => array( $post_id )
			);

			$get_related_post_by = Madara::getOption( 'get_related_post_by' );

			if ( $get_related_post_by == 'cat' ) {
				$categories = wp_get_post_categories( $post_id );

				$args['category__in'] = $categories;
			} else {
				$posttags   = wp_get_post_tags( $post_id );
				$array_tags = array();
				if ( $posttags ) {
					foreach ( $posttags as $tag ) {
						$tags = $tag->term_id;
						array_push( $array_tags, $tags );
					}
				}

				$args['tag__in'] = $array_tags;
			}

			$related_items = new \WP_Query( $args );

			return $related_items;
		}

	}
