jQuery(document).ready(function ($) {

    // ---------------------------------------------------------
    // 下拉
    // ---------------------------------------------------------
    $('.menu').dropit();

    // ---------------------------------------------------------
    // 图片遮罩，鼠标滑过显示隐藏的元素
    // ---------------------------------------------------------
    $(".ui-hover li").each(function () {
        $(this).hover(function () {
            $(this).find(".hide").fadeIn('slow');
        }, function () {
            $(this).find(".hide").fadeOut('fast');
        });
    });


    // ---------------------------------------------------------
    // Tab 切换
    // ---------------------------------------------------------
    $('.ui-tabs').each(function () {
        var el = $(this);
        el.find('.tab-content-pane:first').show();
    });

    $('.tab-title').each(function () {
        var el = $(this);
        el.find('.tab-title-item:first').addClass('active');
    });

    $('.ui-tabs .tab-title .tab-title-item a').hover(function () {
        var el = $(this),
            parent = el.closest('.ui-tabs'),
            activetab = el.attr('href');
        parent.find('.tab-title-item').removeClass('active');

        el.closest('li').addClass('active');
        parent.find('.tab-content-pane').hide();

        parent.find(activetab).show();

        return false;
    });


    // ---------------------------------------------------------
    // accordion
    // ---------------------------------------------------------
    var all = $('.accordion > dd');
    all.hide(); //所有内容

    $('.accordion > dt > a').click(function () { //点击标题

        if ($(this).attr('href') === "#") {
            $(this).click(function () {
                return false;
            });
        }

        var next = $(this).parent().next(); //相邻的内容元素
        if (next.is(":visible")) { //如果相邻的元素是展开的，隐藏
            all.slideUp('fast');
        } else {
            all.slideUp(); //所有内容折叠
            next.slideDown('fast'); //和标题紧邻的内容展开
        }

        return false;
    });


    // ---------------------------------------------------------
    // Toggle
    // ---------------------------------------------------------
    var allt = $('.toggle > dd');
    allt.hide(); //所有内容

    $('.toggle > dt > a').click(function () { //点击标题

        if ($(this).attr('href') === "#") {
            $(this).click(function () {
                return false;
            });
        }

        var next = $(this).parent().next(); //相邻的内容元素
        if (next.is(":visible")) { //如果相邻的元素是展开的，隐藏
            next.slideUp('fast');
        } else {
            next.slideDown('fast'); //和标题紧邻的内容展开
        }

        return false;
    });

});
