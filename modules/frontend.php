<?php

// 获取插件设置值
$is_enable_static = get_option( 'is_enable_static' );
$is_enable_font   = get_option( 'is_enable_font' );


// 加载 FontAwesome
if ( $is_enable_font ) {
	add_action( 'wp_enqueue_scripts', 'wizhi_ui_font' );
}


add_filter( 'body_class', 'wizhi_body_class' );
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


if ( $is_enable_static ) {
	//加载 CSS 和 JS
	if ( ! is_admin() ) {
		add_action( 'wp_enqueue_scripts', 'wizhi_ui_scripts' );
		add_action( 'wp_enqueue_scripts', 'wizhi_ui_style' );
	}
}


/**
 * 加载CSS
 *
 * @package front
 */
function wizhi_ui_style() {
	wp_register_style( 'wizhi-style', plugins_url( '../front/dist/styles/main.css', __FILE__ ) );
	wp_enqueue_style( 'wizhi-style' );
}


/**
 * 加载 FontAwesome 字体图标
 *
 * @package front
 */
function wizhi_ui_font() {
	wp_register_style( 'wizhi-font', plugins_url( '../front/dist/styles/font.css', __FILE__ ) );
	wp_enqueue_style( 'wizhi-font' );
}


/**
 * 加载 JavaScript
 *
 * @package front
 */
function wizhi_ui_scripts() {
	wp_register_script( 'wizhi-script', plugins_url( '../front/dist/scripts/main.js', __FILE__ ), [ 'jquery' ], '1.1', true );
	wp_register_script( 'wizhi-cms-script', plugins_url( '../admin/dist/scripts/front.js', __FILE__ ), [ 'jquery' ], '1.1', true );
	wp_enqueue_script( 'wizhi-script' );
	wp_enqueue_script( 'wizhi-cms-script' );
}


/**
 * 加载后台样式
 */
add_action( 'admin_enqueue_scripts', 'load_custom_admin_style' );
function load_custom_admin_style() {
	wp_enqueue_media();
	wp_enqueue_style( 'wizhi-form-style', plugins_url( '../admin/dist/styles/main.css', __FILE__ ) );
	wp_enqueue_script( 'wizhi-form-scripts', plugins_url( '../admin/dist/scripts/admin.js', __FILE__ ), [ 'jquery' ], WIZHI_CMS_VERSION );
}

