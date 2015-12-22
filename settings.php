<?php

add_action( 'admin_init', 'wizhi_cms_admin_init' );
add_action('admin_menu', 'wizhi_cms_menu');

function wizhi_cms_menu() {
    $page_hook_suffix = add_options_page(
        'CMS设置',
        'CMS设置',
        'manage_options',
        'wizhi-cms.php',
        'wizhi_cms_management_page'
    );

    add_action('admin_print_scripts-' . $page_hook_suffix, 'wizhi_cms_admin_scripts');
    add_action( 'admin_print_styles-' . $page_hook_suffix, 'wizhi_cms_admin_styles' );
}

// 加载js和样式
function wizhi_cms_admin_init() {
    wp_register_style( 'wizhi-cms-style', plugins_url('assets/style.css', __FILE__) );
    wp_register_script( 'wizhi-cms-script', plugins_url( 'assets/script.js', __FILE__ ) );
}

// 挂载插件js
function wizhi_cms_admin_scripts() {
    wp_enqueue_script( 'wizhi-cms-script' );
}

// 挂载插件样式
function wizhi_cms_admin_styles() {
   wp_enqueue_style( 'wizhi-cms-style' );
}

/**
 * 添加验证页面到后台
 */
function wizhi_cms_management_page() {

    echo '<div class="wrap">';
    echo '<h2>Wizhi CMS 插件设置</h2>';

    /*** 存设置选项到数据库 ***/
    if (isset($_POST['save_filters'])) :

        // 获取选项数据
        $wizhi_use_cms_front = isset( $_POST["wizhi_use_cms_front"] ) ? $_POST["wizhi_use_cms_front"] : '';

        // 保存设置选项到数据库
        update_option('wizhi_use_cms_front', $wizhi_use_cms_front);

        echo '<div class="updated"><p>恭喜！保存成功。</p></div>';

    endif;

    ?>

    <form action="" method="post">

        <table class="form-table">

            <tr>
                <th scope="row"><label>内置前端样式</label></th>
                <td>
                    <label>
                        <input type="checkbox" name="wizhi_use_cms_front" value="1" <?php echo ( get_option('wizhi_use_cms_front') ) ? 'checked' : ''; ?>>
                        使用插件自带的CSS和JS
                    </label> <br/>
                </td>
            </tr>

        </table>

        <p class="submit">
            <input type="submit" name="save_filters" value="保存" class="button-primary" />
        </p>

    </form>

    <?php

    echo '</div>';
}

?>
