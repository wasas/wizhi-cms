<?php

namespace Wizhi\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;


class TermTaxonomyBuilder extends Builder
{
	private $slug;
	/**
	 * Add posts to the relationship builder.
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function posts()
	{
		return $this->with('posts');
	}
	/**
	 * Set taxonomy type to category.
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function category()
	{
		return $this->where('taxonomy', 'category');
	}
	/**
	 * Set taxonomy type to nav_menu.
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function menu()
	{
		return $this->where('taxonomy', 'nav_menu');
	}
	/**
	 * Get a term taxonomy by specific slug.
	 *
	 * @param string slug
	 *
	 * @return \Wizhi\Models\TermTaxonomyBuilder
	 */
	public function slug($slug = null)
	{
		if (!is_null($slug) and !empty($slug)) {
			// set this slug to be used in with callback
			$this->slug = $slug;
			// exception to filter on specific slug
			$exception = function ($query) {
				$query->where('slug', '=', $this->slug);
			};
			// load term to filter
			return $this->whereHas('term', $exception);
		}
		return $this;
	}
}