<?php
/**
 * 加载静态资源到前端
 */

use TheFold\WordPress\Dispatch;

global $cms_settings;

/**
 * 获取由 Less 编译压缩后的 CSS 文件
 */
new Dispatch( [

	// 获取购物车
	'cms.css' => function ( $request ) {
		$less_files = [
			WIZHI_CMS . '/assets/styles/theme.less' => WIZHI_URL . '/front/cache/',
		];

		$options = [
			'compress'  => true,
			'cache_dir' => WIZHI_CMS . '/front/cache/',
		];

		$css_file_name = Less_Cache::Get( $less_files, $options );
		$css_file_path = WIZHI_CMS . '/front/cache/' . $css_file_name;

		$etag               = md5_file( $css_file_path );
		$last_modified_time = filemtime( $css_file_path );

		header_remove( "Pragma" );
		header_remove( "X-Powered-By" );
		header_remove( "Cache-Control" );
		header_remove( "Expires" );

		header( "Accept-Ranges:bytes" );
		header( "Content-type:text/css" );
		header( "Last-Modified: " . gmdate( "D, d M Y H:i:s", $last_modified_time ) . "GMT" );
		header( "ETag: $etag" );
		header( "Content-Length: " . filesize( $css_file_path ) );

		echo file_get_contents( $css_file_path );
	},

] );