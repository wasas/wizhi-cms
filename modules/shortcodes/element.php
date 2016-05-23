<?php
/**
 * Wizhi Shortcode
 * Wizhi CMS 插件使用的简码
 */

if ( ! function_exists( 'wizhi_shortcode_divider' ) ) {
	/**
	 * 显示几种不同类型的分割线
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [divider type="solid"]
	 *
	 * @return string 经简码格式化后的 HTML 字符串
	 */
	function wizhi_shortcode_divider( $atts ) {
		$default = [
			'type' => 'solid',
		];
		extract( shortcode_atts( $default, $atts ) );

		// 输出
		$retour = '';
		$retour .= '<div class="ui-divider ui-divider-' . $type . '"></div>';

		return $retour;

	}
}
add_shortcode( 'divider', 'wizhi_shortcode_divider' );


if ( ! function_exists( 'wizhi_shortcode_heading' ) ) {
	/**
	 * 显示几种不同类型的分割线
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [heading type="background" content="这是二级标题"]
	 *
	 * @return string 经简码格式化后的 HTML 字符串
	 */
	function wizhi_shortcode_heading( $atts ) {
		$default = [
			'type'    => 'background',
			'content' => '这是二级标题',
		];
		extract( shortcode_atts( $default, $atts ) );

		// 输出
		$retour = '';
		$retour .= '<h2 class="ui-heading ui-heading-' . $type . '">' . $content . '</h2>';

		return $retour;

	}
}
add_shortcode( 'heading', 'wizhi_shortcode_heading' );


if ( ! function_exists( 'wizhi_shortcode_alert' ) ) {
	/**
	 * 显示几种不同类型的分割线
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [alert type="success" content="这是提示信息"]
	 *
	 * @return string 经简码格式化后的 HTML 字符串
	 */
	function wizhi_shortcode_alert( $atts ) {
		$default = [
			'type'    => 'info',
			'content' => '这是提示信息。',
		];
		extract( shortcode_atts( $default, $atts ) );

		// 输出
		$retour = '';
		$retour .= '<div class="ui-alert ui-alert-' . $type . '">' . $content . '</div>';

		return $retour;

	}
}
add_shortcode( 'alert', 'wizhi_shortcode_alert' );


if ( ! function_exists( 'wizhi_shortcode_button' ) ) {
	/**
	 * 显示链接按钮
	 *
	 * @param $atts
	 *
	 * @package shortcode
	 *
	 * @usage [button type="success" size='' text="这是链接" url="http://www.baidu.com"]
	 *
	 * @return string 经简码格式化后的 HTML 字符串
	 */
	function wizhi_shortcode_button( $atts ) {
		$default = [
			'type' => 'success',
			'size' => '',
			'text' => '这是链接',
			'url'  => 'http://',
		];
		extract( shortcode_atts( $default, $atts ) );

		// 输出
		$retour = '';
		$retour .= '<a class="pure-button button-' . $type . ' button-' . $size . '" href="' . $url . '">' . $text . '</a>';

		return $retour;

	}
}
add_shortcode( 'button', 'wizhi_shortcode_button' );