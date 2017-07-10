<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;


class Comment extends Model {
	protected $primaryKey = 'comment_ID';

	/**
	 * 评论和文章的关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function post() {
		return $this->hasOne( 'Wizhi\Models\Post' );
	}
}