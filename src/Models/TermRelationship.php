<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TermRelationship.
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class TermRelationship extends Model
{
	protected $table = 'term_relationships';
	protected $primaryKey = ['object_id', 'term_taxonomy_id'];
	public $timestamps = false;
	public function post()
	{
		return $this->belongsTo('Wizhi\Models\Post', 'object_id');
	}
	public function taxonomy()
	{
		return $this->belongsTo('Wizhi\Models\TermTaxonomy', 'term_taxonomy_id');
	}
}