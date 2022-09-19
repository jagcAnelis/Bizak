/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * @author    PresTeamShop.com <support@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   see file: LICENSE.txt
 * @category  PrestaShop
 * @category  Module
 *
 * Original copyright: jeoQuery v0.4  - Copyright 2012, Thomas Haukland
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */

var jeoquery = (function ($) {
    var my = {};

    my.geoNamesApiServer = 'api.geonames.org';
    my.geoNamesProtocol = 'http';
    my.defaultData = {
      userName: 'presteamshop',
      lang: 'en',
      countryCode: 'ES'
    };

    my.getGeoNames = function(method, data, callback) {
        var deferred = $.Deferred();
        var data_geonames = {
            'is_ajax': true,
            'dataType': 'html',
            'action': 'callGeonamesJSON',
            'method': method + 'JSON',
            'params': $.extend({}, my.defaultData, data)
        };

        $.ajax({
            url: prestashop.urls.pages.order + '?rand=' + new Date().getTime(),
            type: 'POST',
            'dataType': 'json',
            data: data_geonames,
            success: function(data) {
                deferred.resolve(data);
                if (!!callback) callback(data);
            },
            error: function (xhr, textStatus) {
              deferred.reject(xhr, textStatus);
              //alert('Ooops, geonames server returned: ' + textStatus);
            }
        });
        return deferred.promise();
    }

    return my;
}(jQuery));

(function ($) {
    $.fn.jeoCountrySelect = function (options) {
        var el = this;
        $.when(jeoquery.getGeoNames('countryInfo'))
         .then(function (data) {
            var sortedNames = data.geonames;
            if (data.geonames.sort) {
                sortedNames = data.geonames.sort(function (a, b) {
                    return a.countryName.localeCompare(b.countryName);
                });
            }
            sortedNames.unshift({countryCode:'', countryName:''});
            var html = $.map(sortedNames, function(c) {
              return '<option value="' + c.countryCode + '">' + c.countryName + '</option>';
            });
            el.html(html);
            if (options && options.callback) options.callback(sortedNames);
        });
    };

    $.fn.jeoPostalCodeLookup = function (options) {
        this.on("change", function () {
            var code = $(this).val();
            var country = options.country || jeoquery.defaultData.countryCode;
            if (options.countryInput) {
                country = options.countryInput.val() || jeoquery.defaultCountry;
            }
            if (code) {
                jeoquery.getGeoNames('postalCodeLookup', {postalcode: code, country: country}, function (data) {
                    if (data && data.postalcodes && data.postalcodes.length > 0) {
                        if (options) {
                            if (options.target) {
                                options.target.val(data.postalcodes[0].placeName);
                            }
                            if (options.callback) {
                                options.callback(data.postalcodes[0]);
                            }
                        } else {
                            if (options.target) {
                                options.target.val('');
                            }
                        }
                    } else {
                        if (options.target) {
                            options.target.val('');
                        }
                    }
                });
            }
        });
    };

    $.fn.jeoCityAutoComplete = function (options) {
        this.autocomplete({
            source: function (request, response) {
                jeoquery.getGeoNames('search', {
                    featureClass: 'P',
                    style: "full",
                    maxRows: 12,
                    name_startsWith: request.term,
                    country: options.country
                }, function (data) {
                    response($.map(data.geonames, function (item) {
                        var displayName = item.name + (item.adminName1 ? ", " + item.adminName1 : "") + ", " + item.countryName;
                        item.term = request.term;

                        return {
                            label: displayName,
                            value: '',
                            details: item
                        };
                    }));
                });
            },
            minLength: 2,
            select: function( event, ui ) {
                if (ui && ui.item && options && options.callback) {
                    options.callback(ui.item.details);
                }
            }
        });
    };

    $.fn.jeoPostCodeAutoComplete = function (options) {
        //se hace para mantener el evento del validador
        var events = $._data(this.clone(true, true)[0], 'events');
        var event_validation = null;

        $.each(events, function(i, event){
            $.each(event, function(i, item){
                if (item.type == 'blur' && item.namespace == 'validation') {
                    event_validation = item;
                }
            });
        });

        this.typeahead('destroy').typeahead(
        {
            name: $(this).attr('id'),
            local: {},
            showHintOnFocus: false,
            source: function (query, process) {
                country = options.country || jeoquery.defaultCountry;

                jeoquery.getGeoNames('postalCodeSearch', {
                    maxRows: 12,
                    postalcode_startsWith: query,
                    country: options.country
                }, function (data) {
                    process($.map(data.postalCodes, function (item) {
                        var displayName = item.postalCode + ', ' + item.placeName + ', ' + item.adminName1 + '(' + item.countryCode + ')';

                        return [{
                            name: displayName,
                            value: item.postalCode,
                            details: item
                        }];
                    }));
                });
            },
            minLength: 2,
            updater: function (item) {
                if (options && options.callback) {
                    options.callback(item.details);
                }

                return item.value;
            }
        });

        if (event_validation !== null) {
            $._data(this[0], 'events')['blur'].push(event_validation);
        }
    };

})(jQuery);