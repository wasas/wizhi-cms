<?php

use Nette\Neon\Neon;

/*----------------------------------------------------*/
// 配置 Corcel 数据库连接
/*----------------------------------------------------*/
$table_prefix = getenv( 'DB_PREFIX' ) ? getenv( 'DB_PREFIX' ) : 'wp_';
$collate      = defined( 'DB_COLLATE' ) && DB_COLLATE ? DB_COLLATE : 'utf8_general_ci';

/*----------------------------------------------------*/
// Illuminate database
/*----------------------------------------------------*/
$capsule = new Illuminate\Database\Capsule\Manager();
$capsule->addConnection( [
	'driver'    => 'mysql',
	'host'      => DB_HOST,
	'database'  => DB_NAME,
	'username'  => DB_USER,
	'password'  => DB_PASSWORD,
	'charset'   => DB_CHARSET,
	'collation' => $collate,
	'prefix'    => $table_prefix,
] );
$capsule->setAsGlobal();
$capsule->bootEloquent();
$GLOBALS[ 'themosis.capsule' ] = $capsule;


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

