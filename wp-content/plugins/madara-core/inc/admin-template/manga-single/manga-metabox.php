<?php


    global $wp_manga, $wp_manga_functions, $post, $wp_manga_post_type, $wp_manga_setting;
    $post_id         = $post->ID;
    $uniqid          = $wp_manga->get_uniqid( $post_id );
    $chapter_type    = get_post_meta( $post_id, '_wp_manga_chapter_type', true );
    $alternative     = get_post_meta( $post_id, '_wp_manga_alternative', true );
    $type            = get_post_meta( $post_id, '_wp_manga_type', true );
    $release         = get_post_meta( $post_id, '_wp_manga_release', true );

    $default_storage = $wp_manga_setting->get_manga_option('default_storage', 'local');
    $allow_to_choose_chapter_type = $wp_manga_post_type->allow_to_choose_chapter_type( $post_id );

    $available_host = $wp_manga->get_available_host();

    $max_upload_file_size = $wp_manga_functions->max_upload_file_size();
    //print max upload file size
    wp_localize_script( 'wp-manga-upload', 'file_size_settings', $max_upload_file_size );

    $volume_dropdown_html = $wp_manga_functions->volume_dropdown( $post_id, false );
    ?>
    <input type="hidden" name="postID" value="<?php echo esc_attr( $post_id ); ?>">
    <input type="hidden" name="uniqid" value="<?php echo esc_attr( $uniqid ); ?>">
    <input type="hidden" name="wp-manga-chapter-type" value="<?php echo !empty( $chapter_type ) ? $chapter_type : 'manga'; ?>">
    <div class="wp-manga-info wp-manga-tabs-wrapper <?php echo $allow_to_choose_chapter_type ? 'choosing-manga-type' : ''; ?>">
        <div class="wp-manga-tabs">
            <ul>
                <li class="tab-active">
                    <a href="#manga-information"> <?php echo __( 'Manga Extra Info', WP_MANGA_TEXTDOMAIN ); ?> </a>
                </li>
                <li>
                    <a href="#chapter-listing"> <?php echo __( 'Manga Chapters List', WP_MANGA_TEXTDOMAIN ); ?> </a>
                </li>

                <?php if( $chapter_type == 'manga' || empty( $chapter_type ) ){ ?>

                    <li class="manga-tab-select">
                        <a href="#chapter-upload"> <?php echo __( 'Chapter Upload ', WP_MANGA_TEXTDOMAIN ); ?> </a>
                    </li>
                    <li class="manga-tab-select">
                        <a href="#manga-upload"> <?php echo __( 'Manga Upload', WP_MANGA_TEXTDOMAIN ); ?> </a>
                    </li>
                <?php } ?>

                <?php if( $chapter_type == 'text' || $allow_to_choose_chapter_type ){ ?>
                    <li class="text-tab-select">
                        <a href="#chapter-content"> <?php echo __( 'Text Chapter', WP_MANGA_TEXTDOMAIN ); ?> </a>
                    </li>
                    <li class="text-tab-select">
                        <a href="#chapter-content-upload"><?php esc_html_e('Upload Multi Chapters', WP_MANGA_TEXTDOMAIN ); ?></a>
                    </li>
                <?php } ?>

                <?php if( $chapter_type == 'video' || $allow_to_choose_chapter_type ){ ?>
                    <li class="video-tab-select">
                        <a href="#chapter-content"> <?php echo __( 'Video Chapter', WP_MANGA_TEXTDOMAIN ); ?> </a>
                    </li>
                    <li class="video-tab-select">
                        <a href="#chapter-content-upload"><?php esc_html_e('Upload Multi Chapters', WP_MANGA_TEXTDOMAIN); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div class="wp-manga-content">
            <!--manga information-->
            <div id="manga-information" style="display:block;" class="tab-content">
                <div id="extra-info">

                    <h2>
                        <span> <?php esc_html_e('Manga Extra Info', WP_MANGA_TEXTDOMAIN ); ?> </span>
                    </h2>

                    <label for="wp-manga-alternative"><h4> <?php esc_attr_e( 'Alternative Name', WP_MANGA_TEXTDOMAIN ) ?> </h4></label>
                    <input type="text" id="wp-manga-alternative" name="wp-manga-alternative" class="large-text" value="<?php echo esc_attr( $alternative ) ?>" tabindex="1">

                    <label for="wp-manga-type"><h4> <?php esc_attr_e( 'Type', WP_MANGA_TEXTDOMAIN ) ?> </h4></label>
                    <input type="text" id="wp-manga-type" name="wp-manga-type" class="large-text" value="<?php echo esc_attr( $type ) ?>" tabindex="1">

                </div>
                <div id="status-section"></div>
                <div id="release-year-section"></div>
                <div id="author-section"></div>
                <div id="artist-section"></div>
                <div id="genre-section"></div>
                <div id="tags-section"></div>
                <div id="views-section"></div>
            </div>

            <!--chapter list-->
            <div id="chapter-listing" class="tab-content">

                <!--search chapter-->
                <div class="search-chapter-section">
                    <input type="text" id="search-chapter" class="regular-text disable-submit" placeholder="<?php esc_html_e( 'Search Chapter', WP_MANGA_TEXTDOMAIN ); ?>">
                    <div class="search-chapter-icons">
                        <i class="fa fa-search" aria-hidden="true"></i>
                        <div class="wp-manga-spinner">
                          <div class="rect1"></div>
                          <div class="rect2"></div>
                          <div class="rect3"></div>
                          <div class="rect4"></div>
                          <div class="rect5"></div>
                        </div>
                    </div>
                </div>
                <div class="fetching-data hidden">
                    <span><?php esc_html_e('Fetching New Chapters Data', WP_MANGA_TEXTDOMAIN ); ?></span><i class="fa fa-spinner fa-spin"></i>
                </div>
                <!--start listing chapter-->
                <div class="chapter-list">
                    <?php
                        $all_chapters = $wp_manga_post_type->list_all_chapters( $post_id );
                        if( $all_chapters !== false ) {
                            echo $all_chapters;
                        }else{
                            esc_html_e( 'This Manga doesn\'t have any chapters yet. ', WP_MANGA_TEXTDOMAIN );
                        }
                    ?>
                </div>
            </div>

            <?php if( $chapter_type == 'manga' || empty( $chapter_type ) ){ ?>
                <!--manga chapter upload-->
                <div id="chapter-upload" class="tab-content manga-chapter-tab chapter-content-tab">
                    <div class="chapter-input">

                        <h2><label for="wp-manga-volume"> <?php esc_attr_e( 'Volume', WP_MANGA_TEXTDOMAIN ) ?></label></h2>

                        <?php echo $volume_dropdown_html; ?>

                        <button id="new-volume" class="button">New Volume</button>
                        <div class="new-volume" style="margin-top:10px; display:none; position:relative;">
                            <input type="text" id="volume-name" name="volume-name" class="disable-submit" style="width:25%;" placeholder="New Volume Name">
                            <i class="fa fa-spinner fa-spin"></i>
                            <button id="add-new-volume" class="add-new-volume button">Add</button>
                        </div>

                        <h2><label for="wp-manga-chapter-name"> <?php esc_attr_e( 'Chapter Name', WP_MANGA_TEXTDOMAIN ) ?></label></h2>
                        <input type="text" id="wp-manga-chapter-name" name="wp-manga-chapter-name" class="large-text disable-submit" value="" tabindex="1">

                        <h2><label for="wp-manga-chapter-name-extend"> <?php esc_attr_e( 'Name Extend', WP_MANGA_TEXTDOMAIN ) ?> </label></h2>
                        <input type="text" id="wp-manga-chapter-name-extend" name="wp-manga-chapter-name-extend" class="large-text disable-submit" value="" tabindex="1">
                        <span class="description"><?php esc_attr_e( 'Name extend of chapter for better display => Chapter name: Name extend', WP_MANGA_TEXTDOMAIN ) ?></span>

                        <h2>
                            <label for="wp-manga-chapter-storage"> <?php esc_attr_e( 'Choose where to upload', WP_MANGA_TEXTDOMAIN ); ?></label>
                        </h2>
                        <select id="wp-manga-chapter-storage" name="wp-manga-chapter-storage">
                            <?php
                            foreach ( $available_host as $host ) { ?>
                                <option value="<?php echo esc_attr( $host['value'] ) ?>" <?php selected( $host['value'], $default_storage ); ?>><?php echo esc_attr( $host['text'] ) ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <?php $GLOBALS['wp_manga_google_upload']->albums_dropdown( $default_storage, true ); ?>

                        <h2><label for="wp-manga-chapter-file"> <?php esc_attr_e( 'File', WP_MANGA_TEXTDOMAIN ) ?></label></h2>
                        <div class="wp-manga-input-file">
                            <input type="file" id="wp-manga-chapter-file" name="wp-manga-chapter-file" value="true" tabindex="1" accept=".zip">
                            <div class="notice-message">
                                <h3> <?php esc_html_e( 'Notice: ' ); ?></h3>
                                <?php _e('Please note that <strong>only .zip file</strong> contains images (<strong>.zip > Images</strong>) only is allowed to be uploaded in this field', WP_MANGA_TEXTDOMAIN ); ?>
                            </div>
                        </div>

                        <span class="description"><?php esc_attr_e( 'Zipfile contain all pictures of the chapter.' , WP_MANGA_TEXTDOMAIN ); ?></span>
                        <br/>
                        <span class="description"><?php esc_html_e( sprintf('Maximum upload file size: %dMB.', $max_upload_file_size['actual_max_filesize_mb'] ), WP_MANGA_TEXTDOMAIN ); ?></span>
                        <div id="chapter-overwrite" class="overwrite-options">
                            <h4> <?php esc_html_e( 'Do you want to overwrite chapter or create a new one?', WP_MANGA_TEXTDOMAIN ); ?> </h4>
                            <label>
                                <input type="radio" name="chapter-overwrite" id="overwrite" value="true" /> <span><?php esc_html_e('Overwrite old chapter', WP_MANGA_TEXTDOMAIN ); ?></span>
                            </label>
                            <label>
                                <input type="radio" name="chapter-overwrite" id="new" value="false" /> <span><?php esc_html_e('Create new chapter', WP_MANGA_TEXTDOMAIN ); ?></span>
                            </label>
                        </div>
                        <div id="chapters-overwrite-select" class="overwrite-options">
                            <h4> <?php esc_html_e( 'Select Chapter to overwrite ', WP_MANGA_TEXTDOMAIN ); ?> </h4>
                            <div class="chapter-overwrite-contains">

                            </div>
                        </div>
                        <p>
                            <button id="wp-manga-chapter-file-upload" class="button button-primary"> <?php esc_attr_e( 'Upload Chapter', WP_MANGA_TEXTDOMAIN ) ?> </button>
                        </p>
                    </div>
                    <div id="chapter-upload-msg" class="wp-manga-popup-content-msg">
                    </div>
                </div>

                <!--manga upload-->
                <div id="manga-upload" class="tab-content manga-chapter-tab chapter-content-tab">
                    <h2>
                        <?php echo esc_html__('Upload Manga', WP_MANGA_TEXTDOMAIN); ?>
                    </h2>
                    <p>
                        <label for="manga-storage"> <?php echo esc_html__('Storage', WP_MANGA_TEXTDOMAIN) ?> </label>
                        <select name="manga-storage">
                            <?php
                                foreach ( $available_host as $host ) { ?>
                                    <option value="<?php echo esc_attr( $host['value'] ) ?>" <?php selected( $host['value'], $default_storage ); ?>><?php echo esc_attr( $host['text'] ) ?></option>
                                <?php
                                }
                            ?>
                        </select>
                        <?php $GLOBALS['wp_manga_google_upload']->albums_dropdown( $default_storage, true ); ?>
                    </p>

                    <div class="wp-manga-volume-section">
                        <label for="wp-manga-volume-upload"><?php esc_html_e( 'Volume', WP_MANGA_TEXTDOMAIN ); ?> </label>
                            <?php echo $volume_dropdown_html; ?>
                        <button id="new-volume" class="button"><?php esc_html_e( 'New Volume', WP_MANGA_TEXTDOMAIN) ?></button>
                        <div class="new-volume" style="margin-top:10px; display:none; position:relative;">
                            <input type="text" id="volume-name" name="volume-name" class="disable-submit" style="width:25%;" placeholder="New Volume Name">
                            <i class="fa fa-spinner fa-spin"></i>
                            <button id="add-new-volume" class="add-new-volume button"><?php esc_html_e( 'Add', WP_MANGA_TEXTDOMAIN ); ?></button>
                        </div>
                    </div>

                    <div class="wp-manga-input-file">
                        <input type="file" id="wp-manga-file" name="wp-manga-file" value="true" tabindex="1" accept=".zip">

                        <div class="notice-message">
                            <h3> <?php esc_html_e( 'Notice: ' ); ?></h3>
                            <?php _e('Please note that <strong>only .zip file contains folders</strong> : ', WP_MANGA_TEXTDOMAIN ); ?>
                            <ul>
                                <li>
                                    <?php esc_html_e('.zip > Volume folder > Chapter folder > Images', WP_MANGA_TEXTDOMAIN ); ?>
                                </li>
                                <li>
                                    <?php esc_html_e('.zip > Chapter folder > Images', WP_MANGA_TEXTDOMAIN ); ?>
                                </li>
                            </ul>
                            <?php esc_html_e('is valid to be uploaded in this field', WP_MANGA_TEXTDOMAIN ); ?>
                        </div>

                        <br>

                        <span class="description"><?php esc_html_e( sprintf('Maximum upload file size: %dMB.', $max_upload_file_size['actual_max_filesize_mb'] ), WP_MANGA_TEXTDOMAIN ); ?></span>

                    </div>

                    <button id="wp-manga-upload" class="button button-primary"> <?php echo esc_html__('Upload Manga', WP_MANGA_TEXTDOMAIN); ?> </button>

                    <div id="manga-upload-msg" class="wp-manga-popup-content-msg">
                    </div>
                </div>
            <?php } ?>

            <?php if( $chapter_type == 'text' || $chapter_type == 'video' || $allow_to_choose_chapter_type ){ ?>
                <!-- content chapter create -->
                <div id="chapter-content" class="tab-content chapter-content-tab">
                    <div class="chapter-input">

                        <h2><label for="wp-manga-volume"> <?php esc_attr_e( 'Volume', WP_MANGA_TEXTDOMAIN ) ?></label></h2>

                        <?php echo $volume_dropdown_html; ?>

                        <button id="new-volume" class="button"><?php esc_html_e('New Volume', WP_MANGA_TEXTDOMAIN ); ?></button>
                        <div class="new-volume" style="margin-top:10px; display:none; position:relative;">
                            <input type="text" id="volume-name" name="volume-name" class="disable-submit" style="width:25%;" placeholder="New Volume Name">
                            <i class="fa fa-spinner fa-spin"></i>
                            <button id="add-new-volume" class="add-new-volume button">Add</button>
                        </div>

                        <h2>
                            <label for="wp-manga-chapter-name"> <?php esc_attr_e( 'Chapter Name', WP_MANGA_TEXTDOMAIN ) ?></label>
                        </h2>
                        <input type="text" id="wp-manga-chapter-name" name="wp-manga-chapter-name" class="large-text disable-submit" value="" tabindex="1">

                        <h2>
                            <label for="wp-manga-chapter-name-extend"> <?php esc_attr_e( 'Name Extend', WP_MANGA_TEXTDOMAIN ) ?> </label>
                        </h2>
                        <input type="text" id="wp-manga-chapter-name-extend" name="wp-manga-chapter-name-extend" class="large-text disable-submit" value="" tabindex="1">
                        <span class="description"><?php esc_attr_e( 'Name extend of chapter for better display => Chapter name: Name extend', WP_MANGA_TEXTDOMAIN ) ?></span>

                        <h2>
                            <label for="wp-manga-chapter-content"> <?php esc_attr_e( 'Chapter Content', WP_MANGA_TEXTDOMAIN ); ?></label>
                        </h2>
                        <?php wp_editor( '', 'wp-manga-chapter-content'); ?>

                        <p>
                            <button id="wp-manga-content-chapter-create" class="button button-primary"> <?php esc_attr_e( 'Create Chapter', WP_MANGA_TEXTDOMAIN ) ?> </button>
                        </p>
                    </div>
                    <div id="chapter-create-msg" class="wp-manga-popup-content-msg">
                    </div>
                </div>

                <!--text/video multi chapters upload-->
                <div id="chapter-content-upload" class="tab-content manga-chapter-tab chapter-content-tab">
                    <div class="wp-manga-volume-section">
                        <h2>
                            <label for="wp-manga-volume-upload"><?php esc_html_e( 'Volume', WP_MANGA_TEXTDOMAIN ); ?> </label>
                        </h2>
                        <?php echo $volume_dropdown_html; ?>
                        <button id="new-volume" class="button"><?php esc_html_e( 'New Volume', WP_MANGA_TEXTDOMAIN) ?></button>
                        <div class="new-volume" style="margin-top:10px; display:none; position:relative;">
                            <input type="text" id="volume-name" name="volume-name" class="disable-submit" style="width:25%;" placeholder="New Volume Name">
                            <i class="fa fa-spinner fa-spin"></i>
                            <button id="add-new-volume" class="add-new-volume button"><?php esc_html_e( 'Add', WP_MANGA_TEXTDOMAIN ); ?></button>
                        </div>
                    </div>

                    <div class="wp-manga-input-file">
                        <h2>
                            <label><?php esc_html_e( 'Multi Chapters File', WP_MANGA_TEXTDOMAIN ); ?> </label>
                        </h2>
                        <input type="file" id="chapter-content-file" name="chapter-content-file" value="true" tabindex="1" accept=".zip">
                        <div class="notice-message">
                            <h3> <?php esc_html_e( 'Notice: ' ); ?></h3>
                            <?php _e('Please note that <strong>only .zip file contains folders</strong> : ', WP_MANGA_TEXTDOMAIN ); ?>
                            <ul>
                                <li>
                                    <?php esc_html_e('.zip > Volume folder > Chapter folder > Text file contains content', WP_MANGA_TEXTDOMAIN ); ?>
                                </li>
                                <li>
                                    <?php esc_html_e('.zip > Chapter folder > Text file contains content', WP_MANGA_TEXTDOMAIN ); ?>
                                </li>
                            </ul>
                            <?php esc_html_e('is valid to be uploaded in this field', WP_MANGA_TEXTDOMAIN ); ?>
                        </div>
                        <br>
                        <span class="description"><?php esc_html_e( sprintf('Maximum upload file size: %dMB.', $max_upload_file_size['actual_max_filesize_mb'] ), WP_MANGA_TEXTDOMAIN ); ?></span>
                    </div>

                    <button id="chapter-content-upload-btn" class="button button-primary"> <?php echo esc_html__('Upload File', WP_MANGA_TEXTDOMAIN); ?> </button>

                    <div id="chapter-content-upload-msg" class="wp-manga-popup-content-msg">
                    </div>
                </div>
            <?php } ?>

            <div class="wp-manga-popup-loading">
                <div class="wp-manga-popup-loading-wrapper">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>

        <?php if( $allow_to_choose_chapter_type ){ ?>
            <div class="choose-manga-type">
                <div class="choose-manga-type-wrapper">
                    <h1>
                        <?php esc_html_e('Chapter Type', WP_MANGA_TEXTDOMAIN); ?>
                    </h1>
                    <div class="description">
                        <?php esc_html_e('This setting cannot be changed after choosen.', WP_MANGA_TEXTDOMAIN ); ?>
                    </div>
                    <p>
                        <div class="manga-type-choice">
                            <input type="radio" name="wp-manga-chapter-type" value="manga" id="wp-manga-type" <?php checked( $chapter_type, 'manga' ); ?>/>
                            <label for="wp-manga-type"><?php esc_html_e('Manga Chapter', WP_MANGA_TEXTDOMAIN ); ?></label>
                        </div>
                        <div class="manga-type-choice">
                            <input type="radio" name="wp-manga-chapter-type" value="text" id="wp-text-type" <?php checked( $chapter_type, 'text' ); ?>/>
                            <label for="wp-text-type"><?php esc_html_e('Text Chapter', WP_MANGA_TEXTDOMAIN ); ?></label>
                        </div>
                        <div class="manga-type-choice">
                            <input type="radio" name="wp-manga-chapter-type" value="video" id="wp-video-type" <?php checked( $chapter_type, 'video' ); ?>/>
                            <label for="wp-text-type"><?php esc_html_e('Video Chapter', WP_MANGA_TEXTDOMAIN ); ?></label>
                        </div>
                    </p>
                </div>
            </div>
        <?php } ?>

    </div>
