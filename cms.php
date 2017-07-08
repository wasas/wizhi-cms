<?php
/*
Plugin Name:        Wizhi CMS
Plugin URI:         http://www.wpzhiku.com/wordpress-cms-plugin-wizhi-cms/
Description:        添加一些实用的功能，增加了一些效果类似dedecms(织梦)模板标签的一些简码。
Version:            1.8
Author:             Amos Lee
Author URI:         http://www.wpzhiku.com/
License:            MIT License
License URI:        http://opensource.org/licenses/MIT
*/

define( 'WIZHI_CMS', plugin_dir_path( __FILE__ ) );
define( 'WIZHI_URL', plugins_url( '', __FILE__ ) );
defined( 'WIZHI_CMS_VERSION' ) or define( 'WIZHI_CMS_VERSION', '1.8' );

require_once( WIZHI_CMS . 'vendor/autoload.php' );

use Nette\Loaders\RobotLoader;

if ( version_compare( phpversion(), '5.6.0', '<' ) ) {

	// 显示警告信息
	if ( is_admin() ) {
		add_action( 'admin_notices', function () {
			printf( '<div class="error"><p>' . __( 'Your PHP version（%1$s）can`t match plugin require, please update to PHP %2$s or later.', 'wizhi' ) . '</p></div>', phpversion(), '5.6' );
		} );
	}

	return;
}

/**
 * 加载翻译文件
 */
add_action( 'plugins_loaded', function () {
	load_plugin_textdomain( 'wizhi', false, basename( dirname( __FILE__ ) ) . '/lang/' );
	load_plugin_textdomain( 'fieldmanager', false, basename( dirname( __FILE__ ) ) . '/lang/' );

	// 检测是否安装了 fieldmanager 插件，如果未安装，包含插件内置的
	if ( ! function_exists( 'fieldmanager_load_class' ) ) {
		require_once WIZHI_CMS . 'inc/fieldmanager/fieldmanager.php';
	}
} );

/**
 * 自动加载 PHP 文件
 *
 * @param  string $folder 需要被包含的文件夹路径, 不能自动发现子目录, 每个子目录需要单独定义
 */
function include_all_php( $folder ) {
	foreach ( glob( "{$folder}/*.php" ) as $filename ) {
		require_once $filename;
	}
}


global $cms_settings;
$cms_settings = get_option( 'wizhi_cms_settings' );

// 自动加载目录中的类
$loader = new RobotLoader;
$loader->addDirectory( WIZHI_CMS . 'src' );

// And set caching to the 'temp' directory
$loader->setTempDirectory( WP_CONTENT_DIR . '/cache' );
$loader->register();

// 加载功能函数
include_all_php( WIZHI_CMS . 'inc/' );

// 加载逻辑设置代码
// todo: 改善自动加载性能
include_all_php( WIZHI_CMS . 'modules/' );
include_all_php( WIZHI_CMS . 'modules/metabox' );
include_all_php( WIZHI_CMS . 'modules/shortcodes' );
include_all_php( WIZHI_CMS . 'mvc/helper' );

// 目录分隔符
defined( 'DS' ) ? DS : define( 'DS', DIRECTORY_SEPARATOR );

// 存储路径
/*----------------------------------------------------*/
defined( 'WIZHI_STORAGE' ) ? WIZHI_STORAGE : define( 'WIZHI_STORAGE', WP_CONTENT_DIR . DS . 'storage' );

if ( ! function_exists( 'wizhi_set_paths' ) ) {
	/**
	 * 全局注册路径
	 *
	 * @param array $paths Paths to register using alias => path pairs.
	 */
	function wizhi_set_paths( array $paths ) {
		foreach ( $paths as $name => $path ) {
			if ( ! isset( $GLOBALS[ 'wizhi.paths' ][ $name ] ) ) {
				$GLOBALS[ 'wizhi.paths' ][ $name ] = $path . DS;
			}
		}
	}
}


