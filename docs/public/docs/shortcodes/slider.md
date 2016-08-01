## 显示幻灯片

```php
echo do_shortcode('[slider type="slider" tax="slidercat" tag="home" num="4" auto="false" minslides="1" maxslides="4" slidewidth="360", tmp="slider"]');
```

*简码参数*

- `type`: 文章类型, 可直接在插件设置中激活
- `tax`: 分类方法
- `tag` : 分类项目别名
- `num` : 需要显示的幻灯片数量
- `auto`: 是否自动播放
- `minslides`: 最小显示的幻灯片数量
- `maxsliders`: 最多显示的幻灯片数量，大于1时，显示为 carousel
- `sliderwidth`: 显示为 carousel 时，每个图片的宽度
- `tmp`: 模板名称，默认为插件中的 content-slider.php 模板