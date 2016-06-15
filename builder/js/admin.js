jQuery(document).ready(function ($) {

    /**
     * 选项卡
     */
    $('body').on('click', '.wizhi_modal_tab[data-for]', function () {
        var modal = $(this).parents('.mce-container:eq(0)');
        var tabContainer = $(this).parents('.wizhi_modal_tabs:eq(0)');
        tabContainer.siblings().hide();
        modal.find('#' + $(this).attr('data-for')).show();
        tabContainer.find('.wizhi_modal_tab').removeClass('active');
        $(this).addClass('active');
    });


    // Change the data-shortcode to remove the click handler
    $('body').on('hover', '.shortcode-list-item[data-shortcode="wizhi_get_more_shortcodes"]', function (e) {
        $(this).attr('data-shortcode', '__wizhi_get_more_shortcodes');
    });


    // ---------------------------------------------------------
    // 添加更多
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


    // 显示表单选择器
    var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;

    $('.wizhi_upload_button').click(function (e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        _custom_media = true;
        wp.media.editor.send.attachment = function (props, attachment) {
            if (_custom_media) {
                button.parent().parent().find('.text').val(attachment.id);
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

function _gambit_microtime() {
    return ( new Date ).getTime() / 1000;
}