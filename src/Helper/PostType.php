<?php
/**
 * 快速添加文章类型和分类方法
 */

namespace Wizhi\Helper;

class PostType {

	use \Nette\StaticClass;

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
	 * @usage   Wizhi\Helper\PostType::create( 'prod', '产品', [ 'title', 'editor', 'thumbnail' ], true );
	 */
	public static function create( $slug, $name, $support, $is_publish, $icon = 'dashicons-networking' ) {

		//文章类型的标签
		$labels = [
			'name'               => $name,
			'singular_name'      => $name,
			'add_new'            => __( 'Add New ', 'wizhi' ) . $name,
			'add_new_item'       => __( 'Add New ', 'wizhi' ) . $name,
			'edit_item'          => __( 'Edit ', 'wizhi' ) . $name,
			'new_item'           => __( 'New ', 'wizhi' ) . $name,
			'all_items'          => __( 'All ', 'wizhi' ) . $name,
			'view_item'          => __( 'View ', 'wizhi' ) . $name,
			'search_items'       => __( 'Search ', 'wizhi' ) . $name,
			'not_found'          => sprintf( __( 'Could not find %s', 'wizhi' ), $name ),
			'not_found_in_trash' => sprintf( __( 'Could not find %s in trash', 'wizhi' ), $name ),
			'menu_name'          => $name,
		];

		$labels = apply_filters( 'wizhi_type_labels' . $slug, $labels );

		//注册文章类型需要的参数
		$args = [
			'labels'              => $labels,
			'description'         => '',
			'public'              => $is_publish,
			'exclude_from_search' => ! $is_publish,
			'publicly_queryable'  => $is_publish,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => $icon,
			// 'capability_type'     => [ $slug, Inflector::pluralize( $slug ) ],
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => $support,
			'has_archive'         => $is_publish,
			'rewrite'             => [ 'slug' => $slug ],
			'query_var'           => $is_publish,

		];


		$args   = apply_filters( 'wizhi_type_args' . $slug, $args );

		if ( strlen( $slug ) > 0 ) {
			register_post_type( $slug, $args );
		}

	}

}