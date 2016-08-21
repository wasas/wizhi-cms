## 快速添加文章自定义字段

### 第一步: 定义表单字段
这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
$fields = [
    ...
]
```

### 第二步: 设置元数据盒子显示参数
```php
$args_post = [
	'post_type' => [ 'post', 'page' ],
	'context'   => 'normal',
	'priority'  => 'high',
];
```

### 第三步: 实例化`WizhiPostMetabox` 类就可以了
```php
new WizhiPostMetabox( 'extra', '文章附加数据', $fields, $args_post );
```
## 使用辅助函数获取文章类型和分类法选项

在 WordPress 中，元素的加载有[先后顺序](https://codex.wordpress.org/Plugin_API/Action_Reference)，在前面的 action 中获取后面 action 中的数据就获取不到的。例如：

直接在 WordPress 主题中使用下面的辅助函数获取分类法项目选项，就会报一个 “分类法无效” 的错误。因为分类法是在`init`中注册的，这个action 运行在主题 function.php 之后。

```php
wizhi_get_term_list('thelocal')
```

这时候，我们挂载这个函数到 `init` 上面。如下：

```php
add_action( 'init', 'show_select' );
function show_select() {

	$fields = [
		[
			'type'  => 'select',
			'name'  => 'cat',
			'label' => '分类目录',
			'size'  => '80',
			'options' => wizhi_get_term_list('category')
		],
		...
	];

	$args = [
		'post_type' => [ 'post' ],
		'context'   => 'normal',
		'priority'  => 'high',
	];

	new WizhiPostMetabox( 'fees-attrs', '运费数据', $fields, $args );
}

```



