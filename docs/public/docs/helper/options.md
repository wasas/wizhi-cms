## 快速添加文章类型和分类法

### 为什么要使用文章类型和分类法

- 每种类型的文章都会有不同的字段，使用文档类型区分开，上传的时候问题比较少。
- 显示子菜单的时候比较方便

### 添加文章类型和分类法的方法

添加之前，首先判断功能时是否存在，以免禁用或未安装插件时出现错误。

其中`pro`是保存在数据库中的文章类型的名称，通过这个字段区分不同的文章类型。‘产品’是显示在后台菜单中的名称。

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

### 获取根分类

```php
wizhi_get_term_root_id()
```

### 获取存档标题

```php
wizhi_get_the_archive_title()
```

### 获取分类法列表模板

```php
wizhi_get_the_archive_title()
```

### 判断文章是否在父级分类中

```
post_is_in_descendant_category($cats, $_post = null)
```

- cats： 父级分类 ID
- $_post ：文章 ID

### 生成订单号

```
order_no()
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