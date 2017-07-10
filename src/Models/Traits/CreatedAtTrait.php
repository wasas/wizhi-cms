<?php

namespace Wizhi\Models\Traits;

/**
 * CreatedAt 特征
 */
trait CreatedAtTrait
{
    public function setCreatedAt($value)
    {
        $field = static::CREATED_AT;
        $this->{$field} = $value;

        $field .= '_gmt';
        $this->{$field} = $value;

        return parent::setCreatedAt($value);
    }
}
