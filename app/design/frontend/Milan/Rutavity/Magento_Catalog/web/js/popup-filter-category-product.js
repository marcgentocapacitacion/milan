define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery-ui-modules/widget'
], function ($, modal) {
    'use strict';

    $.widget('mage.popupFilterCategoryProduct', {
        modalWindow: null,
        options: {
            elementForPopup: '#narrow-by-list',
            elementToClick: '#icon-filter-category-product'
        },

        /**
         * Create mixin
         * @private
         */
        _create: function () {
            let self = this;
            self.createPopUp();
            $(self.options.elementToClick).click(function() {
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
                'modalClass': 'popup-filter-category-product',
                'responsive': true,
                'innerScroll': true,
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

    return $.mage.popupFilterCategoryProduct;
});
