<?php

namespace Wizhi\Route\Matching;

use Wizhi\Foundation\Request;
use Wizhi\Route\Route;

interface IMatching {
	/**
	 * 使用指定的规则验证路由或请求
	 *
	 * @param \Wizhi\Route\Route        $route
	 * @param \Wizhi\Foundation\Request $request
	 *
	 * @return bool
	 */
	public function matches( Route $route, Request $request );
}
