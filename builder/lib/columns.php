<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Sunra\PhpSimple\HtmlDomParser as Dom;


/**
 * 可视化页面生成器分栏
 */
class WizhiVisualBuilderColumns {

	protected $modalTabs = [ ];

	/**
	 * 挂载到 WordPress
	 *
	 * WizhiVisualBuilderColumns constructor.
	 */
	function __construct() {
		add_action( 'wp_head', [ $this, 'renderColumnStyles' ] );

		add_action( 'admin_head', [ $this, 'addColumnButton' ] );
		add_action( 'admin_head', [ $this, 'addModalVar' ] );

		add_action( 'admin_init', [ $this, 'addModalTabs' ] );
		add_action( 'admin_footer', [ $this, 'addColumnTemplates' ] );
		add_action( 'admin_footer', [ $this, 'addModalTabTemplates' ] );

		add_action( 'the_content', [ $this, 'cleanColumnOutput' ] );
		add_action( 'save_post', [ $this, 'rememberColumnStyles' ], 10, 3 );

		add_filter( 'wizhi_js_vars', [ $this, 'addModalTabVars' ] );
		add_filter( 'wizhi_toolbar_buttons', [ $this, 'addColumnToolbarButtons' ], 1 );
	}


	/**
	 * 表格只是在编辑器用使用, 在前端需要转换成 div
	 *
	 * @param    $content string 需要在前端输出的内容
	 *
	 * @return    string 修改后的内容
	 */
	public function cleanColumnOutput( $content ) {
		$parsed = $this->parseColumnContent( $content );

		return $parsed[ 'content' ];
	}


	/**
	 * 封装 simple_html_dom 的 str_get_html 方法
	 *
	 * @param $str
	 *
	 * @return mixed
	 */
	public function str_get_html( $str ) {
		return Dom::str_get_html( $str, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = false );
	}


