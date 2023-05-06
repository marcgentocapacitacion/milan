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
            messageText: "",
            template: 'Wagento_Catalog/message-verify-stock'
        },
        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super();
            if (!customerData.get('message-verify-stock').message) {
                customerData.reload(['message-verify-stock'], true);
            }
        },

        /**
         * @return string
         */
        message: function () {
            return customerData.get('message-verify-stock')().message;
        },

        /**
         * @return boolean
         */
        showMessage: function () {
            return customerData.get('message-verify-stock')().showMessage;
        }
    });
});
