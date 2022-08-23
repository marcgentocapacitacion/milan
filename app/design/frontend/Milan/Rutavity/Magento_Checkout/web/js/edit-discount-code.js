
define([
    'jquery',
    'jquery-ui-modules/widget'
], function ($) {
    'use strict';

    $.widget('mage.editDiscountCode', {
        options: {
        },

        _create: function () {
            $(this.options.editButton).on('click', $.proxy(function (){
                $(this.options.applyButton).removeAttr('disabled');
                $(this.options.textCode).removeAttr('disabled');
            }, this));
        }
    });

    return $.mage.editDiscountCode;
});
