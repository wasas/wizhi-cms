## 快速添加设置选项


### 第一步: 定义设置选项字段

这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
$fields = [
    ...
]
```

### 第二步: 设置选项页面参数
```php
$args = [
		'parent' => 'options-general.php,
		'slug'   => "theme_settings",
		'label'  => __( '存档设置', 'wizhi' ),
		'title'  => __( '存档设置', 'wizhi' ),
	];
```

- `parent`: 设置选项出现的父级页面
- `slug`: 设置选项的 URL 参数
- `label` : 设置选项的菜单名称
- `title` : 设置选项页面的标题

### 第三步: 实例化设置选项页面

```php
if ( class_exists( 'WizhiOptionPage' ) ) {
	new WizhiOptionPage( $fields, $args );
}
```

把以上代码加入到主题的function.php即可