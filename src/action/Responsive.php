<?php
/**
 * Created by PhpStorm.
 * User: amoslee
 * Date: 2017/8/26
 * Time: 22:39
 */

namespace Wizhi\Action;


class Responsive {

	/**
	 * 根据设置自动切换主题
	 *
	 * @param $theme
	 *
	 * @return mixed
	 */
	function switch_theme( $theme ) {

		$mobile_theme = get_option( 'wizhi_cms_settings' )[ 'mobile_theme' ];

		if ( isset( $mobile_theme ) && wp_is_mobile() && $mobile_theme != 0 ) {
			$theme = $mobile_theme;
		}

		return $theme;
	}

}