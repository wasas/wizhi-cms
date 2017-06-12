<?php
/**
 * 快速添加文章类型和分类方法
 */

use PostTypes\PostType;

if ( ! function_exists( "wizhi_create_types" ) ) {
	/**
	 * 快速添加文章类型
	 *
	 * @since   wizhi 1.0
	 *
	 * @param string  $slug       文章类型名称
	 * @param string  $name       文章类型菜单名称
	 * @param array   $support    文章类型支持的功能
	 * @param boolean $is_publish 文章类型是否在前后台可见
	 * @param string  $icon       后台使用的 dashicon 图标
	 *
	 * @package backend
	 *
	 * @usage   wizhi_create_types( 'prod', '产品', [ 'title', 'editor', 'thumbnail' ], true );
	 *
	 * @return \PostTypes\PostType mixed 创建的文件类型对象
	 */
	function wizhi_create_types( $slug, $name, $support, $is_publish, $icon = 'dashicons-networking' ) {

		$type = '';

		$names = [
			'name'     => $slug,
			'singular' => $name,
			'plural'   => $name,
			'slug'     => $slug,
		];

		//文章类型的标签
		$labels = [
			'name'               => sprintf( __( '%s', 'wizhi' ), $name ),
			'singular_name'      => sprintf( __( '%s', 'wizhi' ), $name ),
			'menu_name'          => sprintf( __( '%s', 'wizhi' ), $name ),
			'all_items'          => sprintf( __( '%s', 'wizhi' ), $name ),
			'add_new'            => sprintf( __( 'Add New %s', 'wizhi' ), $name ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'wizhi' ), $name ),
			'edit_item'          => sprintf( __( 'Edit %s', 'wizhi' ), $name ),
			'new_item'           => sprintf( __( 'New %s', 'wizhi' ), $name ),
			'view_item'          => sprintf( __( 'View %s', 'wizhi' ), $name ),
			'search_items'       => sprintf( __( 'Search %s', 'wizhi' ), $name ),
			'not_found'          => sprintf( __( 'No %s found', 'wizhi' ), $name ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'wizhi' ), $name ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'wizhi' ), $name ),
		];

		//注册文章类型需要的参数
		$options = [
			'labels'             => $labels,
			'public'             => $is_publish,
			'publicly_queryable' => $is_publish,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => $is_publish,
			'rewrite'            => [ 'slug' => $slug ],
			'capability_type'    => 'post',
			'has_archive'        => $is_publish,
			'hierarchical'       => false,
			'supports'           => $support,
			'menu_position'      => 5,
			'menu_icon'          => $icon,
		];

		if ( strlen( $slug ) > 0 ) {
			$type = new PostType( $names, $options, $labels );
		}

		return $type;

	}
}


if ( ! function_exists( "wizhi_create_taxs" ) ) {
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
	 * @usage   wizhi_create_taxs('prodcat', 'prod', '产品分类', true);
	 *
	 * @return \PostTypes\PostType mixed 创建的文件类型对象
	 */
	function wizhi_create_taxs( $tax_slug, $post_type, $tax_name, $hierarchical = true ) {

		$type = '';

		$names = [
			'name'     => $tax_slug,
			'singular' => $tax_name,
			'plural'   => $tax_name,
			'slug'     => $tax_slug,
		];

		//分类法的标签
		$labels = [
			'name'                       => sprintf( __( '%s', 'wizhi' ), $tax_name ),
			'singular_name'              => sprintf( __( '%s', 'wizhi' ), $tax_name ),
			'menu_name'                  => sprintf( __( '%s', 'wizhi' ), $tax_name ),
			'all_items'                  => sprintf( __( 'All %s', 'wizhi' ), $tax_name ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'wizhi' ), $tax_name ),
			'view_item'                  => sprintf( __( 'View %s', 'wizhi' ), $tax_name ),
			'update_item'                => sprintf( __( 'Update %s', 'wizhi' ), $tax_name ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'wizhi' ), $tax_name ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'wizhi' ), $tax_name ),
			'parent_item'                => sprintf( __( 'Parent %s', 'wizhi' ), $tax_name ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'wizhi' ), $tax_name ),
			'search_items'               => sprintf( __( 'Search %s', 'wizhi' ), $tax_name ),
			'popular_items'              => sprintf( __( 'Popular %s', 'wizhi' ), $tax_name ),
			'separate_items_with_commas' => sprintf( __( 'Seperate %s with commas', 'wizhi' ), $tax_name ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'wizhi' ), $tax_name ),
			'choose_from_most_used'      => sprintf( __( 'Choose from most used %s', 'wizhi' ), $tax_name ),
			'not_found'                  => sprintf( __( 'No %s found', 'wizhi' ), $tax_name ),
		];

		//分类法参数
		$options = [
			'hierarchical'      => $hierarchical,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => $tax_slug ],
			'sort'              => true,
		];

		if ( strlen( $tax_slug ) > 0 ) {
			$type = new PostType( $post_type );
			$type->taxonomy( $names, $options );
		}

		return $type;
	}
}