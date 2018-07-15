<?php

	global $madara_shortcodes;

	$madara_shortcodes = array(
		'madara_shortcode_list' => array(
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/all-shortcodes.js'
		),
		'madara_post_slider'           => array(
			'path'       => 'shortcodes/post-slider.php',
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/post-slider.js',
			'class'      => 'MadaraShortcodePostSlider',
			'tag'        => 'manga_post_slider'
		),
		'madara_heading'           => array(
			'path'       => 'shortcodes/heading.php',
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/heading.js',
			'class'      => 'MadaraShortcodeHeading',
			'tag'        => 'manga_heading'
		),
		'madara_blog'            => array(
			'path'       => 'shortcodes/blog.php',
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/blog.js',
			'tag'        => 'manga_blog'
		),
		'manga_listing' => array(
			'path'       => 'shortcodes/manga-listing.php',
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/manga-listing.js',
			'tag'        => 'manga_listing'
		),
		'manga_chapter' => array(
			'path'       => 'shortcodes/manga-chapter.php',
			'classic_js' => CT_SHORTCODE_PLUGIN_URL . 'shortcodes/js/admin/manga-chapter.js',
			'tag'        => 'manga_chapter'
		),
	);
