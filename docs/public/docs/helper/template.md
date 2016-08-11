## 模板辅助函数

### 获取根分类

```php
wizhi_get_term_root_id()
```

### 获取存档标题

```php
wizhi_get_the_archive_title()
```

### 判断文章是否在父级分类中

```php
post_is_in_descendant_category($cats, $_post = null)
```

- cats： 父级分类 ID
- $_post ：文章 ID

### 生成订单号

```php
order_no()
```

### 分页代码

```php
if( function_exists( 'wizhi_bootstrap_pagination' ) ){ wizhi_bootstrap_pagination(); }
```

### BootStrap 导航菜单

```php+HTML
<nav id="site-navigation" class="navbar navbar-inverse main-navigation">
  <div class="row container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
      data-target="#navbar-main" aria-expanded="false">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      </button>
      <h1 class="site-title">
        <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
      </h1>
    </div>

    <div class="collapse navbar-collapse" id="navbar-main">
      <?php
      if (has_nav_menu('primary')) :
        wp_nav_menu([
          'theme_location' => 'primary',
          'menu_class' => 'nav navbar-nav navbar-right',
          'container' => 'ul',
          'walker' => new wizhi_bootstrap_navwalker,
        ]);
      endif;
      ?>
    </div>
  </div>

</nav>
```

