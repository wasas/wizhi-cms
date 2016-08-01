## 显示文章循环内容

```php
echo do_shortcode('[loop type="post" tax="category" tag="default" num="6" pager="0" tmp="lists"]');
```

*简码参数*

- `post`: 文章类型
- `tax`: 分类方法
- `tag` : 分类项目别名
- `num`: 显示的文章数量
- `pager`: 是否显示分页
- `tmp`: 循环所使用的模板，默认为插件模板中的 content-list.php