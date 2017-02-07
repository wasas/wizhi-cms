<?php
/**
 * 添加自定义数据表
 *
 */

global $wpdb;
define( 'LIKE', $wpdb->prefix . '_likes' );
define( 'MSG', $wpdb->prefix . '_messages' );

R::setup( 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD );
R::setAutoResolve( true );

R::ext( 'model', function ( $type ) {
    return R::getRedBean()
            ->dispense( $type );
} );