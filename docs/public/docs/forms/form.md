## WizhiFormBuilder 类

这是一个比较底层的类, 用来为文章、用户、分类项目等自定义字段提供支持, 当然也可以直接使用这个类构建自定表单。

WizhiFormBuilder 支持4个参数

- $form_type: 字符串、表单类型, 可能的值为: post_meta、option、term_meta、user_meta
- $fields: 数组, 具体参考下方的示例
- $id: 如果表单类型是post_meta、term_meta 或 user_meta, 需要传入一个数据 ID 才能正确保存和读取数据, 根据数据类型的不同, 这个 ID 也不同
- $args: 数组, 附加参数, 构建各种不同的表单所需的附加参数, 具体在各种表单构建类中介绍


## `$fields` 表单数组示例

插件还在积极开发中, 目前没有太多时间写详细的文档, 直接用一个数组演示各种表单类型的添加方法。

```php
$fields = [
    [
        'type'   => 'container',
        'name'   => 'urlstext',
        'label'  => '表单组',
        'size'   => '80',
        'fields' => [
            [
                'type'        => 'text',
                'name'        => 'first_name',
                'label'       => '名字',
                'size'        => '80',
                'placeholder' => '请输入名字',
            ],
            [
                'type'        => 'text',
                'name'        => 'second_name',
                'label'       => '姓氏',
                'size'        => '80',
                'placeholder' => '请输入姓氏',
            ],
        ],
    ],
    [
        'type'        => 'text',
        'name'        => 'prod_name',
        'label'       => '商品名称',
        'size'        => '80',
        'desc'        => '输入商品的名称,可以适当的加上属性',
        'placeholder' => '商品名称',
    ],
    [
        'type'        => 'email',
        'name'        => 'email',
        'label'       => '联系邮件',
        'size'        => '80',
        'placeholder' => '请输入商品的联系邮件',
    ],
    [
        'type'        => 'url',
        'name'        => 'prod_url',
        'label'       => '网址',
        'size'        => '80',
        'placeholder' => 'http://',
    ],
    [
        'type'        => 'number',
        'name'        => 'prod_count',
        'label'       => '库存数量',
        'size'        => '80',
        'placeholder' => '有多少库存',
    ],
    [
        'type'        => 'date',
        'name'        => 'end_date',
        'label'       => '截至日期',
        'size'        => '80',
        'placeholder' => '可以销售到什么时候',
    ],
    [
        'type'        => 'month',
        'name'        => 'prod_month',
        'label'       => '适合月份',
        'size'        => '80',
        'placeholder' => '商品适合在什么月份使用',
    ],
    [
        'type'        => 'week',
        'name'        => 'weeks',
        'label'       => '可用周数',
        'size'        => '80',
        'placeholder' => '商品可以使用多少周',
    ],

    [
        'type'        => 'time',
        'name'        => 'send_time',
        'label'       => '发货时间',
        'size'        => '80',
        'placeholder' => '商品发货时间',
    ],

    [
        'type'        => 'datetime-local',
        'name'        => 'local_datetime',
        'label'       => '本地时间',
        'size'        => '80',
        'placeholder' => '本地时区的时间',
    ],

    [
        'type'        => 'search',
        'name'        => 'search',
        'label'       => '搜索字段',
        'size'        => '80',
        'placeholder' => '可以增加搜索提示',
    ],
    [
        'type'        => 'editor',
        'name'        => 'prod_desc',
        'label'       => '商品简介',
        'size'        => '80',
        'placeholder' => '商品的简单介绍, 不要输入太多',
        'attr'        => [
            'rows' => 5,
            'cols' => 50,
        ],
    ],
    [
        'type'        => 'color',
        'name'        => 'urls_color',
        'label'       => '商品颜色',
        'size'        => '80',
        'placeholder' => '商品颜色,请直接选择',
    ],
    [
        'type'        => 'editor',
        'name'        => 'prod_detail',
        'label'       => '商品详细信息',
        'size'        => '80',
        'placeholder' => '商品详情, 尽量详细的介绍',
        'attr'        => [
            'rows' => 5,
            'cols' => 50,
        ],
    ],
    [
        'type'        => 'upload',
        'name'        => 'prod_image',
        'label'       => '商品主图',
        'size'        => '80',
        'placeholder' => '商品的主要图片, 尽量清晰, 具有吸引力',
    ],
    [
        'type'        => 'editor',
        'name'        => 'prod_intro',
        'label'       => '使用说明',
        'size'        => '80',
        'placeholder' => '商品使用说明, 语言尽量简洁',
        'attr'        => [
            'rows' => 5,
            'cols' => 50,
        ],
    ],
    [
        'type'    => 'checkbox',
        'name'    => 'is_deliver',
        'label'   => '是否发货',
        'size'    => '80',
    ],
    [
        'type'    => 'multi-checkbox',
        'name'    => 'pay_method',
        'label'   => '支付方式',
        'size'    => '80',
        'options' => [
            '4' => '微信',
            '5' => '支付宝',
        ],
    ],
    [
        'type'    => 'radio',
        'name'    => 'pod',
        'label'   => '是否支持货到付款',
        'size'    => '80',
        'default' => '1',
        'options' => [
            '1' => '支持',
            '2' => '不支持',
        ],
    ],
    [
        'type'    => 'select',
        'name'    => 'content_type',
        'label'   => '文章类型',
        'default' => 'post',
        'options' => wizhi_get_post_types(),
    ],
    [
        'type'    => 'select',
        'name'    => 'thumb_size',
        'label'   => '缩略图大小',
        'default' => 'full',
        'options' => wizhi_get_image_sizes(),
    ],
];
```