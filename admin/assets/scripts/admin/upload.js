/*
 *	JavaScript Wordpress editor
 *	Author: 		Ante Primorac
 *	Author URI: 	http://anteprimorac.from.hr
 *	Version: 		1.1
 */


;(function ($, window) {
    $.fn.wp_editor = function (opts) {

        if (!$(this).is('textarea')) {
            console.warn('Element must be a textarea');
        }

        if (typeof tinyMCEPreInit == 'undefined' || typeof QTags == 'undefined' || typeof wizhi_vars == 'undefined') {
            console.warn('js_wp_editor( $settings ); must be loaded');
        }

        if (!$(this).is('textarea') || typeof tinyMCEPreInit == 'undefined' || typeof QTags == 'undefined' || typeof wizhi_vars == 'undefined') {
            return this;
        }


        // 默认设置选项
        var default_options = {
            'mode': 'tmce',
            'mceInit': {
                "theme": "modern",
                "skin": "lightgray",
                "language": "zh",
                "formats": {
                    "alignleft": [{
                        "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                        "styles": {"textAlign": "left"},
                        "deep": false,
                        "remove": "none"
                    },
                        {
                            "selector": "img,table,dl.wp-caption",
                            "classes": ["alignleft"],
                            "deep": false,
                            "remove": "none"
                        }],
                    "aligncenter": [{
                        "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                        "styles": {"textAlign": "center"},
                        "deep": false,
                        "remove": "none"
                    },
                        {
                            "selector": "img,table,dl.wp-caption",
                            "classes": ["aligncenter"],
                            "deep": false,
                            "remove": "none"
                        }],
                    "alignright": [{
                        "selector": "p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li",
                        "styles": {"textAlign": "right"},
                        "deep": false,
                        "remove": "none"
                    },
                        {
                            "selector": "img,table,dl.wp-caption",
                            "classes": ["alignright"],
                            "deep": false,
                            "remove": "none"
                        }],
                    "strikethrough": {
                        "inline": "del",
                        "deep": true,
                        "split": true
                    }
                },
                "relative_urls": false,
                "remove_script_host": false,
                "convert_urls": false,
                "browser_spellcheck": true,
                "fix_list_elements": true,
                "entities": "38,amp,60,lt,62,gt",
                "entity_encoding": "raw",
                "keep_styles": false,
                "paste_webkit_styles": "font-weight font-style color",
                "preview_styles": "font-family font-size font-weight font-style text-decoration text-transform",
                "wpeditimage_disable_captions": false,
                "wpeditimage_html5_captions": false,
                "plugins": "charmap,hr,media,paste,tabfocus,textcolor,fullscreen,wordpress,wpautoresize,wpemoji,wpeditimage,wpgallery,wptextpattern,wplink,wpdialogs,wpview,image,wpembed",
                "content_css": wizhi_vars.includes_url + "css/dashicons.css," + wizhi_vars.includes_url + "js/mediaelement/mediaelementplayer.min.css," + wizhi_vars.includes_url + "js/mediaelement/wp-mediaelement.css," + wizhi_vars.includes_url + "js/tinymce/skins/wordpress/wp-content.css" + wizhi_vars.cms_url + "wizhi-cms/front/dist/styles/main.css",
                "wp_lang_attr": "zh-CN",
                "selector": "#wid",
                "resize": "vertical",
                "menubar": false,
                "wpautop": true,
                "indent": false,
                "toolbar1": "bold,italic,strikethrough,bullist,numlist,blockquote,hr,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,fullscreen,wp_adv",
                "toolbar2": "formatselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
                "toolbar3": "",
                "toolbar4": "",
                "tabfocus_elements": ":prev,:next",
                "body_class": "typo wizhi wid",
                wp_autoresize_on: true
            }
        }, id_regexp = new RegExp('wid', 'g');

        if (tinyMCEPreInit.mceInit['wid']) {
            default_options.mceInit = tinyMCEPreInit.mceInit['wid'];
        }

        var options = $.extend(true, default_options, opts);


        // 初始化可视化编辑器
        return this.each(function () {

            if (!$(this).is('textarea')) {
                console.warn('Element must be a textarea');
            } else {
                var current_id = $(this).attr('id');
                $.each(options.mceInit, function (key, value) {
                    if ($.type(value) == 'string') {
                        options.mceInit[key] = value.replace(id_regexp, current_id);
                    }
                });
                options.mode = options.mode == 'tmce' ? 'tmce' : 'html';

                tinyMCEPreInit.mceInit[current_id] = options.mceInit;

                $(this).addClass('wp-editor-area').show();
                var self = this;
                if ($(this).closest('.wp-editor-wrap').length) {
                    var parent_el = $(this).closest('.wp-editor-wrap').parent();
                    $(this).closest('.wp-editor-wrap').before($(this).clone());
                    $(this).closest('.wp-editor-wrap').remove();
                    self = parent_el.find('textarea[id="' + current_id + '"]');
                }

                var wrap = $('<div id="wp-' + current_id + '-wrap" class="wp-core-ui wp-editor-wrap ' + options.mode + '-active" />');
                var editor_tools = $('<div id="wp-' + current_id + '-editor-tools" class="wp-editor-tools hide-if-no-js" />');
                var editor_tabs = $('<div class="wp-editor-tabs" />');
                var switch_editor_html = $('<a id="' + current_id + '-html" class="wp-switch-editor switch-html" data-wp-editor-id="' + current_id + '">Text</a>');
                var switch_editor_tmce = $('<a id="' + current_id + '-tmce" class="wp-switch-editor switch-tmce" data-wp-editor-id="' + current_id + '">Visual</a>');
                var media_buttons = $('<div id="wp-' + current_id + '-media-buttons" class="wp-media-buttons" />');
                var insert_media_button = $('<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="' + current_id + '" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>'), editor_container = $('<div id="wp-' + current_id + '-editor-container" class="wp-editor-container" />');
                var content_css = false;

                insert_media_button.appendTo(media_buttons);
                media_buttons.appendTo(editor_tools);

                switch_editor_html.appendTo(editor_tabs);
                switch_editor_tmce.appendTo(editor_tabs);
                editor_tabs.appendTo(editor_tools);

                editor_tools.appendTo(wrap);
                editor_container.appendTo(wrap);

                editor_container.append($(self).clone().addClass('wp-editor-area'));


                // 内容样式
                if (content_css != false) {
                    $.each(content_css, function () {
                        if (!$('link[href="' + this + '"]').length) {
                            $(self).before('<link rel="stylesheet" type="text/css" href="' + this + '">');
                        }
                    });
                }

                $(self)
                    .before('<link rel="stylesheet" id="editor-buttons-css" href="' + wizhi_vars.includes_url + 'css/editor.css" type="text/css" media="all">');

                $(self).before(wrap);
                $(self).remove();


                // 快捷按钮
                new QTags(current_id);
                QTags._buttonsInit();
                switchEditors.go(current_id, options.mode);

                // 插入媒体
                $(wrap).on('click', '.insert-media', function (event) {
                    var elem = $(event.currentTarget), editor = elem.data('editor'), options = {
                        frame: 'post',
                        state: 'insert',
                        title: wp.media.view.l10n.addMedia,
                        multiple: true
                    };

                    event.preventDefault();

                    elem.blur();

                    if (elem.hasClass('gallery')) {
                        options.state = 'gallery';
                        options.title = wp.media.view.l10n.createGalleryTitle;
                    }

                    wp.media.editor.open(editor, options);
                });
            }
        });
    }
})(jQuery, window);


jQuery(document).ready(function ($) {

    jQuery('.tinymce').wp_editor({
        "body_class": "typo wizhi wid"
    });

    // ---------------------------------------------------------
    // 添加更多, 用于重复字段
    // ---------------------------------------------------------
    $('.add-row').on('click', function () {
        var row = $(this).parent().find('input:last').clone(true);
        row.addClass('new-row');
        row.insertAfter($(this).parent().find('input:last'));
        return false;
    });

    // 移除添加的元素
    $('.remove-row').on('click', function () {
        $(this).parent().find('input:last').remove();
        return false;
    });


    // ---------------------------------------------------------
    // 文件上传, 目前插入 URL, 考虑做成插入 ID的方式, 方便获取缩略图
    // ---------------------------------------------------------
    var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;

    $('.wizhi_upload_button').click(function (e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        _custom_media = true;
        wp.media.editor.send.attachment = function (props, attachment) {
            if (_custom_media) {
                button.parent().parent().find('input[type=text]').val(attachment.url);
            } else {
                return _orig_send_attachment.apply(this, [props,
                    attachment]);
            }
        };

        wp.media.editor.open(button);
        return false;
    });

    $('.add_media').on('click', function () {
        _custom_media = false;
    });

});
