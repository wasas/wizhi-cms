<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 加载插件依赖文件
require_once( WIZHI_CMS . 'builder/lib/columns.php' );
require_once( WIZHI_CMS . 'builder/lib/toolbar.php' );


/**
 * 可视化页面生成器
 */
class WizhiVisualBuilder {

	/**
	 * WizhiVisualBuilder 构造器, 挂载到 WordPress
	 */
	function __construct() {
		add_action( 'admin_init', [ $this, 'addEditorStyles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'loadAdminScripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'loadFrontendScripts' ] );
		add_filter( 'tiny_mce_before_init', [ $this, 'addWizhiBootstrap' ] );
		add_action( 'admin_head', [ $this, 'addWizhiPlugin' ] );
		add_filter( 'mce_buttons', [ $this, 'addPageBreakButton' ] );

		if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			add_action( 'media_buttons', [ $this, 'addShortcodeButton' ], 100 );
		}
	}


	public static function printDisabledShortcakeStlyes( $shortcode, $label ) {
		?>
		<style>
			.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"]:after {
				content: "<?php echo addslashes( $label ) ?>";
				top: 50%;
				position: absolute;
				text-align: center;
				transform: translateY(-50%);
				background: rgba(255, 255, 255, .8);
				padding: .3em .5em;
				font-style: italic;
				left: 0;
			}

			.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"] {
				box-shadow: none;
				pointer-events: none;
			}

			.add-shortcode-list .shortcode-list-item[data-shortcode="<?php echo $shortcode ?>"] > * {
				opacity: .3;
			}
		</style>
		<?php
	}


	/**
	 * 为 "table" 分栏添加样式
	 *
	 * @return    void
	 */
	public function addEditorStyles() {
		add_editor_style( plugins_url( 'css/editor.css', __FILE__ ) );
		add_editor_style( plugins_url( '../front/dist/styles/main.css', __FILE__ ) );
	}


	/**
	 * 加载前端脚本文件
	 */
	public function loadFrontendScripts() {
		wp_enqueue_script( 'wizhi', plugins_url( 'js/frontend.js', __FILE__ ), [ 'jquery' ], WIZHI_CMS_VERSION );
	}


	/**
	 * 添加管理界面按钮样式
	 *
	 * @return    void
	 */
	public function loadAdminScripts() {
		wp_enqueue_style( 'wizhi-admin', plugins_url( 'css/admin.css', __FILE__ ) );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wizhi-admin', plugins_url( 'js/admin.js', __FILE__ ), [ 'jquery' ], WIZHI_CMS_VERSION );
	}


	/**
	 * 添加分栏按钮到可视化编辑器工具栏
	 *
	 * @return    void
	 */
	public function addWizhiPlugin() {

		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		add_filter( 'mce_external_plugins', [ $this, 'addTinyMCEPlugin' ] );
	}


	/**
	 * 添加JavaScript脚本到可视化编辑器
	 *
	 * @param    array $pluginArray An array of TinyMCE plugins
	 *
	 * @return    array An array of TinyMCE plugins
	 */
	public function addTinyMCEPlugin( $pluginArray ) {
		$pluginArray[ 'wizhi' ] = plugins_url( 'js/editor.js', __FILE__ );

		return $pluginArray;
	}


	/**
	 * @param 添加 “wizhi” 类名到编辑器 body
	 *
	 * @return mixed
	 */
	public function addWizhiBootstrap( $init ) {
		$init[ 'body_class' ] = 'wizhi wizhi-editer typo';

		return $init;
	}


	/**
	 * 添加按钮到“添加媒体”后面
	 */
	public function addShortcodeButton() {
		if ( apply_filters( 'wizhi_add_shortcode_button', true ) ) {
			echo '<a href="#" class="button wizhi-add-shortcode"><span class="dashicons dashicons-plus st_add_content"></span>' . __( 'Add content', 'wizhi' ) . '</a>';
		}
	}


	/**
	 * 添加分页按钮
	 *
	 * @param $mceButtons array 现有的 TinyMCE 按钮
	 *
	 * @return array TinyMCE 按钮数组
	 */
	public function addPageBreakButton( $mceButtons ) {
		// 如果已存在了, 就不需要再加载了
		$pos = array_search( 'wp_page', $mceButtons, true );
		if ( $pos !== false ) {
			return;
		}

		// 添加分页按钮
		$pos = array_search( 'wp_more', $mceButtons, true );
		if ( $pos !== false ) {
			$tmpButtons   = array_slice( $mceButtons, 0, $pos + 1 );
			$tmpButtons[] = 'wp_page';
			$mceButtons   = array_merge( $tmpButtons, array_slice( $mceButtons, $pos + 1 ) );
		}

		return $mceButtons;
	}

}

new WizhiVisualBuilder();
