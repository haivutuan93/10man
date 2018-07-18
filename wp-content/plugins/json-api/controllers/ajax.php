<?php

class JSON_API_Ajax_Controller {
    public function ajax_chapter() {
        $post_id = $_POST[postID];
        $cur_chap = $_POST[curChap];
        global $wp_manga_chapter, $wp_manga_text_type, $wp_manga;

        $chapter = $wp_manga_chapter->get_chapter_by_slug($post_id, $cur_chap);
        $all_chapters = madara_get_global_wp_manga_functions()->get_all_chapters($post_id);
        $all_chaps = $all_chapters[$chapter['volume_id']][chapters];

        $args = array(
            'cur_chap' => $cur_chap,
            'chapter' => $chapter,
            'all_chaps' => $all_chaps,
            'position' => 'header',
        );
        $outout = '';

        ob_start();
        $wp_manga_text_type->manga_nav_ajax($args, $post_id);
        $outout = ob_get_contents();
        ob_end_clean();

        return $outout;
    }

}
