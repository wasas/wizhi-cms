## 独立于页面的路由系统

有时候，我们需要建立自定义 URL 来实现一些功能，比如 Ajax, 自定义功能页面等。这时候我们并不想把这些功能行的页面添加到 WordPress 后台，以免弄乱 WordPress 后台的内容。Wizhi CMS 集成了WordPress Dispatcher 路由系统来实现自定义 URL 的功能。

## 使用方法

### 基本使用方法

给`Dispatch` 类传入一个数据，数据元素的键就是 URL 名称，值就是访问这个 URL 时执行的函数。

```php
use \TheFold\WordPress\Dispatch;

// 新建一个自定义 URL
new Dispatch( [
   'charge' => function ( $request ) {
   		echo $request;
   }
] );
```

### 使用 URL 参数

URL 支持使用正则表达式匹配参数，如下。

```php
new Dispatch( [
	'charge/([a-z]*)' => function ( $request, $status="all" ) {
		echo $request . $status;
	},
] );
```

### 使用自定义模板

有时候，我们需要使用自定义页面模板来展示内容，这也很简单，直接在回调函数中包含需要使用的模板就可以了，页面的具体内容在模板中定义，URL 中的变量也可以直接在模板中使用。

```php
new Dispatch( [
	'orders/([a-z]*)' => function ( $request, $status="all" ) {
		include( get_template_directory() . '/page-orders.php' );
	},
] );
```