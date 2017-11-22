<?php
/**
 * 提交表单后的消息通知
 * User: amoslee
 * Date: 2017/9/6
 * Time: 11:53
 */

namespace Wizhi\Helper;

use Plasticbrain\FlashMessages\FlashMessages;


class Messages {

	/**
	 * 显示表单提示消息
	 *
	 * @param $message
	 */
	public static function message( $message ) {
		if ( $message ) {
			echo '<div class="alert alert-' . $message[ 'type' ] . '">' . $message[ 'message' ] . '</div>';
		}
	}


	/**
	 * 生成通知消息
	 *
	 * @param $type    string 通知消息类型
	 * @param $message string 通知消息内容
	 *
	 * @return FlashMessages
	 */
	public static function flash( $type, $message ) {
		$msg = new FlashMessages();
		$msg->$type( $message );

		return $msg;
	}


	/**
	 * 显示通知消息
	 */
	public static function messages() {
		$msg = new FlashMessages();
		$msg->display();
	}

}