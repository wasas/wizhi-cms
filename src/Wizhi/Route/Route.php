<?php

namespace Wizhi\Route;

use Illuminate\Http\Request;
use Illuminate\Routing\Route as IlluminateRoute;
use Illuminate\Support\Arr;
use Wizhi\Route\Matching\ConditionMatching;

class Route extends IlluminateRoute {
	/**
	 * WordPress 模板条件.
	 *
	 * @var string
	 */
	protected $condition;

	/**
	 * WordPress 模板条件的附加参数
	 *
	 * @var array|void
	 */
	protected $conditionalParameters = [];

	/**
	 * 命名自定义路由标签的前缀
	 *
	 * @var string
	 */
	protected $rewrite_tag_prefix = 'wizhi';

	/**
	 * WordPress 条件标签
	 *
	 * @var array
	 */
	protected $conditions = [
		'404'             => 'is_404',
		'archive'         => 'is_archive',
		'attachment'      => 'is_attachment',
		'author'          => 'is_author',
		'category'        => 'is_category',
		'date'            => 'is_date',
		'day'             => 'is_day',
		'front'           => 'is_front_page',
		'home'            => 'is_home',
		'month'           => 'is_month',
		'page'            => 'is_page',
		'paged'           => 'is_paged',
		'postTypeArchive' => 'is_post_type_archive',
		'search'          => 'is_search',
		'subpage'         => 'wizhi_is_subpage',
		'single'          => 'is_single',
		'sticky'          => 'is_sticky',
		'singular'        => 'is_singular',
		'tag'             => 'is_tag',
		'tax'             => 'is_tax',
		'template'        => 'is_page_template',
		'time'            => 'is_time',
		'year'            => 'is_year',
	];

	/**
	 * 构建路由实例
	 *
	 * @param array|string $methods
	 * @param string       $uri
	 * @param mixed        $action
	 */
	public function __construct( $methods, $uri, $action ) {
		parent::__construct( $methods, $uri, $action );

		$this->parameters = [];

		/*
		 * 和参数一起解析 WordPress 条件
		 */
		$this->condition             = $this->parseCondition( $uri );
		$this->conditionalParameters = $this->parseConditionalParameters( $action );

		/*
		 * 如果路由不是 WordPress 路由，创建 WordPress 重写规则
		 */
		$this->createRewriteRule();
	}

	/**
	 * Parses the conditional parameter out of the action parameter. This is the parameter
	 * given to WordPress conditional functions later.
	 *
	 * @param array $action The action parameter where the conditional parameters are in
	 *
	 * @return array       An array with the conditional parameters or null
	 */
	protected function parseConditionalParameters( $action ) {
		// Retrieve parameters. Accept only string or array.
		// This help filter the $action parameters as it might also be a Closure.
		$parameters = Arr::first( $action, function ( $value, $key ) {
			return is_string( $value ) || is_array( $value );
		} );

		if ( $this->condition() && ! is_null( $parameters ) ) {
			if ( is_string( $parameters ) && strrpos( $parameters, '@' ) !== false ) {
				/**
				 * In case of a controller value statement, return empty array.
				 */
				return [];
			}

			return is_array( $parameters ) ? $parameters : [ $parameters ];
		}

		return [];
	}

	/**
	 * 返回真实的 WordPress 条件标签
	 *
	 * @param string $uri
	 *
	 * @return string
	 */
	protected function parseCondition( $uri ) {
		// Retrieve all defined WordPress conditions.
		$conditions = $this->getConditions();

		if ( isset( $conditions[ $uri ] ) ) {
			return $conditions[ $uri ];
		}

		return null;
	}

	/**
	 * 获取 WordPress 条件列表R
	 *
	 * @return array
	 */
	protected function getConditions() {
		return apply_filters( 'wizhiRouteConditions', $this->conditions );
	}

