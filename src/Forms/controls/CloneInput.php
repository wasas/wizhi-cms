<?php

namespace Wizhi\Forms\Controls;

use Nette\Forms\Form;
use Nette\Forms\Helpers;
use Nette\Forms\Validator;


/**
 * 克隆输入
 *
 * todo: 优化实现方法
 */
class CloneInput extends \Nette\Forms\Controls\ChoiceControl {

	/** validation rule */
	const VALID = ':selectBoxValid';

	/**
	 * DropdownInput constructor.
	 *
	 * @param string|null $label
	 */
	public function __construct( $label = null ) {
		parent::__construct( $label );
		$this->setOption( 'type', 'text' );
		$this->addCondition( Form::BLANK )
		     ->addRule( [ $this, 'isOk' ], Validator::$messages[ self::VALID ] );
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

		$name        = $this->getHtmlName();
		$id          = $this->getHtmlId();
		$placeholder = $this->control->getAttribute( 'placeholder' );
		$required    = $this->isRequired ? 'required' : '';
		$rules       = json_encode( Helpers::exportRules( $this->rules ) ? : null );

		// 模拟下拉选择默认值
		$default_value = $this->value ? $this->value : [];

		$html = '<div id="' . $id . '" class="frm-group-input">';

		// 设置默认值
		if ( count( $default_value ) > 0 ) {
			foreach ( $default_value as $k => $v ) {
				if ( $k == 0 ) {
					$html .= '<input ' . $required . ' name="' . $name . '" value="' . $v . '" data-nette-rules=\'' . $rules . '\' class="form-control" placeholder="' . $placeholder . '">';
				} else {
					$html .= '<div class="input-group"><input name="' . $name . '" value="' . $v . '" class="form-control"><span class="input-group-btn"><a class="btn btn-default remove_button">Remove</a></span></div>';
				}
			}
		} else {
			$html .= '<input ' . $required . ' name="' . $name . '" value="" class="form-control" data-nette-rules=\'' . $rules . '\' placeholder="' . $placeholder . '">';
		}

		$html .= '</div>';
		$html .= '<button class="btn btn-default btn-sm add_more_button">Add More Fields</button>';

		$html .= '<script>
        jQuery(document).ready(function($) {
            var max_fields      = 10;
            var wrapper         = $("#' . $id . '");
            var add_button      = $(" .add_more_button");

            var x = 1;
            $(add_button).click(function(e){
                e.preventDefault();
                if(x < max_fields){
                    x++;
                    x++;
                    $(wrapper).append(\'<div class="input-group"><input class="form-control" name="' . $name . '"/><span class="input-group-btn"><a class="btn btn-default remove_button">Remove</a></span></div>\');
                }
                
                return false;
            });

            $(wrapper).on("click", ".remove_button", function(e){
                e.preventDefault();
                $(this).parent().parent().remove(); 
                x--;
                return false;
            })
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
	 * 获取 HTML 名称
	 *
	 * @return mixed
	 */
	public function getHtmlName() {
		return parent::getHtmlName() . '[]';
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
