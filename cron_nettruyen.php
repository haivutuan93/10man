<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

 require_once("/home/10manga.com/public_html/wp-load.php");
 $cron_nettruyen = new JSON_API_Net_Truyen_Controller();

 for($index_net = 1; $index_net < 2; $index_net++){
	 $cron_nettruyen->getMultiMangaNetTruyenInAPage("http://www.nettruyen.com/?page=" . $index_net);	 
 }

 //$cron_nettruyen->getAMangaNetTruyen("http://www.nettruyen.com/truyen-tranh/toan-chuc-phap-su", 0);
class Proxy {

    private $ch, $proxy;

    function __construct() {

        $torSocks5Proxy = "socks5://127.0.0.1:9050";
		
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($this->ch, CURLOPT_PROXY, $torSocks5Proxy);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($this->ch, CURLOPT_HEADER, false);
    }

    public function curl($url, $path) {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        ob_start();
        curl_exec($this->ch);
        curl_close($this->ch);
        
        $result = ob_get_contents();
        file_put_contents($path, $result);
        ob_end_clean();
    }
}

class JSON_API_Net_Truyen_Controller {
	
    function getMultiMangaNetTruyenInAPage($url) {
        $index = 0;
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/nettruyen';
        $a_page = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOM_home = $this->getDataFromUrlSSL($url, $a_page);

        $manga_list_all = $this->getElementByClass($DOM_home, "image");
        for ($k = 0; $k < 5; $k++) {
            $a_manga_url = $manga_list_all->item($k)->getElementsByTagName("a")->item(0)->getAttribute("href");
            $addOrUpdate = $this->getAMangaNetTruyen($a_manga_url, $index);
            if($addOrUpdate == FALSE){
                break;
            }
        }
        unlink($a_page);
    }
    
    function getAMangaNetTruyen($url_a_manga, $index) {
		$addOrUpdate = FALSE;
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/nettruyen';
        $a_manga_home = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOM = $this->getDataFromUrlSSL($url_a_manga, $a_manga_home);

        $title_manga = $DOM->getElementsByTagName("h1")->item(0)->nodeValue;
        $slug_manga = sanitize_title($title_manga);
        $all_chap = $DOM->getElementById("nt_listchapter")->getElementsByTagName("ul")->item(0)->getElementsByTagName("a");

		$manga_id = $this->post_exists($title_manga);
//add new post
        if ($manga_id == null) {
//folder a manga
            $folder_a_manga = $folder_mangas_sum . '/' . $slug_manga;
            if (!file_exists($folder_a_manga)) {
                mkdir($folder_a_manga, 0777, true);
            }

            $info = "";
            $info_div = $this->getElementByClass($DOM, "list-info")->item(0)->getElementsByTagName("li");
            $index_li = 0;
            if($info_div->length == 5){
                $index_li =1;
                $alter = $info_div->item(0)->getElementsByTagName("h2")->item(0)->nodeValue;
            }else{
                $alter = $title_manga;
            }
            
            $author = trim($info_div->item($index_li)->getElementsByTagName("p")->item(1)->nodeValue);
            $status = trim($info_div->item($index_li +1)->getElementsByTagName("p")->item(1)->nodeValue);
            if ($status == "Đang tiến hành") {
                $status = "on-going";
            } else {
                $status = "end";
            }

            $info .= "<title>" . $title_manga . "</title>\n";
            $description = $this->getElementByClass($DOM, "detail-content")->item(0)->getElementsByTagName("p")->item(0)->nodeValue;
            $info .= "<description>" . $description . "</description>\n";
            $tags = $info_div->item($index_li +2)->getElementsByTagName("a");

            $tags_name = "";

            for ($i = 0; $i < $tags->length; $i++) {
                if ($i == 0) {
                    $tags_name .= $tags->item($i)->nodeValue;
                } else {
                    $tags_name .= ", " . $tags->item($i)->nodeValue;
                }
            }
            $info .= "<type>" . $tags_name . "</type>\n";
            $info .= "<alter>" . $alter . "</alter>\n";
            $info .= "<author>" . $author . "</author>\n";
            $info .= "<artist>" . $author . "</artist>\n";
            $info .= "<tag>" . $tags_name . "</tag>\n";
            $info .= "<genre>" . $tags_name . "</genre>\n";
            $info .= "<status>" . $status . "</status>\n";
            $image_src = $this->getElementByClass($DOM, "col-image")->item(0)->getElementsByTagName("img")->item(0)->getAttribute("src");
            if(substr($image_src, 0,2) == "//"){
                $image_src = str_replace("//", "http://", $image_src);
            }
            $info .= "<image>" . $image_src . "</image>";

            $this->saveImageFromUrlSSL($image_src, $folder_a_manga . '/' . $slug_manga . '.png');

            file_put_contents($folder_a_manga . "/info.txt", $info);

            for ($i = 0; $i < $all_chap->length; $i++) {
                $chap_url = $all_chap->item($i)->getAttribute("href");
                $chap_name = $all_chap->item($i)->nodeValue;
                $chap_name = $this->removeSignImpossibleLinux($chap_name);

                $this->downloadAChapNetTruyen($chap_url, $chap_name, $folder_a_manga, ++$index, $title_manga);
            }
            $this->insert_manga($folder_a_manga, "video", "Truyện tranh");
			$addOrUpdate = TRUE;
        } else { // update or skip
//check update
			$manga_id = intval($manga_id);
			$latest_chap = $all_chap->item(0)->nodeValue;
            $latest_chap = sanitize_title($latest_chap);
            global $wpdb;
            $chapter = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters INNER JOIN wp_posts ON wp_manga_chapters.post_id = wp_posts.ID WHERE wp_manga_chapters.post_id = ".$manga_id." AND wp_manga_chapters.chapter_slug = '" . $latest_chap . "'");
            $update = TRUE;
            if ($chapter->chapter_slug !== NULL) {
                $update = FALSE;
            }
            if ($update) {
                $folder_a_manga = $folder_mangas_sum . '/' . $slug_manga;
                if (!file_exists($folder_a_manga)) {
                    mkdir($folder_a_manga, 0777, true);
                }
//update manga
                for ($i = 0; $i < $all_chap->length; $i++) {
                    $update_chap = $all_chap->item($i)->nodeValue;
                    $update_chap_slug = sanitize_title($update_chap);
                    $chapter_update = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters INNER JOIN wp_posts ON wp_manga_chapters.post_id = wp_posts.ID WHERE wp_manga_chapters.post_id = ".$manga_id." AND wp_manga_chapters.chapter_slug = '" . $update_chap_slug . "'");
                    if ($chapter_update->chapter_slug == NULL) {
                        $folder_chap = $folder_a_manga . '/' . $update_chap;
                        if (!file_exists($folder_chap)) {
                            mkdir($folder_chap, 0777, true);
                        }
                        $chap_url = $all_chap->item($i)->getAttribute("href");
                        $chap_name = $all_chap->item($i)->nodeValue;
                        $chap_name = $this->removeSignImpossibleLinux($chap_name);

                        $this->downloadAChapNetTruyen($chap_url, $chap_name, $folder_a_manga, ++$index, $title_manga);
                    } else {
                        $this->upload_handler_crawl($this->post_exists($title_manga), $folder_a_manga);
                        break;
                    }
                }
				$addOrUpdate = TRUE;
            }
        }
        unlink($a_manga_home);
		return $addOrUpdate;
    }

