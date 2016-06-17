## 快速添加分类项目自定义字段

### 第一步: 定义表单字段
这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
$fields = [
    ...
]
```

### 文章元数据盒子需要用到的附加数据
```php
$args_term = [
	'id'         => 'test',
	'title'      => '测试盒子',
	'taxonomies' => [ 'category', 'post_tag', 'product_cat' ],
];
```

### 最后、直接实例化`WizhiTermMetabox` 类就可以了
```php
new WizhiTermMetabox( $fields, $args_term );
```