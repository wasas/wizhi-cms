<?php
/**
 * 初始化需要加载的功能
 * User: amoslee
 * Date: 2017/8/20
 * Time: 20:53
 */

namespace Wizhi\Bootstrap;

use Nette\Neon\Neon;
use Wizhi\Helper\GitHubUpdater;

class Bootstrap {
	public function __construct() {

		global $wizhi_option;

		// 添加默认的文章类型、分类方法和存档设置
		add_action( 'init', [ 'Wizhi\Option\Content', 'type_options' ] );
		add_action( 'init', [ 'Wizhi\Option\Content', 'default_content_type' ] );

		// 选项设置
		add_action( 'init', [ 'Wizhi\Option\Settings', 'init' ] );

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
		add_action( 'init', [ 'Wizhi\Shortcode\UI', 'init' ] );

		// 初始化 Metabox
		add_action( 'after_setup_theme', [ 'Wizhi\Metabox\Post', 'init' ] );
		add_action( 'after_setup_theme', [ 'Wizhi\Metabox\Slider', 'init' ] );
		add_action( 'after_setup_theme', [ 'Wizhi\Metabox\Term', 'init' ] );

		// 替换 Slug 为拼音
		add_filter( 'name_save_pre', [ 'Wizhi\Action\Slug', 'post' ], 0 );
		add_filter( 'name_save_pre', [ 'Wizhi\Action\Slug', 'term' ], 0 );
		add_filter( 'name_save_pre', [ 'Wizhi\Action\Slug', 'file' ], 0 );

		// 根据设置自动切换移动短主题
		add_filter( 'template', [ 'Wizhi\Action\Responsive', 'switch_theme' ] );
		add_filter( 'stylesheet', [ 'Wizhi\Action\Responsive', 'switch_theme' ] );

		// 根据设置自动替换固定链接
		add_filter( 'the_permalink', [ 'Wizhi\Action\Permalink', 'custom' ] );

		// 自动添加当前语言到 Body CSS Class
		add_filter( 'body_class', [ 'Wizhi\Helper\Language', 'body_class' ] );

		// 清理后台菜单和管理工具条
		if ( $wizhi_option[ 'is_cleanup' ] ) {
			add_action( 'admin_menu', [ 'Wizhi\Action\Cleanup', 'remove_menu' ] );
			add_action( 'wp_before_admin_bar_render', [ 'Wizhi\Action\Cleanup', 'remove_admin_bar' ] );
		}

		// 禁止修改文件
		if ( $wizhi_option[ 'deny_modify' ] ) {
			define( 'DISALLOW_FILE_EDIT', true );
			define( 'DISALLOW_FILE_MODS', true );
		}

	}
}

new Bootstrap;


/**
 * 从 Github 升级
 */
$config = "
		owner: iwillhappy1314
		repo: wizhi-cms
		basename: wizhi-cms/cms.php
	";

$up = new GitHubUpdater( Neon::decode( $config ) );