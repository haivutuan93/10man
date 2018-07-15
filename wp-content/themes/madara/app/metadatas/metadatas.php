<?php
	/**
	 * Add metadata (meta-boxes) for all post types
	 */
	require( 'page-metadatas.php' );

	/**
	 * Add metadata for categories
	 */
	require( 'category-metadatas.php' );

	App\Plugins\madara_Author\Author::initialize();
	/**
	 * Add metadata for author
	 */
	require( 'post-metadatas.php' );