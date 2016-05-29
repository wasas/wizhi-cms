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
	 */
	public function menu() {

		$args = $this->args;

		add_options_page( $args[ 'title' ], $args[ 'label' ], 'manage_options', $args[ 'slug' ], [ $this, 'page', ] );
	}


	/**
	 * 添加设置页面
	 */
	public function page() {

		$args   = $this->args;
		$fields = $this->fields;

		echo Html::el( 'h1', $args[ 'title' ] );
		echo Html::el( 'p', '插件描述' );

		add_thickbox();

		echo '<div id="transform" style="display:none;">
     <p>
          This is my hidden content! It will appear in ThickBox when the link is clicked.
     </p>
</div>

<a href="#TB_inline?width=600&height=550&inlineId=transform" class="thickbox">查看弹出内容</a>	';


		// 显示表单
		$form = new WizhiFormBuilder( 'option', $fields );
		$form->init();

	}

}