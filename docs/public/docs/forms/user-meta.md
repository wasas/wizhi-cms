## 快速添加用户字段

### 添加用户自定义自定的演示代码

```php
add_action( 'fm_user', function() {
    $fm = new Fieldmanager_TextField( array(
        'name' => 'demo',
    ) );
    $fm-> add_user_form( 'Basic Field' );
} );
```

