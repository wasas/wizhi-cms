<?php

namespace Wizhi\Action;

use Nette\Utils\Arrays;

class Responsive {

	/**
	 * 根据设置自动切换主题
	 *
	 * @param $theme
	 *
	 * @return mixed
	 */
	public static function switch_theme( $theme ) {

		global $wizhi_option;

		if ( $wizhi_option ) {
			$mobile_theme = Arrays::get( $wizhi_option, [ 'general', 'mobile_theme' ], 'dashicons-admin-post' );
		}

		if ( isset( $mobile_theme ) && wp_is_mobile() && $mobile_theme != 0 ) {
			$theme = $mobile_theme;
		}

		return $theme;
	}

}