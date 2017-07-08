<?php

namespace Wizhi\Ajax;

interface IAjax {
	/**
	 * 监听 AJAX API 调用
	 *
	 * @param string          $action   AJAX action 名称
	 * @param \Closure|string $callback 回调功能名称, 函数或类方法
	 * @param string|bool     是否登录，true , false 或 'both'
	 */
	public function listen( $action, $callback, $logged = 'both' );
}
