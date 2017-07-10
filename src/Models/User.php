<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class 类
 *
 * @package Wizhi\Models
 */
class User extends Model
{
	protected $primaryKey = 'ID';
	protected $timestamp = false;
	public function meta()
	{
		return $this->hasMany('Wizhi\Models\UserMeta', 'user_id');
	}
}