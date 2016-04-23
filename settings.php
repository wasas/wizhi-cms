<?php

if ( ! class_exists( 'Wizhi_CMS_Setting' ) ):

	/**
	 * Wizhi CMS 插件设置
	 *
	 * @package settings
	 *
	 * @author  Amos Lee
	 */
	class Wizhi_CMS_Setting {

		private $settings_api;

		/**
		 * 构造函数
		 */
		function __construct() {
			$this->settings_api = new WeDevs_Settings_API;

			add_action( 'admin_init', [ $this, 'admin_init' ] );
			add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		}

		/**
		 * 初始化
		 */
		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		/**
		 * 管理菜单
		 */
		function admin_menu() {
			add_options_page( 'CMS 设置', 'CMS 设置', 'delete_posts', 'settings_api_test', [ $this, 'plugin_page' ] );
		}

		/**
		 * 设置选项卡
		 *
		 * @return array 设置选项卡数组
		 */
		function get_settings_sections() {
			$sections = [
				[
					'id'    => 'wedevs_basics',
					'title' => __( '常用设置', 'wizhi' ),
				],
				[
					'id'    => 'wedevs_advanced',
					'title' => __( '高级设置', 'wizhi' ),
				],
				[
					'id'    => 'wedevs_others',
					'title' => __( '其他设置', 'wizhi' ),
				],
			];

			return $sections;
		}

		/**
		 * 返回所有插件设置字段
		 *
		 * @return array 设置字段
		 */
		function get_settings_fields() {
			$settings_fields = [
				'wedevs_basics'   => [
					[
						'name'              => 'text_val',
						'label'             => __( 'Text Input', 'wizhi' ),
						'desc'              => __( 'Text input description', 'wizhi' ),
						'type'              => 'text',
						'default'           => 'Title',
						'sanitize_callback' => 'intval',
					],
					[
						'name'              => 'number_input',
						'label'             => __( 'Number Input', 'wizhi' ),
						'desc'              => __( 'Number field with validation callback `intval`', 'wizhi' ),
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'intval',
					],
					[
						'name'  => 'textarea',
						'label' => __( 'Textarea Input', 'wizhi' ),
						'desc'  => __( 'Textarea description', 'wizhi' ),
						'type'  => 'textarea',
					],
					[
						'name'  => 'wizhi_use_cms_front',
						'label' => __( '加载前端文件', 'wizhi' ),
						'desc'  => __( '选中加载插件自带的 CSS 和 JS 文件', 'wizhi' ),
						'type'  => 'checkbox',
					],
					[
						'name'    => 'radio',
						'label'   => __( 'Radio Button', 'wizhi' ),
						'desc'    => __( 'A radio button', 'wizhi' ),
						'type'    => 'radio',
						'options' => [
							'yes' => 'Yes',
							'no'  => 'No',
						],
					],
					[
						'name'    => 'multicheck',
						'label'   => __( 'Multile checkbox', 'wizhi' ),
						'desc'    => __( 'Multi checkbox description', 'wizhi' ),
						'type'    => 'multicheck',
						'options' => [
							'one'   => 'One',
							'two'   => 'Two',
							'three' => 'Three',
							'four'  => 'Four',
						],
					],
					[
						'name'    => 'selectbox',
						'label'   => __( 'A Dropdown', 'wizhi' ),
						'desc'    => __( 'Dropdown description', 'wizhi' ),
						'type'    => 'select',
						'default' => 'no',
						'options' => [
							'yes' => 'Yes',
							'no'  => 'No',
						],
					],
					[
						'name'    => 'password',
						'label'   => __( 'Password', 'wizhi' ),
						'desc'    => __( 'Password description', 'wizhi' ),
						'type'    => 'password',
						'default' => '',
					],
					[
						'name'    => 'file',
						'label'   => __( 'File', 'wizhi' ),
						'desc'    => __( 'File description', 'wizhi' ),
						'type'    => 'file',
						'default' => '',
						'options' => [
							'button_label' => 'Choose Image',
						],
					],
				],
				'wedevs_advanced' => [
					[
						'name'    => 'color',
						'label'   => __( 'Color', 'wizhi' ),
						'desc'    => __( 'Color description', 'wizhi' ),
						'type'    => 'color',
						'default' => '',
					],
					[
						'name'    => 'password',
						'label'   => __( 'Password', 'wizhi' ),
						'desc'    => __( 'Password description', 'wizhi' ),
						'type'    => 'password',
						'default' => '',
					],
					[
						'name'    => 'wysiwyg',
						'label'   => __( 'Advanced Editor', 'wizhi' ),
						'desc'    => __( 'WP_Editor description', 'wizhi' ),
						'type'    => 'wysiwyg',
						'default' => '',
					],
					[
						'name'    => 'multicheck',
						'label'   => __( 'Multile checkbox', 'wizhi' ),
						'desc'    => __( 'Multi checkbox description', 'wizhi' ),
						'type'    => 'multicheck',
						'default' => [ 'one' => 'one', 'four' => 'four' ],
						'options' => [
							'one'   => 'One',
							'two'   => 'Two',
							'three' => 'Three',
							'four'  => 'Four',
						],
					],
					[
						'name'    => 'selectbox',
						'label'   => __( 'A Dropdown', 'wizhi' ),
						'desc'    => __( 'Dropdown description', 'wizhi' ),
						'type'    => 'select',
						'options' => [
							'yes' => 'Yes',
							'no'  => 'No',
						],
					],
					[
						'name'    => 'password',
						'label'   => __( 'Password', 'wizhi' ),
						'desc'    => __( 'Password description', 'wizhi' ),
						'type'    => 'password',
						'default' => '',
					],
					[
						'name'    => 'file',
						'label'   => __( 'File', 'wizhi' ),
						'desc'    => __( 'File description', 'wizhi' ),
						'type'    => 'file',
						'default' => '',
					],
				],
				'wedevs_others'   => [
					[
						'name'    => 'text',
						'label'   => __( 'Text Input', 'wizhi' ),
						'desc'    => __( 'Text input description', 'wizhi' ),
						'type'    => 'text',
						'default' => 'Title',
					],
					[
						'name'  => 'textarea',
						'label' => __( 'Textarea Input', 'wizhi' ),
						'desc'  => __( 'Textarea description', 'wizhi' ),
						'type'  => 'textarea',
					],
					[
						'name'  => 'checkbox',
						'label' => __( 'Checkbox', 'wizhi' ),
						'desc'  => __( 'Checkbox Label', 'wizhi' ),
						'type'  => 'checkbox',
					],
					[
						'name'    => 'radio',
						'label'   => __( 'Radio Button', 'wizhi' ),
						'desc'    => __( 'A radio button', 'wizhi' ),
						'type'    => 'radio',
						'options' => [
							'yes' => 'Yes',
							'no'  => 'No',
						],
					],
					[
						'name'    => 'multicheck',
						'label'   => __( 'Multile checkbox', 'wizhi' ),
						'desc'    => __( 'Multi checkbox description', 'wizhi' ),
						'type'    => 'multicheck',
						'options' => [
							'one'   => 'One',
							'two'   => 'Two',
							'three' => 'Three',
							'four'  => 'Four',
						],
					],
					[
						'name'    => 'selectbox',
						'label'   => __( 'A Dropdown', 'wizhi' ),
						'desc'    => __( 'Dropdown description', 'wizhi' ),
						'type'    => 'select',
						'options' => [
							'yes' => 'Yes',
							'no'  => 'No',
						],
					],
					[
						'name'    => 'password',
						'label'   => __( 'Password', 'wizhi' ),
						'desc'    => __( 'Password description', 'wizhi' ),
						'type'    => 'password',
						'default' => '',
					],
					[
						'name'    => 'file',
						'label'   => __( 'File', 'wizhi' ),
						'desc'    => __( 'File description', 'wizhi' ),
						'type'    => 'file',
						'default' => '',
					],
				],
			];

			return $settings_fields;
		}


		/**
		 * 插件页面
		 */
		function plugin_page() {
			echo '<div class="wrap">';

			$this->settings_api->show_navigation();
			$this->settings_api->show_forms();

			echo '</div>';
		}

		/**
		 * 获取所有页面
		 *
		 * @return array 页面“ID->名称”键值对
		 */
		function get_pages() {
			$pages         = get_pages();
			$pages_options = [ ];
			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}

	}

endif;
