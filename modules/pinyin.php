<?php

use Overtrue\Pinyin\Pinyin;

add_filter( 'name_save_pre', 'wizhi_post_slug', 0 );
add_filter( 'pre_category_nicename', 'wizhi_term_slug', 0 );

/**
 * 替换文章标题为拼音
 *
 * @param $slug
 *
 * @return mixed
 */
function wizhi_post_slug( $slug ) {

	// 手动编辑时，不自动转换为拼音
	if ( $slug ) {
		return $slug;
	}

	$pinyin = new Pinyin();

	// 替换文章标题
	$title = $pinyin->permalink( $_POST[ 'post_title' ] );

	return sanitize_title( $title );
}


/**
 * 替换分类标题为拼音
 *
 * @param $slug
 *
 * @return mixed
 */
function wizhi_term_slug( $slug ) {

	// 手动编辑时，不自动转换为拼音
	if ( $slug ) {
		return $slug;
	}

	$pinyin = new Pinyin();

	// 替换文章标题
	$title = $pinyin->permalink( $_POST[ 'tag-name' ] );

	return sanitize_title( $title );
}