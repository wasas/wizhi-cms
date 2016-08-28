## 快速添加分类项目自定义字段

### 添加分类法字段的演示代码
```php
add_action( 'after_setup_theme', 'wizhi_cms_term_meta' );
function wizhi_cms_term_meta() {

	$fm = new Fieldmanager_Textfield( [
		'name' => '_term_posts_per_page',
	] );
	$fm->add_term_meta_box( __( 'Post per page', 'wizhi' ), 'category' );

	$fm = new Fieldmanager_Media( [
		'name' => '_banner_image',
	] );
	$fm->add_term_meta_box( __( 'Cover image', 'wizhi' ), 'category' );

	$fm = new Fieldmanager_Select( [
		'name'    => '_term_template',
		'options' => wizhi_get_loop_template( 'wizhi/category' ),
	] );
	$fm->add_term_meta_box( __( 'Archive template', 'wizhi' ), 'category' );
}
```