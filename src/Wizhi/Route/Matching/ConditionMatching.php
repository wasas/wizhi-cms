<?php

namespace Wizhi\Route\Matching;

use Wizhi\Foundation\Request;
use Wizhi\Route\Route;

class ConditionMatching implements IMatching {
	public function matches( Route $route, Request $request ) {
		// 检查模板是否相关，并和当前路由条件进行比较
		if ( $route->condition() && call_user_func_array( $route->condition(), [ $route->conditionalParameters() ] ) ) {
			return true;
		}

		return false;
	}
}
