<?php

namespace Wizhi\Metaboxs;

use Wizhi\Helper\DataOption;
use Wizhi\Helper\Template;

/**
 * 默认分类项目的数据盒子
 *
 */
class Term {
	public static function init() {

		$column = [
			''   => __( '1 Column', 'wizhi' ),
			'-2' => __( '2 Column', 'wizhi' ),
			'-3' => __( '3 Column', 'wizhi' ),
			'-4' => __( '4 Column', 'wizhi' ),
			'-5' => __( '5 Column', 'wizhi' ),
			'-6' => __( '6 Column', 'wizhi' ),
		];

		$taxonomies = apply_filters( 'wizhi_taxonomy_setting_supports', [ 'category' ] );

		$fm = new \Fieldmanager_Textfield( [
			'name' => '_term_posts_per_page',
		] );
		$fm->add_term_meta_box( __( 'Post per page', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Media( [
			'name' => '_banner_image',
		] );
		$fm->add_term_meta_box( __( 'Cover image', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_RichTextArea( [
			'name' => '_banner_text',
		] );
		$fm->add_term_meta_box( __( 'Banner Text', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Select( [
			'name'    => '_term_template',
			'options' => Template::get_loop( 'wizhi/archive' ),
		] );
		$fm->add_term_meta_box( __( 'Archive template', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Select( [
			'name'    => 'main_tax',
			'options' => DataOption::taxonomies(),
		] );
		$fm->add_term_meta_box( __( 'Main Taxonomy', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Select( [
			'name'    => 'column',
			'options' => $column,
		] );
		$fm->add_term_meta_box( __( 'Column', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Select( [
			'name'    => 'column_small',
			'options' => $column,
		] );
		$fm->add_term_meta_box( __( 'Small Screen Column', 'wizhi' ), $taxonomies );

		$fm = new \Fieldmanager_Select( [
			'name'    => 'per_page',
			'options' => $column,
		] );
		$fm->add_term_meta_box( __( 'Posts Per Page', 'wizhi' ), $taxonomies );


		$fm = new \Fieldmanager_Select( [
			'name'    => '_related_page',
			'options' => DataOption::posts( 'page' ),
		] );
		$fm->add_term_meta_box( __( 'Related Page', 'wizhi' ), $taxonomies );

	}
}