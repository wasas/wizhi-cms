jQuery(document).ready(function ($) {

    /**
     * Jetpack Contact Form
     * replace Shortcake's UI with Jetpack's own UI for the contact form shortcode
     */
    $('body').on('mousedown', '.media-frame [data-shortcode="contact-form"]', function (e) {
        e.preventDefault();
        wp.media.frame.close();
        $('#insert-jetpack-contact-form').trigger('click');
        return false;
    });

});

jQuery(document).ready(function ($) {
    /**
     * Tabs
     */
    $('body').on('click', '.wizhi_modal_tab[data-for]', function () {
        var modal = $(this).parents('.mce-container:eq(0)');
        var tabContainer = $(this).parents('.wizhi_modal_tabs:eq(0)');
        tabContainer.siblings().hide();
        modal.find('#' + $(this).attr('data-for')).show();
        tabContainer.find('.wizhi_modal_tab').removeClass('active');
        $(this).addClass('active');
    });
});

/**
 * Open the extensions link when the "Get more shortcodes" shortcode is clicked.
 *
 * Since we can't override the click event, we change the data-shortcode attribute to
 * invalidate the default Shortcake behavior first. Only then can we add a click handler
 */
jQuery(document).ready(function ($) {

    // Change the data-shortcode to remove the click handler
    $('body').on('hover', '.shortcode-list-item[data-shortcode="wizhi_get_more_shortcodes"]', function (e) {
        $(this).attr('data-shortcode', '__wizhi_get_more_shortcodes');
    });

    // Open our extensions link when it's clicked
    $('body').on('click', '.shortcode-list-item[data-shortcode="__wizhi_get_more_shortcodes"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
    });
});

function _gambit_microtime() {
    return ( new Date ).getTime() / 1000;
}