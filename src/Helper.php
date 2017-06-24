<?php
/**
 * 翻译固定字符串
 */

use Wizhi\Helper\Translator;

class Helper {

	/**
	 * 定义需要翻译的字符串
	 *
	 * @param $message
	 *
	 * @return string
	 */
	public static function lang( $message ) {
		$lang = new Translator;

		return $lang->translate( $message );
	}


}