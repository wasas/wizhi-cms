<?php


/**
 * 替换 gravatar服务器为多说的服务器
 */
add_filter('get_avatar', 'wizhi_cms_get_avatar', 10, 3);
function wizhi_cms_get_avatar($avatar) {

	$avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "gravatar.duoshuo.com", $avatar);

	return $avatar;
}


/**
 * 移除Google字体
 */
add_action('init', 'wizhi_cms_remove_open_sans');
function wizhi_cms_remove_open_sans() {
	wp_deregister_style('open-sans');
	wp_register_style('open-sans', false);
	wp_enqueue_style('open-sans', '');
}


/**
 * 移除前端的dashicons字体
 */
add_action('init', 'wizhi_cms_remove_dashicons');
function wizhi_cms_remove_dashicons() {
	if (!is_user_logged_in()) {
		wp_deregister_style('dashicons');
		wp_register_style('dashicons', false);
		wp_enqueue_style('dashicons', '');
		wp_deregister_style('editor-buttons');
		wp_register_style('editor-buttons', false);
		wp_enqueue_style('editor-buttons', '');
	}
}


/**
 * 生成随机字符串
 *
 * @param int $length 随机字符串长度
 *
 * @return string
 */
function wizhi_cms_rand_str($length) {
	$chars = array_merge(range('a', 'z'), range('0', '9'));
	$length = intval($length) > 0 ? intval($length) : 8;
	$max = count($chars) - 1;
	$str = "";

	while ($length--) {
		shuffle($chars);
		$rand = mt_rand(0, $max);
		$str .= $chars[$rand];
	}

	return $str;
}


/**
 * 生成文件名，解决中文文件名问题
 */
add_filter('sanitize_file_name', 'wizhi_cms_upload_file', 5, 1);
function wizhi_cms_upload_file($filename) {
	$parts = explode('.', $filename);
	$filename = array_shift($parts);
	$extension = array_pop($parts);
	foreach ((array)$parts as $part) {
		$filename .= '.' . $part;
	}

	if (preg_match('/[\x{4e00}-\x{9fa5}]+/u', $filename)) {
		$filename = date('md') . wizhi_cms_rand_str(8);
	}
	$filename .= '.' . $extension;

	return $filename;
}
