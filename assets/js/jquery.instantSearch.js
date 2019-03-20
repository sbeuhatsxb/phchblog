/**
 * jQuery plugin for an instant searching.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
(function ($) {
    'use strict';

    String.prototype.render = function (parameters) {
        return this.replace(/({{ (\w+) }})/g, function (match, pattern, name) {
            return parameters[name];
        })
    };

    // INSTANTS SEARCH PUBLIC CLASS DEFINITION
    // =======================================

    var InstantSearch = function (element, options) {
        this.$input = $(element);
        this.$form = this.$input.closest('form');
        this.$preview = $('<div class="row">').appendTo(this.$form);
        this.options = $.extend({}, InstantSearch.DEFAULTS, this.$input.data(), options);

        this.$input.keyup(this.debounce());
    };

    InstantSearch.DEFAULTS = {
        minQueryLength: 2,
        limit: 10,
        delay: 500,
        noResultsMessage: 'No results found',
            itemTemplate: '\
                            <article>\
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 post_item" style="padding-left: 0;">\
                                    <a href="article/{{ id }}">\
                                        <div data-mh="my-group" class="card card-small" style="min-height: 800px">\
                                                <img class="rounded mx-auto d-block card-img-top" style="min-height: 400px" src="uploads/images/{{ linkedImage }}"\
                                                     alt="{{ title }}">\
                                            <div class="card-body d-flex flex-column" style="min-width: 360px;">\
                                                <h2 style="min-height: 66px">{{ title }}</h2>\
                                                <p class="card-text" style="min-height: 168px">{{ summary }}</p>\
                                                <div class="container text-right">\
                                                    <b >Marque : {{ linkedBrands }}</b>\
                                                </div>\
                                                <div class="container text-right">\
                                                    <b >Prix : {{ price }} €</b>\
                                                </div>\
                                                <div class="mt-auto">\
                                                   <button class="btn btn-danger" style="margin-top: auto;">Ajouter au panier</button>\
                                                </div>\
                                            </div>\
                                        </div>\
                                    </a>\
                                </div>\
                            </article>'
    };

    InstantSearch.prototype.debounce = function () {
        var delay = this.options.delay;
        var search = this.search;
        var timer = null;
        var self = this;

        return function () {
            clearTimeout(timer);
            timer = setTimeout(function () {
                search.apply(self);
            }, delay);
        };
    };

    InstantSearch.prototype.search = function () {
        var query = $.trim(this.$input.val()).replace(/\s{2,}/g, ' ');
        if (query.length < this.options.minQueryLength) {
            this.$preview.empty();
            return;
        }

        var self = this;
        var data = this.$form.serializeArray();
        data['l'] = this.limit;

        $.getJSON(this.$form.attr('action'), data, function (items) {
            self.show(items);
        });
    };

    InstantSearch.prototype.show = function (items) {
        var $preview = this.$preview;
        var itemTemplate = this.options.itemTemplate;

        if (0 === items.length) {
            $preview.html(this.options.noResultsMessage);
        } else {
            $preview.empty();
            $.each(items, function (index, item) {
                $preview.append(itemTemplate.render(item));
            });
        }
    };

    // INSTANTS SEARCH PLUGIN DEFINITION
    // =================================

    function Plugin(option) {
        return this.each(function () {
            var $this = $(this);
            var instance = $this.data('instantSearch');
            var options = typeof option === 'object' && option;

            if (!instance) $this.data('instantSearch', (instance = new InstantSearch(this, options)));

            if (option === 'search') instance.search();
        })
    }

    $.fn.instantSearch = Plugin;
    $.fn.instantSearch.Constructor = InstantSearch;

})(window.jQuery);
