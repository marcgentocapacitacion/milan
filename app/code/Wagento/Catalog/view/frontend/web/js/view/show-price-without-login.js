/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

define([
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'jquery',
    'mage/mage',
    'mage/decorate'
], function (Component, customerData, $) {
    'use strict';

    return Component.extend({
        defaults: {
            blockPrice: ""
        },
        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
            if (!customerData.get('show-price-without-login')().showPrice) {
                customerData.reload(['show-price-without-login'], true);
            }
        },

        /**
         * return string
         */
        getBlockHtml: function () {
            if (this.showPrice()) {
                return this.blockPrice;
            }
            return '';
        },

        /**
         * @return boolean
         */
        showPrice: function () {
            return customerData.get('show-price-without-login')().showPrice;
        }
    });
});
