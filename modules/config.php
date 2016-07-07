<?php

function wizhi_post_types() {
	$post_types = [
		'prod'     => __( 'Product', 'wizhi' ),
		'case'     => __( 'Portfolio', 'wizhi' ),
		'corp'     => __( 'Cooperation', 'wizhi' ),
		'team'     => __( 'Team', 'wizhi' ),
		'slider'   => __( 'Slider', 'wizhi' ),
		'faq'      => __( 'Faq', 'wizhi' ),
		'download' => __( 'Download', 'wizhi' ),
	];

	return $post_types;
}


function wizhi_post_types_icon() {
	$post_types_icons = [
		'prod'     => 'dashicons-cart',
		'case'     => 'dashicons-awards',
		'corp'     => 'dashicons-universal-access',
		'team'     => 'dashicons-groups',
		'slider'   => 'dashicons-slides',
		'faq'      => 'dashicons-editor-help',
		'download' => 'dashicons-download',
	];

	return $post_types_icons;
}


if ( is_admin() ) {
	$config = [
		'slug'               => plugin_basename( __FILE__ ),
		'proper_folder_name' => 'wizhi-cms',
		'api_url'            => 'https://api.github.com/repos/username/iwillhappy1314',
		'raw_url'            => 'https://raw.githubusercontent.com/iwillhappy1314/wizhi-cms/master',
		'github_url'         => 'https://github.com/iwillhappy1314/wizhi-cms',
		'zip_url'            => 'https://github.com/iwillhappy1314/wizhi-cms/zipball/master',
		'sslverify'          => true,
		'requires'           => '3.0',
		'tested'             => '4.5',
		'readme'             => 'readme.txt',
		'access_token'       => '',
	];
	new WP_GitHub_Updater( $config );
}