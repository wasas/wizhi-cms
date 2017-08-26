<?php

namespace Wizhi\Option;

use Wizhi\Helper\DataOption;

class Settings {

	/**
	 * 插件功能设置选项
	 * 添加插件设置页面
	 */
	public static function init() {

		if ( function_exists( 'fm_register_submenu_page' ) ) {
			fm_register_submenu_page( 'wizhi_cms_settings', 'themes.php', __( 'Modules', 'wizhi' ) );
		}

		add_action( 'fm_submenu_wizhi_cms_settings', function () {

			$post_types = wizhi_post_types();

			$fields = [
				"enabled_post_types" => new \Fieldmanager_Checkboxes( __( 'Enabled content types', 'wizhi' ), [ 'options' => $post_types ] ),
				"mobile_theme"       => new \Fieldmanager_Select( __( 'Mobile Theme', 'wizhi' ), [ 'options' => DataOption::themes() ] ),
				"is_cleanup"         => new \Fieldmanager_Checkbox( __( 'Clean up useless menus for normal user', 'wizhi' ) ),
				"deny_modify"        => new \Fieldmanager_Checkbox( __( 'Deny modify files in backend', 'wizhi' ) ),
			];

			$fm = new \Fieldmanager_Group( [
				'name'           => 'wizhi_cms_settings',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => apply_filters( 'wizhi_option_fields', $fields ),
			] );

			$fm->activate_submenu_page();

		} );

	}
}