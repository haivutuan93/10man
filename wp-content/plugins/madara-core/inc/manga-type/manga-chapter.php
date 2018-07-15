<?php

	/**
	 * Text Chapter for WP Manga
	 **/

	class WP_MANGA_CHAPTER {

		function __construct() {

		}

		/**
		 * Parse Manga Nav for Manga Chapter
		 */

		function manga_nav( $args ) {

			global $wp_manga_functions, $wp_manga_template, $wp_manga_chapter, $wp_manga, $wp_manga_setting;

			extract( $args );

			$style       = ! empty( $_GET['style'] ) ? $_GET['style'] : 'paged';
			$single_chap = $wp_manga->get_single_chapter( get_the_ID(), $chapter['chapter_id'] );
			$inUse       = $single_chap['storage']['inUse'];

			$hosting_selection = $wp_manga_setting->get_manga_option( 'hosting_selection', true );
			$s_host = isset( $_GET['host'] ) && $hosting_selection ? $_GET['host'] : null;

			global $wp_manga_volume, $wp_manga_storage;
			$all_vols = $wp_manga_volume->get_manga_volumes( get_the_ID() );
			$cur_vol = get_query_var( 'volume' );

			?>
            <div class="wp-manga-nav">
                <div class="select-view">

                    <?php
						if( $hosting_selection ){ ?>
							<!-- select host -->
							<div class="c-selectpicker selectpicker_version">
								<label>
									<?php
									$host_arr = $wp_manga->get_chapter_hosts( get_the_ID(), $chapter['chapter_id'] );
									?>
									<select class="selectpicker host-select">
										<?php

										if ( $s_host ) {
											$inUse = $s_host;
										}

										foreach ( $host_arr as $h ) {
											$host_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, $style, $h );
											?>
											<option class="short" data-limit="40" value="<?php echo $h ?>" data-redirect="<?php echo esc_url( $host_link ); ?>" <?php selected( $h, $inUse, true ) ?>><?php esc_html_e( 'Host: ', WP_MANGA_TEXTDOMAIN ); ?><?php echo $h; ?></option>
										<?php }
										?>
									</select> </label>
								</div>
						<?php }
					?>

					<!-- select volume -->
					<?php

						if( !empty( $all_vols ) ){
							?>
								<div class="c-selectpicker selectpicker_volume">
								<label>
									<select class="selectpicker volume-select">
										<?php foreach ( $all_vols as $vol ) { ?>
												<?php
													$vol_slug = $wp_manga_storage->slugify( $vol['volume_name'] );
													if( $vol_slug == $cur_vol ){
														$cur_vol_id = $vol['volume_id'];
													}
												?>
												<option class="short" data-limit="40" value="<?php echo $vol['volume_id'] ?>" <?php selected( $vol_slug, $cur_vol, true ) ?>>
													<?php echo esc_attr( $vol['volume_name'] ); ?>
												</option>
										<?php } ?>
									</select>
								</label>
							</div>
							<?php
						}
					?>

                    <!-- select chapter -->
                    <div class="chapter-selection">
						<?php
							if( !in_array( $chapter['volume_id'], array_column( $all_vols, 'volume_id' ) ) ){
								array_push( $all_vols, array(
									'volume_id' => $chapter['volume_id']
								) );
							}
							$this_vol_all_chaps = $all_chaps;
						?>
						<?php foreach( $all_vols as $vol ){ ?>
							<?php
								if( $vol['volume_id'] == $chapter['volume_id'] ){
									$all_chaps = $this_vol_all_chaps;
								}else{
									$all_chaps = $wp_manga_volume->get_volume_chapters( get_the_ID(), $vol['volume_id'], 'name', 'asc' );
								}

								if( empty( $all_chaps ) ){
									continue;
								}

								$is_current_vol = $chapter['volume_id'] == $vol['volume_id'] ? true : false;
							?>
							<div class="c-selectpicker selectpicker_chapter" for="volume-id-<?php echo esc_attr( $vol['volume_id'] ); ?>" <?php echo !$is_current_vol ? 'style="display:none;"' : '';?> >
		                        <label>
									<select class="selectpicker single-chapter-select">

										<?php if( !$is_current_vol ){ ?>
											<option><?php esc_html_e('Select Chapter', WP_MANGA_TEXTDOMAIN); ?></option>
										<?php } ?>

										<?php
											foreach ( $all_chaps as $chap ) {
												//$link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chap['chapter_slug'], $style );
												$link = $wp_manga_functions->build_chapter_url_not_by_slug( get_the_ID(),$chap, $chap['chapter_slug'], $style );
												?>
		                                        <option class="short" data-limit="40" value="<?php echo $chap['chapter_slug'] ?>" data-redirect="<?php echo esc_url( $link ) ?>" <?php selected( $chap['chapter_slug'], $cur_chap, true ) ?>>
													<?php echo esc_attr( $chap['chapter_name'] . $wp_manga_functions->filter_extend_name( $chap['chapter_name_extend'] ) ); ?>
												</option>
											<?php }
										?>
		                            </select>
								</label>
		                    </div>
						<?php } ?>
                    </div>

                    <!-- select page style -->
                    <div class="c-selectpicker selectpicker_load">
						<?php
							$list_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, 'list', $s_host );

							$paged_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, 'paged', $s_host );
						?>
                        <label>
							<select class="selectpicker reading-style-select">
								<option data-redirect="<?php echo esc_url( $list_link ); ?>" <?php selected( 'list', $style ); ?>><?php esc_html_e( 'List style', WP_MANGA_TEXTDOMAIN ); ?></option>
                                <option data-redirect="<?php echo esc_url( $paged_link ); ?>" <?php selected( 'paged', $style ); ?>><?php esc_html_e( 'Paged style', WP_MANGA_TEXTDOMAIN ); ?></option>
                            </select>
						</label>
                    </div>

                </div>
				<?php
					if ( 'paged' == $style ) {
						$current_page = isset( $_GET['manga-paged'] ) ? $_GET['manga-paged'] : 1;
						$total_page   = isset( $single_chap['total_page'] ) ? $single_chap['total_page'] : '';
						$this->manga_pager( $current_page, $single_chap['total_page'], $style, $this_vol_all_chaps );
					}elseif( $style == 'list' ){
						$this->manga_list_navigation( $this_vol_all_chaps, $cur_chap );
					}
				?>
            </div>

			<?php
		}

		function manga_list_navigation( $all_chaps, $cur_chap ){

			global $wp_manga_functions;

			$page_style = 'list';

			$cur_chap_index = array_search( $cur_chap, array_column( $all_chaps, 'chapter_slug' ) );
			$prev_chap = isset( $all_chaps[$cur_chap_index - 1] ) ? $all_chaps[$cur_chap_index - 1] : null;
			$next_chap = isset( $all_chaps[$cur_chap_index + 1] ) ? $all_chaps[$cur_chap_index + 1] : null;
			?>
				<div class="select-pagination">
					<div class="nav-links">
						<?php if ( $prev_chap ): ?>
							<?php $prev_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $prev_chap['chapter_slug'], $page_style ); ?>
							<div class="nav-previous"><a href="<?php echo $prev_link; ?>" class="btn prev_page"><?php esc_html_e('Prev', WP_MANGA_TEXTDOMAIN); ?></a>
							</div>
						<?php endif ?>
						<?php if ( $next_chap ): ?>
							<?php $next_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $next_chap['chapter_slug'], $page_style ); ?>
							<div class="nav-next"><a href="<?php echo $next_link ?>" class="btn next_page"><?php esc_html_e('Next', WP_MANGA_TEXTDOMAIN); ?></a></div>
						<?php endif ?>
					</div>
				</div>
			<?php

			//put prev and next link to global variable, so other function from other place can get it
			if( !empty( $next_link ) ){
				$GLOBALS['madara_next_page_link'] = $next_link;
			}
			if( !empty( $prev_link ) ){
				$GLOBALS['madara_prev_page_link'] = $prev_link;
			}

		}

		function manga_pager( $cur_page, $total_page, $style, $all_chaps ) {

			global $wp_manga_functions;

			$cur_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
			$cur_chap = get_query_var( 'chapter' );
			$link     = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, $style, $cur_host );
			$using_ajax = function_exists('madara_page_reading_ajax') && madara_page_reading_ajax();
			//get prev and next chap url
			$cur_chap_index = array_search( $cur_chap, array_column( $all_chaps, 'chapter_slug' ) );

			if( isset( $all_chaps[ $cur_chap_index + 1 ] ) ){
				$next_chap = $all_chaps[ $cur_chap_index + 1 ]['chapter_slug'];
				$next_chap_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $next_chap, $style, $cur_host );
			}

			if( isset( $all_chaps[ $cur_chap_index - 1 ] ) ){
				$prev_chap = $all_chaps[ $cur_chap_index - 1 ]['chapter_slug'];
				$prev_chap_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $prev_chap, $style, $cur_host );
			}

			$prev_page = intval( $cur_page ) - 1;
			if ( $prev_page != 0 ) {
				if( $using_ajax ){
					$prev_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $cur_chap, $prev_page, $total_page );
					$prev_link = '#';
				}else{
					$prev_link = add_query_arg( array( 'manga-paged' => $prev_page ), $link );
				}
			} else {
				$prev_ajax_params = '0';
				if( isset( $prev_chap_link ) && !$using_ajax ){
					$prev_link = $prev_chap_link;
				}elseif( $using_ajax ){
					$prev_link = 'javascript:void(0)';
				}else{
					$prev_link = false;
				}
			}

			if ( function_exists( 'madara_get_img_per_page' ) ) {
				$img_per_page = madara_get_img_per_page();
			} else {
				$img_per_page = 1;
			}

			if ( ! empty( $img_per_page ) && $img_per_page != '1' && is_numeric( $img_per_page ) ) {
				$total_page   = intval( $total_page );
				$img_per_page = intval( $img_per_page );

				$total_page = intval( $total_page / $img_per_page ) < $total_page / $img_per_page ? intval( $total_page / $img_per_page ) + 1 : intval( $total_page / $img_per_page );
			}

			$next_page = intval( $cur_page ) + 1;

			if ( intval( $next_page ) <= intval( $total_page ) ) {
				if( $using_ajax ){
					$next_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $cur_chap, $next_page, $total_page );
					$next_link = '#';
				}else{
					$next_link = add_query_arg( array( 'manga-paged' => $next_page ), $link );
				}
			} else {
				$next_ajax_params = '0';
				if( isset( $next_chap_link ) && !$using_ajax ){
					$next_link = $next_chap_link;
				}elseif( $using_ajax ){
					$next_link = '#';
				}else{
					$next_link = false;
				}
			}

			if( $using_ajax ){

				$params = array(
					'chapter' => $cur_chap,
					'postID' => get_the_ID()
				);

				if( isset( $prev_chap_link ) ){
					$params['prev_chap_url'] = $prev_chap_link;
				}

				if( isset( $next_chap_link ) ){
					$params['next_chap_url'] = $next_chap_link;
				}

				wp_localize_script( 'wp-manga', 'reading_ajax_params', $params );
			}

			?>
            <div class="select-pagination">
                <div class="c-selectpicker selectpicker_page">
                    <label>
						<select id="single-pager" class="selectpicker">
							<?php for ( $i = 1; $i <= intval( $total_page ); $i ++ ) { ?>

									<?php
										$data_redirect = 'data-redirect="' .  add_query_arg( array( 'manga-paged' => $i ), $link ) . '"';
									?>
                                    <option value="<?php echo $i ?>" <?php echo $data_redirect; ?> <?php selected( $i, $cur_page, true ) ?>>
										<?php echo $i . '/' . $total_page; ?>
									</option>
							<?php }?>
                        </select>
					</label>
                </div>
                <div class="nav-links">
					<?php if ( $prev_link ): ?>
                        <div class="nav-previous">
							<a href="<?php echo $prev_link; ?>" class="btn prev_page" >
								<?php esc_html_e('Prev', 'madara'); ?>
							</a>
                        </div>
					<?php endif ?>
					<?php if ( $next_link ): ?>
                        <div class="nav-next">
							<a href="<?php echo $next_link ?>" class="btn next_page">
								<?php esc_html_e('Next', 'madara'); ?>
							</a>
						</div>
					<?php endif ?>
                </div>
            </div>
			<?php

			//put prev and next link to global variable, so other function from other place can get it
			if( !empty( $next_link ) ){
				$GLOBALS['madara_next_page_link'] = $next_link;
			}
			if( !empty( $prev_link ) ){
				$GLOBALS['madara_prev_page_link'] = $prev_link;
			}

		}

		function chapter_navigate_ajax_params( $post_id, $cur_chap, $paged, $total_page ){

			$params = array(
				'postID'      => $post_id,
				'chapter'     => $cur_chap,
				'manga-paged' => $paged,
				'style'       => 'paged',
				'total-page'  => $total_page,
				'action'      => 'chapter_next_page'
			);

			return http_build_query( $params );
		}

	}

	$GLOBALS['wp_manga_chapter_type'] = new WP_MANGA_CHAPTER();
