<?php
/**
 * 微信支付网关
 *
 * @package enter
 */

namespace Wizhi\OpenAuth;

use EasyWeChat\Foundation\Application;
use Symfony\Component\HttpFoundation\Request;
use Wizhi\Models\OpenAuth;

/**
 * 获取微信公众号 open_id, 保存到 cookies 中
 *
 * 实例化此类的时候, 应该可以完成用户注册或登录的动作, open_id 和 access_token 和 refresh_token 要保存在用户字段中, 定期刷新授权
 *
 * Class get_wechat_open_id
 */
class WeChatAuth {

	/**
	 * 初始化时, 自动登录
	 *
	 * WeChatAuth constructor.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'auth' ] );
	}

	/**
	 * 使用 Overtrue\Socialite 库, 几种授权在一个方法中完成
	 *
	 * @return mixed
	 */
	public function auth() {

		$request = Request::createFromGlobals();

		// 怎么获取 Access token 和 refresh token
		$app   = new Application( Helper::get_wechat_config() );
		$oauth = $app->oauth;

		// 如果用户已经登录，就不用再登录了
		if ( ! is_user_logged_in() ) {

			// code 只能使用一次
			$code = $request->get( 'code', false );

			// 如果没有 $code, 说明还没有活得登录授权、发起授权请求
			if ( ! $code ) {

				$oauth->redirect()->send();

				// 已经获得授权请求
			} else {

				// 根据 code 获取微信用户
				$user = $oauth->user();
				$data = $user->getOriginal();

				// 获取用户数据
				$open_id  = $data[ 'openid' ];
				$username = 'wx' . substr( $open_id, 0, 8 );
				$sex      = $data[ 'sex' ];
				$nickname = $data[ 'nickname' ];
				$avatar   = $data[ 'headimgurl' ];
				$city     = $data[ 'city' ];

				// 获取访问码和刷新码
				$token         = $user->getToken();
				$access_token  = $token[ 'access_token' ];
				$refresh_token = $token[ 'refresh_token' ];

				// 根据 Open Id 获取用户
				$auth = OpenAuth::firstOrNew( [ 'open_id' => $open_id ] );

				// 确保能获取用户，否则将会一直尝试注册，且注册不成功
				$oauth_user = get_user_by( 'login', $username );

				// 如果未注册，注册并登录
				if ( ! $oauth_user ) {

					$login_name      = $username;
					$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

					$user_data = [
						'user_login'    => $login_name,
						'user_pass'     => $random_password,
						'user_nicename' => $nickname,
						'nicename' => $nickname,
						'user_email'    => wp_generate_password( 8 ) . '@ddsuda.com',
						'display_name'  => $nickname,
					];

					// 注册后直接登录
					$user_id = wp_insert_user( $user_data );
					update_user_meta( $user_id, 'last_name', $nickname );
					wp_signon( [ "user_login" => $login_name, "user_password" => $random_password ], false );

					// 更新用户信息
					$auth->user_id       = $user_id;
					$auth->open_id       = $open_id;
					$auth->union_id      = ' ';
					$auth->access_token  = $access_token;
					$auth->refresh_token = $refresh_token;
					$auth->nickname      = $nickname;
					$auth->sex           = $sex;
					$auth->avatar        = $avatar;
					$auth->city          = $city;

					$auth->save();

					// 如果已注册, 直接登录，并绑定用户
				} else {
					$user_id = $oauth_user->ID;

					// 登录不了的问题，必须在 header 之前设置
					clean_user_cache($oauth_user->ID);
					wp_clear_auth_cookie();
					wp_set_current_user( $user_id, $oauth_user->user_login );
					wp_set_auth_cookie( $user_id, true, true );
					update_user_caches($oauth_user);

					wp_safe_redirect( home_url() );

					$auth = Oauth::firstOrNew( [ 'user_id' => $oauth_user->ID ] );

					if ( ! $auth->open_id ) {
						$auth->user_id       = $user_id;
						$auth->open_id       = $open_id;
						$auth->union_id      = ' ';
						$auth->access_token  = $access_token;
						$auth->refresh_token = $refresh_token;
						$auth->nickname      = $nickname;
						$auth->sex           = $sex;
						$auth->avatar        = $avatar;
						$auth->city          = $city;

						$auth->save();
					}
				}

			}

		} else {
			$user = wp_get_current_user();
		}

		return false;

	}

}