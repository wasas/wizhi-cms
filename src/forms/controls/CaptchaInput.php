<?php

/**
 * This file is part of the Nette Framework (https://nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

namespace Wizhi\Forms\Controls;

use Nette\Forms\Controls\TextBase;

/**
 * 颜色选择
 */
class CaptchaInput extends TextBase {

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

		$id        = $this->getHtmlId();
		$action_id = $id . '-action';

		$name          = $this->getHtmlName();
		$settings      = $this->settings;
		$data_url      = $this->control->getAttribute( 'data-url' );
		$default_value = $this->value ? $this->value : '';

		$settings_default = [
			'textarea_name' => $name,
			'teeny'         => true,
			'media_buttons' => false,
		];

		$settings = wp_parse_args( $settings_default, $settings );

		$html = "<script>
            // 刷新验证码
		    function refresh_code(obj) {
		        obj.src = obj.src + '?code=' + Math.random();
		    }</script>";

		$html .= '<div class="input-group">
                    <input id="' . $id . '" class="form-control" name="' . $name . '" value="' . $default_value . '">
                    <span class="input-group-btn">
						<img alt="captcha" onclick="refresh_code(this)" id="' . $action_id . '" data-toggle="tooltip" title="点击刷新验证码" src="' . $data_url . '" />
					</span>
                  </div>';

		return $html;
	}
}
