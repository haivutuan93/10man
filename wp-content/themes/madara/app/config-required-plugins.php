<?php

	$madara_required_plugins = array(
		array(
			'name'     => 'Option Tree',
			'slug'     => 'option-tree',
			'required' => true
		),

		array(
			'name'     => 'Madara - Shortcodes',
			'slug'     => 'madara-shortcodes',
			'source'   => get_template_directory() . '/app/plugins/packages/madara-shortcodes.zip',
			'required' => true,
			'version'  => '1.3'
		),

		array(
			'name'     => 'Madara - Core',
			'slug'     => 'madara-core',
			'source'   => get_template_directory() . '/app/plugins/packages/madara-core.zip',
			'required' => true,
			'version'  => '1.3'
		),

		array(
			'name'     => 'Top 10',
			'slug'     => 'top-10',
			'required' => false
		),

		array(
			'name'     => 'AccessPress Social Share',
			'slug'     => 'accesspress-social-share',
			'required' => false
		),

		array(
			'name'     => 'Widget Logic',
			'slug'     => 'widget-logic',
			'required' => false
		),

	);