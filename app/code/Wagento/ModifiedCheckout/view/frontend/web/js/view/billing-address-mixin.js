/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'underscore',
    'jquery',
    'mage/translate'
], function (_, $, $t) {
    'use strict';

    var mixin = {
        /**
         * Alert after add to cart
         * @param id
         * @param isSame
         */
        sameShippingAddress: function (id, isSame) {
            var action_yes = $('#action-same-yes');
            var action_no = $('#action-same-no');
            if (isSame) {
                if (!action_no.hasClass('_active')) {
                    action_yes.removeClass('_active');
                    action_no.addClass('_active');
                    $('#' + id).prop('checked', isSame).trigger('click');
                }
            } else {
                if (!action_yes.hasClass('_active')) {
                    action_yes.addClass('_active');
                    action_no.removeClass('_active');
                    $('#' + id).prop('checked', isSame).trigger('click');
                }
            }
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