	/**
	 * 允许开发者添加 WordPress 条件路由
	 *
	 * @param array|string $conditions
	 */
	public function addConditions( array $conditions ) {
		$this->conditions = $this->conditions + $conditions;
	}

	/**
	 * 获取路由参数键值列表
	 *
	 * @return array
	 */
	public function parameters() {
		if ( $this->condition ) {
			global $post, $wp_query;

			// Pass WordPress globals to closures or controller methods as parameters.
			$parameters = array_merge( $this->parameters, [ 'post' => $post, 'query' => $wp_query ] );

			// When no posts, $post is null.
			// When is null, set the parameter value of $post to false.
			// This avoid missing arguments in methods for routes or controllers.
			if ( is_null( $parameters[ 'post' ] ) ) {
				$parameters[ 'post' ] = false;
			}

			$this->parameters = $parameters;

			return $parameters;
		}

		return parent::parameters();
	}

	/**
	 * 判断路由是否符合指定的请求
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param bool                     $includingMethod
	 *
	 * @return bool
	 */
	public function matches( Request $request, $includingMethod = true ) {
		// If this route uses a WordPress conditional tag
		if ( $this->condition() ) {
			// Loop trough every validator and if the route passes, return true else false.
			foreach ( $this->getWpValidators() as $validator ) {
				return $validator->matches( $this, $request );
			}

			return false;
		}

		// If no WordPress condition is found, use the normal way of getting a route
		$matches = parent::matches( $request, $includingMethod );

		// If we can not find a route using the normal laravel router check if the route which is being checked has the uri "404". If so we return this route as the valid one.
		if ( method_exists( $this, 'getUri' ) ) {
			return ! $matches && $this->getUri() === '404' ? true : $matches;
		}

		return ! $matches && $this->uri() === '404' ? true : $matches;
	}

	/**
	 * 获取普陪的验证规则
	 *
	 * @return array
	 */
	public function getWpValidators() {
		// To match the route, we will use a chain of responsibility pattern with the
		// validator implementations. We will spin through each one making sure it
		// passes and then we will know if the route as a whole matches request.
		return [ new ConditionMatching() ];
	}

	/**
	 * 如果路由不是 WordPress 路由，创建 WordPress 重写规则
	 * 通过使用路由的正则表达式注册重写规则，我们强制W ordPress 不要将 URL 更改为一个 Wordpress 知道的 URL。
	 *
	 */
	public function createRewriteRule() {
		if ( ! $this->condition() ) {
			// 编译路由以获得 Symfony 编译路由
			$this->compileRoute();

			// 获取正则表达式以注册此路由的重写规则
			$regex = $this->getRewriteRuleRegex();

			// 添加重写规则到顶部
			add_action( 'init', function () use ( $regex ) {
				add_rewrite_tag( '%is_' . $this->rewrite_tag_prefix . '_route%', '(\d)' );
				add_rewrite_rule( $regex, 'index.php?is_' . $this->rewrite_tag_prefix . '_route=1', 'top' );
			} );
		}
	}

	/**
	 * 获取 WordPress 条件
	 *
	 * @return string
	 */
	public function condition() {
		return $this->condition;
	}

	/**
	 * 返回条件参数
	 *
	 * @return array
	 */
	public function conditionalParameters() {
		return $this->conditionalParameters;
	}

	/**
	 * Returns the regex to be registered as a rewrite rule to let WordPress know the existence of this route
	 *
	 * @return mixed|string
	 */
	public function getRewriteRuleRegex() {
		// Get the regex of the compiled route
		$routeRegex = $this->getCompiled()->getRegex();
		// Remove the first part (#^/) of the regex because WordPress adds this already by itself
		$routeRegex = preg_replace( '/^\#\^\//', '^', $routeRegex );
		// Remove the last part (#s$) of the regex because WordPress adds this already by itself
		$routeRegex = preg_replace( '/\#[s]$/', '', $routeRegex );

		return $routeRegex;
	}
}
