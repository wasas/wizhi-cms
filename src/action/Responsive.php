<?php
namespace Wizhi\Action;


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

		$mobile_theme = $wizhi_option['general'][ 'mobile_theme' ];

		if ( isset( $mobile_theme ) && wp_is_mobile() && $mobile_theme != 0 ) {
			$theme = $mobile_theme;
		}

		return $theme;
	}

}