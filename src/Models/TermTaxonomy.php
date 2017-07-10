<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;

class TermTaxonomy extends Model {
	protected $table = 'term_taxonomy';
	protected $primaryKey = 'term_taxonomy_id';
	protected $with = [ 'term' ];
	public $timestamps = false;


	/**
	 * 元数据值关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function meta() {
		return $this->hasMany( 'Wizhi\Models\TermMeta', 'term_id' );
	}


	/**
	 * 分类法项目关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function term() {
		return $this->belongsTo( 'Wizhi\Models\Term', 'term_id' );
	}


	/**
	 * 父级分类关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function parentTerm() {
		return $this->belongsTo( 'Wizhi\Models\TermTaxonomy', 'parent' );
	}


	/**
	 * 文章关系
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function posts() {
		return $this->belongsToMany( 'Wizhi\Models\Post', 'term_relationships', 'term_taxonomy_id', 'object_id' );
	}


	/**
	 * 文章别名，使查询 nav_items 更简洁，只有当菜单模型被调用或分类法为 'nav_menu' 时使用
	 *
	 * @return $this
	 */
	public function nav_items() {
		if ( $this->taxonomy == 'nav_menu' ) {
			return $this->posts()->orderBy( 'menu_order' );
		}

		return $this;
	}


	/**
	 * 已自定义方法覆盖 newQuery() 以自定义 TermTaxonomyBuilder
	 *
	 * @param bool $excludeDeleted
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function newQuery( $excludeDeleted = true ) {
		$builder = new TermTaxonomyBuilder( $this->newBaseQueryBuilder() );
		$builder->setModel( $this )->with( $this->with );
		if ( isset( $this->taxonomy ) and ! empty( $this->taxonomy ) and ! is_null( $this->taxonomy ) ) {
			$builder->where( 'taxonomy', $this->taxonomy );
		}

		return $builder;
	}


	/**
	 * 获取分类法元数据的魔术方法，以获取原生数据的方法获取元数据
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	public function __get( $key ) {
		if ( ! isset( $this->$key ) ) {
			if ( isset( $this->term->$key ) ) {
				return $this->term->$key;
			}
		}

		return parent::__get( $key );
	}

}