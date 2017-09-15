<?php
/**
 * 翻译固定字符串
 */

namespace Wizhi\Helper;

class Condition {

	/**
	 * 判断是否为微信
	 */
	public static function is_wechat() {
		if ( strpos( $_SERVER[ 'HTTP_USER_AGENT' ], 'MicroMessenger' ) !== false ) {
			return true;
		}

		return false;
	}


	/**
	 * 判断是否为 Ajax 请求
	 *
	 * @return bool
	 */
	public static function is_ajax() {
		if ( ! empty( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) == 'xmlhttprequest' ) {
			return true;
		}

		return false;
	}

}