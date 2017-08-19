<?php

use Nette\Neon\Neon;
use Wizhi\Helper\GitHubUpdater;

/**
 * 内置的文章类型选项
 *
 * @return array
 */
function wizhi_post_types() {
	$post_types = [
		'prod'     => __( 'Product', 'wizhi' ),
		'event'    => __( 'Event', 'wizhi' ),
		'review'   => __( 'Review', 'wizhi' ),
		'case'     => __( 'Portfolio', 'wizhi' ),
		'corp'     => __( 'Cooperation', 'wizhi' ),
		'team'     => __( 'Team', 'wizhi' ),
		'slider'   => __( 'Slider', 'wizhi' ),
		'faq'      => __( 'Faq', 'wizhi' ),
		'download' => __( 'Download', 'wizhi' ),
	];

	return $post_types;
}


/**
 * 默认的文章类型图标
 *
 * @return array
 */
function wizhi_post_types_icon() {

	$post_types_icons = "
		prod: dashicons-cart
		event: dashicons-calendar
		review: dashicons-thumbs-up
		case: dashicons-awards
		corp: dashicons-universal-access
		team: dashicons-groups
		slider: dashicons-slides
		faq: dashicons-editor-help
		download: dashicons-download
	";

	return Neon::decode( $post_types_icons );
}


$config = "
	owner: iwillhappy1314
	repo: wizhi-cms
	basename: wizhi-cms/cms.php'
";

new GitHubUpdater( Neon::decode( $config ) );