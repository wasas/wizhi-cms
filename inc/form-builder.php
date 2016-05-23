<?php
require_once( WIZHI_CMS . 'vendor/autoload.php' );
use Nette\Forms\Form;


class WizhiFormBuilder {

	/**
	 * 表单字段类型
	 *
	 * @var string
	 */
	private $form_type;


	/**
	 * 表单字段
	 *
	 * @var array 表单字段数据
	 */
	private $fields = [ ];


	/**
	 * 文章类型
	 *
	 * @var array 自定义文章类型
	 */
	private $post_types;


	/**
	 * 分类法
	 *
	 * @var string 自定义分类法
	 */
	private $taxonomies;


	/**
	 * ID 文章、用户或分类项目字段 ID
	 *
	 * @var string
	 */
	private $id;


	/**
	 * WizhiFormBuilder constructor.
	 *
	 * @param       $form_type
	 * @param       $fields
	 * @param array $post_types
	 * @param array $taxonomies
	 * @param int   $id
	 */
	public function __construct( $form_type, $fields, $id = 0, $post_types = [ ], $taxonomies = [ ] ) {
		$this->form_type = $form_type;
		$this->fields    = $fields;
		$this->id        = $id;


		$fields = wp_parse_args( $fields, [
			'post_type' => 'post',
			'context'   => 'advanced',
			'priority'  => 'default',
		] );

		if ( is_string( $fields[ 'post_type' ] ) ) {
			$fields[ 'post_type' ] = [ $fields[ 'post_type' ] ];
		}

		$this->post_types = $fields[ 'post_type' ];
		$this->context    = $fields[ 'context' ];
		$this->priority   = $fields[ 'priority' ];


		add_action( 'load-post.php', [ $this, 'pre_register' ] );
		add_action( 'load-post-new.php', [ $this, 'pre_register' ] );
	}


	// 初始化表单
	public function init() {
		$this->display();
		$this->save();
	}


	/**
	 * 获取已经保存的值
	 */
	public function values() {
		$form_type = $this->form_type;
		$fields    = $this->fields;
		$id        = $this->id;

		$values = [ ];

		foreach ( $fields as $field ) {

			switch ( $form_type ) {
				case 'option':
					$values[ $field[ 'name' ] ] = get_option( $field[ 'name' ] );
					break;

				case 'post_meta':
					$values[ $field[ 'name' ] ] = get_post_meta( $id, $field[ 'name' ], true );
					break;

				case 'user_meta':
					$values[ $field[ 'name' ] ] = get_user_meta( $id, $field[ 'name' ], true );
					break;

				case 'term_meta':
					$values[ $field[ 'name' ] ] = get_term_meta( $id, $field[ 'name' ], true );
					break;

				default:
					$values[ $field[ 'name' ] ] = get_post_meta( $id, $field[ 'name' ], true );
			}

		}

		return $values;

	}


	/**
	 * 生成表单
	 */
	public function build() {

		$form = new Form;

		$renderer                                        = $form->getRenderer();
		$renderer->wrappers[ 'controls' ][ 'container' ] = 'table class=form-table';
		$renderer->wrappers[ 'pair' ][ 'container' ]     = 'tr';
		$renderer->wrappers[ 'label' ][ 'container' ]    = 'th class=row';
		$renderer->wrappers[ 'control' ][ 'container' ]  = 'td';

		$fields = $this->fields;
		$values = $this->values();

		$form->addHidden( 'wizhi_nonce' )
		     ->setDefaultValue( wp_create_nonce( 'wizhi_nonce' ) );

		foreach ( $fields as $field ) {

			switch ( $field[ 'type' ] ) {
				case 'text':
					$form->addText( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setAttribute( 'placeholder', $field[ 'placeholder' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'textarea':
					$form->addTextArea( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'rows', $field[ 'attr' ][ 'rows' ] )
					     ->setAttribute( 'cols', $field[ 'attr' ][ 'cols' ] )
					     ->setAttribute( 'class', 'large-text' )
					     ->setAttribute( 'placeholder', $field[ 'placeholder' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'checkbox':
					$form->addCheckbox( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'checkbox-list':
					$form->addCheckboxList( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'radio':
					$form->addRadioList( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'select':
					$form->addSelect( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'multi-select':
					$form->addMultiSelect( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] );
					break;

				case 'password':
					$form->addPassword( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;
				case 'upload':
					$form->addUpload( $field[ 'name' ], $field[ 'label' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'multi-upload':
					$form->addMultiUpload( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'hidden':
					$form->addHidden( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				default:
					$form->addText( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;
			}

		}

		$form->addSubmit( 'send', '保存更改' );

		return $form;

	}


	/**
	 * 验证表单提交的数据
	 */
	public function validate() {

		$form = $this->build();

		if ( $form->isSuccess() ) {
			return true;
		}

		return false;

	}


	/**
	 * 显示表单
	 */
	public function display() {

		$form = $this->build();

		echo $form;

	}


	/**
	 * 保存提交的数据
	 */
	public function save() {

		$form = $this->build();

		$form_type = $this->form_type;
		$fields    = $this->fields;
		$id        = $this->id;

		// 保存到数据库
		if ( $form->isSuccess() ) {

			$values = $form->getValues();

			$nonce = $values->wizhi_nonce;

			foreach ( $fields as $field ) {

				switch ( $form_type ) {
					case 'option':
						update_option( $field[ 'name' ], $values->$field[ 'name' ] );
						break;

					// 保存文章元数据
					case 'post_meta':

						// 检查训技术
						if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wizhi_nonce' ) ) {
							return;
						}

						// 检查是否有权限编辑
						if ( ! current_user_can( 'edit_post', $id ) ) {
							return;
						}

						// 自动保存和版本不保存元数据
						if ( wp_is_post_autosave( $id ) || wp_is_post_revision( $id ) ) {
							return;
						}

						update_post_meta( $id, $field[ 'name' ], $values->$field[ 'name' ] );
						break;

					case 'user_meta':
						update_user_meta( $id, $values->$field[ 'name' ] );
						break;

					case 'term_meta':
						update_term_meta( $id, $values->$field[ 'name' ] );
						break;

					default:
						update_post_meta( $id, $field[ 'name' ], $values->$field[ 'name' ] );
				}

			}

			echo '<div class="notice notice-success is-dismissible"><p>设置保存成功</p></div>';

		}

	}

}

