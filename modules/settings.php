<?php

/**
 * 插件功能设置选项
 * 添加插件设置页面
 */
add_action( 'init', function () {

	if ( function_exists( 'fm_register_submenu_page' ) ) {
		fm_register_submenu_page( 'wizhi_cms_settings', 'themes.php', __( 'Modules', 'wizhi' ) );
	}

	add_action( 'fm_submenu_wizhi_cms_settings', function () {

		$post_types = wizhi_post_types();

		$fields = [
			"enabled_post_types" => new Fieldmanager_Checkboxes( __( 'Enabled content types', 'wizhi' ), [ 'options' => $post_types ] ),
			"is_enable_css"      => new Fieldmanager_Checkbox( __( 'Use build-in CSS', 'wizhi' ) ),
			"is_enable_js"       => new Fieldmanager_Checkbox( __( 'Use build-in Javascript', 'wizhi' ) ),
			"is_enable_font"     => new Fieldmanager_Checkbox( __( 'Load build-in FontAwesome icons', 'wizhi' ) ),
			"is_cleanup"         => new Fieldmanager_Checkbox( __( 'Clean up useless menus for normal user', 'wizhi' ) ),
			"deny_modify"        => new Fieldmanager_Checkbox( __( 'Deny modify files in backend', 'wizhi' ) ),
		];

		$fm = new Fieldmanager_Group( [
			'name'           => 'wizhi_cms_settings',
			'serialize_data' => false,
			'add_to_prefix'  => false,
			'children'       => apply_filters( 'wizhi_option_fields', $fields ),
		] );

		$fm->activate_submenu_page();

	} );

} );