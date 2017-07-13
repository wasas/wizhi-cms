<?php
/**
 * 添加默认的文章类型、添加文章类型存档设置
 *
 */

use Nette\Utils\Arrays;
use Nette\Utils\Strings;

add_action( 'init', 'add_type_options' );
add_action( 'init', 'add_default_content_type' );

/**
 * 为每个文章类型添加存档设置
 *
 * @param $type
 */
function add_type_options( $type ) {

	if ( function_exists( 'fm_register_submenu_page' ) ) {
		fm_register_submenu_page( $type . '_archive_settings', 'edit.php?post_type=' . $type, __( 'Archive Settings', 'wizhi' ) );
	}

	add_action( 'fm_submenu_' . $type . '_archive_settings', function ( $type ) {

		$column = [
			''   => __( '1 Column', 'wizhi' ),
			'-2' => __( '2 Column', 'wizhi' ),
			'-3' => __( '3 Column', 'wizhi' ),
			'-4' => __( '4 Column', 'wizhi' ),
			'-5' => __( '5 Column', 'wizhi' ),
			'-6' => __( '6 Column', 'wizhi' ),
		];

		$fields = [
			"banner"       => new Fieldmanager_Media( __( 'Cover image', 'wizhi' ) ),
			"title"        => new Fieldmanager_TextField( __( 'Archive Title', 'wizhi' ) ),
			"description"  => new Fieldmanager_RichTextArea( __( 'Archive Description', 'wizhi' ) ),
			"template"     => new Fieldmanager_Select( __( 'Archive Template', 'wizhi' ), [
				'options' => wizhi_get_loop_template( 'wizhi/archive' ),
			] ),
			"main_tax"     => new Fieldmanager_Select( __( 'Main Taxonomy', 'wizhi' ), [
				'options' =>  DataOption::taxonomies(),
			] ),
			"column"       => new Fieldmanager_Select( __( 'Column', 'wizhi' ), [
				'options' => $column,
			] ),
			"column_small" => new Fieldmanager_Select( __( 'Small Screen Column', 'wizhi' ), [
				'options' => $column,
			] ),
			"per_page"     => new Fieldmanager_Textfield( __( 'Posts Per Page', 'wizhi' ) ),
		];

		$fm = new Fieldmanager_Group( [
			'name'     => $type,
			'children' => apply_filters( 'wizhi_archive_setting_fields', $fields ),
		] );

		$fm->activate_submenu_page();

	} );

}


/**
 * 添加默认的自定义文章类型和分类法
 */
function add_default_content_type() {

	$types              = wizhi_post_types();
	$icons              = wizhi_post_types_icon();
	$enabled_post_types = get_option( 'wizhi_cms_settings' )[ 'enabled_post_types' ];

	if ( count( $enabled_post_types ) > 0 ) {

		// 添加默认的文章类型和分类方法
		foreach ( $enabled_post_types as $slug ) {

			wizhi_create_types( $slug, Arrays::get( $types, $slug, Strings::capitalize( $slug ) ), [
				'title',
				'editor',
				'thumbnail',
			], true, Arrays::get( $icons, $slug, 'dashicons-admin-post' ) );

			wizhi_create_taxs( $slug . 'cat', $slug, Arrays::get( $types, $slug, Strings::capitalize( $slug ) ) . __( ' Category', 'wizhi' ), true );

		}

		$enabled_post_types = apply_filters( 'wizhi_archive_setting_supports', $enabled_post_types );

		foreach ( $enabled_post_types as $slug ) {
			add_type_options( $slug );
		}

	}

}