<?php
	/**
	 * Madara Functions and Definitions
	 *
	 * @package madara
	 */
	require( get_template_directory() . '/app/theme.php' );
	
	function rikaki_delete_manga_start($post_id) {
    $post_type = get_post_type($post_id);

    if ($post_type != "wp-manga") {
        return;
    }
    $title = get_the_title($post_id);
    if ($title == NULL || $title == "" || $title == "Auto Draft") {
        return;
    }
    $name_slug = to_slug(get_the_title($post_id));
    $root_path = fs_get_root_path();
    $site_url = 'https://10manga.com';

    $end_id = getEndPointId($post_id);
    $start_id = $end_id - 100000;

    $manga_home_url = $site_url . '/manga-sitemap/';
    $manga_story_path = $root_path . '/manga-sitemap/' . $name_slug . '_' . $post_id . '.xml';
    $manga_story_url = $manga_home_url . $name_slug . '_' . $post_id . '.xml';

    if (file_exists($manga_story_path)) {
        unlink($manga_story_path);
    }

    $manga_sum_path = $root_path . '/manga-sitemap/' . $start_id . '-' . $end_id . '.xml';
    if (file_exists($manga_sum_path)) {
        $contents = file_get_contents($manga_sum_path);
        $manga_story = "<sitemap>\n";
        $manga_story .= "<loc>" . $manga_story_url . "</loc>\n";
        $manga_story .= "</sitemap>\n";
        $pos = strpos($contents, $manga_story);
        if ($pos !== FALSE) {
// $contents = str_replace($manga_story, "", $contents);
// $contents = substr($contents, 0, $pos - 9) . substr($contents, $pos + 10);
            $contents = str_replace($manga_story, "", $contents);
            file_put_contents($manga_sum_path, $contents);
        }
    }
}

function submit($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $httpCode;
}

function pingSitemap($sitemapUrl) {
    $sitemapUrl = htmlentities($sitemapUrl);
    //Google  
    $url = "http://www.google.com/webmasters/sitemaps/ping?sitemap=" . $sitemapUrl;
    submit($url);

    //Bing / MSN
    $url = "http://www.bing.com/webmaster/ping.aspx?siteMap=" . $sitemapUrl;
    submit($url);
}


function rikaki_insert_manga_chapter_start($var_manga) {
    $manga_slug = '/manga/';
    $root_path = fs_get_root_path();
    $site_url = 'https://10manga.com';
    $link_url = $site_url . $manga_slug . $var_manga['chapter_slug'];
    $id = $var_manga['id'];
    $name_slug = to_slug(get_the_title($id));

    $endId = getEndPointId($id);
    $startId = $endId - 100000;

    $manga_home_path = $root_path . '/manga-sitemap/';
    $manga_home_url = $site_url . '/manga-sitemap/';

    $manga_story_path = $root_path . '/manga-sitemap/' . $name_slug . '_' . $id . '.xml';
    $manga_story_url = $manga_home_url . $name_slug . '_' . $id . '.xml';
	
	
// add to summary sitemap
    if (!file_exists($manga_story_path)) {
// create manga month year xml file
        $header_story = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="https://10manga.com/wp-content/plugins/google-sitemap-generator/sitemap.xsl"?>' . "\n";
        $header_story .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $header_story .= '<url>' . "\n";
        $header_story .= '<loc>' . $link_url . '</loc>' . "\n";
        $header_story .= '<changefreq>daily</changefreq>' . "\n";
        $header_story .= '<priority>1.0</priority>' . "\n";
        $header_story .= '</url>' . "\n";
        $header_story .= '</urlset>' . "\n";
        file_put_contents($manga_story_path, $header_story);
    } else {
        $contents = file_get_contents($manga_story_path);
        if (strpos($contents, $link_url) == FALSE) {
            $string = "";
            $string .= '<url>' . "\n";
            $string .= '<loc>' . $link_url . '</loc>' . "\n";
            $string .= '<changefreq>daily</changefreq>' . "\n";
            $string .= '<priority>1.0</priority>' . "\n";
            $string .= '</url>' . "\n";
            $string .= '</urlset>' . "\n";
            $contents = str_replace('</urlset>', $string, $contents);
            file_put_contents($manga_story_path, $contents);
        }
    }
	pingSitemap($manga_story_url);
}

