<?php

use Nette\Utils\Arrays;
use Nette\Utils\Finder;

if ( ! function_exists( 'wizhi_get_template_part' ) ) {
	/**
	 * 自定义模板加载器, 优先加载主题中的模板, 如果主题中的模板不存在, 就加载插件中的
	 *
	 * @param mixed  $slug 模板名称的前缀, 模板名称的后缀
	 * @param string $name (default: '')
	 *
	 * @package template
	 */

	function wizhi_get_template_part( $slug, $name = '' ) {
		$template = '';

		// 查找主题中定义的插件模板
		if ( $name ) {
			$template = locate_template( [ "{$slug}-{$name}.php", "wizhi/{$slug}-{$name}.php" ] );
		}

		// 加载插件中定义的模板
		if ( ! $template && $name && file_exists( WIZHI_CMS . "templates/{$slug}-{$name}.php" ) ) {
			$template = WIZHI_CMS . "templates/{$slug}-{$name}.php";
		}

		// 加载主题中的默认模板
		if ( ! $template ) {
			$template = locate_template( [ "{$slug}.php", "wizhi/{$slug}.php" ] );
		}

		// 允许第三方插件过滤模板文件
		$template = apply_filters( 'wizhi_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'wizhi_get_loop_template' ) ) {
	/**
	 * 获取存档页面模板
	 *
	 * @param string $dir 模板文件所在的目录名称
	 *
	 * @return array
	 */
	function wizhi_get_loop_template( $dir = "wizhi" ) {
		$template_in_plugin = WIZHI_CMS . "templates/" . $dir;
		$template_in_theme  = get_template_directory() . "/" . $dir;

		$templates_in_plugin = [ ];
		$templates_in_theme  = [ ];

		$finder = Finder::findFiles( '*.php' )
		                ->in( $template_in_plugin );

		foreach ( $finder as $key => $file ) {

			$filename        = $file->getFilename();
			$file_name_array = explode( '-', $filename );
			$name            = Arrays::get( $file_name_array, 1, 'None' );

			$headers = [
				'Name' => 'Loop Template Name',
			];

			$file_info = get_file_data( $key, $headers );

			// 获取模板名称
			if ( $file_info[ 'Name' ] ) {
				$option_name = $file_info[ 'Name' ];
			} else {
				$option_name = ucfirst( $name );
			}

			$templates_in_theme[ explode( '.', $name )[ 0 ] ] = $option_name;

		}

		if ( file_exists( $template_in_theme ) ) {

			$finder = Finder::findFiles( '*.php' )
			                ->in( $template_in_theme );

			foreach ( $finder as $key => $file ) {

				$filename        = $file->getFilename();
				$file_name_array = explode( '-', $filename );
				$name            = Arrays::get( $file_name_array, 1, 'None' );

				$headers = [
					'Name' => 'Loop Template Name',
				];

				$file_info = get_file_data( $key, $headers );

				// 获取模板名称
				if ( $file_info[ 'Name' ] ) {
					$option_name = $file_info[ 'Name' ];
				} else {
					$option_name = ucfirst( $name );
				}

				$templates_in_theme[ explode( '.', $name )[ 0 ] ] = $option_name;

			}
		}

		$templates = wp_parse_args( $templates_in_theme, $templates_in_plugin );

		return $templates;
	}

}


if ( ! function_exists( 'wizhi_load_template_part' ) ) {

	/**
	 * 获取模板为变量, 而不是直接显示
	 *
	 * @param  string $slug 模板名称前缀
	 * @param string  $name 模板名称
	 *
	 * @return string
	 */
	function wizhi_load_template_part( $slug, $name = '' ) {
		ob_start();
		wizhi_get_template_part( $slug, $name );
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}