    private function downloadAChapNetTruyen($url, $chap_name, $folder_a_manga, $index, $title_manga) {
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/nettruyen';
//folder a manga
        $folder_a_chap = $folder_a_manga . '/' . $chap_name;
        if (!file_exists($folder_a_chap)) {
            mkdir($folder_a_chap, 0777, true);
        }
        $a_manga_chap = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOMChap = $this->getDataFromUrlSSL($url, $a_manga_chap);

        $all_img = $this->getElementByClass($DOMChap, "reading-detail")->item(0)->getElementsByTagName("img");
        $data = "";
        for ($j = 0; $j < $all_img->length; $j++) {
            $img_src = $all_img->item($j)->getAttribute("src");
            if (strpos($img_src, "https://") !== false) {
                $img_src = str_replace("https://", "http://", $img_src);
            }else if(substr($img_src, 0, 2) == "//"){
                $img_src = str_replace("//", "http://", $img_src);
            }
            $data_original = $all_img->item($j)->getAttribute("data-original");
            if($data_original == NULL){
                $data_original = "";
            }
            $data .= '<img src="' . $img_src . '" alt="'.$title_manga . " - " . $chap_name . ' - Trang ' . $j . '"  data-original="' . $data_original . '"/>' . "\n";
        }
        file_put_contents($folder_a_chap . '/data.txt', $data);
        unlink($a_manga_chap);
    }

    function insert_from_folder($path, $mangaType, $displayType) {
        global $wp_manga_storage;

        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/' . $path;
        $folder_manga_list = glob($folder_mangas_sum . '/*');

        foreach ($folder_manga_list as $folder_manga) {
            if (is_dir($folder_manga)) {
                $this->insert_manga($folder_manga, $mangaType, $displayType);
            }
        }
    }

    function getMultiMangaA3MangaInAPage($url) {
        $index = 0;
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/a3manga';
        $a_page = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOM_home = $this->getDataFromUrlSSL($url, $a_page);

        $manga_list_all = $this->getElementByClass($DOM_home, "comic-img");
        for ($k = 0; $k < $manga_list_all->length; $k++) {
            $a_manga_url = $manga_list_all->item($k)->getElementsByTagName("a")->item(0)->getAttribute("href");
            $this->getAMangaA3Manga($a_manga_url, $index);
        }
        unlink($a_page);
    }

     private function getDataFromUrlSSL($url_a_manga, $path) {
//        $options = array('http' => array('user_agent' => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/64.4.146 Chrome/58.4.3029.146 Safari/537.36"));
//        $context = stream_context_create($options);
//        $aMangaHomePage = file_get_contents($url_a_manga, false, $context);
//        $a_manga_home = $path;
//        $handle = fopen($a_manga_home, "w");
//        fwrite($handle, $aMangaHomePage);
//        fclose($handle);
        $proxy = new Proxy();
        $proxy->curl($url_a_manga, $path);

        $DOM = new DOMDocument();
        $DOM->loadHTMLFile($path);
        return $DOM;
    }

    function getAMangaA3Manga($url_a_manga, $index) {
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/a3manga';
        $a_manga_home = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOM = $this->getDataFromUrlSSL($url_a_manga, $a_manga_home);

        $title_manga = $this->getElementByClass($DOM, "info-title")->item(0)->nodeValue;
        $slug_manga = sanitize_title($title_manga);
        $all_chap = $this->getElementByClass($DOM, "table-striped")->item(0)->getElementsByTagName("a");

//add new post
        if ($this->post_exists($title_manga) == null) {
//folder a manga
            $folder_a_manga = $folder_mangas_sum . '/' . $slug_manga;
            if (!file_exists($folder_a_manga)) {
                mkdir($folder_a_manga, 0777, true);
            }

            $info = "";
            $info_div = $this->getElementByClass($DOM, "comic-info")->item(0);
            $alter = trim($info_div->getElementsByTagName("span")->item(0)->nodeValue);
            $author = trim($info_div->getElementsByTagName("span")->item(1)->nodeValue);
            $status = trim($info_div->getElementsByTagName("span")->item(2)->nodeValue);
            if ($status == "Đang tiến hành") {
                $status = "on-going";
            } else {
                $status = "end";
            }

            $info .= "<title>" . $title_manga . "</title>\n";
            $description_div = $this->getElementByClass($DOM, "widget")->item(1)->getElementsByTagName("p");
            $description = "";
            $i = 0;
            while (true) {
                if ($description_div->item($i)->nodeValue != NULL && $description_div->item($i)->nodeValue != "") {
                    $description .= $description_div->item($i)->nodeValue . "\n";
                    $i++;
                } else {
                    break;
                }
            }
            $info .= "<description>" . $description . "</description>\n";
            $tags = $this->getElementByClass($DOM, "tags")->item(0)->getElementsByTagName("a");
            $tags_name = "";

            for ($i = 0; $i < $tags->length; $i++) {
                if ($i == 0) {
                    $tags_name .= $tags->item($i)->nodeValue;
                } else {
                    $tags_name .= ", " . $tags->item($i)->nodeValue;
                }
            }
            $info .= "<type>" . $tags_name . "</type>\n";
            $info .= "<alter>" . $alter . "</alter>\n";
            $info .= "<author>" . $author . "</author>\n";
            $info .= "<artist>" . $author . "</artist>\n";
            $info .= "<tag>" . $tags_name . "</tag>\n";
            $info .= "<genre>" . $tags_name . "</genre>\n";
            $info .= "<status>" . $status . "</status>\n";
            $image_src = $this->getElementByClass($DOM, "img-thumbnail")->item(0)->getAttribute("src");
            $info .= "<image>" . $image_src . "</image>";

            $this->saveImageFromUrlSSL($image_src, $folder_a_manga . '/' . $slug_manga . '.png');

            file_put_contents($folder_a_manga . "/info.txt", $info);

            for ($i = 0; $i < $all_chap->length; $i++) {
                $chap_url = $all_chap->item($i)->getAttribute("href");
                $chap_name = $all_chap->item($i)->nodeValue;
                $chap_name = $this->removeSignImpossibleLinux($chap_name);

                $this->downloadAChapA3Manga($chap_url, $chap_name, $folder_a_manga, ++$index);
            }
            $this->insert_manga($folder_a_manga, "video", "Truyện tranh");
        } else { // update or skip
//check update
            $latest_chap = $all_chap->item(0)->nodeValue;
            $latest_chap = sanitize_title($this->removeSignImpossibleLinux($latest_chap));
            global $wpdb;
            $chapter = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters WHERE chapter_slug like '%" . $latest_chap . "%'");
            $update = TRUE;
            if ($chapter->chapter_slug !== NULL) {
                $update = FALSE;
            }
            if ($update) {
                $folder_a_manga = $folder_mangas_sum . '/' . $slug_manga;
                if (!file_exists($folder_a_manga)) {
                    mkdir($folder_a_manga, 0777, true);
                }
//update manga
                for ($i = 0; $i < $all_chap->length; $i++) {
                    $update_chap = $all_chap->item($i)->nodeValue;
                    $update_chap_slug = sanitize_title($this->removeSignImpossibleLinux($update_chap));
                    $chapter_update = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters WHERE chapter_slug like '%" . $update_chap_slug . "%'");
                    if ($chapter_update->chapter_slug == NULL) {
                        $folder_chap = $folder_a_manga . '/' . $update_chap;
                        if (!file_exists($folder_chap)) {
                            mkdir($folder_chap, 0777, true);
                        }
                        $chap_url = $all_chap->item($i)->getAttribute("href");
                        $chap_name = $all_chap->item($i)->getAttribute("title");
                        $chap_name = $this->removeSignImpossibleLinux($chap_name);

                        $this->downloadAChapA3Manga($chap_url, $chap_name, $folder_chap, ++$index);
                    } else {
                        $this->upload_handler_crawl($this->post_exists($title_manga), $folder_a_manga);
                        break;
                    }
                }
            }
        }
        unlink($a_manga_home);
    }

    private function downloadAChapA3Manga($url, $chap_name, $folder_a_manga, $index) {
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/a3manga';
//folder a manga
        $folder_a_chap = $folder_a_manga . '/' . $chap_name;
        if (!file_exists($folder_a_chap)) {
            mkdir($folder_a_chap, 0777, true);
        }
        $a_manga_chap = $folder_mangas_sum . '/site' . ++$index . '.html';
        $DOMChap = $this->getDataFromUrlSSL($url, $a_manga_chap);

        $all_img = $DOMChap->getElementById("view-chapter")->getElementsByTagName("img");
        $data = "";
        for ($j = 0; $j < $all_img->length; $j++) {
            $data .= '<img src="' . $all_img->item($j)->getAttribute("src") . '" alt="' . $chap_name . '_' . $j . '"/>' . "\n";
        }
        file_put_contents($folder_a_chap . '/data.txt', $data);
        unlink($a_manga_chap);
    }

    function getMultiPageSSManga() {
        $numPage = 1;
        for ($pageIndex = 0; $pageIndex < $numPage; $pageIndex++) {
            $url_page = "http://sstruyen.com/doc-truyen/truyen-moi-nhat/page-" . $pageIndex . ".html";
            $this->getPageSSManga($url_page);
        }
    }

