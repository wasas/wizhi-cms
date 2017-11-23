<?php

namespace Wizhi\Helper;

/**
 * 翻译固定字符串
 */
class Archive {

	/**
	 * 获取文章类型存档设置
	 *
	 * @param string $type 文章类型别名
	 *
	 * @return mixed
	 */
	public static function option( $type, $name = null ) {

		$option = get_option( $type . "_archive_settings" );

		if ( $name ) {
			return $option[ $name ];
		} else {
			return $option;
		}

	}


	/**
	 * 获取分类设置的值
	 *
	 * @param string $name      设置的值
	 * @param array  $arguments 文章类型
	 *
	 * @return bool|mixed
	 */
	public static function __callStatic( $name, $arguments = [] ) {

		$post_type = $arguments[ 0 ];

		if ( ! empty( Archive::option( $post_type, $name ) ) ) {
			return Archive::option( $post_type, $name );
		}

		return false;
	}


}