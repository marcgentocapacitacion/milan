/**
 * Rutavity theme stick header script
 * @package Milan_Rutavity
 * @author Rudie Wang <rudi.wang@wagento.com>
 */
require([
    'jquery'
], function ($) {
    $(window).scroll(function () {
        //variables
        var getHeaderHeight = $('.page-header').innerHeight();
        var scroll = $(window).scrollTop();
        if(scroll >= getHeaderHeight) {
            $(".page-header").addClass("sticky active");
        }
        else
        {
            $(".page-header").removeClass("sticky active");
        }
    });
});
