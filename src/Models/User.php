<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $primaryKey = 'ID';
	protected $timestamp = false;
	public function meta()
	{
		return $this->hasMany('WeDevs\ORM\WP\UserMeta', 'user_id');
	}
}