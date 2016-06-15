<?php
require_once( WIZHI_CMS . 'vendor/autoload.php' );
use Nette\Forms\Form;
use Nette\Utils\Html;


/**
 * 基于 Nette form 的表单生成类
 *
 * Class WizhiFormBuilder
 */
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
	private $fields;


	/**
	 * ID 文章、用户或分类项目字段 ID
	 *
	 * @var string
	 */
	private $id;


	/**
	 * 附加参数
	 *
	 * @var array 根据各种表单类型, 添加的附加参数
	 */
	private $args = [ ];


	/**
	 * WizhiFormBuilder constructor.
	 *
	 * @param  string $form_type 表单类型
	 * @param array   $fields    表单项目
	 * @param array   $args      附加属性数组
	 * @param int     $id        表单数据 id, 文章、分类法或用户 id
	 *
	 * todo: 处理附加参数
	 */
	public function __construct( $form_type, $fields, $id = 0, $args = [ ] ) {
		$this->form_type = $form_type;
		$this->fields    = $fields;
		$this->id        = $id;
		$this->args      = $args;

		add_action( 'admin_notices', [ $this, 'notice' ] );

	}


	/**
	 * 初始化表单
	 */
	public function init() {
		$this->show();
		$this->save();
	}


	/**
	 * 获取已经保存的值
	 *
	 * todo: 考虑是否需要合并 meta 数据获取方法
	 */
	public function values() {
		$form_type = $this->form_type;
		$fields    = $this->fields;
		$id        = $this->id;

		$values = [ ];

		foreach ( $fields as $field ) {

			switch ( $form_type ) {
				case 'option':
					$values[ $field[ 'name' ] ] = get_option( $field[ 'name' ], $field[ 'default' ] );
					break;

				case 'post_meta':
					$value                      = get_post_meta( $id, $field[ 'name' ], true );
					$values[ $field[ 'name' ] ] = ( $value ) ? $value : $field[ 'default' ];
					break;

				case 'user_meta':
					$value                      = get_user_meta( $id, $field[ 'name' ], true );
					$values[ $field[ 'name' ] ] = ( $value ) ? $value : $field[ 'default' ];
					break;

				case 'term_meta':
					$value                      = get_term_meta( $id, $field[ 'name' ], true );
					$values[ $field[ 'name' ] ] = ( $value ) ? $value : $field[ 'default' ];
					break;

				default:
					$value                      = get_post_meta( $id, $field[ 'name' ], true );
					$values[ $field[ 'name' ] ] = ( $value ) ? $value : $field[ 'default' ];
			}

		}

		return $values;

	}


	/**
	 * 生成表单
	 */
	public function build() {

		$form_type = $this->form_type;

		$form = new Form;

		$renderer = $form->getRenderer();

		$screen = get_current_screen();

		$renderer->wrappers[ 'group' ][ 'container' ] = null;
		$renderer->wrappers[ 'group' ][ 'label' ]     = 'h2';

		switch ( $form_type ) {

			case 'term_meta':
				if ( $screen->base == 'term' ) {
					$renderer->wrappers[ 'controls' ][ 'container' ] = 'table class=form-table';
					$renderer->wrappers[ 'pair' ][ 'container' ]     = 'tr class=form-field';
				} else {
					$renderer->wrappers[ 'controls' ][ 'container' ] = '';
					$renderer->wrappers[ 'pair' ][ 'container' ]     = 'div class=form-field';
				}
				break;

			default:
				$renderer->wrappers[ 'controls' ][ 'container' ] = 'table class=form-table';
				$renderer->wrappers[ 'pair' ][ 'container' ]     = 'tr class=form-field';
		}

		$renderer->wrappers[ 'label' ][ 'container' ]   = 'th class=row';
		$renderer->wrappers[ 'control' ][ 'container' ] = 'td class="repeatable-fieldset"';

		$fields = $this->fields;
		$values = $this->values();

		$form->addHidden( 'wizhi_nonce' )
		     ->setDefaultValue( wp_create_nonce( 'wizhi_nonce' ) );

		foreach ( $fields as $field ) {

			// 对于多选项, 排序默认数组中没有的, 以免设置默认值时出错
			if ( $field[ 'type' ] == 'multi-checkbox' || $field[ 'type' ] == 'multi-select' || $field[ 'type' ] == 'multi-upload' ) {
				$values[ $field[ 'name' ] ] = array_flip( array_intersect_key( array_flip( $values[ $field[ 'name' ] ] ), $field[ 'options' ] ) );
			}

			switch ( $field[ 'type' ] ) {
				case 'group':
					$form->addGroup( $field[ 'label' ] )
					     ->setOption( 'embedNext', true );

					break;


				// container 里面也会有各种各样的表单类型, 怎么渲染, 基本表单类型单独提取为一个方法?
				case 'container':
					$container = $form->addContainer( $field[ 'name' ] );

					foreach ( $field[ 'options' ] as $option ) {
						$container->addText( $option[ 'name' ], $option[ 'label' ] )
						          ->setDefaultValue( $values[ $field[ 'name' ] ]->$option[ 'name' ] );
					}

					break;

				case 'text':
					$form->addText( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setAttribute( 'placeholder', $field[ 'placeholder' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] )
					     ->setOption( 'description', Html::el()
					                                     ->setHtml( '<a class="add-row button" href="#">添加</a> <a class="button remove-row" href="#">删除</a>' ) );


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

				case 'multi-checkbox':
					$form->addCheckboxList( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'radio':
					$form->addRadioList( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				case 'select':
					$form->addSelect( $field[ 'name' ], $field[ 'label' ], $field[ 'options' ] )
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
					$form->addText( $field[ 'name' ], $field[ 'label' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] )
					     ->setOption( 'description', Html::el()
					                                     ->setHtml( '<a class="wizhi_upload_button button" href="#">选择</a>' ) );
					break;

				case 'multi-upload':
					$form->addMultiUpload( $field[ 'name' ], $field[ 'label' ] );
					break;

				case 'hidden':
					$form->addHidden( $field[ 'name' ], $field[ 'label' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;

				default:
					$form->addText( $field[ 'name' ], $field[ 'label' ] )
					     ->setAttribute( 'size', $field[ 'size' ] )
					     ->setDefaultValue( $values[ $field[ 'name' ] ] );
					break;
			}

		}

		return $form;

	}


	/**
	 * 验证表单提交的数据
	 *
	 * todo: 添加验证规则, 同时添加前端验证规则
	 */
	public function validate() {

		$form = $this->build();

		if ( $form->isSuccess() ) {
			return true;
		}

		return false;

	}


	/**
	 * 只显示表单项目, 没有表单提交按钮
	 */
	public function display() {

		$form = $this->build();

		$form = str_replace( '<form action="" method="post">', '', $form );
		$form = str_replace( '</form>', '', $form );
		$form = str_replace( '</fieldset>', '', $form );
		$form = str_replace( '</table>', '</table></fieldset>', $form );

		echo $form;

	}


	/**
	 * 添加表单按钮, 并提交表单项目
	 */
	public function show() {

		$form = $this->build();
		$form->addSubmit( 'send', __( 'Save', 'wizhi' ) );

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

			// 无论哪种表单类型, 都要检查随机数
			if ( ! isset( $nonce ) || ! wp_verify_nonce( $nonce, 'wizhi_nonce' ) ) {
				return false;
			}

			// 循环保存所有数据
			foreach ( $fields as $field ) {

				if ( $field[ 'type' ] != 'group' ) {

					switch ( $form_type ) {

						case 'option':
							update_option( $field[ 'name' ], $values->$field[ 'name' ] );
							break;

						// 保存文章元数据, 保存之前检查权限和文章类型
						case 'post_meta':

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
							update_user_meta( $id, $field[ 'name' ], $values->$field[ 'name' ] );
							break;

						case 'term_meta':
							update_term_meta( $id, $field[ 'name' ], $values->$field[ 'name' ] );
							break;

						default:
							update_post_meta( $id, $field[ 'name' ], $values->$field[ 'name' ] );
					}

				}

			}

			return true;

		}

		return false;

	}


	/**
	 * 保存表单后的通知信息
	 */
	function notice() {

		$error = $this->save();

		if ( $error ) {
			$class   = 'notice notice-error';
			$message = __( 'Oah, Some errors occured', 'wizhi' );

			echo '<div class="' . $class . '"><p>' . $message . '</p></div>';
		}

	}

}