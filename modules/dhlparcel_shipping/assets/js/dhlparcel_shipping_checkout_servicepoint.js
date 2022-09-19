jQuery(document).ready(function($) {
    var dhlparcel_shipping_checkout_servicepoint_modal_loaded;
    var dhlparcel_shipping_checkout_servicepoint_modal_busy_loading;

    $(document.body).on('change', 'form#js-delivery input[type=radio]', function () {

        $(document.body).trigger('dhlparcel_shipping:load_servicepoint_info');

    }).on('dhlparcel_shipping:load_servicepoint_info', function() {
        var element = $('form#js-delivery .delivery-options input[type=radio]:checked:first');
        var value = element.val();

        if (typeof value === "undefined") {
            return;
        }

        var id_carrier = value.replace(/[^0-9]/gi, '');

        $.post(dhlparcel_shipping_checkout_servicepoint_link, [], function (response) {
            if ($.inArray(id_carrier, dhlparcel_shipping_checkout_servicepoints) > -1) {
                try {
                    var servicepoint = response.data.view.servicepoint;
                } catch (error) {
                    alert('Error');
                    return;
                }
                element.closest('.delivery-option').nextAll('.carrier-extra-content:first').html(servicepoint);
            }
        });

    }).on('click', 'button.dhlparcel_shipping_checkout_servicepoint_button', function (e) {
        e.preventDefault();

        // Do nothing if the base modal hasn't been loaded yet.
        if (dhlparcel_shipping_checkout_servicepoint_modal_loaded === false) {
            return;
        }

        if (typeof  window.dhlparcel_shipping_reset_servicepoint === "function") {

            var first_address = null;
            for (var address_id in prestashop.customer.addresses) {
                first_address = prestashop.customer.addresses[address_id];
                break;
            }

            var options = {
                //host: dhlpwc_parcelshop_locator.gateway, // TODO
                apiKey: dhlparcel_shipping_checkout_servicepoint_maps_key,
                query: first_address.postcode.toUpperCase(),
                countryCode: first_address.country_iso,
                limit: '7'
            };

            // Use the generated function provided by the component to load the ServicePoints
            window.dhlparcel_shipping_reset_servicepoint(options);

            $('div.dhlparcel_shipping_modal').show();
        } else {
            console.log('An unexpected error occured. ServicePoint functions were not loaded.');
        }


    }).on('dhlparcel_shipping:load_servicepoint_modal', function() {
        if (dhlparcel_shipping_checkout_servicepoint_modal_loaded === true) {
            return;
        }

        if (dhlparcel_shipping_checkout_servicepoint_modal_busy_loading === true) {
            return;
        }

        dhlparcel_shipping_checkout_servicepoint_modal_busy_loading = true;

        $(document.body).append(dhlparcel_shipping_checkout_servicepoint_locator);

        /* Set background image dynamically */
        $('.dhlparcel_shipping_modal_content').css('background-image', 'url(' + dhlparcel_shipping_checkout_servicepoint_gradient + ')');

        // Create selection function
        window.dhlparcel_shipping_select_servicepoint = function(event)
        {
            var first_address = null;
            for (var address_id in prestashop.customer.addresses) {
                first_address = prestashop.customer.addresses[address_id];
                break;
            }

            var dhlparcel_shipping_selected_servicepoint_id = event.id;
            var postcode = first_address.postcode.toUpperCase();

            if (typeof event.shopType !== 'undefined' && event.shopType === 'packStation' && event.address.countryCode === 'DE') {
                var dhlparcel_shipping_additional_servicepoint_id = prompt("Add your 'postnumber' for delivery at a DHL Packstation:");
                if (dhlparcel_shipping_additional_servicepoint_id != null && dhlparcel_shipping_additional_servicepoint_id != '') {
                    dhlparcel_shipping_selected_servicepoint_id = dhlparcel_shipping_selected_servicepoint_id + '|' + dhlparcel_shipping_additional_servicepoint_id;
                    $(document.body).trigger("dhlparcel_shipping:add_servicepoint_confirm_button");

                    event.name = event.keyword + ' ' + dhlparcel_shipping_additional_servicepoint_id;
                    $(document.body).trigger("dhlparcel_shipping:servicepoint_selection_sync", [dhlparcel_shipping_selected_servicepoint_id, postcode, event.address.countryCode]);
                } else {
                    $(document.body).trigger("dhlparcel_shipping:servicepoint_selection_sync", [null, null, null]);
                    $(document.body).trigger('dhlparcel_shipping:hide_servicepoint_selection_modal');
                }
            } else {
                $(document.body).trigger("dhlparcel_shipping:add_servicepoint_confirm_button");
                $(document.body).trigger("dhlparcel_shipping:servicepoint_selection_sync", [dhlparcel_shipping_selected_servicepoint_id, postcode, event.address.countryCode]);
            }
        };

        // Load CSS
        $('<link>')
            .appendTo('head')
            .attr({
                type: 'text/css',
                rel: 'stylesheet',
                href: dhlparcel_shipping_checkout_servicepoint_stylesheet
            });

        // Disable getScript from adding a custom timestamp
        $.ajaxSetup({cache: true});
        $.getScript("https://servicepoint-locator.dhlparcel.nl/servicepoint-locator.js").done(function() {
            dhlparcel_shipping_checkout_servicepoint_modal_loaded = true;
            dhlparcel_shipping_checkout_servicepoint_modal_busy_loading = false;
        });

    }).on('dhlparcel_shipping:servicepoint_selection_sync', function(e, servicepoint_id, postcode, country_code) {
        // Due to the cart page not having an actual form, we will temporarily remember the selection as a shadow selection.
        // The actual checkout form will always have priority, this is just backup logic.
        var data = {
            'servicepoint_id': servicepoint_id,
            'postcode': postcode,
            'country_code': country_code
        };

        $.post(dhlparcel_shipping_checkout_servicepoint_sync, data, function (response) {
            /* Reload */
            $(document.body).trigger('dhlparcel_shipping:load_servicepoint_info');
        });

    }).on('dhlparcel_shipping:add_servicepoint_confirm_button', function() {
        if ($('.dhl-parcelshop-locator .dhl-parcelshop-locator-desktop ul .dhlparcel_shipping_checkout_servicepoint_confirm_button').length === 0) {
            $('.dhl-parcelshop-locator .dhl-parcelshop-locator-desktop ul').prepend(dhlparcel_shipping_checkout_servicepoint_confirm_button);
        }

    }).on('click', '.dhlparcel_shipping_checkout_servicepoint_confirm_button', function(e) {
        e.preventDefault();
        $(document.body).trigger('dhlparcel_shipping:hide_servicepoint_modal');

    }).on('click', 'span.dhlparcel_shipping_modal_close', function(e) {
        e.preventDefault();
        $(document.body).trigger('dhlparcel_shipping:hide_servicepoint_modal');

    }).on('dhlparcel_shipping:hide_servicepoint_modal', function(e) {
        $('div.dhlparcel_shipping_modal').hide();

    });

    // Preload modal, since it's loaded dynamically (hidden DOM defaults)
    $(document.body).trigger('dhlparcel_shipping:load_servicepoint_modal');
    // Check ServicePoint on first load
    $(document.body).trigger('dhlparcel_shipping:load_servicepoint_info');

});
