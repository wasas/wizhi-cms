<?php

namespace Wizhi\Forms\Controls;

use Nette\Forms\Form;
use Nette\Forms\Controls\ChoiceControl;


/**
 * 克隆输入
 *
 * todo: 优化实现方法
 */
class SliderInput extends ChoiceControl {

	/** validation rule */
	const VALID = ':selectBoxValid';

	private $args = [];

	/**
	 * DropdownInput constructor.
	 *
	 * @param string|null $label
	 * @param array       $args
	 */
	public function __construct( $label = null, array $args ) {
		parent::__construct( $label );
		$this->setOption( 'type', 'text' );
		$this->args = $args;
		$this->addCondition( Form::BLANK );
	}


	/**
	 * 加载 HTTP 数据
	 *
	 * @return void
	 */
	public function loadHttpData() {
		$this->setValue( $this->getHttpData( Form::DATA_LINE ) );
	}


	/**
	 * Sets selected item (by key).
	 *
	 * @param  string|int
	 *
	 * @return static
	 * @internal
	 */
	public function setValue( $value ) {
		$this->value = $value;

		return $this;
	}

	/**
	 * 修改控制类型属性
	 *
	 * @param  string
	 *
	 * @return static
	 */
	public function setHtmlType( $type ) {
		return $this->setType( $type );
	}


	/**
	 * setHtmlType 的别名
	 *
	 * Alias for setHtmlType()
	 *
	 * @param  string
	 *
	 * @return static
	 */
	public function setType( $type ) {
		$this->control->type = $type;

		return $this;
	}


	/**
	 * 生成 HTML 元素
	 *
	 * @return string
	 */
	public function getControl() {

		wp_enqueue_script( 'frm-slider' );

		$name     = $this->getHtmlName();
		$id       = $this->getHtmlId();
		$required = $this->isRequired ? 'required' : '';
		$args     = $this->args;

		// 模拟下拉选择默认值
		$default_value = $this->value ? $this->value : '';

		// 设置默认值
		$html = '<input id="' . $id . '" type="hidden" ' . $required . ' name="' . $name . '" value="' . $default_value . '">';

		$html .= '<script>
	        jQuery(document).ready(function($) {
	            $("#' . $id . '").ionRangeSlider({
				    type: "' . $args[ "type" ] . '",
				    min: ' . $args[ "min" ] . ',
				    max: ' . $args[ "max" ] . ',
				    grid: ' . $args[ "grid" ] . '
				});
	        });
	    </script>';

		return $html;

	}

	/**
	 * 返回表单值
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * 添加验证规则
	 *
	 * @return static
	 */
	public function addRule( $validator, $errorMessage = null, $arg = null ) {
		if ( $this->control->type === null && in_array( $validator, [ Form::EMAIL, Form::URL, Form::INTEGER ], true ) ) {
			static $types = [ Form::EMAIL => 'email', Form::URL => 'url', Form::INTEGER => 'number' ];
			$this->control->type = $types[ $validator ];

		} elseif ( in_array( $validator, [ Form::MIN, Form::MAX, Form::RANGE ], true )
		           && in_array( $this->control->type, [ 'number', 'range', 'datetime-local', 'datetime', 'date', 'month', 'week', 'time' ], true )
		) {
			if ( $validator === Form::MIN ) {
				$range = [ $arg, null ];
			} elseif ( $validator === Form::MAX ) {
				$range = [ null, $arg ];
			} else {
				$range = $arg;
			}
			if ( isset( $range[ 0 ] ) && is_scalar( $range[ 0 ] ) ) {
				$this->control->min = isset( $this->control->min ) ? max( $this->control->min, $range[ 0 ] ) : $range[ 0 ];
			}
			if ( isset( $range[ 1 ] ) && is_scalar( $range[ 1 ] ) ) {
				$this->control->max = isset( $this->control->max ) ? min( $this->control->max, $range[ 1 ] ) : $range[ 1 ];
			}

		} elseif ( $validator === Form::PATTERN && is_scalar( $arg )
		           && in_array( $this->control->type, [ null, 'text', 'search', 'tel', 'url', 'email', 'password' ], true )
		) {
			$this->control->pattern = $arg;
		}

		return parent::addRule( $validator, $errorMessage, $arg );
	}

	/**
	 * 只要输入不为空，即为验证通过
	 *
	 * @return bool
	 */
	public function isOk() {

		return $this->isDisabled()
		       || $this->getValue() == 0
		       || $this->getValue() !== null;
	}

}