	/**
	 * 解析 HTML 内容, 修复分栏, 转换表格为 div, 分离 margin
	 *
	 * @param    $content string 需要在前端输出的内容
	 *
	 * @return    string The modified content
	 */
	public function parseColumnContent( $content ) {
		// 没有内容时, simple_html_dom 会报错
		$contentChecker = trim( $content );
		if ( empty( $contentChecker ) ) {
			return [
				'content' => $content,
				'styles'  => '',
			];
		}

		$columnStyles = '';

		// 移除没用的 jQuery sortable classes
		$html = preg_replace( '/(ui-sortable-handle|ui-sortable)/', '', $content );
		$html = $this->str_get_html( $html );

		// 搜索分栏表格
		$tables = $html->find( 'table.wizhi_column' );
		$hashes = [ ];
		while ( count( $tables ) > 0 ) {
			$tr = $html->find( 'table.wizhi_column', 0 )
			           ->find( 'tr', 0 );

			$newDivs   = '';
			$styleDump = '';

			// 获取表格样式
			$tableStyles = $html->find( 'table.wizhi_column', 0 )->style;

			// 移除没有任何效果的编辑器样式

			// width: 100%; height: auto; border: none;
			$tableStyles = trim( preg_replace( '/(^|\s)width:[^;]+;?\s?/', '', $tableStyles ) );
			$tableStyles = trim( preg_replace( '/(^|\s)height:[^;]+;?\s?/', '', $tableStyles ) );
			$tableStyles = trim( preg_replace( '/(^|\s)border:\s?none;?\s?/', '', $tableStyles ) );

			// 获取分栏样式, 因为暂时没有 唯一 ID, 使用占位符作为 ID
			if ( ! empty( $tableStyles ) ) {
				$columnStyles .= '.wizhi.wizhi_column_%' . ( count( $hashes ) + 1 ) . '$s { ' . wp_kses( $tableStyles, [ ], [ ] ) . ' }';
			}
			$styleDump .= esc_attr( $tableStyles );


			// 循环替换表格里面的内容
			foreach ( $tr->children() as $key => $td ) {
				if ( $td->tag != 'td' ) {
					continue;
				}

				$innerHTML = trim( $td->innertext );

				// 只在没有时添加段落标签, 这能确保空格是正确的
				// @see http://www.htmlhelp.com/reference/html40/inline.html
				if ( preg_match( '/^<(a|abbr|acronym|b|bdo|big|br|cite|code|dfn|em|i|img|input|kbd|label|q|samp|select|small|span|strong|sub|sup|textarea|tt|var|button|del|ins|map|object|script)[^>]+>/', $innerHTML ) === 1 ) {
					$innerHTML = '<p>' . $innerHTML . '</p>';
				} else if ( preg_match( '/^</', $innerHTML ) === 0 ) {
					$innerHTML = '<p>' . $innerHTML . '</p>';
				}

				// 移除空白字符
				$innerHTML = preg_replace( '/\sclass=[\'"]\s*[\'"]/', '', $innerHTML );

				// 清理结尾
				$innerHTML = trim( $innerHTML );

				// 移除宽度, 使用 class 代替
				$columnStyle = trim( preg_replace( '/(^|\s)width:[^;]+;\s?/', '', $td->style ) );

				// 获取分栏样式, 因为暂时没有 唯一 ID, 使用占位符作为 ID
				if ( ! empty( $columnStyle ) ) {
					$columnStyles .= '.wizhi.wizhi_column_%' . ( count( $hashes ) + 1 ) . '$s > div > div:nth-of-type(' . ( $key + 1 ) . ') { ' . wp_kses( $columnStyle, [ ], [ ] ) . ' }';
				}
				$styleDump .= esc_attr( $td->style );

				// 获取所有分栏数据属性
				$dataAttributes = '';
				foreach ( $td->getAllAttributes() as $key => $value ) {
					if ( stripos( $key, 'data-' ) !== 0 || strlen( $value ) == '' ) {
						continue;
					}
					if ( $key == 'data-wp-columnselect' ) { // 这是一个伪造的属性
						continue;
					}
					$dataAttributes .= ' ' . $key . '="' . esc_attr( $value ) . '"';
				}

				$newDivs .= '<div class="' . esc_attr( $td->class ) . '" ' . $dataAttributes . '>' . $innerHTML . '</div>';
			}

			// 使用 crc32 根据分栏的 margin 规则生成唯一 ID
			$hash     = crc32( $styleDump );
			$hashes[] = $hash;

			/**
			 * Build our converted <table>
			 */
			// Our main class
			$tableClasses = [ 'wizhi' ];
			// Carry over custom classes
			$tableClasses[] = $html->find( 'table.wizhi_column', 0 )->class;
			// Custom styles class
			if ( ! empty( $columnStyles ) ) {
				$tableClasses[] = 'wizhi_column_' . $hash;
			}

			// 获取所有row/table 数据属性
			$dataAttributes = '';
			foreach (
				$html->find( 'table.wizhi_column', 0 )
				     ->getAllAttributes() as $key => $value
			) {
				if ( stripos( $key, 'data-' ) !== 0 || strlen( $value ) == '' ) {
					continue;
				}
				$dataAttributes .= ' ' . $key . '="' . esc_attr( $value ) . '"';
			}

			// 创建真实的行 div
			$newDivs = '<div class="' . esc_attr( join( ' ', $tableClasses ) ) . '" ' . $dataAttributes . '><div class="pure-g row">' . $newDivs . '</div></div>';

			$html->find( 'table.wizhi_column', 0 )->outertext = $newDivs;

			// 保存替换 table 后的 html
			$html = $html->save();
			$html = $this->str_get_html( $html );

			// 继续处理下一个table
			$tables = $html->find( 'table.wizhi_column' );
		}

		// Sanitize the output for security
		$columnStyles = wp_kses( $columnStyles, [ ], [ ] );
		// Make sure our html entities are correct to make our rules work properly
		$columnStyles = html_entity_decode( $columnStyles );

		// 插入#号
		foreach ( $hashes as $key => $hash ) {
			$columnStyles = str_replace( '%' . ( $key + 1 ) . '$s', $hash, $columnStyles );
		}

		// 移除空行
		$newHTML = (string) $html;

		// Remove weirdly appended paragraph tags (they are there for some odd reason)
		$newHTML = preg_replace( '/(<p[^>]*>)([^<]*$)/', '$2', $newHTML );

		// Sometimes, our rows/columns get tangled up inside paragraphs, get rid of those
		$newHTML = preg_replace( '/<p[^>]*>\s*(<div)/', '$1', $newHTML );
		$newHTML = preg_replace( '/(<\/div>)\s*<\/p>/', '$1', $newHTML );

		// Since we're dealing with wrong paragraph tags, remove other wrong stuff such as
		// multiple end paragraphs & multiple start paragraphs
		$newHTML = preg_replace( '/(<\/p>)\s*<\/p>/', '$1', $newHTML );
		$newHTML = preg_replace( '/<p[^>]+>\s*(<p[^>]+>)/', '$1', $newHTML );

		return [
			'content' => $newHTML,
			'styles'  => $columnStyles,
		];
	}


