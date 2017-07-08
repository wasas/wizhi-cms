<?php

namespace Wizhi\Route;

use Illuminate\Routing\RouteCollection as IlluminateRouteCollection;

class RouteCollection extends IlluminateRouteCollection {
	/**
	 * 添加指定的路由到路由数组
	 *
	 * @param \Wizhi\Route\Route $route
	 */
	protected function addToCollections( $route ) {
		if ( method_exists( $route, 'getUri' ) ) {
			$domainAndUri = $route->domain() . $route->getUri();
		} else {
			$domainAndUri = $route->domain() . $route->uri();
		}

		if ( $route->condition() && $route->conditionalParameters() ) {
			$domainAndUri .= serialize( $route->conditionalParameters() );
		}

		foreach ( $route->methods() as $method ) {
			$this->routes[ $method ][ $domainAndUri ] = $route;
		}

		$this->allRoutes[ $method . $domainAndUri ] = $route;
	}

	/**
	 * 判断数组中的路由是否匹配请求
	 *
	 * @param array                    $routes
	 * @param \Illuminate\http\Request $request
	 * @param bool                     $includingMethod
	 *
	 * @return \Illuminate\Routing\Route|null
	 */
	protected function check( array $routes, $request, $includingMethod = true ) {
		$foundRoute = parent::check( $routes, $request, $includingMethod );

		// 如果没找到路由，检查是否设置了404 路由，如果设置了，返回该路由为我们找到的路由
		if ( ! $foundRoute && isset( $routes[ '404' ] ) ) {
			$foundRoute = $routes[ '404' ];
		}

		return $foundRoute;
	}
}
