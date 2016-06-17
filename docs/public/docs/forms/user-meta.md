## 快速添加用户字段

### 为什么要使用文章类型和分类法

- 每种类型的文章都会有不同的字段，使用文档类型区分开，上传的时候问题比较少。
- 显示子菜单的时候比较方便

### 字段类型数组示例

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