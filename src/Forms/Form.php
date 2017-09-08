<?php

use Nette\Forms\Container;

/**
 * 随机数保护
 */
Container::extensionMethod( 'addCsrf', function ( $form, $name, $errorMessage ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\CsrfInput( $errorMessage ) );
} );


/**
 * 可视化编辑器
 */
Container::extensionMethod( 'addEditor', function ( $form, $name, $label = null, $cols = null, $rows = null ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\TextEditor( $label ) )
		->setHtmlAttribute( 'cols', $cols )->setHtmlAttribute( 'rows', $rows );
} );


/**
 * Ajax 上传
 */
Container::extensionMethod( 'addAjaxUpload', function ( $form, $name, $label = null, $multiple = false ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\AjaxUploadInput( $label, $multiple ) );
} );


/**
 * Clone 输入
 */
Container::extensionMethod( 'addClone', function ( $form, $name, $label = null ) {
	return $this[ $name ] = ( new \Wizhi\Forms\Controls\CloneInput( $label ) );
} );


/**
 * 模拟下拉
 */
Container::extensionMethod( 'addDropdownSelect', function ( $form, $name, $label = null, array $items = null ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\DropdownSelectInput( $label, $items ) );
} );


/**
 * 滑动输入
 */
Container::extensionMethod( 'addSlider', function ( $form, $name, $label = null, $args ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\SliderInput( $label, $args ) );
} );


/**
 * 日期选择
 */
Container::extensionMethod( 'addDatepicker', function ( $form, $name, $label = null, $settings ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\ColorpickerInput( $label, $settings ) );
} );


/**
 * 颜色选择
 */
Container::extensionMethod( 'addColorpicker', function ( $form, $name, $label = null, $settings ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\DatepickerInput( $label, $settings ) );
} );


/**
 * 链式选择
 */
Container::extensionMethod( 'addChainedSelect', function ( $form, $name, $label = null, $settings, $field ) {
	return $form[ $name ] = ( new \Wizhi\Forms\Controls\ChainedInput( $label, $settings ) );
} );


/**
 * HTMl 内容
 */
Container::extensionMethod( 'addHtml', function ( $form, $name, $caption = null ) {
	return $form[ $name ] = new \Wizhi\Forms\Controls\HtmlContent( $caption );
} );


/**
 * 获取 SMS 验证码
 */
Container::extensionMethod( 'AddSms', function ( $form, $name, $caption = null ) {
	return $form[ $name ] = new \Wizhi\Forms\Controls\GetSmsInput( $caption );
} );