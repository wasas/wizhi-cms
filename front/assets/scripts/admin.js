jQuery(document).ready(function ($) {

    // 拖拽 Ajax 上传
    $('.fn-dnd-zone').dmUploader({
        url            : 'http://cssa.dev/helper/ajax/upload',
        type           : 'POST',
        dataType       : 'json',
        allowedTypes   : 'image/*',
        onUploadSuccess: function (id, data) {
            $(this).find('.frm-thumbs').append('<input type="hidden" name="' + $(this).data('name') + '" value="' + data.id + '">');

            $(this).find('.frm-preview').show()
                   .append('<div class="col-xs-6 col-md-3"><button data-value="' + data.id + '" type="button" class="close"><span>×</span></button><a href="#" class="thumbnail"><img src="' + data.thumb + '" alt="..."></a></div>');
        }
    });

    // 删除已添加的缩略图，需要保存后才能生效
    $('.fn-dnd-zone button.close').on('click', function () {
        var value = $(this).data('value');

        $(this).parent().remove();
        $('.frm-thumbs input[value=' + value + ']').remove();
    })

});