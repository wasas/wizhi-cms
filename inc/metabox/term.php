<?php

/**
 * 注册元数据盒子
 */
class WizhiTermMetabox {

	/**
	 * Metabox ID.
	 *
	 * @var string
	 */
	private $id;


	/**
	 * Title.
	 *
	 * @var string
	 */
	private $title;


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
	 * @param       $id
	 * @param       $title
	 * @param array $fields
	 * @param array $args
	 */
	public function __construct( $id, $title, $fields = [ ], $args = [ ] ) {

		$this->id         = $id;
		$this->title      = $title;
		$this->fields     = $fields;
		$this->args       = $args;
		$this->post_types = $args[ 'post_type' ];
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

	// 构建表单
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