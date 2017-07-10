<?php

namespace Wizhi\Models\Traits;

/**
 * UpdatedAt 特征
 */
trait UpdatedAtTrait {
	public function setUpdatedAt( $value ) {
		$field          = static::UPDATED_AT;
		$this->{$field} = $value;

		$field          .= '_gmt';
		$this->{$field} = $value;

		return parent::setUpdatedAt( $value );
	}
}
