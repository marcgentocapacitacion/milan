define([
        'jquery',
    ],
    function($){
        return function() {
            $.widget('mage.custommenu', $.mage.menu, {
                _toggleMobileMode: function () {
                    var subMenus;

                    $(this.element).off('mouseenter mouseleave');
                    this._on({

                        /**
                         * @param {jQuery.Event} event
                         */
                        'click .ui-menu-item:has(a)': function (event) {
                            var target;

                            event.preventDefault();
                            target = $(event.target).closest('.ui-menu-item');
                            target.get(0).scrollIntoView();

                            // Open submenu on click
                            if (target.has('.ui-menu').length) {
                                this.expand(event);
                            } else if (!this.element.is(':focus') &&
                                $(this.document[0].activeElement).closest('.ui-menu').length
                            ) {
                                // Redirect focus to the menu
                                this.element.trigger('focus', [true]);

                                // If the active item is on the top level, let it stay active.
                                // Otherwise, blur the active item since it is no longer visible.
                                if (this.active && this.active.parents('.ui-menu').length === 1) { //eslint-disable-line
                                    clearTimeout(this.timer);
                                }
                            }

                            if (!target.hasClass('parent') || !target.has('.ui-menu').length) {
                                window.location.href = target.find('> a').attr('href');
                            }
                        },

                        /**
                         * @param {jQuery.Event} event
                         */
                        'click .ui-menu-item:has(.ui-state-active)': function (event) {
                            this.collapseAll(event, true);
                        }
                    });

                    subMenus = this.element.find('.parent');
                    $.each(subMenus, $.proxy(function (index, item) {
                        var category = $(item).find('> a span').not('.ui-menu-icon').text(),
                            categoryUrl = $(item).find('> a').attr('href'),
                            menu = $(item).find('> .ui-menu');

                        this.categoryLink = $('<a>')
                            .attr('href', categoryUrl)
                            .text($.mage.__('All %1').replace('%1', category));

                        this.categoryParent = $('<li>')
                            .addClass('ui-menu-item all-category')
                            .html(this.categoryLink);

                        if (menu.find('.all-category').length === 0) {
                            menu.prepend(this.categoryParent);
                        }

                    }, this));
                }
            });

            return $.mage.custommenu;
        }
    });
