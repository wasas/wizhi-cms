<?php
/**
 * 初始化需要加载的功能
 * User: amoslee
 * Date: 2017/8/20
 * Time: 20:53
 */

class Init {
	public function __construct() {
		add_shortcode( 'slider', [ new  Wizhi\Shortcode\PostSlider, 'render' ] );
		add_shortcode( 'loop', [ new  Wizhi\Shortcode\PostLoop, 'render' ] );
		add_shortcode( 'list', [ new  Wizhi\Shortcode\PostList, 'render' ] );
		add_shortcode( 'media', [ new  Wizhi\Shortcode\PostGrid, 'render' ] );
		add_shortcode( 'content', [ new  Wizhi\Shortcode\PageContent, 'render' ] );
		add_shortcode( 'divider', [ new  Wizhi\Shortcode\Element, 'divider' ] );
		add_shortcode( 'heading', [ new  Wizhi\Shortcode\Element, 'heading' ] );
		add_shortcode( 'alert', [ new  Wizhi\Shortcode\Element, 'alert' ] );
		add_shortcode( 'button', [ new  Wizhi\Shortcode\Element, 'button' ] );
	}
}

new init;