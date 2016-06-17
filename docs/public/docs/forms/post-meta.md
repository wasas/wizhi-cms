## 快速添加文章自定义字段

### 第一步: 定义表单字段
这里字段定义数组和 form 中的是一样的, 代码就不再复制了。
```php
$fields = [
    ...
]
```

### 文章元数据盒子需要用到的附加数据
```php
$args_post = [
	'post_type' => [ 'post', 'page' ],
	'context'   => 'normal',
	'priority'  => 'high',
];
```

### 最后、直接实例化`WizhiPostMetabox` 类就可以了
```php
new WizhiPostMetabox( 'extra', '文章附加数据', $fields, $args_post );
```