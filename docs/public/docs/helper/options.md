获取在表单选项如、select、multi checkbox、multi radio 中使用的数据，返回的数据是数组的形式，直接传入表单字段的`options` 中即可。

## 获取后台数据

### 获取注册的文章类型

```
wizhi_get_post_types()
```

### 获取文章类型中的文章

```
wizhi_get_post_list( $type = "post", $id = "false" )
```

- $type: 分类方法
- $id：是否在选项名称中显示文章 ID

### 获取自定义分类法列表

```
wizhi_get_taxonomy_list( $type = "taxonomy" )
```

### 获取注册的所有自定义分类法

```
wizhi_get_taxonomies()
```

### 获取某分类法中添加的所有分类项目

```
wizhi_get_term_list($taxonomy = 'post_tag')
```

- $taxonomy：分类法名称

### 获取分类法列表模板

```php
wizhi_get_the_archive_title()
```

### 获取缩略图尺寸列表

```
wizhi_get_image_sizes()
```

## 固定数据

### 获取排序方法选项

```
wizhi_get_display_order()
```

### 获取排序方向选项

```
wizhi_get_display_direction()
```

### 获取颜色选项

```
wizhi_color_option()
```