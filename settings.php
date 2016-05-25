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
			'type'    => 'checkbox',
			'name'    => 'wizhi_use_cms_front',
			'scope'   => 'option',
			'label'   => '使用插件前端资源',
			'size'    => '80',
		],

	];

	// 显示表单
	$form = new WizhiFormBuilder( 'option', $fields );
	$form->init();

	echo '</div>';

}