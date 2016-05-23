<?php
add_action( 'admin_menu', 'wizhi_cms_settings_menu' );
function wizhi_cms_settings_menu() {
	add_options_page( 'CMS 设置', 'CMS 设置', 'manage_options', 'wizhi-cms-settings.php', 'wizhi_cms_setting_page' );
}

/**
 * 添加设置页面
 */
function wizhi_cms_setting_page() {

	echo '<div class="wrap">';
	echo '<h1>固定链接设置</h1>';

	echo '<p>Wizhi CMS 插件常用设置</p>';

	echo '<h2 class="title">常用设置</h2>';

	$fields = [
		[
			'type'        => 'text',
			'name'        => 'url',
			'scope'       => 'option',
			'label'       => '表单',
			'size'        => '80',
			'default'     => '请输入文本',
			'placeholder' => '输入文本, 明天更美好',
		],
		[
			'type'        => 'textarea',
			'name'        => 'text',
			'scope'       => 'option',
			'label'       => '文本',
			'size'        => '80',
			'default'     => '请输入文本',
			'placeholder' => '输入文本, 明天更美好',
			'attr'        => [
				'rows' => 5,
				'cols' => 50,
			],
		],
		[
			'type'    => 'checkbox',
			'name'    => 'checkbox',
			'scope'   => 'option',
			'label'   => '文本',
			'size'    => '80',
			'options' => [
				'1' => '老大',
				'2' => '老二',
			],
		],
		[
			'type'    => 'radio',
			'name'    => 'radio',
			'scope'   => 'option',
			'label'   => '文本',
			'size'    => '80',
			'options' => [
				'1' => '老大',
				'2' => '老二',
			],
		],
		[
			'type'    => 'select',
			'name'    => 'select',
			'scope'   => 'option',
			'label'   => '文本',
			'options' => [
				'1' => '老大',
				'2' => '老二',
				'3' => '老三',
				'4' => '老四',
			],
		],
		[
			'type'  => 'upload',
			'name'  => 'upload',
			'scope' => 'option',
			'label' => '文本',
		],
		[
			'type'    => 'multi-select',
			'name'    => 'select2',
			'scope'   => 'option',
			'label'   => '文本',
			'size'    => '80',
			'options' => [
				'1' => '老大',
				'2' => '老二',
				'3' => '老三',
				'4' => '老四',
			],
		],
	];

	// 显示表单
	//create_forms( 'option', $args );
	$form = new WizhiFormBuilder( 'option', $fields );
	$form->init();

	echo '</div>';

}