<?php

namespace Wizhi\Controllers;

use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Request;
use Themosis\Route\BaseController;
use Wizhi\Helper\Api;
use Wizhi\Models\PhoneCode;

class HelperController extends BaseController {

	// Ajax 上传文件
	public function upload() {

		if ( ! is_user_logged_in() ) {
			return false;
		}

		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/media.php' );

		$attachment_id    = media_handle_upload( 'file', 0 );
		$attachment_thumb = wp_get_attachment_thumb_url( $attachment_id );

		$file_data = [
			'id'    => $attachment_id,
			'thumb' => $attachment_thumb,
		];

		wp_send_json( $file_data );

	}


	/**
	 * 获取短信验证码
	 */
	public function sms() {

		$mobile = isset( $_POST[ 'mobile' ] ) ? $_POST[ 'mobile' ] : '';
		$random = mt_rand( 100000, 999999 );

		// 判断用户是否存在
		if ( username_exists( $mobile ) ) {
			$msg = [
				'success' => 0,
				'message' => '你已经注册过了！',
			];
		} else {
			// 插入验证码到数据库中
			$code       = PhoneCode::firstOrCreate( [ 'mobile' => $mobile ] );
			$code->code = $random;
			$code->save();

			$msg = Api::send_sms( $mobile, $code->code );
		}

		wp_send_json( $msg );

	}


	/**
	 * 获取图形验证码
	 *
	 * @param $type
	 */
	function get_captcha( $type ) {

		header( 'Content-type: image/jpeg' );
		$builder = new CaptchaBuilder;

		// 验证码放在什么地方？
		update_option( $type . '_captcha', $builder->getPhrase() );

		$builder->build( 96, 34 )->output();

	}


	/**
	 * 验证手机短信
	 */
	public function validate_sms() {

		$request = Request::createFromGlobals();

		$mobile        = $request->get( 'mobile' );
		$validate_code = $request->get( 'validate_code' );

		$code = PhoneCode::where( 'mobile', '=', $mobile )->where( 'code', '=', $validate_code )->first();

		$msg = [
			'success' => 1,
			'message' => '验证通过！',
		];

		if ( ! $code ) {
			$msg = [
				'success' => 0,
				'message' => '你已经注册过了！',
			];
		}

		wp_send_json( $msg );

	}


	/**
	 * 验证用户名是否已存在
	 */
	public function validate_username() {

		$request    = Request::createFromGlobals();
		$user_login = $request->get( 'user_login' );

		if ( ! $user_login ) {
			$user_login = $request->get( 'mobile' );
		}

		if ( ! $user_login ) {
			$msg = [
				'success' => false,
				'message' => __( '请输入用户名!', ' wizhi' ),
			];
		} else {
			if ( username_exists( $user_login ) ) {
				$msg = [
					'success' => false,
					'message' => __( '用户名已存在, 请更换!', ' wizhi' ),
				];
			} else {
				$msg = [
					'success' => true,
					'message' => __( '用户名可以注册!', ' wizhi' ),
				];
			}
		}

		wp_send_json( $msg );

	}


	/**
	 * 验证邮箱是否已存在
	 */
	public function validate_email() {

		$request    = Request::createFromGlobals();
		$user_email = $request->get( 'user_email' );

		if ( ! is_email( $user_email ) ) {
			$msg = [
				'success' => false,
				'message' => __( '请输入正确的电子邮件地址!', ' wizhi' ),
			];
		} else {
			if ( email_exists( $user_email ) ) {
				$msg = [
					'success' => false,
					'message' => __( '电子邮件已存在, 请更换!', ' wizhi' ),
				];
			} else {
				$msg = [
					'success' => true,
					'message' => __( '电子邮件可以使用!', ' wizhi' ),
				];
			}
		}

		wp_send_json( $msg );

	}

}
