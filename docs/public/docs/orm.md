## 使用 RedBean PHP ORM 操作自定义数据表

### 注意事项

- 只有自定义数据表才使用 ORM 操作，WordPress 默认的数据表尽量使用官方的函数存取
- 注意字段类型，如果数据表字段类型不匹配，保存数据会失败

## 使用方法

先新建一个常亮，把数据表名称放进去，如：

```php
define( 'BOOK', 'wp_messages' );

$book = R::model( BOOK );
```

`$books` 就是我们新建的模型了，我们可以使用这个模型，进行数据的 CRUD操作。

### 保存数据

```php
// 定义数据
$books->name = '射雕英雄传';
$books->author = '金庸';

// 存储数据
$id = R::store( $post );
```

### 获取数据

```php
// 根据 ID 获取数据
$post = R::load( BOOK, $id ); 

// 根据某个条件获取一条数据
$book = R::findOne( BOOK, ' author = ? ', [ '金庸' ] );

// 获取所有数据
$books = R::find( 'book', ' author = ? ', [ '金庸' ] );

// 多个条件
$books = R::find( 'book', ' author = ? AND name = ?', [ '金庸', '射雕英雄传' ] );

// 删除数据
R::trash( $book );
```