<?php

use Nette\Utils\Html;

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


	/**
	 * WizhiOptionPage constructor.
	 *
	 * @param array $fields 表单字段
	 * @param array $args   附加参数
	 */
	public function __construct( $fields = [ ], $args = [ ] ) {

		$this->fields = $fields;
		$this->args   = $args;

		add_action( 'admin_menu', [ $this, 'menu' ] );

	}

	/**
	 * 显示菜单
	 * todo: 在 slug 上面添加参数或者过滤函数， 实现设置选项 tab
	 */
	public function menu() {

		$args = $this->args;

		add_submenu_page( $args['parent'], $args[ 'title' ], $args[ 'label' ], 'manage_options', $args[ 'slug' ], [ $this, 'page', ] );
	}


	/**
	 * 添加设置页面
	 */
	public function page() {

		$args   = $this->args;
		$fields = $this->fields;

		echo '<div class="wrap">';

		echo Html::el( 'h1', $args[ 'title' ] );

		// 显示表单
		$form = new WizhiFormBuilder( 'option', $fields );
		$form->init();

		echo '</div>';

	}

}