<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

class WizhiVisualBuilderToolbar {

    protected $toolbarButtons = [ ];

    function __construct() {
        add_filter( 'pbs_js_vars', [ $this, 'addToolbars' ] );
        add_filter( 'pbs_toolbar_buttons', [ $this, 'addCoreToolbarButtons' ], 1 );
    }

    public function addToolbars( $columnVars ) {
        if( empty( $columnVars ) ) {
            $columnVars = [ ];
        }

        $toolbarButtons = [ ];

        // 提供接口, 允许开发者添加工具条按钮
        $toolbarButtons = apply_filters( 'pbs_toolbar_buttons', $toolbarButtons );

        // 清理工具条参数
        foreach ( $toolbarButtons as $key => $args ) {
            $toolbarButtons[ $key ] = $this->clearToolbarButtonArgs( $args );
        }

        // 根据属性数量排序
        usort( $toolbarButtons, [ $this, 'toolbarPrioritySort' ] );

        $columnVars[ 'toolbar_buttons' ] = $toolbarButtons;

        return $columnVars;
    }

    /**
     * Filter: pbs_toolbar_buttons
     *
     * action - 动作的名称, 点击按钮时执行的 JavaScript 对象
     * icon    - dashicon icon 类
     * label - 鼠标滑过按钮时显示的标签, 使用 '|' 为分隔符, 没有动作
     * shortcode - 此工具条出现在哪个简码上, 留空显示在所有简码上, 包括分栏和行
     * priority - 按钮位置, 默认为 10.
     *            >= 100 为编辑按钮左边
     *            >= 0 为删除按钮左边
     *            < 0 暗处按钮右边
     * hash - 自动生成唯一 ID
     */
    public function clearToolbarButtonArgs( $args ) {
        return [
            'action'    => empty( $args[ 'action' ] ) ? '' : $args[ 'action' ],
            'icon'      => empty( $args[ 'icon' ] ) ? 'dashicons dashicons-edit' : $args[ 'icon' ],
            'label'     => empty( $args[ 'label' ] ) ? '' : $args[ 'label' ],
            'shortcode' => empty( $args[ 'shortcode' ] ) ? '' : $args[ 'shortcode' ],
            'priority'  => empty( $args[ 'priority' ] ) ? 10 : ( (int)$args[ 'priority' ] === 0 ? 10 : $args[ 'priority' ] ),
            'hash'      => substr( md5( microtime() ), 0, 8 ),
        ];
    }

    public function toolbarPrioritySort( $a, $b ) {
        return $b[ 'priority' ] - $a[ 'priority' ];
    }


    /**
     * 添加核心工具条按钮
     */
    public function addCoreToolbarButtons( $toolbarButtons ) {

        /**
         * 全功能复制按钮
         */
        $toolbarButtons[] = [
            'action'   => 'clone',
            'icon'     => 'dashicons dashicons-images-alt',
            'label'    => __( '复制', 'pbwizhi' ),
            'priority' => 0,
        ];

        return $toolbarButtons;
    }

}

new WizhiVisualBuilderToolbar();

?>