<?php

namespace Wizhi\Asset;

use Wizhi\Hook\IHook;
use Wizhi\Html\HtmlBuilder;

class Asset implements IAsset {
	/**
	 * 加载资源的默认区域
	 *
	 * @var string
	 */
	protected $area = 'front';

	/**
	 * 允许的区域
	 *
	 * @var array
	 */
	protected $allowedAreas = [ 'admin', 'login', 'customizer' ];

	/**
	 * 资源类型
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * WordPress 的资源属性
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * 资源键名称
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * 所有资源实例列表
	 *
	 * @var array
	 */
	protected static $instances;

	/**
	 * 入队资源名称
	 *
	 * @var array
	 */
	protected static $instantiated;

	/**
	 * @var \Wizhi\Hook\ActionBuilder
	 */
	protected $action;

	/**
	 * @var \Wizhi\Html\HtmlBuilder
	 */
	protected $html;

	/**
	 * @var \Wizhi\Hook\FilterBuilder
	 */
	protected $filter;

	/**
	 * 构建资源实例
	 *
	 * @param string                  $type
	 * @param array                   $args
	 * @param \Wizhi\Hook\IHook       $action
	 * @param \Wizhi\Html\HtmlBuilder $html
	 * @param \Wizhi\Hook\IHook       $filter
	 */
	public function __construct( $type, array $args, IHook $action, HtmlBuilder $html, IHook $filter ) {
		$this->type   = $type;
		$this->args   = $this->parse( $args );
		$this->key    = strtolower( trim( $args[ 'handle' ] ) );
		$this->action = $action;
		$this->html   = $html;
		$this->filter = $filter;

		$this->registerInstance();

		// Listen to WordPress asset events.
		$action->add( 'wp_enqueue_scripts', [ $this, 'install' ] );
		$action->add( 'admin_enqueue_scripts', [ $this, 'install' ] );
		$action->add( 'login_enqueue_scripts', [ $this, 'install' ] );
		$action->add( 'customize_preview_init', [ $this, 'install' ] );
	}

	/**
	 * Parse defined asset properties.
	 *
	 * @param array $args The asset properties.
	 *
	 * @return mixed
	 */
	protected function parse( array $args ) {
		/*
		 * Parse version.
		 */
		$args[ 'version' ] = $this->parseVersion( $args[ 'version' ] );

		/*
		 * Parse mixed.
		 */
		$args[ 'mixed' ] = $this->parseMixed( $args[ 'mixed' ] );

		return $args;
	}

	/**
	 * Parse the version number.
	 *
	 * @param string|bool|null $version
	 *
	 * @return mixed
	 */
	protected function parseVersion( $version ) {
		if ( is_string( $version ) ) {
			if ( empty( $version ) ) {
				// Passing empty string is equivalent to set it to null.
				return;
			}

			// Return the defined string version.
			return $version;
		} elseif ( is_null( $version ) ) {
			// Return null.
			return;
		}

		// Version can only be a string or null. If anything else, return false.
		return false;
	}

	/**
	 * Parse the mixed argument.
	 *
	 * @param $mixed
	 *
	 * @return string|bool
	 */
	protected function parseMixed( $mixed ) {
		if ( 'style' === $this->type ) {
			$mixed = ( is_string( $mixed ) && ! empty( $mixed ) ) ? $mixed : 'all';
		} elseif ( 'script' === $this->type ) {
			$mixed = is_bool( $mixed ) ? $mixed : false;
		}

		return $mixed;
	}

	/**
	 * Register asset instances.
	 */
	protected function registerInstance() {
		if ( isset( static::$instances[ $this->area ][ $this->key ] ) ) {
			return;
		}

		static::$instances[ $this->area ][ $this->key ] = $this;
	}

	/**
	 * Allow the developer to define where to load the asset.
	 * Only 'admin', 'login' and 'customizer' are accepted. If none of those
	 * values are used, simply keep the default front-end area.
	 *
	 * @param string $area Specify where to load the asset: 'admin', 'login' or 'customizer'.
	 *
	 * @return Asset
	 */
	public function to( $area ) {
		if ( is_string( $area ) && in_array( $area, $this->allowedAreas ) ) {
			$this->area = $area;
			$this->orderInstances();
		}

		return $this;
	}

