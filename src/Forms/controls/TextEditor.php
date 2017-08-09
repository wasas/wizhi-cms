<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;

use Nette\Forms\Controls\TextBase;


/**
 * WordPress TinyMce 可视化编辑器
 */
class TextEditor extends TextBase {

	private $settings = [];

	/**
	 * @param  string|object Html      标签
	 * @param  array         $settings TinyMce 设置
	 */
	public function __construct( $label = null, $settings = [] ) {
		parent::__construct( $label );
		$this->settings = $settings;
		$this->control->setName( 'textarea' );
		$this->setOption( 'type', 'textarea' );
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

		ob_start();
		wp_editor( $default_value, $id, $settings );
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}
}
