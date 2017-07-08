<?php

namespace Wizhi\Route\Matching;

use Wizhi\Foundation\Request;
use Wizhi\Route\Route;

interface IMatching
{
    /**
     * Validate a given rule against a route and request.
     *
     * @param \Wizhi\Route\Route        $route
     * @param \Wizhi\Foundation\Request $request
     *
     * @return bool
     */
    public function matches(Route $route, Request $request);
}
