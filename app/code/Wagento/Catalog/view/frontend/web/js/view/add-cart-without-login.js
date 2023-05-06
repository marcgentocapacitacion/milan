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
            blockAddToCart: ""
        },
        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
            if (!customerData.get('add-cart-without-login')().showAddToCart) {
                customerData.reload(['add-cart-without-login'], true);
            }
        },

        /**
         * return string
         */
        getBlockHtml: function () {
            if (this.showAddToCart()) {
                return this.blockAddToCart;
            }
            return '';
        },

        /**
         * @return boolean
         */
        showAddToCart: function () {
            return customerData.get('add-cart-without-login')().showAddToCart;
        }
    });
});
