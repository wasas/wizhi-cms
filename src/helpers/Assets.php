<?php

namespace Wizhi\Helper;

class Assets {

	/**
	 * 加载前端资源
	 */
	public static function load() {
		wp_enqueue_style( 'wizhi-form-style', WIZHI_CMS_URL . '/front/dist/styles/admin.css' );
		wp_enqueue_script( 'wizhi-form-scripts', WIZHI_CMS_URL . '/front/dist/scripts/admin.js', [ 'jquery' ], WIZHI_CMS_VERSION, false );
	}

}