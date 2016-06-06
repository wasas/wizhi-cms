<?php

// register Foo_Widget widget
add_action( 'widgets_init', 'register_foo_widget' );
function register_foo_widget() {
	register_widget( 'WizhiWidget' );
}

/**
 * 通用注册小工具类
 */
class WizhiWidget extends WP_Widget {


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
	 * 注册小工具
	 *
	 * @param array $fields 小工具表单元素
	 * @param array $args 小工具参数
	 */
	public function __construct( $fields = [ ], $args = [ ] ) {
		$this->fields = $fields;
		$this->args   = $args;

		print_r($fields);
		print_r($args);

		parent::__construct( 'uytre', 'ASD', [ 'description' => $args[ 'desc' ] ] );
	}


	/**
	 * 前端显示,
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * todo: 怎么获取小工具值, 小工具逻辑主要在这里, 怎么解耦
	 */
	public function widget( $args, $instance ) {
		echo $args[ 'before_widget' ];

		// 这里的内容在前端显示
		if ( ! empty( $instance[ 'title' ] ) ) {
			echo $args[ 'before_title' ] . apply_filters( 'widget_title', $instance[ 'title' ] ) . $args[ 'after_title' ];
		}
		echo __( esc_attr( 'Hello, World!' ), 'text_domain' );

		echo $args[ 'after_widget' ];
	}


	public function build( $instance ) {

		$args   = $this->args;
		$id     = $args[ 'slug' ];
		$fields = $this->fields;

		$args = wp_parse_args( $args, [
			'instance' => $instance,
		] );

		$form = new WizhiFormBuilder( 'widget', $fields, $id, $args );

		return $form;
	}


	/**
	 * 显示表单
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$fields = $this->fields;
		$args   = $this->args;

		$args = wp_parse_args( $args, [
			'instance' => $instance,
		] );

		$form = new WizhiFormBuilder( 'widget', $fields, 0, $args );

		$form->show();
	}


	/**
	 * 保存插件设置, 怎么统一保存
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$form = $this->build( $new_instance );

		$instance            = [ ];
		$instance[ 'title' ] = ( ! empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';

		return $instance;
	}

}