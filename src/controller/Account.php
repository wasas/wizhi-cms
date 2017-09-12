<?php

namespace Wizhi\Controllers;

use Wizhi\Forms\Form;
use Themosis\Route\BaseController;
use Wizhi\Models\OpenAuth;
use Wizhi\Models\PhoneCode;

class Account extends BaseController {

	/**
	 * 用户注册
	 *
	 * @return string
	 */
	public function register() {

		if ( is_user_logged_in() ) {
			wp_redirect( home_url( 'affiliate-area-page' ) );
		}

		$aff_user_id = isset( $_GET[ 'aff' ] ) ? $_GET[ 'aff' ] : 1;

		// 设置推广 Cookie
		setcookie( 'aff', $aff_user_id, time() + 2629800 );

		$form = new Form;
		wam_form( $form, 'vertical' );

		$form->setMethod( 'POST' );

		$form->addCsrf( 'user_register', '随机数验证失败。' );
		$form->addText( 'user_email', ' 邮箱' )->setRequired( '请输入邮箱。' )->addRule(Form::EMAIL, '请输入正确的邮箱地址。');
		$form->addPassword( 'password', '密码' )->setRequired( '请输入密码。' )->addRule( Form::MIN_LENGTH, '密码最少为 %d 位数字或字母的组合。', 6 );
		$form->addPassword( 're_password', '重复密码' )->setRequired( '请再次输入密码。' )->addRule( Form::EQUAL, '两次输入的密码不匹配。', $form[ 'password' ] );

		$form->addSubmit( 'send', '注册' );
		$form->addHtml( 'login', '<a class="btn" href="' . home_url( 'affiliate-login-page' ) . '">登录</a>' );

		if ( $form->isSuccess() ) {

			$values = $form->getValues();

			$user_email = $values->user_email;
			$user_login = str_replace( '@', '_', $user_email );
			$password   = $values->password;

			if ( $user_login == null || $user_email == null || $password == null ) {

				flash( 'error', '请填写所有字段!' );

			} elseif ( strlen( $password ) < 6 ) {

				flash( 'error', '密码至少为6位!' );

			} else {

				$user_id_or_errors = wp_create_user( $user_login, $password, $user_email );

				if ( is_wp_error( $user_id_or_errors ) ) {

					flash( 'error', $user_id_or_errors->get_error_message() );

				} else {

					// 生成激活码并添加到用户自定义字段中
					$activation_code = $code = sha1( $user_id_or_errors . time() );
					add_user_meta( $user_id_or_errors, 'activation_code', $activation_code, true );

					$activation_link = home_url( 'affiliate-activation/' . $user_id_or_errors . '/' . $activation_code );
					$headers         = [ 'Content-Type: text/html; charset=UTF-8' ];

					wp_mail( $user_email, '请激活您的账户', '点击下面的激活链接激活您的账户：' . $activation_link . ' <br>你的密码是：' . $password, $headers );

					// 如果能在推广记录表里面查找被推荐人的账户为当前账户 ID，则说明该用户是被别人推荐过来的，
					// 则设置本次推荐的级别 2， 否则为 1
					$is_affed = Income::where( 'user_id', '=', $user_id_or_errors );

					$level = 1;
					if ( $is_affed ) {
						$level = 2;
					}

					// 创建推广记录
					Affiliate::create( [
						'user_id'        => $user_id_or_errors,
						'aff_user_id'    => $aff_user_id,
						'parent_user_id' => $aff_user_id,
						'status'         => 'wait',
						'level'          => $level,
					] );

					$aff_award_new_user = get_option( 'aff_award_new_user' );

					// 创建推广收益记录
					if ( $aff_award_new_user && $aff_award_new_user != 0 ) {
						Income::create( [
							'user_id'     => $aff_user_id,
							'operator_id' => 1,
							'amount'      => get_option( 'aff_award_new_user' ), // 待设置
							'type'        => 'register',
							'status'      => 'complete',
						] );
					}

					flash( 'success', '注册成功, 请登录邮箱激活。' );

				}

			}

		}

		return view( 'account.register', [ 'form' => $form ] );

	}


	/**
	 * 用户登录
	 *
	 * @return string
	 */
	public function login() {

		if ( is_user_logged_in() ) {
			wp_redirect( home_url( 'affiliate-area-page' ) );
		}

		$from_id = isset( $_POST[ 'from_id' ] ) ? $_POST[ 'from_id' ] : null;

		$form = new Form;
		wam_form( $form, 'vertical' );

		$form->setMethod( 'POST' );

		$form->addCsrf( 'user_login', '随机数验证失败。' );
		$form->addText( 'user_email', ' 邮箱' )->setRequired( '请输入邮箱。' )->addRule(Form::EMAIL, '请输入正确的邮箱地址。');
		$form->addPassword( 'password', '密码' )->setRequired( '请输入密码。' )->addRule( Form::MIN_LENGTH, '密码最少为 %d 位数字或字母的组合。', 6 );

		$form->addSubmit( 'send', '登录' );
		$form->addHtml( 'register', '<a class="btn" href="' . home_url( 'affiliate-register-page' ) . '">注册</a>' );

		if ( $form->isSuccess() ) {

			$values     = $form->getValues();
			$user_email = $values->user_email;
			$user_login = str_replace( '@', '_', $user_email );
			$password   = $values->password;

			$user = get_user_by( 'email', $user_email );

			$code = get_user_meta( $user->ID, 'activation_code', true );

			if ( $user == null ) {
				flash( 'error', '用户名或密码错误' );
			} elseif ( $code != false ) {
				flash( 'error', '账户未激活，请登录邮箱激活您的账户。' );
			} else {

				// 执行登录操作
				$credentials = [
					'user_login'    => $user_login,
					'user_password' => $password,
				];

				if ( $credentials[ 'user_login' ] == null || $credentials[ 'user_password' ] == null ) {

					flash( 'error', '请填写所有字段。' );

				} else {

					if ( $credentials[ 'user_login' ] != null && $credentials[ 'user_password' ] != null ) {
						$errors = wp_signon( $credentials, false );
					}

					if ( is_wp_error( $errors ) ) {

						flash( 'error', '用户名或密码错误。' );

					} else {

						flash( 'error', '登录成功, 正在跳转...' );

						if ( $from_id ) {
							wp_redirect( get_permalink( $from_id ) );
						} else {
							wp_redirect( home_url( 'affiliate-area-page' ) );
						}

					}

				}

			}

		}

		return view( 'account.login', [
			'form' => $form,
		] );
	}


	/**
	 * 激活用户
	 *
	 * @param $user_id
	 * @param $activation_code
	 *
	 * @return string
	 */
	public function activation( $user_id, $activation_code ) {

		if ( is_user_logged_in() ) {
			wp_redirect( home_url( 'affiliate-area-page' ) );
		}

		if ( $user_id && $activation_code ) {
			$code = get_user_meta( $user_id, 'activation_code', true );

			if ( $code ) {
				if ( $code == $activation_code ) {

					// 激活后，修改推荐状态为 complete
					$aff         = Affiliate::where( 'user_id', '=', $user_id )->first();
					$aff->status = 'complete';
					$aff->save();

					delete_user_meta( $user_id, 'activation_code' );
					wp_set_auth_cookie( $user_id );
					wp_redirect( home_url( 'affiliate-area-page/?from=activation' ) );

				} else {
					flash( 'error', '激活链接已失效。' );
				}
			} else {
				flash( 'success', '您已经激活过了，请直接登录。' );
			}

		}

		return view( 'account.activation' );
	}

}
