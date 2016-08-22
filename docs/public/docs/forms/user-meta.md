## 快速添加用户字段

用户的数据模型比较简单, 直接传入表单数组到`WizhiPostMetabox` 就可以了。

### 第一步: 定义表单字段

这里字段定义数组和 form 中的是一样的, 代码就不再复制了。

```php
$fields = [
    ...
]
```

### 第二步: 然后直接实例化`WizhiPostMetabox` 

```php
if ( class_exists( 'WizhiUserMetabox' ) ) {
	new WizhiUserMetabox( $fields );
}
```

