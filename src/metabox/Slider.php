<?php
/**
 * 幻灯需要的数据盒子
 *
 */

namespace Wizhi\Metabox;

class Slider {
	public static function init() {
		$fm = new \Fieldmanager_Textfield( [ 'name' => '_link_url', 'label' => __( 'Custom link', 'wizhi' ) ] );
		$fm->add_meta_box( __( 'Custom link', 'wizhi' ), 'slider' );
	}
}