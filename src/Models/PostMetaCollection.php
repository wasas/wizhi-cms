<?php

/**
 * 文章元数据 Collection.
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Collection;

class PostMetaCollection extends Collection {
	protected $changedKeys = [];

	/**
	 * 搜索指定值并返回获取的行
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function getAttribute( $key ) {
		foreach ( $this->items as $item ) {
			if ( $item->meta_key == $key ) {
				return $item->value;
			}
		}
	}

	/**
	 * 获取属性方法的快捷方式，传入对象属性
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function __get( $key ) {
		return $this->getAttribute( $key );
	}

	/**
	 * 设置属性方法的快捷方式，传入对象属性
	 *
	 * @param $key
	 * @param $value
	 */
	public function __set( $key, $value ) {
		$this->changedKeys[] = $key;

		foreach ( $this->items as $item ) {
			if ( $item->meta_key == $key ) {
				$item->meta_value = $value;

				return;
			}
		}

		$item = new PostMeta( [
			'meta_key'   => $key,
			'meta_value' => $value,
		] );

		$this->push( $item );
	}


	/**
	 * 保存元数据值
	 *
	 * @param $postId
	 */
	public function save( $postId ) {
		$this->each( function ( $item ) use ( $postId ) {
			if ( in_array( $item->meta_key, $this->changedKeys ) ) {
				$item->post_id = $postId;
				$item->save();
			}
		} );
	}
}