function rikaki_insert_manga_start($post_id) {
    $post_type = get_post_type($post_id);

    if ($post_type != "wp-manga") {
        return;
    }
    $title = get_the_title($post_id);
    if ($title == NULL || $title == "" || $title == "Auto Draft" || $title == "Lưu bản nháp tự động") {
        return;
    }
	$votes_num = rand(100, 1000);
    $score = (float) rand(3, 5) * $votes_num;

    update_post_meta($post_id, '_kksr_casts', $votes_num);
    update_post_meta($post_id, '_kksr_ratings', $score);
	
    $root_path = fs_get_root_path();
    $site_url = 'https://10manga.com';
    $id = $post_id;
    $name_slug = to_slug(get_the_title($post_id));

    $endId = getEndPointId($id);
    $startId = $endId - 100000;

    $manga_home_path = $root_path . '/manga-sitemap/';
    $manga_home_url = $site_url . '/manga-sitemap/';

    $manga_sum_path = $root_path . '/manga-sitemap/' . $startId . '-' . $endId . '.xml';
    $manga_sum_url = $manga_home_url . $startId . '-' . $endId . '.xml';

    $manga_story_path = $root_path . '/manga-sitemap/' . $name_slug . '_' . $id . '.xml';
    $manga_story_url = $manga_home_url . $name_slug . '_' . $id . '.xml';

    $manga_all_path = $manga_home_path . 'manga-sitemap.xml';
    $manga_all_url = $manga_home_url . 'manga-sitemap.xml';

    if (file_exists($manga_all_path)) {
        $contents = file_get_contents($manga_all_path);
        if (strpos($contents, $manga_sum_url) == FALSE) {
            $string = '<sitemap>' . "\n";
            $string .= '<loc>' . $manga_sum_url . '</loc>' . "\n";
            $string .= '</sitemap>' . "\n";
            $string .= '</sitemapindex>' . "\n";
            $contents = str_replace('</sitemapindex>', $string, $contents);
            file_put_contents($manga_all_path, $contents);
        }
    }

// add to summary sitemap
    if (!file_exists($manga_sum_path)) {
// create manga month year xml file
        $header_sum = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="https://10manga.com/wp-content/plugins/google-sitemap-generator/sitemap.xsl"?>' . "\n" .
                '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $string = '<sitemap>' . "\n";
        $string .= '<loc>' . $manga_story_url . '</loc>' . "\n";
        $string .= '</sitemap>' . "\n";
        $string .= '</sitemapindex>' . "\n";

        $string = $header_sum . $string;

        file_put_contents($manga_sum_path, $string);
    } else {
        $contents = file_get_contents($manga_sum_path);
        if (strpos($contents, $manga_story_url) == FALSE) {
            $string = '<sitemap>' . "\n";
            $string .= '<loc>' . $manga_story_url . '</loc>' . "\n";
            $string .= '</sitemap>' . "\n";
            $string .= '</sitemapindex>' . "\n";
            $contents = str_replace('</sitemapindex>', $string, $contents);
            file_put_contents($manga_sum_path, $contents);
        }
    }

    if (!file_exists($manga_story_path)) {
        $header_story = '<?xml version="1.0" encoding="UTF-8"?><?xml-stylesheet type="text/xsl" href="https://10manga.com/wp-content/plugins/google-sitemap-generator/sitemap.xsl"?>' . "\n";
        $header_story .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $footer_story = '</urlset>' . "\n";
        file_put_contents($manga_story_path, $header_story . $footer_story);
    }
}

function fs_get_root_path() {
    return dirname(dirname(dirname(dirname(__FILE__))));
}

function getEndPointId($id) {
    $end = 0;
    for ($i = $id; $i < $id + 100001; $i++) {
        if ($i % 100000 == 0) {
            $end = $i;
            break;
        }
    }
    return $end;
}

function to_slug($str) {
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '-', $str);
    $str = preg_replace('/---/', '-', $str);
    $str = preg_replace('/--/', '-', $str);
    return $str;
}

function rikaki_custom_url_img($content) {
    $dom = new DOMDocument();
    $dom->loadHTML($content);

    $content_change = "";
    $img_list = $dom->getElementsByTagName("img");
    for ($img_index = 0; $img_index < $img_list->length; $img_index++) {
        $img_url = $img_list->item($img_index)->getAttribute("src");
        $img_alt = utf8_decode($img_list->item($img_index)->getAttribute("alt"));
        if (strpos($img_url, "focus-opensocial.googleusercontent") !== FALSE) {
            $img_url = explode("&url=", $img_url)[1];
            $img_url = urldecode($img_url);
        }
        $content_change .= '<img src="' . $img_url . '" alt="' .$img_alt .'">';
    }

    return $content_change;
}

add_action('rikaki_insert_manga', 'rikaki_insert_manga_start');
add_action('rikaki_insert_manga_chapter', 'rikaki_insert_manga_chapter_start');
add_action('save_post', 'rikaki_insert_manga_start', 99, 1);
add_action('before_delete_post', 'rikaki_delete_manga_start');

add_filter('the_content', 'rikaki_custom_url_img');
	
register_sidebar( array(
'name' => 'Footer Sidebar 1',
'id' => 'footer-sidebar-1',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => 'Footer Sidebar 2',
'id' => 'footer-sidebar-2',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
register_sidebar( array(
'name' => 'Footer Sidebar 3',
'id' => 'footer-sidebar-3',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );