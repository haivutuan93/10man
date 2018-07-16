<?php
/** Manga paged * */
$name = get_query_var('chapter');

$global_wp_manga_functions = madara_get_global_wp_manga_functions();
$global_manga_chapters = $global_wp_manga_functions->get_all_chapters(get_the_ID());

if ($name == '') {
    get_template_part(404);
    exit();
}
$wp_manga_chapter = madara_get_global_wp_manga_chapter();
$chapter_slug = get_query_var('chapter');

$global_chapter_by_slug = $wp_manga_chapter->get_chapter_by_slug(get_the_ID(), $chapter_slug);
$c_name = '';
if (!empty($chapter_slug)) {
    $chapter_json = $wp_manga->get_chapter(get_the_ID());
    $c_name = isset($global_chapter_by_slug['chapter_name']) ? $global_chapter_by_slug['chapter_name'] : '';
}

add_filter('pre_get_document_title', function( $title ) {
    global $global_manga_chapters;
    global $global_wp_manga_functions;
//tags
    $tag_name = '';
    $manga_tags = get_the_terms(get_the_ID(), 'wp-manga-tag');
    $manga_tags = isset($manga_tags) && !empty($manga_tags) ? $manga_tags : array();
    $tag_count = count($manga_tags);
    $tag_flag = 0;
    $separate_char = ', ';

    if ($manga_tags == false || is_wp_error($manga_tags)) {
        
    }else{
        foreach ($manga_tags as $tag) {
            $tag_flag ++;
            if ($tag->name == 'Truyện tranh') {
                $tag_name = ' tranh';
                break;
            }
            if ($tag->name == 'Truyện chữ') {
                $tag_name = ' chữ';
                break;
            }
        }
    }
    
    $cur_chap = get_query_var('chapter');
    $cur_chap_name = '';
    $cur_vol_name = '';
    foreach ($global_manga_chapters as $h_vol) {
        foreach ($h_vol['chapters'] as $h_chap) {
            if ($h_chap['chapter_slug'] == $cur_chap) {
                $cur_chap_name = $h_chap['chapter_name'];
                $cur_vol_name = $h_vol['volume_name'];
                break;
            }
            if ($cur_chap_name != '') {
                break;
            }
        }
    }
    if ($cur_vol_name == 'No Volume') {
        $title = get_the_title() . ' - ' . $cur_chap_name . ' - Truyện';
    } else
        $title = get_the_title() . ' - ' . $cur_vol_name . ' - ' . $cur_chap_name . ' - Truyện';
    
    if($tag_name != ''){
        $title = $title. $tag_name;
    }
    return $title;
}, 999, 1);

function custom_add_meta_description_tag() {
    global $global_wp_manga_functions;
    global $global_manga_chapters;
    $cur_chap = get_query_var('chapter');
    $cur_chap_name = '';
    $cur_vol_name = '';
    $h_chap_related_name_1 = '';
    $h_chap_related_name_2 = '';
    foreach ($global_manga_chapters as $h_vol) {
        for ($i = 0; $i < sizeof($h_vol['chapters']); $i++) {
            $h_chap = $h_vol['chapters'][$i];
            if ($h_chap['chapter_slug'] == $cur_chap) {
                $cur_chap_name = $h_chap['chapter_name'];
                $cur_vol_name = $h_vol['volume_name'];

                if (sizeof($h_vol['chapters']) > 2) {
                    if ($i == sizeof($h_vol['chapters']) - 1) {
                        $h_chap_related_name_1 = $h_vol['chapters'][$i - 1]['chapter_name'];
                        $h_chap_related_name_2 = $h_vol['chapters'][$i - 2]['chapter_name'];
                    } else if ($i == sizeof($h_vol['chapters']) - 2) {
                        $h_chap_related_name_1 = $h_vol['chapters'][$i - 1]['chapter_name'];
                        $h_chap_related_name_2 = $h_vol['chapters'][$i + 1]['chapter_name'];
                    } else {
                        $h_chap_related_name_1 = $h_vol['chapters'][$i + 1]['chapter_name'];
                        $h_chap_related_name_2 = $h_vol['chapters'][$i + 2]['chapter_name'];
                    }
                } else if (sizeof($h_vol['chapters']) == 2) {
                    if ($i == sizeof($h_vol['chapters']) - 1) {
                        $h_chap_related_name_1 = $h_vol['chapters'][$i - 1]['chapter_name'];
                    } else {
                        $h_chap_related_name_1 = $h_vol['chapters'][$i + 1]['chapter_name'];
                    }
                }

                break;
            }
            if ($cur_chap_name != '') {
                break;
            }
        }
    }
    $description = get_the_title() . ' - ' . $cur_chap_name;
    $keyword = $description;
    if ($h_chap_related_name_1 != '') {
        $description = $description . '. Truyện liên quan: ' . get_the_title() . '-' . $h_chap_related_name_1;
        $keyword = $keyword . ', ' . get_the_title() . ' - ' . $h_chap_related_name_1;
    }
    if ($h_chap_related_name_2 != '') {
        $description = $description . ', ' . get_the_title() . '-' . $h_chap_related_name_2;
        $keyword = $keyword . ', ' . get_the_title() . ' - ' . $h_chap_related_name_2;
    }
    $description = $description . '. Đọc ' . get_the_title() . ' mới nhất tại 10manga.com. Kho truyện tranh, truyện chữ lớn nhất Việt Nam';
    ?>
    <meta name="keywords" content="<?php echo $keyword; ?>"/>
    <meta name="description" content="<?php echo $description; ?>" />
	<meta http-equiv="content-language" content="vi" />
    <?php
}

add_action('wp_head', 'custom_add_meta_description_tag', 999, 1);

get_header();

use App\Madara;

$wp_manga = madara_get_global_wp_manga();
$post_id = get_the_ID();
$paged = !empty($_GET['manga-paged']) ? $_GET['manga-paged'] : 1;
$style = !empty($_GET['style']) ? $_GET['style'] : 'paged';

$chapters = $wp_manga->get_chapter(get_the_ID());
$cur_chap = get_query_var('chapter');

$wp_manga_settings = get_option('wp_manga_settings');
$related_manga = isset($wp_manga_settings['related_manga']['state']) ? $wp_manga_settings['related_manga']['state'] : null;

$madara_single_sidebar = madara_get_theme_sidebar_setting();
$madara_breadcrumb = Madara::getOption('manga_single_breadcrumb', 'on');
$manga_reading_discussion = Madara::getOption('manga_reading_discussion', 'on');

if ($madara_single_sidebar == 'full') {
    $main_col_class = 'sidebar-hidden col-xs-12 col-sm-12 col-md-12 col-lg-12';
} else {
    $main_col_class = 'main-col col-xs-12 col-sm-8 col-md-8 col-lg-8';
}
?>

<div class="c-page-content style-1">
    <div class="content-area" >
        <div class="container">
            <div class="row">
                <div class="main-col col-md-12 col-sm-12 sidebar-hidden">
                    <!-- container & no-sidebar-->
                    <div class="main-col-inner">
                        <div class="c-blog-post">
                            <div class="entry-header">
<?php $wp_manga->manga_nav('header'); ?>
                            </div>
                            <h1><?php echo the_title() . ': ' . $c_name; ?></h1>
                            <div class="entry-content">
                                <div class="entry-content_wrap">
                                    <div class="">

<style>#M329105ScriptRootC227556 {min-height: 300px;}</style> 
<!-- Composite Start --> 
    <div id="M329105ScriptRootC227556"> 
        <div id="M329105PreloadC227556"> Loading... 
        </div> 
        <script> (function(){ var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById'; var i=d[ce]('iframe');i[st][ds]=n;d[gi]("M329105ScriptRootC227556")[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln("<ht"+"ml><bo"+"dy></bo"+"dy></ht"+"ml>");iw.close();var c=iw[b];} catch(e){var iw=d;var c=d[gi]("M329105ScriptRootC227556");}var dv=iw[ce]('div');dv.id="MG_ID";dv[st][ds]=n;dv.innerHTML=227556;c[ac](dv); var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src="//jsc.mgid.com/1/0/10manga.com.227556.js?t="+D.getYear()+D.getMonth()+D.getUTCDate()+D.getUTCHours();c[ac](s);})(); 
        </script> 
    </div> 
<!-- Composite End -->


                                        <?php
                                        if ($wp_manga->is_content_manga(get_the_ID())) {
                                            $GLOBALS['wp_manga_template']->load_template('reading-content/content', 'reading-content', true);
                                        } else {
                                            $GLOBALS['wp_manga_template']->load_template('reading-content/content', 'reading-' . $style, true);
                                        }
                                        ?>

<style>#M329105ScriptRootC227554 {min-height: 300px;}</style>
<!-- Composite Start -->
<div id="M329105ScriptRootC227554">
        <div id="M329105PreloadC227554">
        Loading...    </div>
        <script>
                (function(){
            var D=new Date(),d=document,b='body',ce='createElement',ac='appendChild',st='style',ds='display',n='none',gi='getElementById';
            var i=d[ce]('iframe');i[st][ds]=n;d[gi]("M329105ScriptRootC227554")[ac](i);try{var iw=i.contentWindow.document;iw.open();iw.writeln("<ht"+"ml><bo"+"dy></bo"+"dy></ht"+"ml>");iw.close();var c=iw[b];}
            catch(e){var iw=d;var c=d[gi]("M329105ScriptRootC227554");}var dv=iw[ce]('div');dv.id="MG_ID";dv[st][ds]=n;dv.innerHTML=227554;c[ac](dv);
            var s=iw[ce]('script');s.async='async';s.defer='defer';s.charset='utf-8';s.src="//jsc.mgid.com/1/0/10manga.com.227554.js?t="+D.getYear()+D.getMonth()+D.getUTCDate()+D.getUTCHours();c[ac](s);})();
    </script>
</div>
<!-- Composite End -->

                                    </div>
                                </div>
                            </div>
                            <h1><?php echo the_title() . ': ' . $c_name; ?></h1>
                        </div>
                        <div class="c-select-bottom">
<?php $wp_manga->manga_nav('footer'); ?>
                        </div>
                        <div style="float: right">
<?php
if (function_exists("kk_star_ratings")) : echo kk_star_ratings($pid);
endif;
?>
                        </div>

                        <?php if ($manga_reading_discussion == 'on') { ?>
                            <div class="row <?php echo ( $madara_single_sidebar == 'left' ) ? 'sidebar-left' : ''; ?>">
                                <div class="<?php echo esc_attr($main_col_class); ?>">
                                    <!-- comments-area -->
                                    <?php do_action('wp_manga_discusion'); ?>
                                    <!-- END comments-area -->
                                </div>

                                <?php
                                if ($madara_single_sidebar != 'full') {
                                    ?>
                                    <div class="sidebar-col col-md-4 col-sm-4">
                                        <?php get_sidebar(); ?>
                                    </div>
                                <?php }
                                ?>

                            </div>
                        <?php } ?>

                        <?php
                        if ($related_manga == 1) {
                            get_template_part('/madara-core/manga', 'related');
                        }

                        if (class_exists('WP_Manga')) {
                            $GLOBALS['wp_manga']->wp_manga_get_tags();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
