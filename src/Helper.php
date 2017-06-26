<?php
/**
 * 翻译固定字符串
 */

use Wizhi\Helper\Translator;

class Helper {

	/**
	 * 定义需要翻译的字符串
	 *
	 * @param $message
	 *
	 * @return string
	 */
	public static function lang( $message ) {
		$lang = new Translator;

		return $lang->translate( $message );
	}


	/**
	 * 格式化 Nette Form
	 *
	 * @param \Form $form
	 */
	public static function form( Form $form ) {
		$renderer                                            = $form->getRenderer();
		$renderer->wrappers[ 'controls' ][ 'container' ]     = null;
		$renderer->wrappers[ 'pair' ][ 'container' ]         = 'div class=form-group';
		$renderer->wrappers[ 'pair' ][ '.error' ]            = 'has-error';
		$renderer->wrappers[ 'control' ][ 'container' ]      = 'div class=col-sm-9';
		$renderer->wrappers[ 'label' ][ 'container' ]        = 'div class="col-sm-3 control-label"';
		$renderer->wrappers[ 'control' ][ 'description' ]    = 'span class=help-block';
		$renderer->wrappers[ 'control' ][ 'errorcontainer' ] = 'span class=help-block';
		$form->getElementPrototype()->class( 'form-horizontal' );
		$form->onRender[] = function ( $form ) {
			foreach ( $form->getControls() as $control ) {
				$type = $control->getOption( 'type' );
				if ( $type === 'button' ) {
					$control->getControlPrototype()->addClass( empty( $usedPrimary ) ? 'btn btn-primary' : 'btn btn-default' );
					$usedPrimary = true;
				} elseif ( in_array( $type, [ 'text', 'textarea', 'select' ], true ) ) {
					$control->getControlPrototype()->addClass( 'form-control' );
				} elseif ( in_array( $type, [ 'checkbox', 'radio' ], true ) ) {
					$control->getSeparatorPrototype()->setName( 'div' )->addClass( $type );
				}
			}
		};
	}


	/**
	 * 判断是否为英文
	 *
	 * @return bool
	 */
	function en() {

		$lang = get_bloginfo( 'language' );

		if ( $lang == 'en-US' ) {
			return true;
		}

		return false;
	}

}