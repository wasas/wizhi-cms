<?php

namespace Wizhi\Models;

/**
 * 菜单类
 */
class Menu extends TermTaxonomy
{
    /**
     * 设置分类法类型
     *
     * @var string
     */
    protected $taxonomy = 'nav_menu';

    /**
     * 添加菜单需要的关联关系
     *
     * @var array
     */
    protected $with = ['term', 'nav_items'];
}
