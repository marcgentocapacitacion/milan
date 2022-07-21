require([
        'jquery'
    ],
    function($) {
        "use strict"

        if ($(window).width() < 1180) {
            $('.footer-content-block .link').click(function () {
                if ($(this).children('ul').css('display') == 'none') {
                    $(this).children('h4').children('.fa-caret-left').hide();
                    $(this).children('h4').children('.fa-caret-down').show();
                    $(this).children('ul').show(300);
                } else {
                    $(this).children('h4').children('.fa-caret-left').show();
                    $(this).children('h4').children('.fa-caret-down').hide();
                    $(this).children('ul').hide(300);
                }
            });

            $('.footer-content-block .category-label').click(function () {
                if ($('.category').css('display') == 'none') {
                    $('.category').show(300);
                    $(this).children('h4').children('.fa-caret-left').hide();
                    $(this).children('h4').children('.fa-caret-down').show();
                } else {
                    $('.category').hide(300);
                    $(this).children('h4').children('.fa-caret-left').show();
                    $(this).children('h4').children('.fa-caret-down').hide();
                }
            });
        }
    }
);
