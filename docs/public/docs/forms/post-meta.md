## 快速添加文章自定义字段

### 添加文章自定义字段的演示代码
这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
add_action( 'after_setup_theme', 'wizhi_cms_post_meta' );
function wizhi_cms_post_meta() {
	$fm = new Fieldmanager_Media( [
		'name' => '_banner_image',
	] );
	$fm->add_meta_box( __( 'Cover image', 'wizhi' ), 'page' );
}
```