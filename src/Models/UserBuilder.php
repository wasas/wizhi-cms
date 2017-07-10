<?php

/**
 * 用户构建类
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Builder;

class UserBuilder extends Builder {

	/**
	 * 结果分页
	 *
	 * @param int $perPage
	 * @param int $currentPage
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function paged( $perPage = 10, $currentPage = 1 ) {
		$skip = $currentPage * $perPage - $perPage;

		return $this->skip( $skip )->take( $perPage )->get();
	}

	/**
	 * 向查询添加嵌套元数据条件。
	 *
	 * @param string $metaKey
	 * @param string $metaValue
	 *
	 * @return UserBuilder|static
	 */
	public function hasMeta( $metaKey, $metaValue ) {
		return $this->whereHas( 'meta', function ( $query ) use ( $metaKey, $metaValue ) {
			$query->where( 'meta_key', $metaKey )
			      ->where( 'meta_value', $metaValue );
		} );
	}
}
