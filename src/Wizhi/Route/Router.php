<?php

namespace Wizhi\Route;

use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Router as IlluminateRouter;
use Wizhi\Foundation\Application;

class Router extends IlluminateRouter {
	/**
	 * 构建路由实例
	 *
	 * @param \Illuminate\Events\Dispatcher $events
	 * @param \Wizhi\Foundation\Application $container
	 */
	public function __construct( Dispatcher $events, Application $container ) {
		parent::__construct( $events, $container );
		$this->routes = new RouteCollection();
	}

	/**
	 * 创建新的路由实例
	 *
	 * @param  array|string $methods
	 * @param  string       $uri
	 * @param  mixed        $action
	 *
	 * @return \Illuminate\Routing\Route
	 */
	protected function newRoute( $methods, $uri, $action ) {
		return ( new Route( $methods, $uri, $action ) )
			->setRouter( $this )
			->setContainer( $this->container );
	}

	/**
	 * 找到符合指定请求的路由
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Wizhi\Route\Route
	 */
	protected function findRoute( $request ) {
		$route = parent::findRoute( $request );

		// 如果当前路由是 WordPress 路由
		if ( $route instanceof Route && ! $route->condition() ) {
			global $wp, $wp_query;

			// 检查当前路由是否不为 WordPress 路由，并提醒开发者刷新重定向规则
			if ( $wp->matched_rule != $route->getRewriteRuleRegex() ) {
				/*
				 * 我们不能指望 flush_rewrite_rules() 函数，因为这是个比较耗时的处理，尤其在“每请求” 管理上
				 * 我们修改头状态并设置为 200，以便适当的处理
				 * 我们需要使用 WordPress 头部函数，在每个请求上只返回响应内容
				 */
				status_header( 200, 'OK' );
			}

			// 重设 WordPress 查询
			$wp_query->init();
		}

		return $route;
	}
}
