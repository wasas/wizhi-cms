<?php
/**
 * 翻译固定字符串
 */

namespace Wizhi\Helper;

use Nette\Localization\ITranslator;
use Nette\Neon\Neon;


class Translator implements ITranslator {
	/**
	 * 根据前端语言显示对应的字符串
	 *
	 * @param  string $message
	 * @param  null   $count
	 *
	 * @return string
	 *
	 * @usage: Translator($message);
	 */
	public function translate( $message, $count = null ) {

		$messages = file_get_contents( get_theme_file_path( 'strings.neon' ) );
		$messages = Neon::decode( $messages );

		$lang = get_bloginfo( 'language' );

		return $messages[ $message ][ $lang ];

	}

}