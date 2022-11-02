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
                    self._removeActive();
                    $(this).addClass('active');
                });
            });
        },

        /**
         * @private
         */
        _removeActive: function () {
            $(this.options.elementClick).each(function () {
                $(this).removeClass('active');
            });
        }
    });

    return $.mage.showInfoAffiliate;
});
