## 显示图文文章媒体列表

```php
echo do_shortcode('[media type="post" tax="category" tag="default" num="6" heading="false", pager="0" tmp="list"]');
```

*简码参数*

- `post`: 文章类型
- `tax`: 分类方法
- `tag` : 分类项目别名
- `num` : 需要显示的文章条数
- `heading`: 是否显示模板标题，模块标题形式是带分类项目链接的分类项目名称
- `pager`: 是否显示分页
- `tmp`: 模板名称，默认为插件中的 content-media.php 模板