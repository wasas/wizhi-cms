<?php

namespace Wizhi\Forms\Controls;

use Nette\Forms\Controls\TextArea;

/**
 * Wisiwyg 可视化编辑器输入框
 *
 * @author     Michal Koutny
 */
class Wysiwyg extends TextArea {

	/**
	 * @param  string $label
	 * @param  int    $cols 列数, 也就是宽度
	 * @param  int    $rows 行数, 也就是高度
	 */
	public function __construct( $label, $cols = null, $rows = null ) {
		parent::__construct( $label, $cols, $rows );
	}

	/**
	 * 获取输入框的值
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * 设置输入框的值
	 *
	 * @param  string
	 *
	 * @return void
	 */
	public function setValue( $value ) {
		parent::setValue( $value );
	}

	/**
	 * 生成需要显示的 HTML
	 *
	 * @return string $control
	 */
	public function getControl() {
		$control        = parent::getControl();
		$control->class = 'tinymce';

		return $control;
	}

}