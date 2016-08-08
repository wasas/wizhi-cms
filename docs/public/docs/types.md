## 快速添加文章类型和分类法

### 为什么要使用文章类型和分类法

- 每种类型的文章都会有不同的字段，使用文档类型区分开，上传的时候问题比较少。
- 显示子菜单的时候比较方便

### 添加文章类型和分类法的方法

添加之前，首先判断功能时是否存在，以免禁用或未安装插件时出现错误。

其中`pro`是保存在数据库中的文章类型的名称，通过这个字段区分不同的文章类型。‘产品’是显示在后台菜单中的名称。

```php
if ( function_exists ("wizhi_create_types") and function_exists ("wizhi_create_taxs") ) {
        wizhi_create_types( "pro", "产品", array( 'title', 'editor', 'author', 'thumbnail', custom-fields', 'comments' ), true );
        wizhi_create_taxs( "procat", 'pro', "产品分类" );

        wizhi_create_types( "slider", "幻灯", array( 'title', 'thumbnail' ), true );
}
```

把以上代码加入到主题的function.php即可

## 文章类型设置

内置的文章类型均支持文章类型设置，目前支持的设置选项有:

- 封面图像
- 每页显示的文章数量
- 文章类型存档页模板
- 文章类型描述

### 获取文章类型设置

文章类型设置是保存在 WordPress 选项数据表中的，可以用 WordPress 标准的 `get_option` 函数获取设置值，也可以用插件增加的快捷方式：

```php
get_archive_option($type, $name)
```

- $type 字符串，文章类型别名
- $name 字符串，设置选项名称

### 目前各选项的名称

设置选项完整的名称为：$type\_archive\_$name

- 封面图像：banner_image
- 模板：template
- 每页文章数：per_page
- 描述：description