<?php

namespace Wizhi\Models;

class TermMeta extends PostMeta {
	protected $table = 'termmeta';
	protected $fillable = [ 'meta_key', 'meta_value', 'term_id' ];

	/**
	 * 文章的分类法项目
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function term() {
		return $this->belongsTo( Term::class );
	}
}
