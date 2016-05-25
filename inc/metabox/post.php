<?php

/**
 * 注册元数据盒子
 */
class WizhiMetabox {

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
	 * Post types.
	 *
	 * @var array
	 */
	private $post_types;


	/**
	 * Constructor.
	 */
	public function __construct( $id, $title, $fields = [ ], $args = [ ] ) {

		$this->id         = $id;
		$this->title      = $title;
		$this->fields     = $fields;
		$this->args       = $args;
		$this->post_types = $args[ 'post_type' ];

		if ( is_admin() ) {
			add_action( 'load-post.php', [ $this, 'init_metabox' ] );
			add_action( 'load-post-new.php', [ $this, 'init_metabox' ] );
		}

	}

	/**
	 * 初始化元数据盒子
	 */
	public function init_metabox() {
		add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
		add_action( 'save_post', [ $this, 'save_metabox' ], 10, 2 );
	}


	/**
	 * 添加元数据盒子
	 */
	public function add_metabox() {
		$id    = $this->id;
		$title = $this->title;
		$post_types = $this->post_types;

		add_meta_box( $id, $title, [ $this, 'display' ], $post_types, 'advanced', 'default' );
	}


	// 构建表单
	public function build( $post_id ) {
		$fields = $this->fields;

		// 显示表单
		$form = new WizhiFormBuilder( 'post_meta', $fields, $post_id );

		return $form;

	}

	/**
	 * 渲染元数据盒子
	 */
	public function display( $post ) {
		$form = $this->build( $post->ID );

		$form->display();
	}


	/**
	 * 处理保存元数据盒子
	 *
	 * @param int    $post_id 文章 ID
	 * @param object $post    文章对象
	 *
	 * @return null
	 */
	public function save_metabox( $post_id, $post ) {

		$form = $this->build( $post_id );

		$form->save();

	}

}