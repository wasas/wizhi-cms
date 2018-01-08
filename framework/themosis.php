<?php
/*
Plugin Name: Themosis framework
Plugin URI: https://framework.themosis.com/
Description: A WordPress framework.
Version: 1.3.2
Author: Julien Lambé
Author URI: http://www.themosis.com/
License: GPLv2
*/

/*----------------------------------------------------*/
// The directory separator.
/*----------------------------------------------------*/
defined( 'DS' ) ? DS : define( 'DS', DIRECTORY_SEPARATOR );

/*----------------------------------------------------*/
// Themosis framework textdomain.
//
// This constant is only used by the core plugin.
// Developers should not try to use it into their
// own projects.
/*----------------------------------------------------*/
defined( 'THEMOSIS_FRAMEWORK_TEXTDOMAIN' ) ? THEMOSIS_FRAMEWORK_TEXTDOMAIN : define( 'THEMOSIS_FRAMEWORK_TEXTDOMAIN', 'themosis-framework' );

/*----------------------------------------------------*/
// Storage path.
/*----------------------------------------------------*/
defined( 'THEMOSIS_STORAGE' ) ? THEMOSIS_STORAGE : define( 'THEMOSIS_STORAGE', WP_CONTENT_DIR . DS . 'storage' );

if ( ! function_exists( 'themosis_set_paths' ) ) {
	/**
	 * Register paths globally.
	 *
	 * @param array $paths Paths to register using alias => path pairs.
	 */
	function themosis_set_paths( array $paths ) {
		foreach ( $paths as $name => $path ) {
			if ( ! isset( $GLOBALS[ 'themosis.paths' ][ $name ] ) ) {
				$GLOBALS[ 'themosis.paths' ][ $name ] = realpath( $path ) . DS;
			}
		}
	}
}

if ( ! function_exists( 'themosis_path' ) ) {
	/**
	 * Helper function to retrieve a previously registered path.
	 *
	 * @param string $name The path name/alias. If none is provided, returns all registered paths.
	 *
	 * @return string|array
	 */
	function themosis_path( $name = '' ) {
		if ( ! empty( $name ) ) {
			return $GLOBALS[ 'themosis.paths' ][ $name ];
		}

		return $GLOBALS[ 'themosis.paths' ];
	}
}

/*
 * Main class that bootstraps the framework.
 */
if ( ! class_exists( 'Themosis' ) ) {
	class Themosis {
		/**
		 * Themosis instance.
		 *
		 * @var \Themosis
		 */
		protected static $instance = null;

		/**
		 * Framework version.
		 *
		 * @var float
		 */
		const VERSION = '1.3.2';

		/**
		 * The service container.
		 *
		 * @var \Themosis\Foundation\Application
		 */
		public $container;

		private function __construct() {
			$this->autoload();
			$this->bootstrap();
		}

		/**
		 * Retrieve Themosis class instance.
		 *
		 * @return \Themosis
		 */
		public static function instance() {
			if ( is_null( static::$instance ) ) {
				static::$instance = new static();
			}

			return static::$instance;
		}

		/**
		 * Check for the composer autoload file.
		 */
		protected function autoload() {
			// Check if there is a autoload.php file.
			// Meaning we're in development mode or
			// the plugin has been installed on a "classic" WordPress configuration.
			if ( file_exists( $autoload = __DIR__ . DS . 'vendor' . DS . 'autoload.php' ) ) {
				require $autoload;

				// Developers using the framework in a "classic" WordPress
				// installation can activate this by defining
				// a THEMOSIS_ERROR constant and set its value to true or false
				// depending of their environment.
				if ( defined( 'THEMOSIS_ERROR' ) && THEMOSIS_ERROR ) {
					$whoops = new \Whoops\Run();
					$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
					$whoops->register();
				}
			}
		}

		/**
		 * Bootstrap the core plugin.
		 */
		protected function bootstrap() {
			/*
			 * Define core framework paths.
			 * These are real paths, not URLs to the framework files.
			 */
			$paths[ 'core' ]    = __DIR__ . DS;
			$paths[ 'sys' ]     = __DIR__ . DS . 'src' . DS . 'Themosis' . DS;
			$paths[ 'storage' ] = THEMOSIS_STORAGE;
			themosis_set_paths( $paths );

			/*
			 * Instantiate the service container for the project.
			 */
			$this->container = new \Themosis\Foundation\Application();

			/*
			 * Create a new Request instance and register it.
			 * By providing an instance, the instance is shared.
			 */
			$request = \Themosis\Foundation\Request::capture();
			$this->container->instance( 'request', $request );

			/*
			 * Setup the facade.
			 */
			\Themosis\Facades\Facade::setFacadeApplication( $this->container );

			/*
			 * Register into the container, the registered paths.
			 * Normally at this stage, plugins should have
			 * their paths registered into the $GLOBALS array.
			 */
			$this->container->registerAllPaths( themosis_path() );

			/*
			 * Register core service providers.
			 */
			$this->registerProviders();

			/*
			 * Setup core.
			 */
			$this->setup();

			/*
			 * Project hooks.
			 * Added in their called order.
			 */
			add_action( 'template_redirect', 'redirect_canonical' );
			add_action( 'template_redirect', 'wp_redirect_admin_locations' );
			add_action( 'template_redirect', [ $this, 'setRouter' ], 20 );
		}

		/**
		 * Register core framework service providers.
		 */
		protected function registerProviders() {
			/*
			 * Service providers.
			 */
			$providers = apply_filters( 'themosis_service_providers', [
				Themosis\Finder\FinderServiceProvider::class,
				Themosis\Route\RouteServiceProvider::class,
				Themosis\View\ViewServiceProvider::class,
			] );

			foreach ( $providers as $provider ) {
				$this->container->register( $provider );
			}
		}

		/**
		 * Setup core framework parameters.
		 * At this moment, all activated plugins have been loaded.
		 * Each plugin has its service providers registered.
		 */
		protected function setup() {

		}

		/**
		 * Hook into front-end routing.
		 * Setup the router API to be executed before
		 * theme default templates.
		 */
		public function setRouter() {
			if ( is_feed() || is_comment_feed() ) {
				return;
			}

			try {
				$request  = $this->container[ 'request' ];
				$response = $this->container[ 'router' ]->dispatch( $request );

				// We only send back the content because, headers are already defined
				// by WordPress internals.
				$response->sendContent();
				die();
			} catch ( \Symfony\Component\HttpKernel\Exception\NotFoundHttpException $exception ) {
				/*
				 * Fallback to WordPress templates.
				 */
			}
		}


	}
}

/*
 * Globally register the instance.
 */
$GLOBALS[ 'themosis' ] = Themosis::instance();
