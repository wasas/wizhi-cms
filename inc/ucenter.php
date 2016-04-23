<?php

/**
 * 根据指定 ID 或当前登录用户获取用户对象
 * 该函数有且只有两种行为, 如果能获取到用户 ID, 则返回用户对象, 如果不能, 跳转到登录页面
 *
 * @package conditions
 *
 * @return mixed
 */
function wizhi_get_current_user() {
	if ( ! isset( $_GET[ 'user' ] ) and ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( 'account' ) );
	} else {
		if ( is_user_logged_in() ) {
			if ( isset( $_GET[ 'user' ] ) ) {
				$user = get_user_by( 'id', $_GET[ 'user' ] );
			} else {
				$user = wp_get_current_user();
			}
		} else {
			if ( isset( $_GET[ 'user' ] ) ) {
				$user = get_user_by( 'id', $_GET[ 'user' ] );
			} else {
				wp_safe_redirect( home_url( 'account' ) );
			}
		}
	}

	return $user;
}


/**
 * 判断是否选中了用户资料中的选择项
 *
 * @param $meta_key  string 用户自定义字段名称
 * @param $value     string 自定义字段的值
 *
 * @package conditions
 */
function is_profile_selected( $meta_key, $value ) {

	$user = wizhi_get_current_user();
	$uid  = $user->ID;

	$meta_value = get_user_meta( $uid, $meta_key, true );

	if ( $meta_value == $value ) {
		echo 'selected';
	}
}


/**
 * 判断是否选中了用户资料中的复选框 checkbox
 *
 * @param $meta_key  string 用户自定义字段名称
 * @param $value     string 自定义字段的值
 *
 * @package conditions
 */
function is_profile_checked( $meta_key, $value ) {

	$user = wizhi_get_current_user();
	$uid  = $user->ID;

	$meta_value = get_user_meta( $uid, $meta_key, true );

	if ( $meta_value == $value ) {
		echo 'checked';
	}
}


/**
 * 判断当前访问的用户资料是否为自己的用户资料
 * 用来区分是否应显示一些自己专有的内容
 *
 * @package conditions
 *
 * @return bool
 */
function is_self_profile() {

	$get_user    = wizhi_get_current_user();
	$logged_user = wp_get_current_user();

	if ( $get_user == $logged_user ) {
		return true;
	}

	return false;

}