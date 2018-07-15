<?php
	/*
	*  Manga Breadcrumb
	*/

    global $wp_query, $wp_manga_functions, $wp_manga, $wp_manga_chapter;
	$object   = $wp_query->queried_object;

    $obj_title = $object->post_title;
    $obj_url   = get_the_permalink( $object->ID );

?>

    <div class="c-breadcrumb-wrapper">
        <div class="c-breadcrumb">
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo esc_url( home_url() ); ?>">
                        <?php esc_html_e( 'Home', WP_MANGA_TEXTDOMAIN ); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'wp-manga' ) ); ?>">
                        <?php esc_html_e( 'Manga', WP_MANGA_TEXTDOMAIN ); ?>
                    </a>
                </li>

                <?php
                $middle = $wp_manga->wp_manga_breadcrumb_middle( $object );

                if ( ! empty( $middle ) ) {
                    $middle = array_reverse( $middle );

                    foreach ( $middle as $name => $link ) { ?>
                        <li>
                            <a href="<?php echo esc_url( $link ); ?>">
                                <?php echo esc_html( $name ); ?>
                            </a>
                        </li>
                    <?php }
                }
                ?>

                <?php if ( $object !== null ) { ?>
                    <li>
                        <a href="<?php echo esc_url( $obj_url ); ?>">
                            <?php echo esc_html( $obj_title ); ?>
                        </a>
                    </li>
                <?php } ?>

                <?php if ( $wp_manga_functions->is_manga_reading_page() ) {
                    $chapter_slug = get_query_var('chapter');

                    if ( ! empty( $chapter_slug ) ) {
                        $chapter_db = $wp_manga_chapter->get_chapter_by_slug( get_the_ID(), $chapter_slug );

                        $c_name   = isset( $chapter_db['chapter_name'] ) ? $chapter_db['chapter_name'] : '';
                        $c_extend = $wp_manga_functions->filter_extend_name( $chapter_db['chapter_name_extend'] );

                        if ( isset( $c_name ) ) {
                            ?>
                            <li class="active">
                                <?php echo esc_html( $c_name . $c_extend ); ?>
                            </li>
                            <?php
                        }
                    }
                } ?>

            </ol>
        </div>

        <?php if ( $wp_manga_functions->is_manga_reading_page() ) { ?>
            <div class="action-icon">
                <ul class="action_list_icon list-inline">
                    <li>
                        <?php echo $wp_manga_functions->create_bookmark_link(); ?>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
