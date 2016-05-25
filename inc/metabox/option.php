<?php

class WizhiOptionPage {

	/**
	 * 设置字段
	 *
	 * @var  array
	 */
	private $fields;


	/**
	 * 设置页面参数
	 *
	 * @var  array
	 */
	private $args;


	public function __construct( $fields = [ ], $args = [ ] ) {

		$this->fields = $fields;
		$this->args   = $args;

		add_action( 'admin_menu', [ $this, 'menu' ] );

	}

	public function menu() {

		$args = $this->args;

		add_options_page( $args[ 'title' ], $args[ 'label' ], 'manage_options', $args[ 'slug' ], [ $this, 'page', ] );
	}


	/**
	 * 添加设置页面
	 */
	public function page() {

		$fields = $this->fields;

		// 显示表单
		$form = new WizhiFormBuilder( 'option', $fields );
		$form->init();

	}

}