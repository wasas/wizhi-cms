<?php
/**
 * Created by PhpStorm.
 * User: amoslee
 * Date: 2017/9/12
 * Time: 16:52
 */

namespace Wizhi\Controllers;

use Wizhi\Helper\Api;


class OpenAuthController {


	/**
	 * 发送社交登录请求
	 *
	 * @param $provider
	 */
	function request( $provider ) {

		$socialite = Api::oauth_services( $provider );

		$response = $socialite->driver( $provider )->redirect();
		$response->send();

	}


	/**
	 * 社交登录回调
	 *
	 * @param $provider
	 */
	function assets( $provider ) {

		$socialite = Api::oauth_services( $provider );
		$user      = $socialite->driver( $provider ) > user();

		$oauth_uid    = $user->getId();
		$oauth_nic    = $user->getNickname();
		$oauth_avatar = $user->getAvatar();
		$uid_key      = 'oauth_' . $provider . '_uid';

		if ( is_user_logged_in() ) {

			// 如果用户已经登录, 绑定用户, 可以绑定多个平台
			$current_user = wp_get_current_user();
			update_user_meta( $current_user->ID, $uid_key, $oauth_uid );
			update_user_meta( $current_user->ID, $provider . "_avatar", $oauth_avatar );
			$this->redirect();

		} else {

			// 根据授权 ID 登录用户
			$oauth_user = get_users( [ "meta_key " => $uid_key, "meta_value" => $oauth_uid ] );

			// 如果登录失败, 说明该用户没有注册, 注册并绑定
			if ( is_wp_error( $oauth_user ) || ! count( $oauth_user ) ) {
				// 创建并登录用户
				$username        = $oauth_nic;
				$login_name      = wp_create_nonce( $oauth_uid );
				$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

				$user_data = [
					'user_login'   => $login_name,
					'display_name' => $username,
					'user_pass'    => $random_password,
					'nick_name'    => $username,
				];

				$user_id = wp_insert_user( $user_data );

				wp_signon( [ "user_login" => $login_name, "user_password" => $random_password ], false );

				// 更新用户信息
				update_user_meta( $user_id, $uid_key, $oauth_uid );
				update_user_meta( $user_id, $provider . "_avatar", $oauth_avatar );
				$this->rredirect();
				// 如果登录成功, 设置登录 cookie

			} else {

				wp_set_auth_cookie( $oauth_user[ 0 ]->ID );
				$this->rredirect();

			}

		}


	}


	/**
	 *  登录成功后跳转
	 */
	function redirect() {
		echo '<script>
			if( window.opener ) {
				window.opener.location.reload();
                window.close();
            }else{
                window.location.href = "' . home_url() . '";
            }
         </script>';
	}

}