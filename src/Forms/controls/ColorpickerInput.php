<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;


/**
 * 颜色选择
 */
class ColorpickerInput extends \Nette\Forms\Controls\TextBase {

	private $settings = [];

	/**
	 * @param  string|object Html      标签
	 * @param  array         $settings TinyMce 设置
	 */
	public function __construct( $label = null, $settings = [] ) {
		parent::__construct( $label );
		$this->settings = $settings;
	}


	/**
	 * 生成控件 HTML 内容
	 *
	 * @return string
	 */
	public function getControl() {

		$id       = $this->getHtmlId();
		$name     = $this->getHtmlName();
		$settings = $this->settings;

		$default_value = $this->value ? $this->value : '';

		$settings_default = [
			'textarea_name' => $name,
			'teeny'         => true,
			'media_buttons' => false,
		];

		$settings = wp_parse_args( $settings_default, $settings );

		wp_enqueue_script( 'frm-iris' );

		$html = '<input id="' . $id . '" class="form-control" name="' . $name . '" value="' . $default_value . '">';

		$html .= "<script>
			jQuery(document).ready(function($) {
				var picker = $('#" . $id . "');
				picker.iris({
					palettes: ['#125', '#459', '#78b', '#ab0', '#de3', '#f0f']}
				);
				picker.blur(function() {
					setTimeout(function() {
					  if (!$(document.activeElement).closest('.iris-picker').length){
					  	  picker.iris('hide');
					  }else{
					      picker.focus();
					  }
					}, 0);
				});
				picker.focus(function() {
					picker.iris('show');
				});
			});
		</script>";

		return $html;
	}
}
