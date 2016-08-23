jQuery(document).ready(function ($) {

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

});
