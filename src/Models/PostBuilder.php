<?php

/**
 * 文章构建器
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Builder;

class PostBuilder extends Builder {
	/**
	 * 只获取处于某种状态的文章
	 *
	 * @param string $postStatus
	 *
	 * @return \Wizhi\Models\PostBuilder
	 */
	public function status( $postStatus ) {
		return $this->where( 'post_status', $postStatus );
	}

	/**
	 * 只获取已发布的文章
	 *
	 * @return \Wizhi\Models\PostBuilder
	 */
	public function published() {
		return $this->status( 'publish' );
	}

	/**
	 * 只获取某个文章类型的文章
	 *
	 * @param string $type
	 *
	 * @return \Wizhi\Models\PostBuilder
	 */
	public function type( $type ) {
		return $this->where( 'post_type', $type );
	}

	/**
	 * 只获取某些文章类型的文章
	 *
	 * @param array $type
	 *
	 * @return \Wizhi\Models\PostBuilder
	 */
	public function typeIn( array $type ) {
		return $this->whereIn( 'post_type', $type );
	}

	/**
	 * 文章的分类方法
	 *
	 * @param string $taxonomy
	 * @param mixed  $terms
	 *
	 * @return Builder|static
	 */
	public function taxonomy( $taxonomy, $terms ) {
		return $this->whereHas( 'taxonomies', function ( $query ) use ( $taxonomy, $terms ) {
			$query->where( 'taxonomy', $taxonomy )->whereHas( 'term', function ( $query ) use ( $terms ) {
				$query->whereIn( 'slug', is_array( $terms ) ? $terms : [ $terms ] );
			} );
		} );
	}

	/**
	 * 只获取指定别名的文章
	 *
	 * @param string $slug
	 *
	 * @return \Wizhi\Models\PostBuilder
	 */
	public function slug( $slug ) {
		return $this->where( 'post_name', $slug );
	}

	/**
	 * 查询结果分页
	 *
	 * @param int $perPage
	 * @param int $currentPage
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function paged( $perPage = 10, $currentPage = 1 ) {
		$skip = $currentPage * $perPage - $perPage;

		return $this->skip( $skip )->take( $perPage )->get();
	}
}
