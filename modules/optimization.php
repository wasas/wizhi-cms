<?php

/**
 * 移除前端的dashicons字体
 */
add_action( 'init', 'wizhi_cms_remove_dashicons' );
/**
 * 移除前端不需要的元素
 *
 * @package front
 */
function wizhi_cms_remove_dashicons() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
		wp_register_style( 'dashicons', false );
		wp_enqueue_style( 'dashicons', '' );
		wp_deregister_style( 'editor-buttons' );
		wp_register_style( 'editor-buttons', false );
		wp_enqueue_style( 'editor-buttons', '' );
	}
}


/**
 * 生成文件名，解决中文文件名问题
 *
 * @param string $filename 上传时的文件名
 *
 * @package backend
 *
 * @return mixed|string 处理后的文件名
 */
function wizhi_cms_upload_file( $filename ) {
	$parts     = explode( '.', $filename );
	$filename  = array_shift( $parts );
	$extension = array_pop( $parts );
	foreach ( (array) $parts as $part ) {
		$filename .= '.' . $part;
	}

	if ( preg_match( '/[\x{4e00}-\x{9fa5}]+/u', $filename ) ) {
		$filename = date( 'md' ) . wizhi_cms_rand_str( 8 );
	}
	$filename .= '.' . $extension;

	return $filename;
}

add_filter( 'sanitize_file_name', 'wizhi_cms_upload_file', 5, 1 );