    function getPageSSManga($url_page) {
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/sstruyen';
        $index = 0;
        $page_manga_content = file_get_contents($url_page);
        $page_manga_file = $folder_mangas_sum . '/page' . ++$index . '.html';
        $handle = fopen($page_manga_file, "w");
        fwrite($handle, $page_manga_content);
        fclose($handle);

        $DOMPage = new DOMDocument();
        $DOMPage->loadHTMLFile($page_manga_file);

        $listStoryAPage = $this->getElementByClass($DOMPage, "storyinfo");
        for ($_page = 0; $_page < $listStoryAPage->length; $_page++) {
            $url_a_manga = "http://sstruyen.com" . $listStoryAPage->item($_page)->getElementsByTagName("a")->item(0)->getAttribute('href');
            $title_manga = $listStoryAPage->item($_page)->getElementsByTagName("a")->item(0)->getAttribute('title');
            $this->getAMangaSS($url_a_manga, $title_manga, $index);
        }
        unlink($page_manga_file);
    }

    function getAMangaSS($url_a_manga, $title_manga, $index) {
        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/sstruyen';
//add new
        if ($this->post_exists($title_manga) == null) {
            $numPage = $this->getNewMangaSSInAPage($url_a_manga, TRUE, $index);
            $folder_a_manga = $folder_mangas_sum . '/' . sanitize_title($title_manga);
            if ($numPage !== -1) {
                for ($i = 1; $i < $numPage; $i++) {
                    $link = str_replace(".html", "/page-" . $i . ".html", $url_a_manga);
                    $this->getNewMangaSSInAPage($link, FALSE, $index);
                }
            }
            $this->insert_manga($folder_a_manga, "text", "text");
        } else { //update or skip
            $site = $url_a_manga;
            $homepage = file_get_contents($site);
            $a_manga_home = $folder_mangas_sum . '/site' . ++$index . '.html';
            $handle = fopen($a_manga_home, "w");
            fwrite($handle, $homepage);
            fclose($handle);

            $DOMUpdate = new DOMDocument();
            $DOMUpdate->loadHTMLFile($a_manga_home);
            $title_manga = $this->getElementByClass($DOMUpdate, "title")->item(0)->nodeValue;

            $news_chapter = $this->getElementByClass($DOMUpdate, "chuongmoi");
            if ($news_chapter->item(1) !== NULL && $news_chapter->item(0) !== NULL) {
                $newsest = $news_chapter->item(0)->getElementsByTagName('a');

                $latest_chap = $newsest->item(0)->nodeValue;
                $latest_chap = sanitize_title($this->removeSignImpossibleLinux($latest_chap));
                global $wpdb;
                $chapter = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters WHERE chapter_slug like '%" . $latest_chap . "%'");
                $update = TRUE;
                if ($chapter->chapter_slug !== NULL) {
                    $update = FALSE;
                }
                if ($update) {
                    $folder_a_manga = $folder_mangas_sum . '/' . sanitize_title($title_manga);
//update manga
                    for ($i = 0; $i < $newsest->length; $i++) {
                        $update_chap = $newsest->item($i)->nodeValue;
                        $chapter_update = $wpdb->get_row("SELECT chapter_slug FROM wp_manga_chapters WHERE chapter_slug like '%" . $latest_chap . "%'");
                        if ($chapter_update->chapter_slug == NULL) {
                            $chap_url = "http://sstruyen.com" . $newsest->item($i)->getAttribute("href");
                            $chap_name = $newsest->item($i)->getAttribute("title");
                            $chap_name = $this->removeSignImpossibleLinux($chap_name);

                            $this->downloadAChapSSInAPage($chap_url, $chap_name, $folder_a_manga, $index);
                        } else {
                            $this->upload_handler_crawl(get_page_by_title($title_manga)->ID, $folder_a_manga);
                            break;
                        }
                    }
                }
            }
            unlink($a_manga_home);
        }
    }

    function getNewMangaSSInAPage($url_a_manga, $is_home, $index) {
        $numPage = -1;

        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/sstruyen';
        $site = $url_a_manga;
        $homepage = file_get_contents($site);
        $a_manga_home = $folder_mangas_sum . '/site' . ++$index . '.html';
        $handle = fopen($a_manga_home, "w");
        fwrite($handle, $homepage);
        fclose($handle);

        $DOMPageChap = new DOMDocument();
        $DOMPageChap->loadHTMLFile($a_manga_home);

        $classname = "title";
        $finder = new DomXPath($DOMPageChap);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        $title_manga = $spaner->item(0)->nodeValue;

        $folder_slug = sanitize_title($title_manga);

        if ($is_home == TRUE) {
            if (!file_exists($folder_mangas_sum . '/' . $folder_slug)) {
                mkdir($folder_mangas_sum . '/' . $folder_slug, 0777, true);
            }

            $infoDetail = $this->getElementByClass($DOMPageChap, "content1")->item(0);
            $imageManga = $infoDetail->getElementsByTagName("img")->item(0);
            $imageMangeName = $imageManga->getAttribute("alt");

            $info_content = "";

            $info_title = $this->getElementByClass($DOMPageChap, "title")->item(0)->getAttribute('title');
            if ($info_title == null) {
                $info_title = "";
            }
            $info_content = $info_content . "<title>" . $info_title . "</title>\n";

            $info_des_element = $this->getElementByClass($DOMPageChap, "story_description")->item(0);
            $info_des_ss_element = $info_des_element->getElementsByTagName('b')->item(1);
            $info_des = str_replace($this->DOMinnerHTML2($info_des_ss_element), "", $this->DOMinnerHTML2($info_des_element));
// $info_des = str_replace($info_des_ss_element->nodeValue, "", $info_des_element->nodeValue);
            if ($info_des == null) {
                $info_des = "";
            }
            $info_content = $info_content . "<description>" . $info_des . "</description>\n";

            $info_item = $this->getElementByClass($DOMPageChap, "item")->item(0)->getElementsByTagName('li');
            $info_type = $info_item->item(1)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_type == null) {
                $info_type = "";
            }
            $info_content = $info_content . "<type>" . $info_type . "</type>\n";
            $info_content = $info_content . "<alter>" . $info_title . "</alter>\n";

            $info_author = $info_item->item(0)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_author == null) {
                $info_author = "";
            }
            $info_content = $info_content . "<author>" . $info_author . "</author>\n";
            $info_content = $info_content . "<artist>" . $info_author . "</artist>\n";
            $info_content = $info_content . "<tag>" . $info_type . "</tag>\n";
            $info_content = $info_content . "<year></year>\n";
            $info_content = $info_content . "<genre>" . $info_type . "</genre>\n";

