<?php
/**
 * 幻灯需要的数据盒子
 *
 */

add_action( 'after_setup_theme', function () {
	$fm = new Fieldmanager_Textfield( [ 'name' => '_link_url', 'label' => __( 'Custom link', 'wizhi' ) ] );
	$fm->add_meta_box( __( 'Custom link', 'wizhi' ), 'slider' );
} );