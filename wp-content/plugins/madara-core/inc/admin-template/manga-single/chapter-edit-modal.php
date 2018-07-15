<?php

    global $post;
    $chapter_type = get_post_meta( $post->ID, '_wp_manga_chapter_type', true );

?>

<div class="wp-manga-modal" id="wp-manga-chapter-modal" tabindex="-1" role="dialog">
    <div class="wp-manga-modal-dialog wp-manga-modal-lg unique-modal-lg wp-manga-imgur-modal-lg" role="document">
        <div class="wp-manga-modal-dismiss">
        </div>
        <div id="wp-manga-modal-content" class="<?php echo !empty( $chapter_type ) ? $chapter_type : ''; ?>">
            <div class="wp-manga-popup-loading">
                <div class="wp-manga-popup-loading-wrapper">
                    <i class="fa fa-spinner fa-spin"></i>
                </div>
            </div>
            <div class="wp-manga-modal-header">
                <input type="text" id="wp-manga-modal-chapter-name" value="" name="wp-manga-modal-chapter-name" placeholder="Chapter Name">
                <span>-</span>
                <input type="text" id="wp-manga-modal-chapter-extend-name" value="" name="wp-manga-modal-chapter-extend-name" placeholder="Chapter Extend Name">
            </div>

            <div class="wp-manga-modal-body">
                <div class="wp-manga-modal-storage">
                    <strong><?php esc_html_e( 'Storage: ', WP_MANGA_TEXTDOMAIN ); ?></strong>
                    <select id="manga-storage-dropdown">

                    </select>
                </div>
                <div class="wp-manga-modal-volume">
                    <strong><?php esc_html_e( 'Volume: ', WP_MANGA_TEXTDOMAIN ); ?></strong>

                        <?php $GLOBALS['wp_manga_functions']->volume_dropdown( get_the_ID(), 'manga-volume-dropdown') ?>

                </div>
                <div class="description">
                    <h4>
                        <?php esc_html_e( 'You can sort the page by draging picture', WP_MANGA_TEXTDOMAIN ); ?>
                    </h4>
                </div>
                <ul id="manga-sortable"></ul>

                <div class="wp-manga-chapter-content-editor">
                    <!-- content editor for text -->
                    <?php wp_editor( '', 'wp-manga-chapter-content-wp-editor', array( 'editor_height' => 350 ) ); ?>
                </div>
            </div>
            <div class="wp-manga-modal-footer">
                <input type="hidden" id="wp-manga-modal-post-id" value="">
                <input type="hidden" id="wp-manga-modal-chapter" value="">
                <div class="duplicate-chapter" style="display:none;">
                    <span> <?php echo esc_html__( 'Duplicate to : ' ) ?> </span>
                    <select name="duplicate-server" id="duplicate-server"> </select>
                    <button id="duplicate-btn" type="button" class="button"> <?php esc_html_e( 'Duplicate', WP_MANGA_TEXTDOMAIN ); ?></button>
                </div>
                <button id="wp-manga-save-paging-button" type="button" class="button button-primary"><?php esc_html_e( 'Save', WP_MANGA_TEXTDOMAIN ); ?></button>
                <button id="wp-manga-delete-chapter-button" type="button" class="button"><?php esc_html_e( 'Delete Chapter', WP_MANGA_TEXTDOMAIN ); ?></button>

                <?php if( !( $chapter_type == 'text' || $chapter_type == 'video' ) ){ ?>
                    <button id="wp-manga-download-chapter-button" type="button" class="button "><?php esc_html_e( 'Download Chapter', WP_MANGA_TEXTDOMAIN ); ?></button>
                <?php } ?>

                <button type="button" id="wp-manga-dismiss-modal" class="button" data-dismiss="modal"><?php esc_html_e( 'Close', WP_MANGA_TEXTDOMAIN ); ?></button>
            </div>
        </div>

    </div>
</div>
<div class="wp-manga-disable hidden"></div>
