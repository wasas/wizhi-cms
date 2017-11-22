<?php

namespace Wizhi\Helper;

/**
 * 翻译固定字符串
 */
class Language {

	/**
	 * 定义需要翻译的字符串
	 *
	 * @param $message
	 *
	 * @return string
	 */
	public static function string( $message ) {
		$lang = new Translator;

		return $lang->translate( $message );
	}

	/**
	 * 自动添加当前语言到 Body CSS Class
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public static function body_class( $classes ) {

		$lang      = get_bloginfo( 'language' );
		$classes[] = 'lang-' . $lang;

		return $classes;
	}

}