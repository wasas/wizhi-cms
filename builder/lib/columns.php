<?php
    /**
     * Sandwich Columns
     */

// Exit if accessed directly
    if (!defined('ABSPATH')) exit;


    /**
     * PB Sandwich Column Class
     */
    class GambitPBSandwichColumns
    {

        protected $modalTabs = array();


        /**
         * Hook onto WordPress
         *
         * @return    void
         */
        function __construct() {
            add_action('the_content', array($this, 'cleanColumnOutput'));
            add_action('admin_head', array($this, 'addColumnButton'));
            add_action('save_post', array($this, 'rememberColumnStyles'), 10, 3);
            add_action('wp_head', array($this, 'renderColumnStyles'));
            add_action('admin_footer', array($this, 'addColumnTemplates'));
            add_filter('pbs_toolbar_buttons', array($this, 'addColumnToolbarButtons'), 1);

            add_action('admin_head', array($this, 'addModalVar'));
            add_action('admin_init', array($this, 'addModalTabs'));
            add_action('admin_footer', array($this, 'addModalTabTemplates'));
            add_filter('pbs_js_vars', array($this, 'addModalTabVars'));
        }


        /**
         * Since we are essentially creating tables in the visual composer, we should convert these tables
         * into divs for the frontend
         *
         * @param    $content string The content being outputted in the frontend
         *
         * @return    string The modified content
         */
        public function cleanColumnOutput($content) {
            $parsed = $this->parseColumnContent($content);

            return $parsed['content'];
        }


        /**
         * Instead of retaining the inline styles of the columns, gather the styles upon saving and
         * save it as post meta, we will use that in `wp_head` to render the styles
         *
         * @param    $content string The content being outputted in the frontend
         *
         * @return    string The modified content
         */
        public function rememberColumnStyles($postID, $post, $update) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }
            if (empty($_POST['content'])) {
                return;
            }
            if (get_post_status($postID) === 'trash') {
                return;
            }

            // If the post is being previewed, save it differently so as not to overwrite
            // the currently saved styles
            $suffix = '';
            if (!empty($_POST['wp-preview'])) {
                if ($_POST['wp-preview'] == 'dopreview') {
                    $suffix = '_preview';
                }
            }

            // Generate the styles & save as post meta
            $parsed = $this->parseColumnContent(stripslashes($_POST['content']));
            update_post_meta($postID, 'pbsandwich_styles' . $suffix, $parsed['styles']);
        }


        /**
         * Adds our column button in the TinyMCE visual editor
         *
         * @return    void
         */
        public function addColumnButton() {

            // check user permissions
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            // check if WYSIWYG is enabled
            add_filter('mce_buttons', array($this, 'registerTinyMCEButton'));

            $columnVars = array(
                'wp_version'            => get_bloginfo('version'),
                'dummy_content'         => __('列内容', 'pbsandwich'),
                'modal_title'           => __('列', 'pbsandwich'),
                'modal_description'     => __('输入用每列的百分比,<br>确保这些数字的和为 1.<br>例如:', 'pbsandwich'),
                'custom_columns'        => __('自定义列', 'pbsandwich'),
                'column_1'              => sprintf(__('%s 列', 'pbsandwich'), 1),
                'column_2'              => sprintf(__('%s 列', 'pbsandwich'), 2),
                'column_3'              => sprintf(__('%s 列', 'pbsandwich'), 3),
                'column_4'              => sprintf(__('%s 列', 'pbsandwich'), 4),
                'column_1323'           => sprintf(__('%s 列', 'pbsandwich'), '1/3 + 2/3'),
                'column_2313'           => sprintf(__('%s 列', 'pbsandwich'), '2/3 + 1/3'),
                'column_141214'         => sprintf(__('%s 列', 'pbsandwich'), '1/4 + 1/2 + 1/4'),
                'delete'                => __('删除', 'pbsandwich'),
                'edit'                  => __('编辑', 'pbsandwich'),
                'change_column'         => __('修改列', 'pbsandwich'),
                'clone'                 => __('复制', 'pbsandwich'),
                'change_columns'        => __('修改列', 'pbsandwich'),
                'cancel'                => __('取消', 'pbsandwich'),
                'preset'                => __('预设', 'pbsandwich'),
                'preset_desc'           => __('You can change the number of columns below:', 'pbsandwich'),
                'use_custom'            => __('自定义', 'pbsandwich'),
                'custom'                => __('自定义', 'pbsandwich'),
                'non_sortable_elements' => $this->formNonSortableElements(),
                'clone_row'             => __('复制行', 'pbsandwich'),
                'delete_row'            => __('删除行', 'pbsandwich'),
                'edit_row'              => __('编辑行', 'pbsandwich'),
                'edit_area'             => __('编辑选区', 'pbsandwich'),
                'clone_area'            => __('复制选区', 'pbsandwich'),
                'delete_area'           => __('删除选区', 'pbsandwich'),
                'column'                => __('列', 'pbsandwich'),
                'row'                   => __('行', 'pbsandwich'),

                // Column edit modal
                'column_settings'       => __('列设置', 'pbsandwich'),
                'styles'                => __('样式', 'pbsandwich'),
                'style'                 => __('样式', 'pbsandwich'),
                'border'                => __('边框', 'pbsandwich'),
                'padding'               => __('内边距', 'pbsandwich'),
                'none'                  => __('None', 'pbsandwich'),
                'dotted'                => __('Dotted', 'pbsandwich'),
                'dashed'                => __('Dashed', 'pbsandwich'),
                'solid'                 => __('Solid', 'pbsandwich'),
                'double'                => __('Double', 'pbsandwich'),
                'groove'                => __('Groove', 'pbsandwich'),
                'ridge'                 => __('Ridge', 'pbsandwich'),
                'inset'                 => __('Inset', 'pbsandwich'),
                'outset'                => __('Outset', 'pbsandwich'),
                'color'                 => __('颜色', 'pbsandwich'),
                'radius'                => __('圆角', 'pbsandwich'),
                'background'            => __('背景', 'pbsandwich'),
                'image'                 => __('图片', 'pbsandwich'),
                'size'                  => __('尺寸', 'pbsandwich'),
                'inherit'               => __('Inherit', 'pbsandwich'),
                'cover'                 => __('Cover', 'pbsandwich'),
                'contain'               => __('Contain', 'pbsandwich'),
                'repeat'                => __('Repeat', 'pbsandwich'),
                'repeatx'               => __('Repeat-x', 'pbsandwich'),
                'repeaty'               => __('Repeat-y', 'pbsandwich'),
                'norepeat'              => __('No-repeat', 'pbsandwich'),
                'round'                 => __('Round', 'pbsandwich'),
                'space'                 => __('Space', 'pbsandwich'),
                'position'              => __('位置', 'pbsandwich'),
                'margin'                => __('外边距', 'pbsandwich'),
                'row_settings'          => __('行设置', 'pbsandwich'),

                // Full-width rows
                'full_width'            => __('全宽', 'pbsandwich'),
                'full_width_normal'     => __('不使用全宽布局', 'pbsandwich'),
                'full_width_1'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '1'),
                'full_width_2'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '2'),
                'full_width_3'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '3'),
                'full_width_4'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '4'),
                'full_width_5'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '5'),
                'full_width_6'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '6'),
                'full_width_7'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '7'),
                'full_width_8'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '8'),
                'full_width_9'          => sprintf(__('打破 %s 个容器', 'pbsandwich'), '9'),
                'full_width_99'         => __('打破所有容器', 'pbsandwich'),
                'full_width_desc'       => '行内容宽度通常被主题的全局宽度限制, 通过此设置可以打破这个限制,显示全宽行.',

                'modal_tabs' => array(),

            );
            $columnVars = apply_filters('pbs_column_vars', $columnVars);
            $columnVars = apply_filters('pbs_js_vars', $columnVars);

            // Print out our variables
            ?>
            <script type="text/javascript">
                var pbsandwich_column = <?php echo json_encode( $columnVars ) ?>;
            </script>
            <?php
        }


        /**
         * Registers our column button in TinyMCE
         *
         * @param    $buttons array Existing TinyMCE buttons
         *
         * @return    An array of TinyMCE buttons
         */
        public function registerTinyMCEButton($buttons) {
            array_push($buttons, 'pbsandwich_column');

            return $buttons;
        }


        /**
         * Gets the column styles saved within the post/page. Saved styles are from the `save_post`
         * action and are saved as post meta data.
         *
         * @return    void
         */
        public function renderColumnStyles() {
            global $post;
            if (empty($post)) {
                return;
            }

            // 2 sets of styles are saved, preview & published, get what we need
            $suffix = '';
            if (is_preview()) {
                $suffix = '_preview';
            }

            $styles = trim(get_post_meta($post->ID, 'pbsandwich_styles' . $suffix, true));
            if (empty($styles)) {
                return;
            }

            echo '<style id="pbsandwich_column">' . $styles . '</style>';
        }


        /**
         * Adds the column template files
         *
         * @return    void
         * @since    0.11
         */
        public function addColumnTemplates() {
            // check user permissions
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            include_once PBS_PATH . "/lib/templates/column-change-modal.php";
            include_once PBS_PATH . "/lib/templates/column-custom-modal-description.php";
            include_once PBS_PATH . "/lib/templates/column-area-edit-modal.php";
            include_once PBS_PATH . "/lib/templates/column-row-edit-modal.php";

        }


        /**
         * Forms a list of elements that when clicked won't initiate a drag
         *
         * @return    void
         */
        protected function formNonSortableElements() {
            // When these elements are clicked, don't drag the element
            $nonSortableElements = 'p,code,blockquote,span,pre,td:not(.pbsandwich_column td),th,h1,h2,h3,h4,h5,h6,dt,dd,li,a,address,img';
            $nonSortableElements = apply_filters('sc_non_sortable_elements', $nonSortableElements);

            // Allow all contents of views to be draggable
            $nonSortableElementsArray = explode(',', $nonSortableElements);
            $nonSortableElements = '';
            foreach ($nonSortableElementsArray as $key => $element) {
                if ($key > 0) {
                    $nonSortableElements .= ',';
                }
                $nonSortableElements .= $element . ':not(.wpview-wrap ' . $element . ')';
            }

            // Add the toolbar elements
            $nonSortableElements .= empty($nonSortableElements) ? '' : ',';
            $nonSortableElements .= '#wp-column-toolbar,.toolbar,.toolbar .dashicons';

            return $nonSortableElements;
        }


        /**
         * Parses the html content, and fixes the columns. <table>s are converted into <div>s, and the
         * styles (margins) are separated.
         *
         * @param    $content string The content being outputted in the frontend
         *
         * @return    string The modified content
         */
        public function parseColumnContent($content) {
            // simple_html_dom errors out when we don't have any content
            $contentChecker = trim($content);
            if (empty($contentChecker)) {
                return array(
                    'content' => $content,
                    'styles'  => '',
                );
            }

            if (!function_exists('file_get_html')) {
                require_once(PBS_PATH . 'inc/simple_html_dom.php');
            }
            wp_enqueue_style('pbsandwich-frontend', PBS_URL . 'css/frontend.css');

            $columnStyles = '';


            // Remove stray jQuery sortable classes
            $html = preg_replace('/(ui-sortable-handle|ui-sortable)/', '', $content);
            $html = str_get_html($html);

            $tables = $html->find('table.pbsandwich_column');
            $hashes = array();
            while (count($tables) > 0) {
                $tr = $html->find('table.pbsandwich_column', 0)->find('tr', 0);

                $newDivs = '';
                $styleDump = '';

                // Gather table styles
                $tableStyles = $html->find('table.pbsandwich_column', 0)->style;

                // Remove the default editor styles that do not have any effects:

                // width: 100%; height: auto; border: none;
                $tableStyles = trim(preg_replace('/(^|\s)width:[^;]+;?\s?/', '', $tableStyles));
                $tableStyles = trim(preg_replace('/(^|\s)height:[^;]+;?\s?/', '', $tableStyles));
                $tableStyles = trim(preg_replace('/(^|\s)border:\s?none;?\s?/', '', $tableStyles));

                // Gather the column styles, use placeholders for the ID since we have yet to generate the unique ID
                if (!empty($tableStyles)) {
                    $columnStyles .= '.sandwich.pbsandwich_column_%' . (count($hashes) + 1) . '$s { ' . wp_kses($tableStyles, array(), array()) . ' }';
                }
                $styleDump .= esc_attr($tableStyles);

                foreach ($tr->children() as $key => $td) {
                    if ($td->tag != 'td') {
                        continue;
                    }

                    $innerHTML = trim($td->innertext);

                    // Only add in paragraph tags if there aren't any.
                    // This is to ensure that the spacing remains correct.
                    // @see http://www.htmlhelp.com/reference/html40/inline.html
                    if (preg_match('/^<(a|abbr|acronym|b|bdo|big|br|cite|code|dfn|em|i|img|input|kbd|label|q|samp|select|small|span|strong|sub|sup|textarea|tt|var|button|del|ins|map|object|script)[^>]+>/', $innerHTML) === 1) {
                        $innerHTML = '<p>' . $innerHTML . '</p>';
                    } else if (preg_match('/^</', $innerHTML) === 0) {
                        $innerHTML = '<p>' . $innerHTML . '</p>';
                    }

                    // Remove blank classes
                    $innerHTML = preg_replace('/\sclass=[\'"]\s*[\'"]/', '', $innerHTML);

                    // Cleanup ends
                    $innerHTML = trim($innerHTML);

                    // Remove the widths since we are using classes for those:
                    $columnStyle = trim(preg_replace('/(^|\s)width:[^;]+;\s?/', '', $td->style));

                    // Gather the column styles, use placeholders for the ID since we have yet to generate the unique ID
                    if (!empty($columnStyle)) {
                        $columnStyles .= '.sandwich.pbsandwich_column_%' . (count($hashes) + 1) . '$s > div > div:nth-of-type(' . ($key + 1) . ') { ' . wp_kses($columnStyle, array(), array()) . ' }';
                    }
                    $styleDump .= esc_attr($td->style);

                    // Gather all column data attributes
                    $dataAttributes = '';
                    foreach ($td->getAllAttributes() as $key => $value) {
                        if (stripos($key, 'data-') !== 0 || strlen($value) == '') {
                            continue;
                        }
                        if ($key == 'data-wp-columnselect') { // This is a dummy attribute
                            continue;
                        }
                        $dataAttributes .= ' ' . $key . '="' . esc_attr($value) . '"';
                    }

                    $newDivs .= '<div class="' . esc_attr($td->class) . '" ' . $dataAttributes . '>' . $innerHTML . '</div>';
                }

                // Generate the unique ID of this column based on the margin rules it has. (crc32 is fast)
                $hash = crc32($styleDump);
                $hashes[] = $hash;

                /**
                 * Build our converted <table>
                 */
                // Our main class
                $tableClasses = array('sandwich');
                // Carry over custom classes
                $tableClasses[] = $html->find('table.pbsandwich_column', 0)->class;
                // Custom styles class
                if (!empty($columnStyles)) {
                    $tableClasses[] = 'pbsandwich_column_' . $hash;
                }

                // Gather all row/table data attributes
                $dataAttributes = '';
                foreach ($html->find('table.pbsandwich_column', 0)->getAllAttributes() as $key => $value) {
                    if (stripos($key, 'data-') !== 0 || strlen($value) == '') {
                        continue;
                    }
                    $dataAttributes .= ' ' . $key . '="' . esc_attr($value) . '"';
                }

                // Create the actual row div
                $newDivs = '<div class="' . esc_attr(join(' ', $tableClasses)) . '" ' . $dataAttributes . '><div class="row">' . $newDivs . '</div></div>';

                $html->find('table.pbsandwich_column', 0)->outertext = $newDivs;

                // Save the new HTML with the table replaced
                $html = $html->save();
                $html = str_get_html($html);

                // Move on to the next table
                $tables = $html->find('table.pbsandwich_column');
            }

            // Sanitize the output for security
            $columnStyles = wp_kses($columnStyles, array(), array());
            // Make sure our html entities are correct to make our rules work properly
            $columnStyles = html_entity_decode($columnStyles);

            // Insert the hashes
            foreach ($hashes as $key => $hash) {
                $columnStyles = str_replace('%' . ($key + 1) . '$s', $hash, $columnStyles);
            }

            // Remove blank paragraphs (not &nbsp;)
            $newHTML = (string)$html;

            // Remove weirdly appended paragraph tags (they are there for some odd reason)
            $newHTML = preg_replace('/(<p[^>]*>)([^<]*$)/', '$2', $newHTML);

            // Sometimes, our rows/columns get tangled up inside paragraphs, get rid of those
            $newHTML = preg_replace('/<p[^>]*>\s*(<div)/', '$1', $newHTML);
            $newHTML = preg_replace('/(<\/div>)\s*<\/p>/', '$1', $newHTML);

            // Since we're dealing with wrong paragraph tags, remove other wrong stuff such as
            // multiple end paragraphs & multiple start paragraphs
            $newHTML = preg_replace('/(<\/p>)\s*<\/p>/', '$1', $newHTML);
            $newHTML = preg_replace('/<p[^>]+>\s*(<p[^>]+>)/', '$1', $newHTML);

            return array(
                'content' => $newHTML,
                'styles'  => $columnStyles,
            );
        }


        public function addColumnToolbarButtons($toolbarButtons) {

            $toolbarButtons[] = array(
                'label'     => __('列', 'pbsandwich'),
                'shortcode' => 'column',
                'priority'  => 1001,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-edit-area',
                'icon'      => 'dashicons dashicons-edit',
                'label'     => __('编辑列', 'pbsandwich'),
                'shortcode' => 'column',
                'priority'  => 1002,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-clone-area',
                'icon'      => 'dashicons dashicons-images-alt',
                'label'     => __('复制列', 'pbsandwich'),
                'shortcode' => 'column',
                'priority'  => 1003,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-remove-area',
                'icon'      => 'dashicons dashicons-no-alt',
                'label'     => __('删除列', 'pbsandwich'),
                'shortcode' => 'column',
                'priority'  => 1004,
            );
            $toolbarButtons[] = array(
                'label'     => '|',
                'shortcode' => 'column',
                'priority'  => 1005,
            );

            $toolbarButtons[] = array(
                'label'     => __('行', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1100,
            );
            // Add align left button
            $toolbarButtons[] = array(
                'action'    => 'row-align-left',
                'icon'      => 'dashicons dashicons-align-left',
                'label'     => __('左对齐', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1101,
            );
            // Add align center button
            $toolbarButtons[] = array(
                'action'    => 'row-align-center',
                'icon'      => 'dashicons dashicons-align-center',
                'label'     => __('居中对齐', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1102,
            );
            // Add align right button
            $toolbarButtons[] = array(
                'action'    => 'row-align-right',
                'icon'      => 'dashicons dashicons-align-right',
                'label'     => __('右对齐', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1103,
            );
            // Add align none button
            $toolbarButtons[] = array(
                'action'    => 'row-align-none',
                'icon'      => 'dashicons dashicons-align-none',
                'label'     => __('不对齐', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1104,
            );
            $toolbarButtons[] = array(
                'label'     => '|',
                'shortcode' => 'row',
                'priority'  => 1105,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-edit-row',
                'icon'      => 'dashicons dashicons-edit',
                'label'     => __('编辑行', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1106,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-columns',
                'icon'      => 'dashicons dashicons-tagcloud',
                'label'     => __('编辑行', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1107,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-clone-row',
                'icon'      => 'dashicons dashicons-images-alt',
                'label'     => __('复制行', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1108,
            );
            $toolbarButtons[] = array(
                'action'    => 'column-remove-row',
                'icon'      => 'dashicons dashicons-no-alt',
                'label'     => __('删除行', 'pbsandwich'),
                'shortcode' => 'row',
                'priority'  => 1109,
            );

            return $toolbarButtons;
        }


        public function addModalVar() {

            // check user permissions
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            // Print out our variables
            ?>
            <script type="text/javascript">
                var pbs_modal_fields = {};
            </script>
            <?php
        }

        public function addModalTabs() {
            $this->modalTabs = apply_filters('pbs_modal_tabs', array());

            foreach ($this->modalTabs as $key => $tab) {
                $defaults = array(
                    'template'    => '',
                    'template_id' => '',
                    'name'        => '',
                    'shortcode'   => 'row',
                );
                $this->modalTabs[$key] = array_merge($defaults, $tab);
            }
        }

        public function addModalTabTemplates() {
            // check user permissions
            if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
                return;
            }

            foreach ($this->modalTabs as $key => $tab) {
                if (!empty($tab['template'])) {
                    include_once $tab['template'];
                }
            }
        }


        public function addModalTabVars($columnVars) {
            if (empty($this->modalTabs)) {
                return $columnVars;
            }

            $varsToOutput = array();
            foreach ($this->modalTabs as $tab) {
                // for security, don't include the template path
                unset($tab['template']);
                $varsToOutput[] = $tab;
            }

            $columnVars['modal_tabs'] = $varsToOutput;

            return $columnVars;
        }

    }

    new GambitPBSandwichColumns();