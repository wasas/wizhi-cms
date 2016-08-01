在页面中显示常用的 UI 元素

## 显示分割线

```php
echo do_shortcode('[divider type="solid"]');
```

*简码参数*

- `type`: 分割线类型

## 显示模块标题

```php
echo do_shortcode('[heading type="background" content="这是二级标题"]');
```

- `type`: 标题类型
- `content`：标题文字内容

## 显示信息框

```php
echo do_shortcode('[alert type="success" content="这是二级标题"]');
```

- `type`: 信息框类型，目前支持信息、警告、提醒、成功四种类型
- `content`：信息框里面的文章

## 显示按钮

```php
echo do_shortcode('[button type="success" size='' text="这是链接" url="http://www.baidu.com"]');
```

- `type`: 按钮类型、显示为不同的颜色，目前支持信息、警告、提醒、成功四种类型
- `size`: 按钮尺寸
- `text`: 按钮文字
- `url`: 按钮链接