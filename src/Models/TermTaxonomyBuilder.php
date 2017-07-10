<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Builder;


class TermTaxonomyBuilder extends Builder {
	private $slug;

	/**
	 * 添加文章到关系构建器
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function posts() {
		return $this->with( 'posts' );
	}


	/**
	 * 设置分类法为分类目录
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function category() {
		return $this->where( 'taxonomy', 'category' );
	}


	/**
	 * 设置分类法为 to nav_menu.
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function menu() {
		return $this->where( 'taxonomy', 'nav_menu' );
	}


	/**
	 * 通过指定的别名获取分类法项目
	 *
	 * @param string $slug
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function slug( $slug = null ) {
		if ( ! is_null( $slug ) and ! empty( $slug ) ) {
			// set this slug to be used in with callback
			$this->slug = $slug;

			// exception to filter on specific slug
			$exception = function ( $query ) {
				$query->where( 'slug', '=', $this->slug );
			};

			// load term to filter
			return $this->whereHas( 'term', $exception );
		}

		return $this;
	}

}