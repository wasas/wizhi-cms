<?php
/**
 * Created by PhpStorm.
 * User: amoslee
 * Date: 2017/9/12
 * Time: 15:50
 */

namespace Wizhi\Helper;


class Api {

	/**
	 * 使用云片网网关发送短信
	 *
	 * @param $mobile   string 手机号
	 * @param $content  string 短信内容
	 *
	 * @return mixed
	 */
	public static function send_sms( $mobile, $content ) {

		// 模板接口网关
		$url = "https://sms.yunpian.com/v2/sms/tpl_single_send.json";

		// 单条接口网关
		// $url = "https://sms.yunpian.com/v2/sms/single_send.json";


		$args = [
			'body' => [
				'apikey'    => '7bc919aa8b7dc539ccb4c4b06ee8152c',
				'mobile'    => $mobile,

				// 如果是模板短信
				'tpl_id'    => 426353,
				'tpl_value' => "#code#=$content",

				// 如果是单条短信
				// 'text'   => '【客维99】验证码：' . $content . '，您正在注册客维99（www.kewei99.com），请输入此验证码。',
			],
		];

		$result = json_decode( wp_remote_retrieve_body( wp_remote_post( $url, $args ) ) );

		// 根据网关返回的数据返回消息
		$msg = [
			'success' => $result->code,
			'message' => $result->msg,
		];

		return $msg;
	}

}