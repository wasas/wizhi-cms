<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * 分类法项目类
 */
class Term extends Model {
	protected $table = 'terms';
	protected $primaryKey = 'term_id';
	public $timestamps = false;

	/**
	 * 分类法
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function taxonomy() {
		return $this->hasOne( TermTaxonomy::class, 'term_id' );
	}

	/**
	 * 分类项目元数据
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function meta() {
		return $this->hasMany( TermMeta::class, 'term_id' );
	}
}