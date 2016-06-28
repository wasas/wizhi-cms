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

});

function _gambit_microtime() {
    return ( new Date ).getTime() / 1000;
}