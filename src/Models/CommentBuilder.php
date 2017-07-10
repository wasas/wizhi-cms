<?php

/**
 * 评论构建器
 */

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Builder;

class CommentBuilder extends Builder {
	/**
	 * 只获取已批准的评论
	 *
	 * @return \Wizhi\Models\CommentBuilder
	 */
	public function approved() {
		return $this->where( 'comment_approved', 1 );
	}
}
