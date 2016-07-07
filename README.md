
## 插件功能

Wizhi CMS 是一个帮助我们加快 WordPress 开发速度的 WordPress 开发插件, 目前支持以下功能。

* 通过简码快速调用文章为 UI 模块
* 内置了常用的文章类型和分类方法，同时支持快速添加自己需要的文章类型和分类方法
* 快速添加文章、分类项目、用户资料自定义字段
* 快速添加设置选项页面
* 内置轻量级页面分栏工具（基于 Page Builder Sandwich 插件）
* 内置常用页面 UI 元素的简码 UI，包括按钮、提醒框等等（需要安装并启用 Shortcake 插件）


## 插件使用文档

这主要是一个帮助我们加快 WordPress 开发速度的插件，安装后，在 WordPress 后台没有太多可以直接使用的功能。详细使用方法请参考[Wizhi CMS 插件文档](http://iwillhappy1314.github.io/wizhi-cms/docs// "Wizhi CMS 插件文档")。

### 插件功能快速演示

#### 显示一个图文列表模块

```php
<?php echo do_shortcode('[media type="post" tax="category" tag="default" num="6" heading="0" tmp="media"]'); ?>
```

插件中只包含了一些基本样式，所以看起来有点丑。网站外观样式是由主题负责的，所以请直接通过CSS定制样式。

####  创建自定义文章类型

如需要添加一个文章类型，只需要这样写：

```php
<?php if ( function_exists ("wizhi_create_types")) {
    wizhi_create_types( "pro", "产品", array( 'title', 'editor', 'author', 'thumbnail', 'comments' ), true );
} ?>
```

参数：

* pro：自定义文章类型别名
* 产品：自定义文章类型名称
* array()：自定义文章类型支持的文章字段
* true：是否是公开的自定义文章类型，如果为false，文章类型在前台和后台看不到，不能查询

#### 创建自定义分类法

需要添加一个自定义分类方法，只需要这样写：

```php
<?php if (function_exists ("wizhi_create_taxs") ) {
    wizhi_create_taxs( "procat", 'pro', "产品分类", true);
} ?>
```

参数：

* procat：自定义分类法别名
* pro：自定义分类法关联到的文章类型
* 产品分类：自定义分类法的名称
* true：是否为层级分类，true为类似于分类目录的方法，false为类似于标签的方式


#### 快速添加设置选项页面

```php
$fields = [
	[
		'type'  => 'text',
		'name'  => 'wechat_app_id',
		'size'  => '80',
		'label' => __( '微信应用 ID', 'wizhi' ),
		'desc'  => '微信应用 ID',
	],
];

$args = [
	'title' => __( '主题设置', 'wizhi' ),
	'label' => __( '主题设置', 'wizhi' ),
	'slug'  => 'enter-theme-settings',
];

if ( class_exists( 'WizhiOptionPage' ) ) {
	new WizhiOptionPage( $fields, $args );
}
```

## BUG反馈和功能建议

BUG反馈和功能建议请发送邮件至：iwillhappy1314@gmail.com

作者网址：[WordPress智库](http://www.wpzhiku.com/ "WordPress CMS 插件")
