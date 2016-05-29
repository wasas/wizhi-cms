<?php

/**
 * 注册元数据盒子
 */
class WizhiTermMetabox {

	/**
	 * 表单字段
	 *
	 * @var array
	 */
	private $fields;


	/**
	 * 附加属性
	 *
	 * @var array
	 */
	private $args;


	/**
	 * WizhiTermMetabox constructor.
	 *
	 * @param array $fields 表单数据
	 * @param array $args   附加参数
	 */
	public function __construct( $fields = [ ], $args = [ ] ) {

		$this->fields     = $fields;
		$this->args       = $args;
		$this->taxonomies = $args[ 'taxonomies' ];

		if ( is_admin() ) {

			foreach ( $args[ 'taxonomies' ] as $taxonomy ) {
				add_action( $taxonomy . '_edit_form_fields', [ $this, 'show' ] );
				add_action( $taxonomy . '_add_form_fields', [ $this, 'show' ], 10, 2 );

				add_action( 'edited_' . $taxonomy, [ $this, 'save' ], 10, 2 );
				add_action( 'create_' . $taxonomy, [ $this, 'save' ], 10, 2 );
			}

		}

	}


	/**
	 * 构造表单
	 *
	 * @param int $term_id 分类法项目 ID
	 *
	 * @return \WizhiFormBuilder
	 */
	public function build( $term_id ) {
		$fields = $this->fields;

		// 显示表单
		$form = new WizhiFormBuilder( 'term_meta', $fields, $term_id );

		return $form;

	}


	/**
	 * 渲染元数据盒子
	 *
	 * @param object $term 分类对象
	 *
	 */
	public function show( $term ) {
		$form = $this->build( $term->term_id );

		$form->display();
	}


	/**
	 * 处理保存元数据盒子
	 *
	 * @param int $term_id 分类项目 ID
	 *
	 * @return null
	 */
	public function save( $term_id ) {

		$form = $this->build( $term_id );

		$form->save();

	}

}