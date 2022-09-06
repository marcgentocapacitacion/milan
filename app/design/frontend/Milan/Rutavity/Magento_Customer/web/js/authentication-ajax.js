
define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Ui/js/model/messageList',
    'Magento_Customer/js/model/authentication-popup',
    'Magento_Customer/js/action/login',
    'Magento_Ui/js/modal/alert',
    'mage/translate',
    'Magento_Customer/js/create-account-popup'
], function ($, ko, Component, messageContainer, authenticationPopup, loginAction, alert, $t, createAccountAjax) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Magento_Customer/login-popup',
            form: '',
            elementClick: '',
            config: {
                autocomplete: 'off',
                customerRegisterUrl: '',
                customerForgotPasswordUrl: '',
                baseUrl: '',
                customerLoginUrl: ''
            }
        },
        username: ko.observable(''),
        password: ko.observable(''),

        /**
         * Init
         */
        initialize: function () {
            var self = this;
            this._super();
            authenticationPopup.createPopUp($(self.form));
            $(self.elementClick).click(function () {
                self.showModal();
            });

            loginAction.registerLoginCallback(function () {
                self.isLoading(false);
            });
        },

        /**
         * Show modal to login
         */
        showModal: function () {
            authenticationPopup.showModal();
        },

        /**
         * Login with ajax
         */
        login: function () {
            var loginData = {},
                login
            ;
            loginData['username'] = this.username();
            loginData['password'] = this.password();
            loginData['context'] = 'login';
            this.isLoading(true);
            login = loginAction(loginData, window.location.href, '*', messageContainer);
            this.prepareMessage(login);
        },

        /**
         * Open popup Create account
         */
        register: function () {
            createAccountAjax({
                "form": "#create-accout-form"
            }).showModal();
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

        /**
         * Prepare message if have error
         */
        prepareMessage: function (login) {
            var error,
                message;

            $.when(login).done(function () {
                error = messageContainer.getErrorMessages()();
                message = '';
                for (var i in error) {
                    message += error[i];
                }

                if (message) {
                    alert({
                        title: $t('Error in login'),
                        content: message,
                        actions: {
                            always: function () {
                            }
                        }
                    });
                    messageContainer.clear();
                }
            });
        }
    });
})
