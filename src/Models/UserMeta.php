<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;


class UserMeta extends Model
{
	protected $primaryKey = 'meta_id';
	public $timestamps    = false;

	/**
	 * 获取用户元数据表
	 *
	 * @return string
	 */
	public function getTable()
	{
		return $this->getConnection()->db->prefix . 'usermeta';
	}
}