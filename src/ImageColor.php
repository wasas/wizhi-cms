<?php

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

/**
 * 获取图片的主要颜色
 */
class ImageColor {

	use Nette\StaticClass;

	/**
	 * 获取图片的主要颜色
	 *
	 * @param $image int|string 需要获取颜色的图片资源，图片 id 或路径
	 * @param $count int 需要获取图片的颜色数量
	 *
	 * @return array
	 */
	public static function color( $image, $count = 5 ) {

		if ( is_int( $image ) ) {
			$image = get_attached_file( $image );
		}

		$palette   = Palette::fromFilename( $image );
		$extractor = new ColorExtractor( $palette );

		$colors = $extractor->extract( $count );

		$colors = collect( $colors )->map( function ( $color ) {
			return Color::fromIntToHex( $color );
		} )->toArray();

		return $colors;
	}

}