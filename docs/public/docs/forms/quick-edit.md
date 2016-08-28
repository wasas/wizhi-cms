## 添加快速编辑自定义字段的演示代码

### 添加用户自定义自定的演示代码

```php
add_action( 'fm_quickedit_post', function() {
    $fm = new Fieldmanager_TextField( array(
        'name' => 'demo',
    ) );
    $fm->add_quickedit_box( 'Basic Field', 'post', function( $values ) {
        return ! empty( $values['demo'] ) ? $values['demo'] : '';
    } );
} );
```

