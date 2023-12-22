
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Ui/js/model/messageList',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/alert',
    'mage/translate',
    'jquery-ui-modules/widget'
], function ($, ko, Component, messageContainer, modal, alert, $t) {
    'use strict';

    $.widget('mage.createCompanyAccountPopup', {
        modalWindow: null,
        options: {
            formSubmit: '',
            elementLinkCreateAccount: ''
        },

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function (element) {
            var options = {
                'type': 'popup',
                'modalClass': 'popup-create-company-account',
                'focus': '#company_name',
                'responsive': true,
                'innerScroll': true,
                'trigger': '.create-account',
                'buttons': []
            };

            this.modalWindow = element;
            try {
                modal(options, $(this.modalWindow));
            } catch (error) {
                console.log(error)
            }
        },

        /** Show login popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal').trigger('contentUpdated');
        },

        /**
         * Show loading
         * @param active
         */
        isLoading: function(active) {
            if (active) {
                $('body').loader().loader('show');
            } else {
                $('body').loader().loader('hide');
            }
        },

        _create: function () {
            var self = this;
            this.createPopUp($(self.options.formSubmit));
            this.isLoading(false);

            if (self.options.elementLinkCreateAccount) {
                $(document).ready(function () {
                    $(self.options.elementLinkCreateAccount).on('click', function (e) {
                        console.log(this)
                        e.preventDefault();
                        self.showModal();
                    });
                });
             }
        }
    });

    return $.mage.createCompanyAccountPopup;
});