            $info_status = $info_item->item(3)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_status == 'FULL') {
                $info_status = "end";
            } else {
                $info_status = "on-going";
            }
            $info_content = $info_content . "<status>" . $info_status . "</status>\n";

            $info_content_img = $this->getElementByClass($DOMPageChap, "truyeninfo")->item(0)->getElementsByTagName('img')->item(0)->getAttribute("src");
            $info_content_img = "http://sstruyen.com" . $info_content_img;
            $info_content = $info_content . "<image>" . $info_content_img . "</image>";
            $this->saveImageFromUrl($info_content_img, $folder_mangas_sum . '/' . $folder_slug . '/' . sanitize_title($title_manga) . '.png');

            file_put_contents($folder_mangas_sum . "/" . $folder_slug . "/info.txt", $info_content);

            $classname = "page-split";
            $finder = new DomXPath($DOMPageChap);
            $spaner = $finder->query("//*[contains(@class, '$classname')]");
            $page_split = $spaner->item(0);

            if ($page_split !== NULL) {
                $page_list = $page_split->getElementsByTagName('a');
                $numPage = $page_list->item($page_list->length - 1)->nodeValue;
            }
        }

//folder a manga
// $folder_a_manga = $folder_mangas_sum . '/' . $title_manga;
        $folder_a_manga = $folder_mangas_sum . '/' . $folder_slug;

        $classname = "chuongmoi";
        $finder = new DomXPath($DOMPageChap);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        $chapOfVolSum = $spaner->item(1);
        if ($chapOfVolSum == NULL) {
            $chapOfVolSum = $spaner->item(0);
        }
        $chapOfVolSum_url = $chapOfVolSum->getElementsByTagName('a');

        for ($i = 0; $i < $chapOfVolSum_url->length; $i++) {
            $chapDetailLink = "http://sstruyen.com" . $chapOfVolSum_url->item($i)->getAttribute('href');
            $chapDetailName = $chapOfVolSum_url->item($i)->nodeValue;
            $chapDetailName = $this->removeSignImpossibleLinux($chapDetailName);

            $this->downloadAChapSSInAPage($chapDetailLink, $chapDetailName, $folder_a_manga, $index);
        }
        unlink($a_manga_home);
        return $numPage;
    }

    private function downloadAChapSSInAPage($url, $name, $folder_a_manga, $index) {
        $folder_chap = $folder_a_manga . '/' . $name;
        if (!file_exists($folder_chap)) {
            mkdir($folder_chap, 0777, true);
        }
        $site = $url;
        $homepage = file_get_contents($site);
        $a_manga_read = $folder_mangas_sum . '/site' . ++$index . '.html';
        $handle = fopen($a_manga_read, "w");
        fwrite($handle, $homepage);
        fclose($handle);

        $DOM = new DOMDocument();
        $DOM->loadHTMLFile($a_manga_read);

        $script = $DOM->getElementsByTagName('script')->item(4)->nodeValue;
        $textInfo = explode(";", $script);
        $chapIdInfo = $textInfo[0];
        $chapterTimeInfo = $textInfo[1];

        $chapId = explode(" ", $chapIdInfo)[3];
        $chapterTime1 = explode(" ", $chapterTimeInfo)[3];
        $chapterTime2 = explode(" ", $chapterTimeInfo)[4];

        $chapterTime = str_replace("-", "", substr($chapterTime1, 1)) . str_replace(":", "", str_replace('"', '', $chapterTime2));
        $site = "http://sstruyen.com/doc-truyen/index.php?ajax=ct&id=" . $chapId . "&t=" . $chapterTime;

//chapter file
        file_put_contents($folder_chap . '/data.txt', $this->get_content_from_url($site));
        unlink($a_manga_read);
    }

    function getChapAMangaSSInAPage($url_a_manga, $is_home, $index) {
        $numPage = -1;

        $folder_mangas_sum = WP_MANGA_DATA_DIR . 'manga/sstruyen';
        $site = $url_a_manga;
        $homepage = file_get_contents($site);
        $a_manga_home = $folder_mangas_sum . '/site' . ++$index . '.html';
        $handle = fopen($a_manga_home, "w");
        fwrite($handle, $homepage);
        fclose($handle);

        $DOM = new DOMDocument();
        $DOM->loadHTMLFile($a_manga_home);

        $classname = "title";
        $finder = new DomXPath($DOM);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        $title_manga = $spaner->item(0)->nodeValue;
        $folder_slug = sanitize_title($title_manga);

        if ($is_home == TRUE) {
            if (!file_exists($folder_mangas_sum . '/' . $folder_slug)) {
                mkdir($folder_mangas_sum . '/' . $folder_slug, 0777, true);
            }

            $infoDetail = $this->getElementByClass($DOM, "content1")->item(0);
            $imageManga = $infoDetail->getElementsByTagName("img")->item(0);
            $imageMangeName = $imageManga->getAttribute("alt");

            $info_content = "";

            $info_title = $this->getElementByClass($DOM, "title")->item(0)->getAttribute('title');
            if ($info_title == null) {
                $info_title = "";
            }
            $info_content = $info_content . "<title>" . $info_title . "</title>\n";

            $info_des_element = $this->getElementByClass($DOM, "story_description")->item(0);
            $info_des_ss_element = $info_des_element->getElementsByTagName('b')->item(1);
            $info_des = str_replace($this->DOMinnerHTML2($info_des_ss_element), "", $this->DOMinnerHTML2($info_des_element));
// $info_des = str_replace($info_des_ss_element->nodeValue, "", $info_des_element->nodeValue);
            if ($info_des == null) {
                $info_des = "";
            }
            $info_content = $info_content . "<description>" . $info_des . "</description>\n";

            $info_item = $this->getElementByClass($DOM, "item")->item(0)->getElementsByTagName('li');
            $info_type = $info_item->item(1)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_type == null) {
                $info_type = "";
            }
            $info_content = $info_content . "<type>" . $info_type . "</type>\n";
            $info_content = $info_content . "<alter>" . $info_title . "</alter>\n";

            $info_author = $info_item->item(0)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_author == null) {
                $info_author = "";
            }
            $info_content = $info_content . "<author>" . $info_author . "</author>\n";
            $info_content = $info_content . "<artist>" . $info_author . "</artist>\n";
            $info_content = $info_content . "<tag>" . $info_type . "</tag>\n";
            $info_content = $info_content . "<year></year>\n";
            $info_content = $info_content . "<genre>" . $info_type . "</genre>\n";

            $info_status = $info_item->item(3)->getElementsByTagName('div')->item(1)->nodeValue;
            if ($info_status == 'FULL') {
                $info_status = "end";
            } else {
                $info_status = "on-going";
            }
            $info_content = $info_content . "<status>" . $info_status . "</status>\n";

            $info_content_img = $this->getElementByClass($DOM, "truyeninfo")->item(0)->getElementsByTagName('img')->item(0)->getAttribute("src");
            $info_content_img = "http://sstruyen.com" . $info_content_img;
            $info_content = $info_content . "<image>" . $info_content_img . "</image>";
            $this->saveImageFromUrl($info_content_img, $folder_mangas_sum . '/' . $folder_slug . '/' . sanitize_title($title_manga) . '.png');

            file_put_contents($folder_mangas_sum . "/" . $folder_slug . "/info.txt", $info_content);

            $classname = "page-split";
            $finder = new DomXPath($DOM);
            $spaner = $finder->query("//*[contains(@class, '$classname')]");
            $page_split = $spaner->item(0);

            if ($page_split !== NULL) {
                $page_list = $page_split->getElementsByTagName('a');
                $numPage = $page_list->item($page_list->length - 1)->nodeValue;
            }
        }

