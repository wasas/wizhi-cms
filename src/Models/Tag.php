<?php

namespace Wizhi\Models;

/**
 * 标签类
 */
class Tag extends TermTaxonomy
{
    /**
     * 设置自定义分类法名称
     */
    protected $taxonomy = 'post_tag';
}
