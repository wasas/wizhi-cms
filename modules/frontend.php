<?php

add_filter( 'body_class', 'wizhi_body_class' );
add_action( 'after_setup_theme', 'wizhi_add_editor_styles' );
add_action( 'admin_enqueue_scripts', 'load_custom_admin_style' );

$cms_settings = get_option( 'wizhi_cms_settings' );

// 获取插件设置值
$is_enable_css  = $cms_settings[ 'is_enable_css' ];
$is_enable_js   = $cms_settings[ 'is_enable_js' ];
$is_enable_font = $cms_settings[ 'is_enable_font' ];

// 加载 FontAwesome
if ( $is_enable_font ) {
	add_action( 'wp_enqueue_scripts', 'wizhi_ui_font' );
}

if ( ! is_admin() ) {
	if ( $is_enable_css ) {
		add_action( 'wp_enqueue_scripts', 'wizhi_ui_style' );
	}
	if ( $is_enable_js ) {
		add_action( 'wp_enqueue_scripts', 'wizhi_ui_scripts' );
	}
}


/**
 * 添加检测到的移动设备名称到 Body Class 中
 *
 * @param $classes
 *
 * @return array
 */
function wizhi_body_class( $classes ) {

	$detect = new Mobile_Detect;

	// top level
	if ( $detect->isTablet() ) {
		$classes[] = "is_tablet";
	};
	if ( $detect->isMobile() ) {
		$classes[] = "is_mobile";
	};
	if ( $detect->isiOS() ) {
		$classes[] = "is_ios";
	};

	return $classes;
}


/**
 * 加载CSS
 *
 * @package front
 */
function wizhi_ui_style() {
	wp_register_style( 'wizhi-style', WIZHI_URL . '/front/dist/styles/main.css' );
	wp_enqueue_style( 'wizhi-style' );
}


/**
 * 加载 FontAwesome 字体图标
 *
 * @package front
 */
function wizhi_ui_font() {
	wp_register_style( 'wizhi-font', WIZHI_URL . '/front/dist/styles/font.css' );
	wp_enqueue_style( 'wizhi-font' );
}


/**
 * 添加编辑器样式
 */
function wizhi_add_editor_styles() {
	add_editor_style( plugins_url( '../front/dist/styles/main.css', __FILE__ ) );
}


/**
 * 加载 JavaScript
 *
 * @package front
 */
function wizhi_ui_scripts() {
	wp_register_script( 'wizhi-script', WIZHI_URL . '/front/dist/scripts/main.js', [ 'jquery' ], '1.1', true );
	wp_register_script( 'wizhi-cms-script', WIZHI_URL . '/admin/dist/scripts/front.js', [ 'jquery' ], '1.1', true );
	wp_enqueue_script( 'wizhi-script' );
	wp_enqueue_script( 'wizhi-cms-script' );
}


/**
 * 加载后台样式
 */

function load_custom_admin_style() {
	wp_enqueue_media();
	wp_enqueue_style( 'wizhi-form-style', plugins_url( '../admin/dist/styles/main.css', __FILE__ ) );
	wp_enqueue_script( 'wizhi-form-scripts', plugins_url( '../admin/dist/scripts/admin.js', __FILE__ ), [ 'jquery' ], WIZHI_CMS_VERSION, true );
}
