
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Ui/js/model/messageList',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, ko, Component, messageContainer, modal, alert, $t) {
    'use strict';

    return Component.extend({
        modalWindow: null,
        formSubmit: '',
        defaults: {
            template: 'Magento_Customer/create-account-popup',
            form: '',
            urlPost: '',
            minimumPasswordLength: 8,
            requiredCharacterClassesNumber: ''
        },
        password: ko.observable(''),
        passwordConfirmation: ko.observable(''),

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function (element) {
            var options = {
                'type': 'popup',
                'modalClass': 'popup-create-account',
                'focus': '#email',
                'responsive': true,
                'innerScroll': true,
                'trigger': '.create-account',
                'buttons': []
            };

            this.modalWindow = element;
            modal(options, $(this.modalWindow));
        },

        /** Show login popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal').trigger('contentUpdated');
        },

        /**
         * Init
         */
        initialize: function () {
            var self = this;
            this._super();
            this.createPopUp($(self.form));
            this.isLoading(false);
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
        }
    });
});
