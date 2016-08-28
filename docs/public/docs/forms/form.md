## Formbuilder

Formbuilder 已改用 WordPress FieldManager 的方案, 请参考文档 [FieldManager](http://fieldmanager.org/docs/)

Formbuilder 的数据结构中，很多类型的数据都是以数组的方式保存的，这种方式可能会在获取和更新数据的时候带来一些不便，不过好处在于可以提高性能。


### 字段组

```php
add_action( 'fm_post_post', function() {
    $fm = new Fieldmanager_Group( array(
        'name' => 'demo-group',
        'children' => array(
            'field-one' => new Fieldmanager_TextField( 'First Field' ),
            'field-two' => new Fieldmanager_TextField( 'Second Field' ),
        ),
    ) );
    $fm->add_meta_box( 'Basic Group', array( 'post' ) );
} );
```

上面的代码中：

- 'name': 自定子字段组的名称，children 以数组的形式保存为自定义字段。
- array('post'): 字段支持的自定义文章类型 

### 带标签的自定义字段盒子

```php
add_action( 'fm_post_post', function() {
    $fm = new Fieldmanager_Group( array(
        'name'           => 'tabbed_meta_fields',
        'tabbed'         => 'vertical',
        'children'       => array(
            'tab-1' => new Fieldmanager_Group( array(
                'label'          => 'First Tab',
                'children'       => array(
                    'text' => new Fieldmanager_Textfield( 'Text Field' ),
                )
            ) ),
            'tab-2' => new Fieldmanager_Group( array(
                'label'          => 'Second Tab',
                'children'       => array(
                    'textarea' => new Fieldmanager_TextArea( 'TextArea' ),
                    'media'    => new Fieldmanager_Media( 'Media File' ),
                )
            ) ),
        )
    ) );
    $fm->add_meta_box( 'Tabbed Group', 'post' );
} );
```