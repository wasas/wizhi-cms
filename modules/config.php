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
	$config = array(
		'owner' => 'iwillhappy1314',
		'repo' => 'wizhi-cms',
		'basename' => 'wizhi-cms/cms.php',
	);

	new WP_GitHub_Updater( $config );
}
