<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Term.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class Term extends Model {
	protected $table = 'terms';
	protected $primaryKey = 'term_id';
	public $timestamps = false;

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function taxonomy() {
		return $this->hasOne( TermTaxonomy::class, 'term_id' );
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function meta() {
		return $this->hasMany( TermMeta::class, 'term_id' );
	}
}