define([
    'jquery',
    'underscore',
    'weltpixel_searchautocomplete',
    'jquery-ui-modules/widget'
], function ($, _, searchAutoComplete) {
        'use strict';

    $.widget('mage.searchautocomplete', {
        options: {
            isEnablePopularSuggestions: '',
            isEnableCategorySearch: '',
            isEnableAutoComplete: '',
            previousSearch: '',
            minNumberOfCharacters: ''
        },

        /**
         * @private
         */
        _create: function () {
            var self = this;
            $(document).ready(function () {
                window.baseURL = window.BASE_URL;
                window.minNumberOfCharacters = self.options.minNumberOfCharacters;
                self._hideSearch();
                if (self.isCanUseSearch()) {
                    $('.search-autocomplete').remove();
                    self.eventKeyUp();
                }
            });
        },

        /**
         * @private
         */
        _hideSearch: function () {
            $('html').on('click', function(event){
                var targetClass = $(event.target).attr('class'),
                    searchClass = 'searchautocomplete';
                if (targetClass != searchClass) {
                    $('#' + searchClass).hide();
                }
            })
        },

        /**
         * @return {boolean}
         */
        isCanUseSearch: function () {
            if (this.options.isEnableAutoComplete
                || this.options.isEnablePopularSuggestions
                || this.options.isEnableCategorySearch) {
                return true;
            }
            return false;
        },

        /**
         * Search data
         */
        eventKeyUp: function () {
            var self = this;
            $('#search').on('keyup', _.debounce(function () {
                var value = $(this).val();
                if (value == self.options.previousSearch && $('#search_autocomplete').is(':visible')) {
                    return;
                }
                self.options.previousSearch = value;
                if (self.options.isEnableAutoComplete && value.length >= window.minNumberOfCharacters) {
                    $(".search .control").addClass("loader-ajax");
                    searchAutoComplete.ajaxSearch();
                } else {
                    $('#searchautocomplete').hide();
                }
            }, 750));
        }
    });

    return $.mage.searchautocomplete;
});