	/**
	 * 链接资源的本地化数据，输出 JS 对象
	 *
	 * @param string $objectName The name of the JS variable that will hold the data.
	 * @param mixed  $data       Any data to attach to the JS variable: string, boolean, object, array, ...
	 *
	 * @return Asset
	 */
	public function localize( $objectName, $data ) {
		if ( 'script' === $this->type ) {
			$this->args[ 'localize' ][ $objectName ] = $data;
		}

		return $this;
	}

	/**
	 * 移除资源
	 *
	 * @return Asset
	 */
	public function remove() {
		if ( $this->isQueued() ) {
			unset( static::$instances[ $this->area ][ $this->key ] );
		}

		return $this;
	}

	/**
	 * 判断资源是否已经入队
	 *
	 * @return bool
	 */
	public function isQueued() {
		if ( isset( static::$instances[ $this->area ][ $this->key ] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * 在已加载的资源前面或后面添加行内代码
	 * Default to "after".
	 *
	 * @param string $data     The inline code to output.
	 * @param string $position Accepts "after" or "before" as values. Note that position is only working for JS assets.
	 *
	 * @return Asset
	 */
	public function inline( $data, $position = 'after' ) {
		if ( 'script' === $this->type ) {
			$args = [
				'data'     => $data,
				'position' => $position,
			];
		} elseif ( 'style' === $this->type ) {
			$args = [
				'data' => $data,
			];
		}

		$this->args[ 'inline' ][] = $args;

		return $this;
	}

	/**
	 * Add attributes to the asset opening tag.
	 *
	 * @param array $atts The asset attributes to add.
	 *
	 * @return Asset
	 */
	public function addAttributes( array $atts ) {
		$html = $this->html;
		$key  = $this->key;

		$replace = function ( $tag, $atts, $append ) use ( $html ) {
			if ( false !== $pos = strrpos( $tag, $append ) ) {
				$tag = substr_replace( $tag, $html->attributes( $atts ), $pos ) . ' ' . trim( $append );
			}

			return $tag;
		};

		if ( 'script' === $this->type ) {
			$append = "></script>\n";
			$this->filter->add( 'script_loader_tag', function ( $tag, $handle ) use ( $atts, $append, $replace, $key ) {
				// Check we're only filtering the current asset and not all.
				if ( $key === $handle ) {
					return $replace( $tag, $atts, $append );
				}

				return $tag;
			} );
		}

		if ( 'style' === $this->type ) {
			$append = " />\n";
			$this->filter->add( 'style_loader_tag', function ( $tag, $handle ) use ( $atts, $append, $replace, $key ) {
				// Check we're only filtering the current asset and not all.
				if ( $key === $handle ) {
					return $replace( $tag, $atts, $append );
				}

				return $tag;
			}, 4 );
		}

		return $this;
	}

	/**
	 * Manipulate the static::$instances variable
	 * in order to separate each asset in its area.
	 */
	protected function orderInstances() {
		if ( array_key_exists( $this->key, static::$instances[ 'front' ] ) ) {
			unset( static::$instances[ 'front' ][ $this->key ] );
			static::$instances[ $this->area ][ $this->key ] = $this;
		}
	}

	/**
	 * Install the appropriate asset depending of its area.
	 */
	public function install() {
		$from = current_filter();

		switch ( $from ) {
			// 前端资源
			case 'wp_enqueue_scripts':

				if ( isset( static::$instances[ 'front' ] ) && ! empty( static::$instances[ 'front' ] ) ) {
					foreach ( static::$instances[ 'front' ] as $asset ) {
						// Check if asset has not yet been called...
						if ( isset( static::$instantiated[ 'front' ][ $asset->getKey() ] ) ) {
							return;
						}

						$this->register( $asset );
					}
				}

				break;

			// 管理后端资源
			case 'admin_enqueue_scripts':

				if ( isset( static::$instances[ 'admin' ] ) && ! empty( static::$instances[ 'admin' ] ) ) {
					foreach ( static::$instances[ 'admin' ] as $asset ) {
						// Check if asset has not yet been called...
						if ( isset( static::$instantiated[ 'admin' ][ $asset->getKey() ] ) ) {
							return;
						}

						$this->register( $asset );
					}
				}

				break;

			// 登录资源
			case 'login_enqueue_scripts':

				if ( isset( static::$instances[ 'login' ] ) && ! empty( static::$instances[ 'login' ] ) ) {
					foreach ( static::$instances[ 'login' ] as $asset ) {
						// Check if asset has not yet been called...
						if ( isset( static::$instantiated[ 'login' ][ $asset->getKey() ] ) ) {
							return;
						}

						$this->register( $asset );
					}
				}

				break;

			case 'customize_preview_init':

				if ( isset( static::$instances[ 'customizer' ] ) && ! empty( static::$instances[ 'customizer' ] ) ) {
					foreach ( static::$instances[ 'customizer' ] as $asset ) {
						// Check if asset has not yet been called...
						if ( isset( static::$instantiated[ 'customizer' ][ $asset->getKey() ] ) ) {
							return;
						}

						$this->register( $asset );
					}
				}

				break;
		}
	}

	/**
	 * 注册资源
	 *
	 * @param Asset $asset
	 */
	protected function register( Asset $asset ) {
		// Avoid duplicate calls to each instance.
		if ( $this->getArea() !== $asset->getArea() ) {
			return;
		}

		// 注册资源
		if ( $asset->getType() === 'script' ) {
			$this->registerScript( $asset );
		} else {
			$this->registerStyle( $asset );
		}

		// Add asset to list of called instances.
		static::$instantiated[ $this->getArea() ][ $this->getKey() ] = $this;
	}

	/**
	 * 注册 'script' 资源
	 *
	 * @param Asset $asset
	 */
	protected function registerScript( Asset $asset ) {
		$args = $asset->getArgs();
		wp_enqueue_script( $args[ 'handle' ], $args[ 'path' ], $args[ 'deps' ], $args[ 'version' ], $args[ 'mixed' ] );

		// Add localized data for scripts.
		if ( isset( $args[ 'localize' ] ) && ! empty( $args[ 'localize' ] ) ) {
			foreach ( $args[ 'localize' ] as $objectName => $data ) {
				wp_localize_script( $args[ 'handle' ], $objectName, $data );
			}
		}

		// Pass the asset instance and register inline code.
		$this->registerInline( $asset );
	}

	/**
	 * 注册 'style' 资源
	 *
	 * @param Asset $asset
	 */
	protected function registerStyle( Asset $asset ) {
		$args = $asset->getArgs();
		wp_enqueue_style( $args[ 'handle' ], $args[ 'path' ], $args[ 'deps' ], $args[ 'version' ], $args[ 'mixed' ] );

		// Pass the asset instance and register inline code.
		$this->registerInline( $asset );
	}

	/**
	 * 注册行内代码
	 *
	 * @param Asset $asset
	 */
	protected function registerInline( Asset $asset ) {
		$args = $asset->getArgs();

		if ( empty( $args ) || ! isset( $args[ 'inline' ] ) ) {
			return;
		}

		// Process if there are inline codes.
		$inlines = $args[ 'inline' ];

		foreach ( $inlines as $inline ) {
			if ( 'script' === $asset->getType() ) {
				wp_add_inline_script( $args[ 'handle' ], $inline[ 'data' ], $inline[ 'position' ] );
			} elseif ( 'style' === $asset->getType() ) {
				wp_add_inline_style( $args[ 'handle' ], $inline[ 'data' ] );
			}
		}
	}

	/**
	 * 返回资源类型
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * 返回资源属性，如果定义了资源键名称，返回对应的值，如果没有设置，返回所有属性
	 *
	 * @param string $name 属性名称
	 *
	 * @return array|string
	 */
	public function getArgs( $name = '' ) {
		if ( ! empty( $name ) && array_key_exists( $name, $this->args ) ) {
			return $this->args[ $name ];
		}

		return $this->args;
	}

	/**
	 * 返回资源区域
	 *
	 * @return string
	 */
	public function getArea() {
		return $this->area;
	}

	/**
	 * 返回资源键名称
	 *
	 * @return string
	 */
	public function getKey() {
		return $this->key;
	}
}
