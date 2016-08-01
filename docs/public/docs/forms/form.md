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
		'type'  => 'group',
		'label' => '群组1',
	],
	[
		'type'    => 'container',
		'name'    => 'urlstext',
		'label'   => '表单组',
		'size'    => '80',
		'fields' => [
			[
				'type'        => 'text',
				'name'        => 'uname',
				'label'       => ' 姓名',
				'size'        => '80',
				'placeholder' => '输入文本, 明天更美好',
			],
			[
				'type'        => 'text',
				'name'        => 'email',
				'label'       => '邮件',
				'size'        => '80',
				'placeholder' => '输入文本, 明天更美好',
			],
		],
	],
	[
		'type'        => 'text',
		'name'        => 'urls',
		'label'       => '文本',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'email',
		'name'        => 'urls_email',
		'label'       => '邮件',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'url',
		'name'        => 'urls_url',
		'label'       => '网址',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],[
		'type'        => 'number',
		'name'        => 'urls_number',
		'label'       => '数字',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'date',
		'name'        => 'urls_date',
		'label'       => '日期',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'month',
		'name'        => 'urls_month',
		'label'       => '月份',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'week',
		'name'        => 'urls_week',
		'label'       => '周数',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'time',
		'name'        => 'urls_time',
		'label'       => '时间',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'datetime-local',
		'name'        => 'urls_datetime',
		'label'       => '本地时间日期',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'search',
		'name'        => 'urls_search',
		'label'       => '搜索',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'color',
		'name'        => 'urls_color',
		'label'       => '颜色',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'upload',
		'name'        => 'ups',
		'label'       => '上传文件',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
	],
	[
		'type'        => 'textarea',
		'name'        => 'text',
		'label'       => '文本',
		'size'        => '80',
		'placeholder' => '输入文本, 明天更美好',
		'attr'        => [
			'rows' => 5,
			'cols' => 50,
		],
	],
	[
		'type'  => 'group',
		'label' => '群组2',
	],
	[
		'type'    => 'checkbox',
		'name'    => 'checkbox',
		'label'   => '文本',
		'size'    => '80',
		'options' => [
			'1' => '老大',
			'2' => '老二',
		],
	],
	[
		'type'  => 'group',
		'label' => '群组3',
	],
	[
		'type'    => 'select',
		'name'    => 'pyype',
		'label'   => '文章类型',
		'default' => 'post',
		'options' => wizhi_get_post_types(),
	],
	[
		'type'    => 'select',
		'name'    => 'thmb',
		'label'   => '缩略图大小',
		'default' => 'full',
		'options' => wizhi_get_image_sizes(),
	],
];
```