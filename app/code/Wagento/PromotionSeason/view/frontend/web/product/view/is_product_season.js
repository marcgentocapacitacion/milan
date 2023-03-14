define(["jquery", 'mage/gallery/gallery', "jquery-ui-modules/widget"], function ($, gallery) {
    $.widget('mage.isProductSeason', {
        options: {
            productOptionsIsPromotionSeason: {},
            productTypeSimple: false
        },

        /**
         * @private
         */
        _create: function () {
            var self = this;
            if (self.options.productTypeSimple) {
                $('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function () {
                    self._showTag();
                });
            } else {
                $('[data-gallery-role=gallery-placeholder]').on('gallery:loaded', function () {
                    $('[class*="swatch-opt"]').on('click', '.swatch-option', function () {
                        $('.fotorama').on('fotorama:ready', function (e, fotorama) {
                            var showTag = false;
                            var optionIdClicked = $(this).attr('data-option-id');
                            var attributeIdClicked = $(this).parent().parent().attr('data-attribute-id');
                            var ariaChecked = ($(this).attr('aria-checked') == 'false');
                            $('.swatch-attribute').each(function () {
                                let optionId = $(this).attr('data-option-selected');
                                let attributeId = $(this).attr('data-attribute-id');
                                if (attributeIdClicked == attributeId) {
                                    optionId = optionIdClicked;
                                }
                                if (!optionId || !attributeId) {
                                    return;
                                }

                                if ((ariaChecked && attributeIdClicked == attributeId) || attributeIdClicked != attributeId) {
                                    if (self._verifyIsProductPromotionSeason(optionId, attributeId)) {
                                        showTag = true;
                                    }
                                }
                            });

                            if (showTag) {
                                self._showTag();
                            } else {
                                self._hideTag();
                            }
                        });
                    });
                });
            }
        },

        /**
         * Verify if product is season and return true or false
         * @private
         */
        _verifyIsProductPromotionSeason: function (optionId, attributeId) {
            if (!this.options.productOptionsIsPromotionSeason[attributeId]) {
                return false;
            }

            if (!this.options.productOptionsIsPromotionSeason[attributeId][optionId]) {
                return false;
            }
            return this.options.productOptionsIsPromotionSeason[attributeId][optionId];
        },

        /**
         * Show tag promotion
         * @private
         */
        _showTag: function () {
            let top = this._positionTag();
            this.element.css('top', top)
            this.element.fadeIn();
        },

        /**
         * Hide tag promotion
         * @private
         */
        _hideTag: function () {
            this.element.fadeOut();
        },

        /**
         * Return size of the image to position the tag
         * @return {*|jQuery}
         * @private
         */
        _positionTag: function () {
            let height = parseInt(
                $('.gallery-placeholder > .fotorama-item > .fotorama__wrap > .fotorama__stage').height()
            );
            if (height > 0) {
                height -= 40;
            }
            return height + 'px';
        }
    });
    return $.mage.isProductSeason;
});
