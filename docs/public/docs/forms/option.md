## 快速添加设置选项


### 添加设置选项页面的演示代码

```php
add_action( 'init', 'cms_settings_page' );

function cms_settings_page() {

    // 添加自定义子菜单
	if ( function_exists( 'fm_register_submenu_page' ) ) {
		fm_register_submenu_page( 'wizhi_cms_settings', 'options-general.php', __( 'CMS Settings', 'wizhi' ) );
	}

    // 自定义菜单视图
	add_action( 'fm_submenu_wizhi_cms_settings', function () {

		$post_types = wizhi_post_types();

		$fm = new Fieldmanager_Group( [
			'name'     => 'wizhi_cms_settings',
			'children' => [
				"enabled_post_types" => new Fieldmanager_Checkboxes( __( 'Enabled content types', 'wizhi' ), [ 'options' => $post_types ] ),
				"is_enable_css"      => new Fieldmanager_Checkbox( __( 'Use build-in CSS', 'wizhi' ) ),
				"is_enable_js"       => new Fieldmanager_Checkbox( __( 'Use build-in Javascript', 'wizhi' ) ),
				"is_enable_font"     => new Fieldmanager_Checkbox( __( 'Load build-in FontAwesome icons', 'wizhi' ) ),
				"is_enable_builder"  => new Fieldmanager_Checkbox( __( 'Enable Shortcode UI', 'wizhi' ) ),
			],
		] );

		$fm->activate_submenu_page();

	} );

}
```

注意上面的 **"wizhi_cms_settings"**  字符，修改的时候，要保持一致，否则设置选项页面注册不了。