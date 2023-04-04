/**
 * @api
 */
define([
    'jquery',
    'matchMedia',
    'jquery-ui-modules/widget'
], function ($, mediaCheck) {
    'use strict';

    $.widget('mage.openSearch', {
        options: {
            formSelector: '',
            closeSearch: '#close-search',
            searchLabel: '[data-role=minisearch-label]',
            submitBtn: 'button[type="submit"]'
        },

        /**
         * @private
         */
        _create: function () {
            this.searchForm = $(this.options.formSelector);
            this.searchLabel = this.searchForm.find(this.options.searchLabel);
            this.closeSearch = this.searchForm.find(this.options.closeSearch);
            this.submitBtn = this.searchForm.find(this.options.submitBtn);
            this.isExpandable = false;

            mediaCheck({
                media: '(max-width: 768px)',
                entry: function () {
                    this.isExpandable = true;
                }.bind(this)
            });

            this.searchLabel.on('click', function (e) {
                if (!this.isExpandable && this.isActive()) {
                    this.searchLabel.trigger('focus');
                    this.setActiveState(false);
                    e.preventDefault();
                }
            }.bind(this));

            this.closeSearch.on('click', function () {
                this.setActiveState(false);
                if(this.isExpandable) {
                    this.searchLabel.show();
                }

            }.bind(this));

            if (this.element.get(0) === document.activeElement) {
                this.setActiveState(true);
            }

            this.element.on('focus', this.setActiveState.bind(this, true));
        },

        /**
         * Checks if search field is active.
         *
         * @returns {Boolean}
         */
        isActive: function () {
            return this.searchLabel.hasClass('active');
        },

        /**
         * Sets state of the search field to provided value.
         *
         * @param {Boolean} isActive
         */
        setActiveState: function (isActive) {
            var searchValue;

            this.searchForm.toggleClass('active', isActive);
            this.searchLabel.toggleClass('active', isActive);

            if (this.isExpandable) {
                this.element.attr('aria-expanded', isActive);
                searchValue = this.element.val();
                this.element.val('');
                this.element.val(searchValue);
            }
        },
    });
    return $.mage.openSearch;
});
