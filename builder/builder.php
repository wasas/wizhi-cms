<?php

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}

// 定义版本
defined( 'PBS_VERSION' ) or define( 'PBS_VERSION', '1.1.1' );

// 定义路径常亮
defined( 'PBS_PATH' ) or define( 'PBS_PATH', trailingslashit( dirname( __FILE__ ) ) );
defined( 'PBS_URL' ) or define( 'PBS_URL', plugin_dir_url( __FILE__ ) );
defined( 'PBS_FILE' ) or define( 'PBS_FILE', __FILE__ );

// 加载插件依赖文件
require_once( PBS_PATH . 'lib/columns.php' );
require_once( PBS_PATH . 'lib/functions.php' );
require_once( PBS_PATH . 'lib/toolbar.php' );


/**
 * PB Sandwich Class
 */
class GambitPBSandwich {

    /**
     * GambitPBSandwich 构造器, 挂载到 WordPress
     */
    function __construct() {
        add_action( 'plugins_loaded', [ $this, 'loadTextDomain' ] );
        add_action( 'admin_init', [ $this, 'addEditorStyles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'loadAdminScripts' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'loadFrontendScripts' ] );
        add_filter( 'tiny_mce_before_init', [ $this, 'addSandwichBootstrap' ] );
        add_action( 'init', [ $this, 'loadShortcake' ], 1 );
        add_action( 'media_buttons', [ $this, 'addShortcodeButton' ], 100 );
        add_action( 'admin_head', [ $this, 'addSandwichPlugin' ] );
        add_filter( 'mce_buttons', [ $this, 'addPageBreakButton' ] );
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
     * 加载翻译文件
     *
     * @return    void
     */
    public function loadTextDomain() {
        load_plugin_textdomain( 'pbsandwich', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    /**
     * 为 "table" 分栏添加样式
     *
     * @return    void
     */
    public function addEditorStyles() {
        add_editor_style( plugins_url( 'css/editor.css', __FILE__ ) );
    }

    public function loadFrontendScripts() {
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_style( 'pbsandwich', plugins_url( 'css/frontend.css', __FILE__ ), [ ], PBS_VERSION );
        wp_enqueue_script( 'pbsandwich', plugins_url( 'js/min/frontend-min.js', __FILE__ ), [ 'jquery' ], PBS_VERSION );
    }

    /**
     * 添加分栏按钮样式
     *
     * @return    void
     */
    public function loadAdminScripts() {
        wp_enqueue_style( 'pbsandwich-admin', plugins_url( 'css/admin.css', __FILE__ ) );

        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'pbsandwich-admin', plugins_url( 'js/min/admin-min.js', __FILE__ ), [ 'jquery' ], PBS_VERSION );
    }

    /**
     * 添加分栏按钮到可视化编辑器工具栏
     *
     * @return    void
     */
    public function addSandwichPlugin() {

        // check user permissions
        if( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }

        add_filter( 'mce_external_plugins', [ $this, 'addTinyMCEPlugin' ] );
    }

    /**
     * 添加分栏插件到可视化编辑器A
     *
     * @param    array $pluginArray An array of TinyMCE plugins
     *
     * @return    array An array of TinyMCE plugins
     */
    public function addTinyMCEPlugin( $pluginArray ) {
        $pluginArray[ 'pbsandwich' ] = plugins_url( 'js/min/editor-min.js', __FILE__ );

        return $pluginArray;
    }

    /**
     * 如果不可用, 加载插件自带的 Shortcake UI
     *
     * @return    void
     */
    public function loadShortcake() {
        // Don't do anything when we're activating a plugin to prevent errors
        // on redeclaring Shortcake classes
        if( is_admin() ) {
            if( !empty( $_GET[ 'action' ] ) && !empty( $_GET[ 'plugin' ] ) ) {
                if( $_GET[ 'action' ] == 'activate' ) {
                    return;
                }
            }
        }


    }

    public function addSandwichBootstrap( $init ) {
        $init[ 'body_class' ] = 'sandwich';

        return $init;
    }

    public function addShortcodeButton() {
         if( apply_filters( 'pbs_add_shortcode_button', true ) ) {
             echo '<a href="#" class="button sandwich-add-shortcode"><span class="dashicons dashicons-plus st_add_content"></span>' . __( '添加内容', 'pbsandwich' ) . '</a>';
         }
    }


    /**
     * 添加分页按钮
     *
     * @param $mceButtons array 现有的 TinyMCE 按钮
     *
     * @return array TinyMCE 按钮数组
     * @see http://wpsites.net/wordpress-admin/how-to-add-next-page-links-in-posts-pages/3/
     */
    public function addPageBreakButton( $mceButtons ) {
        // 如果已存在了, 就不需要再加载了
        $pos = array_search( 'wp_page', $mceButtons, true );
        if( $pos !== false ) {
            return;
        }

        // 添加分页按钮
        $pos = array_search( 'wp_more', $mceButtons, true );
        if( $pos !== false ) {
            $tmpButtons = array_slice( $mceButtons, 0, $pos + 1 );
            $tmpButtons[] = 'wp_page';
            $mceButtons = array_merge( $tmpButtons, array_slice( $mceButtons, $pos + 1 ) );
        }

        return $mceButtons;
    }

}

new GambitPBSandwich();
