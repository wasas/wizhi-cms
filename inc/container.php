<?php
/**
 * 主题辅助函数
 *
 */

global $wizhi;
$wizhi = new League\Container\Container;
$wizhi->add( 'option', 'DataOption' );
$wizhi->add( 'helper', 'Helper' );