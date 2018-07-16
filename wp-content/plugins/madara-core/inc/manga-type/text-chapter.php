<?php

    /**
 	 * Text Chapter for WP Manga
 	 **/

    class WP_MANGA_TEXT_CHAPTER{

        function manga_nav( $args ){

            global $wp_manga_template, $wp_manga, $wp_manga_functions, $wp_manga_chapter;

            extract( $args );
			$single_chap = $wp_manga->get_single_chapter( get_the_ID(), $chapter['chapter_id'] );
			global $wp_manga_volume, $wp_manga_storage;
			$all_vols = $wp_manga_volume->get_manga_volumes( get_the_ID() );
			$cur_vol = get_query_var( 'volume' );

            ?>

            <div class="wp-manga-nav">
                <div class="select-view">
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
                    
                    <div class="chapter-selection">
                        <?php
                        foreach ( $all_chaps as $chap ) {

                                        //$link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chap['chapter_slug'] );
										$link = $wp_manga_functions->build_chapter_url_not_by_slug( get_the_ID(),$chap, $chap['chapter_slug'] );

//                                        if( isset( $cur_chap_passed ) && !isset( $next_chap ) ){
//                                            $next_chap = $link;
//                                        }
                                        if( isset( $cur_chap_passed ) && !isset( $prev_chap ) ){
                                            $prev_chap = $link;
                                        }

                                        if( $chap['chapter_slug'] == $cur_chap ){
                                            $cur_chap_passed = true;
                                            $cur_chap_link = $link;
                                        }

                                        //always set current chap in loop as $prev_chap, stop once current chap is passed
//                                        if( !isset( $cur_chap_passed ) ){
//                                            $prev_chap = $link;
//                                        }
                                        if( !isset( $cur_chap_passed ) ){
                                            $next_chap = $link;
                                        }
                        }
                        ?>
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
											<option><?php esc_html_e('Chọn Chapter', WP_MANGA_TEXTDOMAIN); ?></option>
										<?php } ?>

										<?php
											foreach ( $all_chaps as $chap ) {
												//$link = $wp_manga_functions->build_chapter_url( get_the_ID(), $chap['chapter_slug'], $style );
												$link = $wp_manga_functions->build_chapter_url_not_by_slug( get_the_ID(),$chap, $chap['chapter_slug'] );
												?>
                                                                                        <option class="short" label="<?php echo esc_attr( $chap['chapter_name'] . $wp_manga_functions->filter_extend_name( $chap['chapter_name_extend'] ) ); ?>" data-limit="40" value="<?php echo $chap['chapter_slug'] ?>" data-redirect="<?php echo esc_url( $link ) ?>" <?php selected( $chap['chapter_slug'], $cur_chap, true ) ?>>
													<?php echo esc_attr( $chap['chapter_name'] . $wp_manga_functions->filter_extend_name( $chap['chapter_name_extend'] ) ); ?>
												</option>
											<?php }
										?>
		                            </select>
								</label>
		                    </div>
						<?php } ?>
                    </div>

                </div>

                <div class="select-pagination">
                    <div class="nav-links">
                        
                        
                        <?php if ( isset( $prev_chap ) && $prev_chap !== $cur_chap_link ): ?>
                            <div class="nav-previous"><a href="<?php echo $prev_chap; ?>" class="btn prev_page">Trước</a>
                            </div>
                        <?php endif ?>
                        <?php if ( isset( $next_chap ) ): ?>
                            <div class="nav-next"><a href="<?php echo $next_chap ?>" class="btn next_page"><?php esc_html_e('Sau', WP_MANGA_TEXTDOMAIN ); ?></a></div>
                        <?php endif ?>
                    </div>
                </div>

            </div>

            <?php

        }

        /**
     	 * Get chapter_content post type which contains content for this chapter
    	 */
        function get_chapter_content_post( $chapter_id ){

            $chapter_post_content = new WP_Query(
                array(
                    'post_parent' => $chapter_id,
                    'post_type'   => 'chapter_text_content'
                )
            );

            if( $chapter_post_content->have_posts() ){
                return $chapter_post_content->posts[0];
            }

            return false;

        }

        /**
        * Handle insert Chapter Content Type
        */
        function insert_chapter( $args ){

            global $wp_manga_chapter, $wp_manga_storage;

            $chapter_content = $args['chapter_content'];
            unset( $args['chapter_content'] );

            //post_id require, volume id, chapter name, chapter extend name, chapter slug
    		$chapter_args = array_merge(
                $args,
                array(
                    'chapter_slug' => $wp_manga_storage->slugify( $args['chapter_name'] )
                )
            );

    		$chapter_id = $wp_manga_chapter->insert_chapter( $chapter_args );

    		if( !$chapter_id ){
    			wp_send_json_error( array( 'message' => esc_html__('Cannot insert Chapter', WP_MANGA_TEXTDOMAIN ) ) );
    		}

    		$chapter_content_args = array(
    			'post_type'    => 'chapter_text_content',
    			'post_content' =>  $chapter_content ,
    			'post_status'  => 'publish',
    			'post_parent'  => $chapter_id, //set chapter id as parent
    		);

    		$resp = wp_insert_post( $chapter_content_args );

            return $resp;

        }

        function upload_handler( $post_id, $zip_file, $volume_id = 0 ){
            $title_manga = get_the_title($post_id);

            global $wp_manga_functions, $wp_manga, $wp_manga_storage, $wp_manga_volume;

    		$uniqid = $wp_manga->get_uniqid( $post_id );

    		$temp_name = $zip_file['tmp_name'];
    		$temp_dir_name = $wp_manga_storage->slugify( explode( '.', $zip_file['name'] )[0] );

    		//open zip
    		$zip_manga = new ZipArchive();

    		if( ! $zip_manga->open( $temp_name ) ) {
    			wp_send_json_error( __('Cannot open Zip file ', 'madara' ) );
    		}

            $extract = WP_MANGA_DATA_DIR . $uniqid . '/' . $temp_dir_name;

            $zip_manga->extractTo( $extract );
		    $zip_manga->close();

            //scan all dir
    		$scandir_lv1 = glob( $extract . '/*' );
    		$result = array();

            $is_invalid_zip_file = true;

    		//Dir level 1
    		foreach( $scandir_lv1 as $dir_lv1 ) {

    			if( basename( $dir_lv1 ) === '__MACOSX' ){
    				continue;
    			}

    			if( is_dir( $dir_lv1 ) ) {

    				$has_volume = true;

                    foreach( glob( $dir_lv1 . '/*' ) as $dir_lv2 ) {

    					if( basename( $dir_lv2 ) === '__MACOSX' ){
    						continue;
    					}

    					//if dir level 2 is dir then dir level 1 is volume
    					if( is_dir( $dir_lv2 ) && $has_volume == true ) {

    						//By now, dir lv1 is volume. Then check if this volume is already existed or craete a new one
    						$this_volume = $wp_manga_volume->get_volumes(
    							array(
    								'post_id' => $post_id,
    								'volume_name' => basename( $dir_lv1 ),
    							)
    						);

    						if( $this_volume == false ){
    							$this_volume = $wp_manga_storage->create_volume( basename( $dir_lv1 ), $post_id );
    						}else{
    							$this_volume = $this_volume[0]['volume_id'];
    						}

                            $chapters = glob( $dir_lv2 . '/*' );

                            foreach( $chapters as $chapter ){
                                //create chapter
                                $chapter_args = array(
                                    'post_id'             => $post_id,
                                    'chapter_name'        => basename( $dir_lv2 ),
                                    'chapter_name_extend' => '',
                                    'volume_id'           => $this_volume,
                                    'chapter_content'     => file_get_contents( $chapter )
                                );

                                $this->insert_chapter( $chapter_args );
                                $manga_chapter_slug = $wp_manga_storage->slugify($title_manga) . '/' . $wp_manga_storage->slugify(basename($dir_lv1)) . '/' . $wp_manga_storage->slugify(basename($dir_lv2)) . '/';
                                $var_manga = array();
                                $var_manga['id'] = $post_id;
                                $var_manga['chapter_slug'] = $manga_chapter_slug;
                                do_action('rikaki_insert_manga_chapter', $var_manga);


                            }
    					}else{

                            if( $has_volume ){
                                $has_volume = false;
                            }

                            //create chapter
                            $chapter_args = array(
                                'post_id'             => $post_id,
                                'chapter_name'        => basename( $dir_lv1 ),
                                'chapter_name_extend' => '',
                                'volume_id'           => $volume_id,
                                'chapter_content'     => file_get_contents( $dir_lv2 )
                            );

                            $this->insert_chapter( $chapter_args );
                            $this->insert_chapter($chapter_args);
                            $manga_chapter_slug = $wp_manga_storage->slugify($title_manga) . '/' .$wp_manga_storage->slugify(basename($dir_lv1)) . '/';
                            $var_manga = array();
                            $var_manga['id'] = $post_id;
                            $var_manga['chapter_slug'] = $manga_chapter_slug;
                            do_action('rikaki_insert_manga_chapter', $var_manga);
                        }

    				}
    			}else{
                    $is_invalid_zip_file = false;
                }
    		}

    		$wp_manga_storage->local_remove_storage( $extract );

            if( !$is_invalid_zip_file ){
                return array(
                    'success' => false,
                    'message' => esc_html__('Upload failed', 'madara')
                );
            }

            return array(
                'success' => true,
                'message' => esc_html__('Upload successfully', 'madara')
            );
        }
    }

    $GLOBALS['wp_manga_text_type'] = new WP_MANGA_TEXT_CHAPTER();