if ( ! function_exists( 'wizhi_path' ) ) {
	/**
	 * 获取前一个已注册路径的辅助函数
	 *
	 * @param string $name 路径 name/alias. 如果没有提供，返回所有已注册的路径
	 *
	 * @return string|array
	 */
	function wizhi_path( $name = '' ) {
		if ( ! empty( $name ) ) {
			return $GLOBALS[ 'wizhi.paths' ][ $name ];
		}

		return $GLOBALS[ 'wizhi.paths' ];
	}
}


/*
 * 启动框架的入口类文件
 */
if ( ! class_exists( 'Wizhi' ) ) {
	class Wizhi {
		/**
		 * Wizhi 实例
		 *
		 * @var \Wizhi
		 */
		protected static $instance = null;


		/**
		 * 框架版本
		 *
		 * @var float
		 */
		const VERSION = '1.3.2';


		/**
		 * 服务容器
		 *
		 * @var \Wizhi\Foundation\Application
		 */
		public $container;


		private function __construct() {
			$this->autoload();
			$this->bootstrap();
		}


		/**
		 * 获取 Wizhi 类实例
		 *
		 * @return \Wizhi
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new static();
			}

			return static::$instance;
		}


		/**
		 * 检查 composer 自动加载文件
		 */
		protected function autoload() {
			// 检查自动加载文件是否存在，这意味着我们在开发模式或经典 WordPress 模式
			if ( file_exists( $autoload = __DIR__ . DS . 'vendor' . DS . 'autoload.php' ) ) {
				require $autoload;
				// 在“经典”WordPress 安装中使用框架的开发人员可以通过定义一个WIZHI_ERROR常量来激活此框架，
				// 并将其值设置为true或false，具体取决于它们的环境。
				if ( defined( 'WIZHI_ERROR' ) && WIZHI_ERROR ) {
					$whoops = new \Whoops\Run();
					$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
					$whoops->register();
				}
			}
		}


		/**
		 * 启动核心插件
		 */
		protected function bootstrap() {
			/*
			 * 定义核心框架路径
			 */
			$paths[ 'core' ]    = __DIR__ . DS;
			$paths[ 'sys' ]     = __DIR__ . DS . 'src' . DS . 'Wizhi' . DS;
			$paths[ 'storage' ] = WIZHI_STORAGE;
			wizhi_set_paths( $paths );

			/*
			 * 为项目初始化服务提供者
			 */
			$this->container = new \Wizhi\Foundation\Application();


			/*
			 * 创建新请求实例并注册
			 * By providing an instance, the instance is shared.
			 */
			$request = \Wizhi\Foundation\Request::capture();
			$this->container->instance( 'request', $request );


			/*
			 * 设置 facade.
			 */
			\Wizhi\Facades\Facade::setFacadeApplication( $this->container );


			/*
			 * 注册到服务容器，已注册的路径，通常在此阶段、插件应该已经注册他们的路径到 $GLOBALS 数组了
			 */
			$this->container->registerAllPaths( wizhi_path() );


			/*
			 * 注册核心服务提供者
			 */
			$this->registerProviders();


			/*
			 * 设置内核
			 */
			$this->setup();


			/*
			 * 插件钩子
			 * Added in their called order.
			 */
			add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
			add_action( 'admin_head', [ $this, 'adminHead' ] );
			add_action( 'template_redirect', 'redirect_canonical' );
			add_action( 'template_redirect', 'wp_redirect_admin_locations' );
			add_action( 'template_redirect', [ $this, 'setRouter' ], 20 );
		}


		/**
		 * 注册核心框架服务提供者
		 */
		protected function registerProviders() {
			/*
			 * 服务提供者
			 */
			$providers = apply_filters( 'wizhi_service_providers', [
				Wizhi\Ajax\AjaxServiceProvider::class,
				Wizhi\Asset\AssetServiceProvider::class,
				Wizhi\Config\ConfigServiceProvider::class,
				Wizhi\Database\DatabaseServiceProvider::class,
				Wizhi\Field\FieldServiceProvider::class,
				Wizhi\Finder\FinderServiceProvider::class,
				Wizhi\Hook\HookServiceProvider::class,
				Wizhi\Html\FormServiceProvider::class,
				Wizhi\Html\HtmlServiceProvider::class,
				Wizhi\Load\LoaderServiceProvider::class,
				Wizhi\Metabox\MetaboxServiceProvider::class,
				Wizhi\Page\PageServiceProvider::class,
				Wizhi\Page\Sections\SectionServiceProvider::class,
				Wizhi\PostType\PostTypeServiceProvider::class,
				Wizhi\Route\RouteServiceProvider::class,
				Wizhi\Taxonomy\TaxonomyServiceProvider::class,
				Wizhi\User\UserServiceProvider::class,
				Wizhi\Validation\ValidationServiceProvider::class,
				Wizhi\View\ViewServiceProvider::class,
			] );
			foreach ( $providers as $provider ) {
				$this->container->register( $provider );
			}
		}


		/**
		 * 设置核心框架参数，此时，所有激活的插件已被加载，每个插件都有自己的服务提供者被注册
		 */
		protected function setup() {
			/*
			 * 添加视图路径
			 */
			$viewFinder = $this->container[ 'view.finder' ];
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'Metabox' . DS . 'Views' );
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'Page' . DS . 'Views' );
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'PostType' . DS . 'Views' );
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'Field' . DS . 'Fields' . DS . 'Views' );
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'Taxonomy' . DS . 'Views' );
			$viewFinder->addLocation( wizhi_path( 'sys' ) . 'User' . DS . 'Views' );


			/*
			 * 添加路径到资源查找器
			 */
			$url         = plugins_url( 'src/Wizhi/_assets', __FILE__ );
			$assetFinder = $this->container[ 'asset.finder' ];
			$assetFinder->addPaths( [ $url => wizhi_path( 'sys' ) . '_assets' ] );


			/*
			 * 添加框架核心资源 URL 到全局管理 JS 对象
			 */
			add_filter( 'wizhiAdminGlobalObject', function ( $data ) use ( $url ) {
				$data[ '_wizhiAssets' ] = $url;

				return $data;
			} );


			/*
			 * 注册框架图像尺寸
			 */
			$images = new Wizhi\Config\Images( [
				'_wizhi_media' => [ 100, 100, true, __( 'Mini', 'wizhi' ) ],
			], $this->container[ 'filter' ] );
			$images->make();


			/*
			 * 注册框架资源
			 */
			// $this->container[ 'asset' ]->add( 'wizhi-core-styles', 'css/_wizhiCore.css', [ 'wp-color-picker' ] )->to( 'admin' );
			// $this->container[ 'asset' ]->add( 'wizhi-core-scripts', 'js/_wizhiCore.js', [
			// 	'jquery',
			// 	'jquery-ui-sortable',
			// 	'underscore',
			// 	'backbone',
			// 	'mce-view',
			// 	'wp-color-picker',
			// ], '1.3.0', true )->to( 'admin' );
		}


		/**
		 * 挂载到前端路由，在主题模板执行之前设置路由 API
		 */
		public function setRouter() {
			if ( is_feed() || is_comment_feed() ) {
				return;
			}
			try {
				$request  = $this->container[ 'request' ];
				$response = $this->container[ 'router' ]->dispatch( $request );
				// 只返回内容、header 已被 WordPress 内核设置好了
				$response->sendContent();
				exit();
			} catch ( \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception ) {
				/*
				 * 退回到 WordPress 模板
				 */
			}
		}


		/**
		 * 注册管理界面脚本
		 */
		public function adminEnqueueScripts() {
			/*
			 * 确保媒体脚本总是被加载
			 */
			wp_enqueue_media();
		}


		/**
		 * 输入全局 JS 对象到 admin <head>
		 */
		public function adminHead() {
			$datas  = apply_filters( 'wizhiAdminGlobalObject', [] );
			$output = "<script type=\"text/javascript\">\n\r";
			$output .= "//<![CDATA[\n\r";
			$output .= "var wizhiAdmin = {\n\r";
			if ( ! empty( $datas ) ) {
				foreach ( $datas as $key => $value ) {
					$output .= $key . ': ' . json_encode( $value ) . ",\n\r";
				}
			}
			$output .= "};\n\r";
			$output .= "//]]>\n\r";
			$output .= '</script>';

			echo $output;
		}
	}
}
/*
 * 全局注册实例
 */
$GLOBALS[ 'wizhi' ] = Wizhi::instance();