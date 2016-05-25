<?php

/**
 * 注册元数据盒子
 */
class WizhiUserMetabox {

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

		$this->id     = $id;
		$this->title  = $title;
		$this->fields = $fields;
		$this->args   = $args;

		if ( is_admin() ) {
			add_action( 'show_user_profile', [ $this, 'show' ] );
			add_action( 'edit_user_profile', [ $this, 'show' ], 10, 2 );

			add_action( 'personal_options_update', [ $this, 'save' ], 10, 2 );
			add_action( 'edit_user_profile_update', [ $this, 'save' ], 10, 2 );
		}

	}

	// 构建表单
	public function build( $user_id ) {
		$fields = $this->fields;

		// 显示表单
		$form = new WizhiFormBuilder( 'user_meta', $fields, $user_id );

		return $form;

	}


	/**
	 * 渲染元数据盒子
	 *
	 * @param object $user 分类对象
	 *
	 */
	public function show( $user ) {
		$form = $this->build( $user->ID );

		$form->display();
	}


	/**
	 * 处理保存元数据盒子
	 *
	 * @param int $user_id 用户 ID
	 *
	 * @return null
	 */
	public function save( $user_id ) {

		$form = $this->build( $user_id );

		$form->save();

	}

}