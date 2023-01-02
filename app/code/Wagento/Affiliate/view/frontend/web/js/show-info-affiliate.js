define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('mage.showInfoAffiliate', {
        options: {
            elementClick: '',
            elementInfo: ''
        },
        /** @inheritdoc */
        _create: function () {
            let self = this;
            $(this.options.elementClick).each(function () {
                $(this).click(function () {
                    let id = $(this).attr('data-id'),
                        idElement = self.options.elementInfo + '[data-id=' + id + ']';
                    self._removeActive(id);
                    if ($(idElement).hasClass(self._getClassActive($(idElement)))) {
                        $(idElement).removeClass(self._getClassActive($(idElement)));
                    } else {
                        $(idElement).addClass(self._getClassActive($(idElement)));
                    }
                });
            });
        },

        /**
         * @private
         */
        _getClassActive: function (element) {
            let width = element.width(),
                position = element.position(),
                screenSize = $(window).width(),
                total = parseInt(width) + parseInt(position.left),
                percentage = (total / screenSize) * 100;
            if (percentage > 70) {
                return 'active-invert';
            }
            return 'active';
        },

        /**
         * @private
         */
        _removeActive: function (id) {
            let self = this;
            $(self.options.elementInfo).each(function () {
                if ($(this).attr('data-id') != id) {
                    $(this).removeClass('active');
                    $(this).removeClass('active-invert');
                }
            });
        }
    });

    return $.mage.showInfoAffiliate;
});
