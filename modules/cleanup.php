<?php

//add_action( 'admin_menu', 'wizhi_cms_remove_menus' );
//add_action( 'wp_before_admin_bar_render', 'wizhi_cms_remove_admin_bar' );

//移除后台无用的菜单
function wizhi_cms_remove_menus() {
    global $submenu;

    remove_menu_page( 'index.php' ); // 仪表盘
    remove_menu_page( 'upload.php' ); //多媒体
    remove_menu_page( 'edit-comments.php' ); //评论
    remove_menu_page( 'plugins.php' ); //插件
    remove_menu_page( 'tools.php' ); //工具
    remove_menu_page( 'options-general.php' ); //设置

    unset( $submenu[ 'themes.php' ][ 6 ] ); //  自定义
    unset( $submenu[ 'themes.php' ][ 15 ] ); // 顶部
    unset( $submenu[ 'themes.php' ][ 20 ] ); // 背景
}

// 移除工具条菜单
function wizhi_cms_remove_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'wp-logo' );
    $wp_admin_bar->remove_menu( 'about' );
    $wp_admin_bar->remove_menu( 'wporg' );
    $wp_admin_bar->remove_menu( 'customize' );
    $wp_admin_bar->remove_menu( 'documentation' );
    $wp_admin_bar->remove_menu( 'support-forums' );
    $wp_admin_bar->remove_menu( 'feedback' );
}