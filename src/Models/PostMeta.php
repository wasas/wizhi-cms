<?php

/**
 * 文章元数据
 *
 * @author Junior Grossi <juniorgro@gmail.com>
 */

namespace Wizhi\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model {
	protected $table = 'postmeta';
	protected $primaryKey = 'meta_id';
	public $timestamps = false;
	protected $fillable = [ 'meta_key', 'meta_value', 'post_id' ];

	/**
	 * Post relationship.
	 *
	 * @param bool $ref
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function post( $ref = false ) {
		if ( $ref ) {
			$this->primaryKey = 'meta_value';

			return $this->hasOne( 'Wizhi\Models\Post', 'ID' );
		}

		return $this->belongsTo( 'Wizhi\Models\Post' );
	}

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [ 'value' ];


	/**
	 * Gets the value.
	 * Tries to unserialize the object and returns the value if that doesn't work.
	 *
	 * @return bool|mixed
	 */
	public function getValueAttribute() {
		try {
			$value = unserialize( $this->meta_value );
			// if we get false, but the original value is not false then something has gone wrong.
			// return the meta_value as is instead of unserializing
			// added this to handle cases where unserialize doesn't throw an error that is catchable
			return $value === false && $this->meta_value !== false ? $this->meta_value : $value;
		} catch ( Exception $ex ) {
			return $this->meta_value;
		}
	}


	/**
	 * Taxonomy relationship from the meta_value.
	 *
	 * @param null $primary
	 * @param null $where
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function taxonomy( $primary = null, $where = null ) {
		// possible to exclude a relationship connection
		if ( ! is_null( $primary ) && ! empty( $primary ) ) {
			$this->primaryKey = $primary;
		}

		// load relationship
		$relation = $this->hasOne( 'Wizhi\Models\TermTaxonomy', 'term_taxonomy_id' );

		// do we need to filter which value to look for with meta_value
		// if (!is_null($where) && !empty($where)) {
		//     $relation->where($where, $this->meta_value);
		// }

		return $relation;
	}

	/**
	 * Override newCollection() to return a custom collection.
	 *
	 * @param array $models
	 *
	 * @return \Wizhi\Models\PostMetaCollection
	 */
	public function newCollection( array $models = [] ) {
		return new PostMetaCollection( $models );
	}
}
