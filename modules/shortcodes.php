<?php
    /**
     * Wizhi Shortcode
     * Wizhi CMS 插件使用的简码
     */

    /*-----------------------------------------------------------------------------------*/
    /* 显示分割线
    /*-----------------------------------------------------------------------------------*/

    /* 显示几种不同类型的分割线
     * 使用方法：<?php echo do_shortcode('[divider type="solid"]'); ?>
     */


    if (!function_exists('wizhi_shortcode_divider')) {
        function wizhi_shortcode_divider($atts) {
            $default = array(
                'type' => 'solid',
            );
            extract(shortcode_atts($default, $atts));

            // 输出
            $retour = '';
            $retour .= '<div class="ui-divider ui-divider-' . $type . '"></div>';

            return $retour;

        }
    }
    add_shortcode('divider', 'wizhi_shortcode_divider');


    /*-----------------------------------------------------------------------------------*/
    /* 显示不同类型的标题
    /*-----------------------------------------------------------------------------------*/

    /* 显示几种不同类型的分割线
     * 使用方法：<?php echo do_shortcode('[heading type="background" content="这是二级标题"]'); ?>
     */


    if (!function_exists('wizhi_shortcode_heading')) {
        function wizhi_shortcode_heading($atts) {
            $default = array(
                'type'    => 'background',
                'content' => '这是二级标题',
            );
            extract(shortcode_atts($default, $atts));

            // 输出
            $retour = '';
            $retour .= '<h2 class="ui-heading ui-heading-' . $type . '">' . $content . '</h2>';

            return $retour;

        }
    }
    add_shortcode('heading', 'wizhi_shortcode_heading');


    /*-----------------------------------------------------------------------------------*/
    /* 显示提示消息
    /*-----------------------------------------------------------------------------------*/

    /* 显示几种不同类型的提示消息
     * 使用方法：<?php echo do_shortcode('[alert type="success" content="这是提示信息"]'); ?>
     */


    if (!function_exists('wizhi_shortcode_alert')) {
        function wizhi_shortcode_alert($atts) {
            $default = array(
                'type'    => 'info',
                'content' => '这是提示信息。',
            );
            extract(shortcode_atts($default, $atts));

            // 输出
            $retour = '';
            $retour .= '<div class="ui-alert ui-alert-' . $type . '">' . $content . '</div>';

            return $retour;

        }
    }
    add_shortcode('alert', 'wizhi_shortcode_alert');


    /*-----------------------------------------------------------------------------------*/
    /* 显示链接按钮
    /*-----------------------------------------------------------------------------------*/

    /* 显示几种不同类型的分割线
     * 使用方法：<?php echo do_shortcode('[button type="success" size='' text="这是链接" url="http://www.baidu.com"]'); ?>
     */


    if (!function_exists('wizhi_shortcode_button')) {
        function wizhi_shortcode_button($atts) {
            $default = array(
                'type' => 'success',
                'size' => '',
                'text' => '这是链接',
                'url'  => 'http://',
            );
            extract(shortcode_atts($default, $atts));

            // 输出
            $retour = '';
            $retour .= '<a class="pure-button button-' . $type . ' button-' . $size . '" href="' . $url . '">' . $text . '</a>';

            return $retour;

        }
    }
    add_shortcode('button', 'wizhi_shortcode_button');


    /* 根据自定义分类显示文章
     * 输出标题文章列表时实现，默认带标题
     * 使用方法：[wizhi_loop id="1" count="200" thumbs="thumbnail" more="true"]
     * todo：可以实现更多的参数控制
    */

    if (!function_exists('wizhi_shortcode_page_cont')) {
        function wizhi_shortcode_page_cont($atts) {
            $default = array(
                'id'     => 1,
                'cont'   => 200,
                'thumbs' => 'thumbnail',
                'more'   => false,
            );
            extract(shortcode_atts($default, $atts));

            $page = get_post($id);

            // 输出
            $retour = '';

            $retour .= '<a target="_blank" href="' . get_page_link($id) . '">';
            $retour .= get_the_post_thumbnail($id, $thumbs);
            $retour .= '</a>';

            if ($cont == 0) {
                $retour .= $page->post_content;
            } else {
                $retour .= wp_trim_words($page->post_content, $cont, "...");
            }

            if ($more == true) {
                $retour .= '<a target="_blank" href="' . get_page_link($id) . '">更多>></a>';
            } else {
                $retour .= '';
            }

            wp_reset_postdata();
            wp_reset_query();

            return $retour;

        }
    }
    add_shortcode('page_cont', 'wizhi_shortcode_page_cont');


    /* 根据自定义分类显示文章
     * 输出标题文章列表时实现，默认带标题
     * 使用方法：[wizhi_loop type="home" tax="home_tag" tag="yxdt" num="6" tp="content" offset="0"]
     * todo：可以实现更多的参数控制
    */
    if (!function_exists('wizhi_shortcode_loop')) {

        function wizhi_shortcode_loop($atts) {

            $default = array(
                'type'   => 'post',
                'tax'    => 'category',
                'tag'    => 'default',
                'tp'     => 'lists',
                'offset' => 0,
                'num'    => 8, // 数量: 显示文章数量，-1为全部显示
            );

            extract(shortcode_atts($default, $atts));

            // 判断是否查询分类
            if (empty($tax)) {
                $tax_query = '';
            } else {
                $tax_query = array(
                    array(
                        'taxonomy' => $tax,
                        'field'    => 'slug',
                        'terms'    => $tag,
                    ),
                );
            }

            // 构建文章查询数组
            $args = array(
                'post_type'      => $type,
                'orderby'        => 'post_date',
                'order'          => 'DESC',
                'posts_per_page' => $num,
                'offset'         => $offset,
                'tax_query'      => $tax_query,
            );

            // 输出
            $i = 1;
            $the_query = new WP_Query($args);

            while ($the_query->have_posts()) : $the_query->the_post();
                get_template_part('template-parts/content', $tp);
            endwhile;

            wp_reset_postdata();
            wp_reset_query();

        }
    }

    add_shortcode('wizhi_loop', 'wizhi_shortcode_loop');


    /* 根据自定义分类显示文章
     * 输出标题文章列表时实现，默认带标题
     * 使用方法：[title_list type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false" time="true" sticky="true"]
     * todo：可以实现更多的参数控制
    */
    if (!function_exists('wizhi_shortcode_title_list')) {

        function wizhi_shortcode_title_list($atts) {

            $default = array(
                'type'    => 'post',
                'tax'     => 'category',
                'tag'     => 'default',
                'offset'  => 0,
                'num'     => 8, // 数量: 显示文章数量，-1为全部显示
                'cut'     => 36, // 切断：标题截取的字符数
                'heading' => true,
                'time'    => false,
            );

            extract(shortcode_atts($default, $atts));

            // 判断是否查询分类
            if (empty($tax)) {
                $tax_query = '';
            } else {
                $tax_query = array(
                    array(
                        'taxonomy' => $tax,
                        'field'    => 'slug',
                        'terms'    => $tag,
                    ),
                );
            }

            // 构建文章查询数组
            $args = array(
                'post_type'      => $type,
                'orderby'        => 'post_date',
                'order'          => 'DESC',
                'posts_per_page' => $num,
                'offset'         => $offset,
                'tax_query'      => $tax_query,
            );

            // get term archive name and link
            $cat = get_term_by('slug', $tag, $tax);

            if ($cat) {
                $cat_name = $cat->name;
                $cat_link = get_term_link($tag, $tax);
            }

            // 输出
            global $post;
            $the_query = new WP_Query($args);

            $retour = '';
            if ($heading == false || empty($tax)) {
                $retour .= '<div class="ui-list-' . $type . $tag . '">';
                $retour .= '<ul class="ui-list">';
                while ($the_query->have_posts()) : $the_query->the_post();

                    //custom links
                    $cus_links = get_post_meta(get_the_ID(), 'cus_links', true);
                    if (empty($cus_links)) {
                        $cus_links = get_permalink();
                    }

                    $retour .= '<li class="ui-list-item">';
                    if ($time == 'true') {
                        $retour .= '<span class="pull-right time">' . get_the_time('m-d') . '</span>';
                    } else {
                        $retour .= '';
                    }
                    $retour .= '<a href="' . $cus_links . '" title="' . get_the_title() . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a>';
                    $retour .= '</li>';
                endwhile;
                $retour .= '</ul>';
                $retour .= '</div>';
            } else {
                $retour .= '<div class="ui-box ' . $type . $tag . '">';
                $retour .= '<div class="ui-box-head">';
                $retour .= '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
                $retour .= '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
                $retour .= '</div>';
                $retour .= '<div class="ui-box-container"><ul class="ui-list ui-list-' . $tag . '">';
                while ($the_query->have_posts()) : $the_query->the_post();

                    //custom links
                    $cus_links = get_post_meta(get_the_ID(), 'cus_links', true);
                    if (empty($cus_links)) {
                        $cus_links = get_permalink();
                    }

                    $retour .= '<li class="ui-list-item">';
                    if ($time == 'true') {
                        $retour .= '<span class="pull-right time">' . get_the_time('m-d') . '</span>';
                    } else {
                        $retour .= '';
                    }
                    $retour .= '<a href="' . $cus_links . '" title="' . get_the_title() . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a>';
                    $retour .= '</li>';
                endwhile;
                $retour .= '</ul></div></div>';
            }

            wp_reset_postdata();
            wp_reset_query();

            return $retour;

        }
    }
    add_shortcode('title_list', 'wizhi_shortcode_title_list');


    /* 图文混排样式简码
     * 需要的参数：文章类型，分类法，分类，缩略图别名，标题字数，是否显示时间，内容字数
     * 使用方法：<?php echo do_shortcode('[photo_list type="home" tax="home_tag" tag="yxdt" num="6" cut="26" heading="false" time="true" thumbs="maintain" cut="6" sticky="true" class="pure-u-1-5"]'); ?>
     */
    if (!function_exists('wizhi_shortcode_photo_list')) {

        function wizhi_shortcode_photo_list($atts) {
            $default = array(
                'type'     => 'post',
                'tax'      => 'category',
                'tag'      => 'default',
                'thumbs'   => 'tumbnails',
                'position' => 'left',
                'num'      => '4',
                'paged'    => '1',
                'cut'      => '',
                'content'  => '',
                'heading'  => true,
                'class'    => '',
            );

            extract(shortcode_atts($default, $atts));

            // 判断是否查询分类
            if (empty($tax)) {
                $tax_query = '';
            } else {
                $tax_query = array(
                    array(
                        'taxonomy' => $tax,
                        'field'    => 'slug',
                        'terms'    => $tag,
                    ),
                );
            }

            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            // 根据分类别名获取分类ID
            $args = array(
                'post_type'      => $type,
                'orderby'        => 'post_date',
                'order'          => 'DESC',
                'posts_per_page' => $num,
                'paged'          => $paged,
                'tax_query'      => $tax_query,
            );

            $cat = get_term_by('slug', $tag, $tax);

            if ($cat) {
                $cat_name = $cat->name;
                $cat_link = get_term_link($tag, $tax);
            }

            if ($position == "left") {
                $position = "media-left";
            } elseif ($position == "right") {
                $position = "media-right";
            } else {
                $position = "media-top";
            }

            // 输出
            global $post;
            $wp_query = new WP_Query($args);
            $retour = '';

            if ($heading == false || empty($tax)) {
                $retour .= '<div class="medias media-' . $type . $tag . '">';
                while ($wp_query->have_posts()) : $wp_query->the_post();

                    //custom links
                    $cus_links = get_post_meta(get_the_ID(), 'cus_links', true);
                    if (empty($cus_links)) {
                        $cus_links = get_permalink();
                    }

                    $retour .= '<div class="' . $class . '">';
                    $retour .= '<div class="media ' . $position . ' media-' . $thumbs . ' clearfix">';
                    if (!empty($thumbs)) {
                        $retour .= '<a class="media-cap" target="_blank" href="' . $cus_links . '">';
                        if (has_post_thumbnail()) {
                            $retour .= get_the_post_thumbnail($post->ID, $thumbs);
                        }
                        $retour .= '</a>';
                    }
                    if (!empty($content)) {
                        $retour .= '<div class="media-body">';
                        $retour .= '<div class="media-body-title"><a href="' . $cus_links . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a></div>';
                        $retour .= '<p>' . wp_trim_words($post->post_content, $content, "...") . '</p>';
                        $retour .= '</div>';
                    } else {
                        if (!empty($cut)) {
                            $retour .= '<a href="' . $cus_links . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a>';
                        }
                    }
                    $retour .= '</div>';
                    $retour .= '</div>';

                endwhile;
                $retour .= '</div>';

            } else {
                $retour .= '<div class="ui-box ' . $type . $tag . '">';
                $retour .= '<div class="ui-box-head">';
                $retour .= '<h3 class="ui-box-head-title"><a href="' . $cat_link . '">' . $cat_name . '</a></h3>';
                $retour .= '<a class="ui-box-head-more" href="' . $cat_link . '" target="_blank">更多></a>';
                $retour .= '</div>';
                $retour .= '<div class="ui-box-container">';
                $retour .= '<div class="ui-box-content">';

                $retour .= '<div class="medias media-' . $tag . '">';
                while ($wp_query->have_posts()) : $wp_query->the_post();

                    //custom links
                    $cus_links = get_post_meta($post->ID, 'cus_links', true);
                    if (empty($cus_links)) {
                        $cus_links = get_permalink();
                    }

                    setup_postdata($post);
                    $retour .= '<div class="' . $class . '">';
                    $retour .= '<div class="media ' . $position . ' media-' .  $thumbs . '">';
                    if (!empty($thumbs)) {
                        $retour .= '<a class="media-cap" target="_blank" href="' . $cus_links . '">';
                        if (has_post_thumbnail()) {
                            $retour .= get_the_post_thumbnail($post->ID, $thumbs);
                        }
                        $retour .= '</a>';
                    }
                    if (!empty($content)) {
                        $retour .= '<div class="media-body">';
                        $retour .= '<div class="media-body-title"><a href="' . $cus_links . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a></div>';
                        $retour .= '<p>' . wp_trim_words($post->post_content, $content, "...") . '</p>';
                        $retour .= '</div>';
                    } else {
                        if (!empty($cut)) {
                            $retour .= '<a href="' . $cus_links . '">' . wp_trim_words($post->post_title, $cut, "...") . '</a>';
                        }
                    }
                    $retour .= '</div>';
                    $retour .= '</div>';
                endwhile;
                $retour .= '</div>';

                $retour .= '</div>';
                $retour .= '</div>';
                $retour .= '</div>';

            }

            wp_reset_postdata();
            wp_reset_query();

            return $retour;

        }
    }
    add_shortcode('photo_list', 'wizhi_shortcode_photo_list');


    /* 分类自适应幻灯
     * 替代方案为上面的slider幻灯，在性能上比较好
     * 存在显示上的一些问题
     * 使用方法：<?php echo do_shortcode('[slider type="post" tax="category" tag="jingcai" speed="1000" num="4" thumbs="full" cut="46"]'); ?>
     */

    if (!function_exists('wizhi_shortcode_slider')) {

        function wizhi_shortcode_slider($atts) {
            $default = array(
                'type'        => 'post',
                'tax'         => 'category',
                'tag'         => 'default',
                'num'         => 8,
                'cut'         => 36,
                'content'     => 60,
                'thumbs'      => 'show',
                'stype'       => 'normal',
                'mode'        => 'horizontal',
                'speed'       => 500,
                'auto'        => true,
                'autohover'   => true,
                'minslides'   => 1,
                'maxslides'   => 1,
                'slidewidth'  => 360,
                'slidewargin' => 10,
                'easing'      => 'swing',
            );

            extract(shortcode_atts($default, $atts));

            // 生成 $options 数组
            $cat = get_term_by('slug', $tag, $tax);

            if ($cat) {
                $cat_name = $cat->name;
                $cat_link = get_term_link($tag, $tax);
            }

            $id = $tax . '-' . $tag;

            $options = array(
                'tax'         => $tax,
                'mode'        => $mode,
                'speed'       => $speed,
                'auto'        => $auto,
                'autohover'   => $autohover,
                'minslides'   => $minslides,
                'maxslides'   => $maxslides,
                'slidewidth'  => $slidewidth,
                'slidemargin' => $slidewargin,
                'easing'      => $easing,
            );

            // 判断是否查询分类
            if (empty($tax)) {
                $tax_query = '';
            } else {
                $tax_query = array(
                    array(
                        'taxonomy' => $tax,
                        'field'    => 'slug',
                        'terms'    => $tag,
                    ),
                );
            }

            // 生成文章查询参数
            $args = array(
                'post_type'      => $type,
                'posts_per_page' => $num,
                'orderby'        => 'post_date',
                'order'          => 'DESC',
                'no_found_rows'  => true,
                'tax_query'      => $tax_query,
            );

            // 输出
            global $post;
            $wp_query = new WP_Query($args);

            $retour = '<div class="bx-box bxslider-' . $stype . '">';
            $retour .= '<ul class="bxslider fix" id="bxslider-' . $id . '">';

            while ($wp_query->have_posts()) : $wp_query->the_post();

                // 自定义链接
                $cus_links = get_post_meta($post->ID, 'cus_links', true);
                if (empty($cus_links)) {
                    $cus_links = get_permalink();
                }

                if ($stype == "full") {
                    $feat_image_url = '';
                    // 全宽模式，使用背景显示图片
                    if (has_post_thumbnail()) {
                        $feat_image_url = wp_get_attachment_url(get_post_thumbnail_id());
                    }

                    $retour .= '<li class="bx-item" style="background-size: contain; background-image:url(' . $feat_image_url . ');">';
                    $retour .= '<a target="_blank" class="item-' . $tax . ' " href="' . $cus_links . '" title="' . get_the_title() . '">';
                    $retour .= '<img src="' . get_template_directory_uri() . '/front/dist/images/holder.png" alt="Slider Holder">';
                    $retour .= '</a>';
                    $retour .= '</li>';

                } else {
                    // 普通模式
                    $retour .= '<li class="bx-item">';
                    $retour .= '<a target="_blank" class="item-' . $tax . ' " href="' . $cus_links . '" title="' . get_the_title() . '">';
                    if (has_post_thumbnail()) {
                        $retour .= get_the_post_thumbnail($post->ID, $thumbs);
                    }
                    if (!empty($cut)) {
                        $retour .= '<div class="bx-caption"><span>' . wp_trim_words($post->post_title, $cut, "...") . '</span>';
                        if (!empty($content)) {
                            $retour .= '<span class="bx-desc">' . wp_trim_words($post->post_content, $content, "...") . '</span>';
                        }
                        $retour .= '</div>';
                    }
                    $retour .= '</a>';

                    $retour .= '</li>';
                }

            endwhile;
            $retour .= '</ul></div>';

            wizhi_slider_js($id, $options);

            wp_reset_postdata();
            wp_reset_query();

            return $retour;

        }
    }
    add_shortcode('slider', 'wizhi_shortcode_slider');


    /**-----------------------------------------------------------------------------------*/
    /* Slider Javascript
    /* Jquery Cycle 幻灯所需的JS
    /* -----------------------------------------------------------------------------------
    */

    if (!function_exists('wizhi_slider_js')) {
        function wizhi_slider_js($id, $options) {

            if ($options["maxslides"] == 1) : ?>

                <script>
                    jQuery(document).ready(function ($) {
                        $('#bxslider-<?php echo $id ?>').bxSlider({
                            mode: 'fade',
                            captions: true,
                            auto: <?php echo $options["auto"] ?>
                        });
                    });
                </script>

            <?php else : ?>

                <script>
                    jQuery(document).ready(function ($) {
                        $('#bxslider-<?php echo $id ?>').bxSlider({
                            minSlides: <?php echo $options["minslides"] ?>,
                            maxSlides: <?php echo $options["maxslides"] ?>,
                            slideWidth: <?php echo $options["slidewidth"] ?>,
                            slideMargin: <?php echo $options["slidemargin"] ?>,
                            infiniteLoop: true,
                            hideControlOnEnd: true,
                            auto: <?php echo $options["auto"] ?>
                        });
                    });
                </script>

            <?php endif;

        }
    }