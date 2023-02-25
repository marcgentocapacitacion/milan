define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/view/summary',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Customer/js/customer-data'
    ],
    function(
        $,
        ko,
        Component,
        stepNavigator,
        customerData
    ) {
        'use strict';

        return Component.extend({

            /**
             * @return {Boolean}
             */
            isVisible: function () {
                return stepNavigator.isProcessed('shipping');
            },

            /**
             * @return {*}
             */
            getMessageShippingType: function (){
                var company = customerData.get('shipping_type')();
                return company.message_shipping_type;
            },

            /**
             * Initialize
             */
            initialize: function () {
                $(function() {
                    $('body').on("click", '#place-order-trigger', function () {
                        $(".payment-method._active").find('.action.primary.checkout').trigger( 'click' );
                    });
                });
                var self = this;
                this._super();
            }

        });
    }
);
