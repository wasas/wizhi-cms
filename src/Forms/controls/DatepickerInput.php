<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;

use Nette\Forms\Controls\TextBase;

/**
 * Multiline text input control.
 */
class DatepickerInput extends TextBase {

	private $settings = [];

	/**
	 * @param  string|object $label    Html 标签
	 * @param  array         $settings TinyMce 设置
	 */
	public function __construct( $label = null, $settings = [] ) {
		parent::__construct( $label );
		$this->settings = $settings;
	}


	/**
	 * Generates control's HTML element.
	 *
	 * @return string
	 */
	public function getControl() {

		$id       = $this->getHtmlId();
		$name     = $this->getHtmlName();
		$settings = $this->settings;

		$default_value = $this->value ? $this->value : '';

		$settings_default = [
			'dateFormat' => 'yy-mm-dd',
		];

		wp_enqueue_script( 'jquery-ui-datepicker' );

		$settings = wp_parse_args( $settings_default, $settings );

		$html = '<input id="' . $id . '" class="form-control" name="' . $name . '" value="' . $default_value . '">';

		$html .= ' <script>
		        jQuery(document).ready(function($){
		        	$( "#'. $id .'" ).datepicker({
		        		"dateFormat" : "yy-mm-dd"
		        	});
		        });
		    </script>';

		return $html;
	}
}
