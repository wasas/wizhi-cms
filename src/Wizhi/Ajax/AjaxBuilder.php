<?php

namespace Wizhi\Ajax;

use Wizhi\Hook\IHook;

class AjaxBuilder implements IAjax {

	/**
	 * Action 实例.
	 */
	protected $action;

	public function __construct( IHook $action ) {
		$this->action = $action;
	}

	/**
	 *  监听 AJAX API 调用
	 *
	 * @param string          $name     AJAX action 名称.
	 * @param \Closure|string $callback 回调功能名称, 函数或类方法
	 * @param string|bool     $logged   是否登录，true, false 或 'both'
	 *
	 * @return \Wizhi\Ajax\IAjax
	 */
	public function listen( $name, $callback, $logged = 'both' ) {
		// 未登录用户的前端 ajax，设置 $logged 为 false
		if ( $logged === false || $logged === 'no' ) {
			$this->action->add( 'wp_ajax_nopriv_' . $name, $callback );
		}

		// 登录用户的前端或后端 Ajax
		if ( $logged === true || $logged === 'yes' ) {
			$this->action->add( 'wp_ajax_' . $name, $callback );
		}

		// 登录和未登录用户的 Ajax
		if ( $logged === 'both' ) {
			$this->action->add( 'wp_ajax_nopriv_' . $name, $callback );
			$this->action->add( 'wp_ajax_' . $name, $callback );
		}

		return $this;
	}

	/**
	 * 向后兼容
	 *
	 * @deprecated
	 *
	 * @param $name
	 * @param $logged
	 * @param $callback
	 *
	 * @return IAjax
	 */
	public function run( $name, $logged, $callback ) {
		return $this->listen( $name, $callback, $logged );
	}
}
