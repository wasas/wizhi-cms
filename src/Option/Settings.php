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
			fm_register_submenu_page( 'wizhi_cms_settings', 'themes.php', __( 'Option', 'wizhi' ) );
		}

		add_action( 'fm_submenu_wizhi_cms_settings', function () {

			$post_types = wizhi_post_types();

			$fields = [
				'general' => new \Fieldmanager_Group( [
					'serialize_data' => false,
					'add_to_prefix'  => false,
					'label'          => __( 'General', 'wizhi' ),
					'children'       => [
						"enabled_post_types" => new \Fieldmanager_Checkboxes( __( 'Enabled content types', 'wizhi' ), [ 'options' => $post_types ] ),
						"mobile_theme"       => new \Fieldmanager_Select( __( 'Mobile Theme', 'wizhi' ), [ 'options' => DataOption::themes() ] ),
						"is_cleanup"         => new \Fieldmanager_Checkbox( __( 'Clean up useless menus for normal user', 'wizhi' ) ),
						"deny_modify"        => new \Fieldmanager_Checkbox( __( 'Deny modify files in backend', 'wizhi' ) ),
					],
				] ),
				'header'  => new \Fieldmanager_Group( [
					'label'          => __( 'Header', 'wizhi' ),
					'serialize_data' => false,
					'add_to_prefix'  => false,
					'children'       => [
						'logo_image'        => new \Fieldmanager_Media( __( 'Logo image', 'wizhi' ) ),
						'logo_area_height'  => new \Fieldmanager_TextField( __( 'Logo area height', 'wizhi' ) ),
						'logo_image_height' => new \Fieldmanager_TextField( __( 'Logo image height', 'wizhi' ) ),
						'code_before_head'  => new \Fieldmanager_TextArea( __( 'Code before </head>', 'wizhi' ) ),
					],
				] ),
				'footer'  => new \Fieldmanager_Group( [
					'label'          => __( 'Footer', 'wizhi' ),
					'serialize_data' => false,
					'add_to_prefix'  => false,
					'children'       => [
						'logo_image'       => new \Fieldmanager_Media( __( 'Footer Logo', 'wizhi' ) ),
						'copyright_text'   => new \Fieldmanager_TextField( __( 'Copyright text', 'wizhi' ) ),
						'beian_no'         => new \Fieldmanager_TextField( __( 'Beian Number', 'wizhi' ) ),
						'code_before_body' => new \Fieldmanager_TextArea( __( 'Code before </body>', 'wizhi' ) ),
					],
				] ),
				'payment' => new \Fieldmanager_Group( [
					'label'          => __( 'Payment', 'wizhi' ),
					'serialize_data' => false,
					'add_to_prefix'  => false,
					'children'       => [
						'alipay_partner'     => new \Fieldmanager_TextField( __( 'Alipay Partner', 'wizhi' ) ),
						'alipay_key'         => new \Fieldmanager_TextField( __( 'Alipay Key', 'wizhi' ) ),
						'alipay_email'       => new \Fieldmanager_TextField( __( 'Alipay Email', 'wizhi' ) ),
						'wechat_app_id'      => new \Fieldmanager_TextField( __( 'Wechat APP ID', 'wizhi' ) ),
						'wechat_api_key'     => new \Fieldmanager_TextField( __( 'Wechat API Key', 'wizhi' ) ),
						'wechat_mch_id'      => new \Fieldmanager_TextField( __( 'Wechat Mch ID', 'wizhi' ) ),
						'wechat_payment_key' => new \Fieldmanager_TextField( __( 'Wechat Payment Key', 'wizhi' ) ),
						"wechat_auto_login"  => new \Fieldmanager_Checkbox( __( 'If open in wechat, auto login', 'wizhi' ) ),
					],
				] ),
			];

			$fm = new \Fieldmanager_Group( [
				'name'           => 'wizhi_cms_settings',
				'tabbed'         => 'vertical',
				'serialize_data' => false,
				'add_to_prefix'  => false,
				'children'       => apply_filters( 'wizhi_option_fields', $fields ),
			] );

			$fm->activate_submenu_page();

		} );

	}
}