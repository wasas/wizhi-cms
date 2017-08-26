<?php
/**
 * 初始化需要加载的功能
 * User: amoslee
 * Date: 2017/8/20
 * Time: 20:53
 */

class Init {
	public function __construct() {

		// 初始化简码
		add_shortcode( 'slider', [ 'Wizhi\Shortcode\PostSlider', 'render' ] );
		add_shortcode( 'loop', [ 'Wizhi\Shortcode\PostLoop', 'render' ] );
		add_shortcode( 'list', [ 'Wizhi\Shortcode\PostList', 'render' ] );
		add_shortcode( 'media', [ 'Wizhi\Shortcode\PostGrid', 'render' ] );
		add_shortcode( 'content', [ 'Wizhi\Shortcode\PageContent', 'render' ] );
		add_shortcode( 'divider', [ 'Wizhi\Shortcode\Element', 'divider' ] );
		add_shortcode( 'heading', [ 'Wizhi\Shortcode\Element', 'heading' ] );
		add_shortcode( 'alert', [ 'Wizhi\Shortcode\Element', 'alert' ] );
		add_shortcode( 'button', [ 'Wizhi\Shortcode\Element', 'button' ] );
		add_action( 'init', [ 'Wizhi\Metaboxs\UI', 'init' ] );

		// 初始化 Metabox
		add_action( 'after_setup_theme', [ 'Wizhi\Metaboxs\Post', 'init' ] );
		add_action( 'after_setup_theme', [ 'Wizhi\Metaboxs\Slider', 'init' ] );
		add_action( 'after_setup_theme', [ 'Wizhi\Metaboxs\Term', 'init' ] );
	}
}

new init;