define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.showInfoAffiliate', {
        options: {
            elementClick: ''
        },
        /** @inheritdoc */
        _create: function () {
            let self = this;
            $(this.options.elementClick).each(function () {
                $(this).click(function () {
                    self._removeActive($(this).attr('data-id'));
                    if ($(this).hasClass('active')) {
                        $(this).removeClass('active');
                    } else {
                        $(this).addClass('active');
                    }
                });
            });
        },

        /**
         * @private
         */
        _removeActive: function (id) {
            $(this.options.elementClick).each(function () {
                if ($(this).attr('data-id') != id) {
                    $(this).removeClass('active');
                }
            });
        }
    });

    return $.mage.showInfoAffiliate;
});
