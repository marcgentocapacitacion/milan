/**
 * Copyright © Wagento, Inc. All rights reserved.
 */

define([
    'jquery',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/alert'
], function ($, authenticationPopup, customerData, alert) {
    'use strict';

    return function (config, element) {
        $(element).ready(function () {
            console.log(config)
            if (config.validateMinimumAmount) {
                alert({
                    content: config.messageError
                });
            }
        });

        $(element).on('click', function (event) {
            if (config.validateMinimumAmount) {
                alert({
                    content: config.messageError
                });
                return false;
            }

            var cart = customerData.get('cart'),
                customer = customerData.get('customer');

            event.preventDefault();

            if (!customer().firstname && cart().isGuestCheckoutAllowed === false) {
                authenticationPopup.showModal();
                return false;
            }
            $(element).attr('disabled', true);
            location.href = config.checkoutUrl;
        });

    };
});
