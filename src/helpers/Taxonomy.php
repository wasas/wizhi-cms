<?php
/**
 * 快速添加文章类型和分类方法
 */

namespace Wizhi\Helper;

class Taxonomy {

	use \Nette\StaticClass;

	/**
	 * 快速添加分类方法
	 *
	 * @since   wizhi 1.0
	 *
	 * @param string  $tax_slug     分类法名称
	 * @param string  $post_type    关联到的文章类型的名称
	 * @param string  $tax_name     分类法菜单名称
	 * @param boolean $hierarchical 是否允许有父级分类
	 *
	 * @package backend
	 *
	 * @usage   Wizhi\Helper\Taxonomy::create('prodcat', 'prod', '产品分类', true);
	 */
	public static function create( $tax_slug, $post_type, $tax_name, $hierarchical = true ) {

		//分类法的标签
		$labels = [
			'name'                       => $tax_name,
			'singular_name'              => $tax_name,
			'menu_name'                  => $tax_name,
			'all_items'                  => __( 'All ', 'wizhi' ) . $tax_name,
			'edit_item'                  => __( 'Edit ', 'wizhi' ) . $tax_name,
			'view_item'                  => __( 'View ', 'wizhi' ) . $tax_name,
			'update_item'                => __( 'Upgrade ', 'wizhi' ) . $tax_name,
			'add_new_item'               => __( 'Add New ', 'wizhi' ) . $tax_name,
			'new_item_name'              => sprintf( __( 'New %s', 'wizhi' ), $tax_name ),
			'parent_item'                => __( 'Parent ', 'wizhi' ) . $tax_name,
			'parent_item_colon'          => __( 'Parent ', 'wizhi' ) . $tax_name,
			'search_items'               => __( 'Search ', 'wizhi' ) . $tax_name,
			'popular_items'              => __( 'Popular ', 'wizhi' ) . $tax_name,
			'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'wizhi' ), $tax_name ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'wizhi' ), $tax_name ),
			'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'wizhi' ), $tax_name ),
			'not_found'                  => sprintf( __( 'No %s found.', 'wizhi' ), $tax_name ),
		];

		//分类法参数
		$args = [
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => $hierarchical,
			'rewrite'           => [ 'slug' => $tax_slug ],
			'sort'              => true,
		];

		if ( strlen( $tax_slug ) > 0 ) {
			register_taxonomy( $tax_slug, [ $post_type ], $args );
		}

	}

}