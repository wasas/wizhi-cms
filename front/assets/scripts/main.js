jQuery(document).ready(function ($) {

    // 选择跳转到选中的值
    $('.se-redirect').change(function () {
        window.location = this.value;
    });

    // 商品相册
    $('.md-gallery').each(function () {
        $(this).magnificPopup({
            delegate: 'a',
            type    : 'image',
            gallery : {
                enabled           : true,
                navigateByImgClick: true,
                preload           : [0,
                    1]
            }
        });
    });


    /**
     * 刷新验证码
     * @param obj
     */
    function refresh_code(obj) {
        obj.src = obj.src + "?code=" + Math.random();
    }


    // 页脚总是显示在底部
    var $window = $(window),
        $footer = $('#colophon'),
        $wrapper = $('.wrapper'),
        windowH = $window.height(),
        wrapperH = $wrapper.height();

    if ($footer.length > 0 && $wrapper.has('#colophon')) {
        if (windowH > wrapperH) {
            $footer.css({'margin-top': ( windowH - wrapperH + 50 )});
            return false;
        }
    }

});