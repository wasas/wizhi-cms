<?php

/**
 * 用户元数据链接
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Collection;

class UserMetaCollection extends Collection {
	protected $changedKeys = [];

	/**
	 * 获取用户元数据
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		foreach ( $this->items as $item ) {
			if ( $item->meta_key == $key ) {
				return $item->meta_value;
			}
		}
	}

	/**
	 * 设置用户元数据
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

		$item = new UserMeta( [
			'meta_key'   => $key,
			'meta_value' => $value,
		] );

		$this->push( $item );
	}


	/**
	 * 保存用户元数据
	 *
	 * @param $userId
	 */
	public function save( $userId ) {
		$this->each( function ( $item ) use ( $userId ) {
			if ( in_array( $item->meta_key, $this->changedKeys ) ) {
				$item->user_id = $userId;
				$item->save();
			}
		} );
	}
}