//folder a manga
// $folder_a_manga = $folder_mangas_sum . '/' . $title_manga;
        $folder_a_manga = $folder_mangas_sum . '/' . $folder_slug;

        $classname = "chuongmoi";
        $finder = new DomXPath($DOM);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        $chapOfVolSum = $spaner->item(1);
        if ($chapOfVolSum == NULL) {
            $chapOfVolSum = $spaner->item(0);
        }
        $chapOfVolSum_url = $chapOfVolSum->getElementsByTagName('a');

        for ($i = 0; $i < $chapOfVolSum_url->length; $i++) {
            $chapDetailLink = "http://sstruyen.com" . $chapOfVolSum_url->item($i)->getAttribute('href');
            $chapDetailName = $chapOfVolSum_url->item($i)->nodeValue;
// $chapDetailName = trim(substr($chapDetailName, strpos($chapDetailName, ".") + 1));
            $chapDetailName = $this->removeSignImpossibleLinux($chapDetailName);

            $this->downloadAChapSSInAPage($chapDetailLink, $chapDetailName, $folder_a_manga, $index);
        }
        unlink($a_manga_home);
        return $numPage;
    }

    private function get_content_from_url($URL) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $URL);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    private function insert_manga($folder_a_manga, $mangaType, $displayType) {
        global $wp_manga_storage;
        $info_file = $folder_a_manga . '/info.txt';
        if (!file_exists($info_file)) {
            return;
        }

// $path = pathinfo(realpath($folder_manga), PATHINFO_DIRNAME);
        $DOM = new DOMDocument();
        $DOM->loadHTMLFile($folder_a_manga . '/info.txt');

        $title_manga = utf8_decode($DOM->getElementsByTagName('title')->item(0)->nodeValue);

        if ($this->post_exists($title_manga) !== null) {
            return;
        }

        $description_manga = utf8_decode($DOM->getElementsByTagName('description')->item(0)->nodeValue);
        $manga_type = utf8_decode($DOM->getElementsByTagName('type')->item(0)->nodeValue);
        $manga_alter = utf8_decode($DOM->getElementsByTagName('alter')->item(0)->nodeValue);
        $image_url = utf8_decode($DOM->getElementsByTagName('image')->item(0)->nodeValue);

        $authors = explode(",", utf8_decode($DOM->getElementsByTagName('author')->item(0)->nodeValue));
        $artists = explode(",", utf8_decode($DOM->getElementsByTagName('artist')->item(0)->nodeValue));
        $tags = explode(",", utf8_decode($DOM->getElementsByTagName('tag')->item(0)->nodeValue));
        if ($displayType == "text") {
            array_push($tags, "Truyện chữ");
        } else if ($displayType == "video") {
            array_push($tags, "Video");
        } else {
            array_push($tags, "Truyện tranh");
        }

        $years = explode(",", utf8_decode($DOM->getElementsByTagName('year')->item(0)->nodeValue));
        $genres = explode(",", utf8_decode($DOM->getElementsByTagName('genre')->item(0)->nodeValue));
        if ($displayType == "text") {
            array_push($genres, "Truyện chữ");
        } else if ($displayType == "video") {
            array_push($genres, "Video");
        } else {
            array_push($genres, "Truyện tranh");
        }

        $views = rand(231241, 924196);
        $status = utf8_decode($DOM->getElementsByTagName('status')->item(0)->nodeValue);
        if ($displayType == 'text') {
            $custom_badges = 'text';
        }

        $crawl_post = array(
            'post_title' => $title_manga,
            'post_content' => $description_manga,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'wp-manga',
        );
        $crawl_post_id = wp_insert_post($crawl_post);

//set feature image
        $this->generate_Featured_Image($folder_a_manga . '/' . sanitize_title($title_manga) . '.png', $crawl_post_id);

        $this->upload_handler_crawl($crawl_post_id, $folder_a_manga, 0);

        update_post_meta($crawl_post_id, '_wp_manga_chapter_type', $mangaType);

        wp_set_post_terms($crawl_post_id, $authors, 'wp-manga-author');
        wp_set_post_terms($crawl_post_id, $artists, 'wp-manga-artist');
        wp_set_post_terms($crawl_post_id, $tags, 'wp-manga-tag');
        wp_set_post_terms($crawl_post_id, $years, 'wp-manga-release');
        wp_set_object_terms($crawl_post_id, $genres, 'wp-manga-genre');

        $manga_avarage_reviews = floatval(rand(30, 50)) / floatval(10);
        $wp_manga_week_views = rand(20000, 60000);

        update_post_meta($crawl_post_id, '_manga_avarage_reviews', $manga_avarage_reviews);
        update_post_meta($crawl_post_id, '_wp_manga_week_views', $wp_manga_week_views);

        if (empty(get_post_meta($crawl_post_id, 'manga_unique_id', true))) {
//add unique id for manga post
            $uniqid = uniqid('manga_');
            update_post_meta($crawl_post_id, 'manga_unique_id', $uniqid);
        }

        if (isset($status)) {
            update_post_meta($crawl_post_id, '_wp_manga_status', $status);
        }

        if (isset($custom_badges)) {
            update_post_meta($crawl_post_id, 'manga_title_badges', 'custom');
            update_post_meta($crawl_post_id, 'manga_custom_badges', $custom_badges);
        }

        if (isset($views)) {
            update_post_meta($crawl_post_id, '_wp_manga_views', $views);
        }

        if (isset($manga_alter)) {
            update_post_meta($crawl_post_id, '_wp_manga_alternative', $manga_alter);
        }

        if (isset($manga_type)) {
            update_post_meta($crawl_post_id, '_wp_manga_type', $manga_type);
        }

        $first_create = get_post_meta($crawl_post_id, '_latest_update', true);
        if (!$first_create || $first_create == '') {
            update_post_meta($crawl_post_id, '_latest_update', current_time('timestamp', false));
        }

        $wp_manga_storage->local_remove_storage($folder_a_manga . '/info.txt');
        $wp_manga_storage->local_remove_storage($folder_a_manga . '/' . sanitize_title($title_manga) . '.png');

        if (file_exists($folder_a_manga)) {
            $this->removedir($folder_a_manga);
        }

        do_action('rikaki_insert_manga', $crawl_post_id);
    }

    private function removedir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    private function insert_chapter_crawl($args) {

        global $wp_manga_chapter, $wp_manga_storage;

        $chapter_content = $args['chapter_content'];
        unset($args['chapter_content']);

//post_id require, volume id, chapter name, chapter extend name, chapter slug
        $chapter_args = array_merge(
                $args, array(
            'chapter_slug' => $wp_manga_storage->slugify($args['chapter_name'])
                )
        );

        $chapter_id = $wp_manga_chapter->insert_chapter($chapter_args);

        if (!$chapter_id) {
            wp_send_json_error(array('message' => esc_html__('Cannot insert Chapter', WP_MANGA_TEXTDOMAIN)));
        }

        $chapter_content_args = array(
            'post_type' => 'chapter_text_content',
            'post_content' => $chapter_content,
            'post_status' => 'publish',
            'post_parent' => $chapter_id, //set chapter id as parent
        );

        $resp = wp_insert_post($chapter_content_args);

        return $resp;
    }

    private function upload_handler_crawl($post_id, $extract, $volume_id = 0) {
        $title_manga = get_the_title($post_id);

        global $wp_manga_functions, $wp_manga, $wp_manga_storage, $wp_manga_volume;

//scan all dir
        $scandir_lv1 = glob($extract . '/*');
        $result = array();

        $is_invalid_zip_file = true;

//Dir level 1
        foreach ($scandir_lv1 as $dir_lv1) {

            if (basename($dir_lv1) === '__MACOSX') {
                continue;
            }

            if (is_dir($dir_lv1)) {

                $has_volume = true;

                foreach (glob($dir_lv1 . '/*') as $dir_lv2) {

                    if (basename($dir_lv2) === '__MACOSX') {
                        continue;
                    }

//if dir level 2 is dir then dir level 1 is volume
                    if (is_dir($dir_lv2) && $has_volume == true) {

//By now, dir lv1 is volume. Then check if this volume is already existed or craete a new one
                        $this_volume = $wp_manga_volume->get_volumes(
                                array(
                                    'post_id' => $post_id,
                                    'volume_name' => basename($dir_lv1),
                                )
                        );

                        if ($this_volume == false) {
                            $this_volume = $wp_manga_storage->create_volume(basename($dir_lv1), $post_id);
                        } else {
                            $this_volume = $this_volume[0]['volume_id'];
                        }

                        $chapters = glob($dir_lv2 . '/*');

                        foreach ($chapters as $chapter) {
//create chapter
                            $chapter_args = array(
                                'post_id' => $post_id,
                                'chapter_name' => basename($dir_lv2),
                                'chapter_name_extend' => '',
                                'volume_id' => $this_volume,
                                'chapter_content' => file_get_contents($chapter)
                            );

                            $this->insert_chapter_crawl($chapter_args);
                            $manga_chapter_slug = $wp_manga_storage->slugify($title_manga) . '/' . $wp_manga_storage->slugify(basename($dir_lv1)) . '/' . $wp_manga_storage->slugify(basename($dir_lv2)) . '/';
                            $var_manga = array();
                            $var_manga['id'] = $post_id;
                            $var_manga['chapter_slug'] = $manga_chapter_slug;
                            do_action('rikaki_insert_manga_chapter', $var_manga);
                        }
                    } else {

                        if ($has_volume) {
                            $has_volume = false;
                        }

//create chapter
                        $chapter_args = array(
                            'post_id' => $post_id,
                            'chapter_name' => basename($dir_lv1),
                            'chapter_name_extend' => '',
                            'volume_id' => $volume_id,
                            'chapter_content' => file_get_contents($dir_lv2)
                        );

                        $this->insert_chapter_crawl($chapter_args);
                        $manga_chapter_slug = $wp_manga_storage->slugify($title_manga) . '/' . $wp_manga_storage->slugify(basename($dir_lv1)) . '/';
                        $var_manga = array();
                        $var_manga['id'] = $post_id;
                        $var_manga['chapter_slug'] = $manga_chapter_slug;
                        do_action('rikaki_insert_manga_chapter', $var_manga);
                    }
                }
            } else {
                $is_invalid_zip_file = false;
            }
        }

        $wp_manga_storage->local_remove_storage($extract);
    }

    private function post_exists($post_name) {
        global $wpdb;
        $post_name = sanitize_title($post_name);
        $result = $wpdb->get_row("SELECT id FROM wp_posts WHERE post_type = 'wp-manga' AND post_name = '" . $post_name  . "'");
		if($result == null){
			return null;
		}
        return $result->id;
    }

    private function generate_Featured_Image($image_path, $post_id) {
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_path);
        $filename = basename($image_path);
        if (wp_mkdir_p($upload_dir['path']))
            $file = $upload_dir['path'] . '/' . $filename;
        else
            $file = $upload_dir['basedir'] . '/' . $filename;
        file_put_contents($file, $image_data);

        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($post_id, $attach_id);
    }

    private function saveImageFromUrl($url, $image_path) {
        $options = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $context = stream_context_create($options);
        $file = file_get_contents($url, false, $context);
        file_put_contents($image_path, $file);
    }

    private function saveImageFromUrlSSL($url, $image_path) {
//        $options = array('http' => array('user_agent' => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/64.4.146 Chrome/58.4.3029.146 Safari/537.36"));
//        $context = stream_context_create($options);
//        $file = file_get_contents($url, false, $context);
//        file_put_contents($image_path, $file);
        $proxy = new Proxy();
        $proxy->curl($url, $image_path);
    }

    private function DOMinnerHTML(DOMNode $element) {
        $innerHTML = "";
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= utf8_decode($element->ownerDocument->saveHTML($child));
        }
        return $innerHTML;
    }

    private function DOMinnerHTML2(DOMNode $element) {
        $innerHTML = "";
        $children = $element->childNodes;

        foreach ($children as $child) {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }
        return $innerHTML;
    }

    private function getElementByClass($filePath, $classname) {
        $finder = new DomXPath($filePath);
        $spaner = $finder->query("//*[contains(@class, '$classname')]");
        return $spaner;
    }

    private function removeSignImpossible($s) {
        $s = str_replace("\\", " ", $s);
        $s = str_replace("/", " ", $s);
        $s = str_replace(":", "-", $s);
        $s = str_replace("*", "", $s);
        $s = str_replace("?", " ", $s);
        $s = str_replace("'", " ", $s);
        $s = str_replace(">", " ", $s);
        $s = str_replace("<", " ", $s);
        $s = str_replace("|", " ", $s);
        $s = str_replace('"', '-', $s);
        $s = str_replace(".", "-", $s);
        $s = str_replace("...", "", $s);
        $s = str_replace("..", "", $s);
        $s = str_replace("!", "", $s);

        return $s;
    }

    private function removeSignImpossibleLinux($s) {
        $s = str_replace("/", " ", $s);
        $s = str_replace('"', ' ', $s);
        return $s;
    }

    private function to_slug($str) {
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

}
