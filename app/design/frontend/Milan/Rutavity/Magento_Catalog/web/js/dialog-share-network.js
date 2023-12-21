define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'jquery-ui-modules/widget'
], function ($, modal, $t) {
    'use strict';

    $.widget('mage.dialogShareNetwork', {
        modalWindow: null,
        options: {
            elementForPopup: '#dialog-share-network'
        },

        /**
         * Create mixin
         * @private
         */
        _create: function () {
            var self = this;
            self.createPopUp();
            this.element.on('click', function () {
                self.showModal();
            });
        },

        /**
         * Create popUp window for provided element
         *
         * @param {HTMLElement} element
         */
        createPopUp: function () {
            let self = this,
                options = {
                    'type': 'popup',
                    'modalClass': 'dialog-share-network',
                    'responsive': true,
                    'innerScroll': true,
                    'title': $t('Share on'),
                    'buttons': []
                };

            this.modalWindow = $(self.options.elementForPopup);
            modal(options, $(self.modalWindow));
        },

        /** Show login popup window */
        showModal: function () {
            $(this.modalWindow).modal('openModal').trigger('contentUpdated');
        }
    });
    return $.mage.dialogShareNetwork;
});