	/**
	 * 收集分栏样式, 保存为文章字段, 输出的 wp_head, 而不是把样式保存的分栏, 比较稳定
	 *
	 * @param $postID int 文章 id
	 * @param $post
	 * @param $update
	 *
	 * @return    string 修改后的内容
	 */
	public function rememberColumnStyles( $postID, $post, $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( empty( $_POST[ 'content' ] ) ) {
			return;
		}
		if ( get_post_status( $postID ) === 'trash' ) {
			return;
		}

		// 如果是预览状态, 用不同的方式保存, 以避免覆盖正式内容
		$suffix = '';
		if ( ! empty( $_POST[ 'wp-preview' ] ) ) {
			if ( $_POST[ 'wp-preview' ] == 'dopreview' ) {
				$suffix = '_preview';
			}
		}

		// 生成样式, 保存文章字段
		$parsed = $this->parseColumnContent( stripslashes( $_POST[ 'content' ] ) );
		update_post_meta( $postID, 'wizhi_styles' . $suffix, $parsed[ 'styles' ] );
	}

	/**
	 * 添加分栏按钮到编辑器
	 *
	 * @return    void
	 */
	public function addColumnButton() {

		// 检查用户权限
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// 检查是否启用了可视化编辑器
		add_filter( 'mce_buttons', [ $this, 'registerTinyMCEButton' ] );

		$columnVars = [
			'wp_version'            => get_bloginfo( 'version' ),
			'dummy_content'         => __( 'Column content', 'wizhi' ),
			'modal_title'           => __( 'Column', 'wizhi' ),
			'modal_description'     => __( 'Enter a composition here of column ratios separated by spaces.<br>Make sure the ratios sum up to 1.<br>For example:', 'wizhi' ),
			'custom_columns'        => __( 'Custom columns', 'wizhi' ),
			'column_1'              => sprintf( __( '%s column', 'wizhi' ), 1 ),
			'column_2'              => sprintf( __( '%s column', 'wizhi' ), 2 ),
			'column_3'              => sprintf( __( '%s column', 'wizhi' ), 3 ),
			'column_4'              => sprintf( __( '%s column', 'wizhi' ), 4 ),
			'column_1323'           => sprintf( __( '%s column', 'wizhi' ), '1/3 + 2/3' ),
			'column_2313'           => sprintf( __( '%s column', 'wizhi' ), '2/3 + 1/3' ),
			'column_141214'         => sprintf( __( '%s column', 'wizhi' ), '1/4 + 1/2 + 1/4' ),
			'delete'                => __( 'Delete', 'wizhi' ),
			'edit'                  => __( 'Edit', 'wizhi' ),
			'change_column'         => __( 'Change column', 'wizhi' ),
			'clone'                 => __( 'Clone', 'wizhi' ),
			'change_columns'        => __( 'Change columns', 'wizhi' ),
			'cancel'                => __( 'Cancel', 'wizhi' ),
			'preset'                => __( 'Preset', 'wizhi' ),
			'preset_desc'           => __( 'Modify column count below:', 'wizhi' ),
			'use_custom'            => __( 'Use custom', 'wizhi' ),
			'custom'                => __( 'Custom', 'wizhi' ),
			'non_sortable_elements' => $this->formNonSortableElements(),
			'clone_row'             => __( 'Clone row', 'wizhi' ),
			'delete_row'            => __( 'Delete row', 'wizhi' ),
			'edit_row'              => __( 'Edit row', 'wizhi' ),
			'edit_area'             => __( 'Edit area', 'wizhi' ),
			'clone_area'            => __( 'Clone area', 'wizhi' ),
			'delete_area'           => __( 'Delete area', 'wizhi' ),
			'column'                => __( 'Column', 'wizhi' ),
			'row'                   => __( 'Row', 'wizhi' ),

			// 分栏编辑弹窗
			'column_settings'       => __( 'Column Settings', 'wizhi' ),
			'styles'                => __( 'Styles', 'wizhi' ),
			'style'                 => __( 'Style', 'wizhi' ),
			'border'                => __( 'Border', 'wizhi' ),
			'padding'               => __( 'Padding', 'wizhi' ),
			'none'                  => __( 'None', 'wizhi' ),
			'dotted'                => __( 'Dotted', 'wizhi' ),
			'dashed'                => __( 'Dashed', 'wizhi' ),
			'solid'                 => __( 'Solid', 'wizhi' ),
			'double'                => __( 'Double', 'wizhi' ),
			'groove'                => __( 'Groove', 'wizhi' ),
			'ridge'                 => __( 'Ridge', 'wizhi' ),
			'inset'                 => __( 'Inset', 'wizhi' ),
			'outset'                => __( 'Outset', 'wizhi' ),
			'color'                 => __( 'Color', 'wizhi' ),
			'radius'                => __( 'Radius', 'wizhi' ),

			// 背景设置
			'background'            => __( 'Background', 'wizhi' ),
			'image'                 => __( 'Image', 'wizhi' ),
			'size'                  => __( 'Size', 'wizhi' ),
			'inherit'               => __( 'Inherit', 'wizhi' ),
			'cover'                 => __( 'Cover', 'wizhi' ),
			'contain'               => __( 'Contain', 'wizhi' ),
			'repeat'                => __( 'Repeat', 'wizhi' ),
			'repeatx'               => __( 'Repeat-x', 'wizhi' ),
			'repeaty'               => __( 'Repeat-y', 'wizhi' ),
			'norepeat'              => __( 'No-repeat', 'wizhi' ),
			'round'                 => __( 'Round', 'wizhi' ),
			'space'                 => __( 'Space', 'wizhi' ),
			'position'              => __( 'Position', 'wizhi' ),
			'margin'                => __( 'Margin', 'wizhi' ),
			'row_settings'          => __( 'Row settings', 'wizhi' ),

			// 全宽行
			'full_width'            => __( 'Full-width', 'wizhi' ),
			'full_width_normal'     => __( 'Do not break out into full width', 'wizhi' ),
			'full_width_1'          => sprintf( __( 'Break out of %s container', 'wizhi' ), '1' ),
			'full_width_2'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '2' ),
			'full_width_3'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '3' ),
			'full_width_4'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '4' ),
			'full_width_5'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '5' ),
			'full_width_6'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '6' ),
			'full_width_7'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '7' ),
			'full_width_8'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '8' ),
			'full_width_9'          => sprintf( __( 'Break out of %s containers', 'wizhi' ), '9' ),
			'full_width_99'         => __( 'Break out of all containers', 'wizhi' ),
			'full_width_desc'       => __( 'Rows are restricted to the content areas defined by your theme. You can use this to break out of the constraint and turn your row into a full width row', 'wizhi' ),


			'modal_tabs' => [ ],

		];

		$columnVars = apply_filters( 'wizhi_column_vars', $columnVars );
		$columnVars = apply_filters( 'wizhi_js_vars', $columnVars );

		// 打印参数
		?>
		<script type="text/javascript">
			var wizhi_column = <?php echo json_encode( $columnVars ) ?>;
		</script>
		<?php
	}

	/**
	 * 点击下面的元素时, 不要拖拽
	 *
	 * @return    void
	 */
	protected function formNonSortableElements() {
		// 点击下面的元素时, 不要拖拽
		$nonSortableElements = 'p,code,blockquote,span,pre,td:not(.wizhi_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img';
		$nonSortableElements = apply_filters( 'sc_non_sortable_elements', $nonSortableElements );

		// 允许拖拽所有内容
		$nonSortableElementsArray = explode( ',', $nonSortableElements );
		$nonSortableElements      = '';
		foreach ( $nonSortableElementsArray as $key => $element ) {
			if ( $key > 0 ) {
				$nonSortableElements .= ',';
			}
			$nonSortableElements .= $element . ':not(.wpview-wrap ' . $element . ')';
		}

		// 添加工具条元素
		$nonSortableElements .= empty( $nonSortableElements ) ? '' : ',';
		$nonSortableElements .= '#wp-column-toolbar,.toolbar,.toolbar .dashicons';

		return $nonSortableElements;
	}

	/**
	 * 在编辑器中注册分栏
	 *
	 * @param    $buttons array Existing TinyMCE buttons
	 *
	 * @return    array An array of TinyMCE buttons
	 */
	public function registerTinyMCEButton( $buttons ) {
		array_push( $buttons, 'wizhi_column' );

		return $buttons;
	}

	/**
	 * 获取在页面中保存的分栏样式, 发布文章时保存为文章字段
	 *
	 * @return    void
	 */
	public function renderColumnStyles() {
		global $post;
		if ( empty( $post ) ) {
			return;
		}

		// 2 sets of styles are saved, preview & published, get what we need
		$suffix = '';
		if ( is_preview() ) {
			$suffix = '_preview';
		}

		$styles = trim( get_post_meta( $post->ID, 'wizhi_styles' . $suffix, true ) );
		if ( empty( $styles ) ) {
			return;
		}

		echo '<style id="wizhi_column">' . $styles . '</style>';
	}

	/**
	 * 添加分栏模板文件
	 *
	 * @return    void
	 * @since    0.11
	 */
	public function addColumnTemplates() {
		// check user permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		include_once WIZHI_CMS . "builder/lib/templates/column-change-modal.php";
		include_once WIZHI_CMS . "builder/lib/templates/column-custom-modal-description.php";
		include_once WIZHI_CMS . "builder/lib/templates/column-area-edit-modal.php";
		include_once WIZHI_CMS . "builder/lib/templates/column-row-edit-modal.php";

	}


	/**
	 * 添加工具条按钮
	 *
	 * @param $toolbarButtons
	 *
	 * @return array 工具条按钮数组
	 */
	public function addColumnToolbarButtons( $toolbarButtons ) {

		$toolbarButtons[] = [
			'label'     => __( 'Column', 'wizhi' ),
			'shortcode' => 'column',
			'priority'  => 1001,
		];
		$toolbarButtons[] = [
			'action'    => 'column-edit-area',
			'icon'      => 'dashicons dashicons-edit',
			'label'     => __( 'Edit column', 'wizhi' ),
			'shortcode' => 'column',
			'priority'  => 1002,
		];
		$toolbarButtons[] = [
			'action'    => 'column-clone-area',
			'icon'      => 'dashicons dashicons-images-alt',
			'label'     => __( 'Clone column', 'wizhi' ),
			'shortcode' => 'column',
			'priority'  => 1003,
		];
		$toolbarButtons[] = [
			'action'    => 'column-remove-area',
			'icon'      => 'dashicons dashicons-no-alt',
			'label'     => __( 'Delete column', 'wizhi' ),
			'shortcode' => 'column',
			'priority'  => 1004,
		];
		$toolbarButtons[] = [
			'label'     => '|',
			'shortcode' => 'column',
			'priority'  => 1005,
		];

		$toolbarButtons[] = [
			'label'     => __( 'Row', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1100,
		];
		// Add align left button
		$toolbarButtons[] = [
			'action'    => 'row-align-left',
			'icon'      => 'dashicons dashicons-align-left',
			'label'     => __( 'Align left', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1101,
		];
		// Add align center button
		$toolbarButtons[] = [
			'action'    => 'row-align-center',
			'icon'      => 'dashicons dashicons-align-center',
			'label'     => __( 'Align center', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1102,
		];
		// Add align right button
		$toolbarButtons[] = [
			'action'    => 'row-align-right',
			'icon'      => 'dashicons dashicons-align-right',
			'label'     => __( 'Align right', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1103,
		];
		// Add align none button
		$toolbarButtons[] = [
			'action'    => 'row-align-none',
			'icon'      => 'dashicons dashicons-align-none',
			'label'     => __( 'No align', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1104,
		];
		$toolbarButtons[] = [
			'label'     => '|',
			'shortcode' => 'row',
			'priority'  => 1105,
		];
		$toolbarButtons[] = [
			'action'    => 'column-edit-row',
			'icon'      => 'dashicons dashicons-edit',
			'label'     => __( 'Edit row', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1106,
		];
		$toolbarButtons[] = [
			'action'    => 'column-columns',
			'icon'      => 'dashicons dashicons-tagcloud',
			'label'     => __( 'Edit column', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1107,
		];
		$toolbarButtons[] = [
			'action'    => 'column-clone-row',
			'icon'      => 'dashicons dashicons-images-alt',
			'label'     => __( 'Clone row', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1108,
		];
		$toolbarButtons[] = [
			'action'    => 'column-remove-row',
			'icon'      => 'dashicons dashicons-no-alt',
			'label'     => __( 'Delete row', 'wizhi' ),
			'shortcode' => 'row',
			'priority'  => 1109,
		];

		return $toolbarButtons;
	}


	/**
	 * 添加弹窗变量
	 */
	public function addModalVar() {

		// 检查用户权限
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// 打印参数
		?>
		<script type="text/javascript">
			var wizhi_modal_fields = {};
		</script>
		<?php
	}


	/**
	 * 添加弹窗标签
	 */
	public function addModalTabs() {
		$this->modalTabs = apply_filters( 'wizhi_modal_tabs', [ ] );

		foreach ( $this->modalTabs as $key => $tab ) {
			$defaults                = [
				'template'    => '',
				'template_id' => '',
				'name'        => '',
				'shortcode'   => 'row',
			];
			$this->modalTabs[ $key ] = array_merge( $defaults, $tab );
		}
	}


	/**
	 * 添加弹窗标签模板
	 */
	public function addModalTabTemplates() {
		// 检查用户权限
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		foreach ( $this->modalTabs as $key => $tab ) {
			if ( ! empty( $tab[ 'template' ] ) ) {
				include_once $tab[ 'template' ];
			}
		}
	}


	/**
	 * 添加弹窗标签变量
	 *
	 * @param $columnVars
	 *
	 * @return mixed
	 */
	public function addModalTabVars( $columnVars ) {
		if ( empty( $this->modalTabs ) ) {
			return $columnVars;
		}

		$varsToOutput = [ ];
		foreach ( $this->modalTabs as $tab ) {
			// 安全起见, 不要包含模板路径
			unset( $tab[ 'template' ] );
			$varsToOutput[] = $tab;
		}

		$columnVars[ 'modal_tabs' ] = $varsToOutput;

		return $columnVars;
	}

}

new WizhiVisualBuilderColumns();