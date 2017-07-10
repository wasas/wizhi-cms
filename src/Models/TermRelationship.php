<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 分类法项目关系
 */
class TermRelationship extends Model {
	protected $table = 'term_relationships';
	protected $primaryKey = [ 'object_id', 'term_taxonomy_id' ];
	public $timestamps = false;


	/**
	 * 分类法项目中的文章
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function post() {
		return $this->belongsTo( 'Wizhi\Models\Post', 'object_id' );
	}

	/**
	 * 分类法项目所属的分类法
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function taxonomy() {
		return $this->belongsTo( 'Wizhi\Models\TermTaxonomy', 'term_taxonomy_id' );
	}
}