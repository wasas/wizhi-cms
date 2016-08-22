## 快速添加分类项目自定义字段

### 第一步: 定义表单字段
这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
$fields = [
    ...
]
```

### 第二步: 定义分类元数据盒子需要的参数
```php
$args_term = [
	'id'         => 'test',
	'title'      => '测试盒子',
	'taxonomies' => [ 'category', 'post_tag', 'product_cat' ],
];
```

### 第三步: 直接实例化`WizhiTermMetabox` 
```php
if ( class_exists( 'WizhiTermMetabox' ) ) {
	new WizhiTermMetabox( $fields, $args_term );
}
```