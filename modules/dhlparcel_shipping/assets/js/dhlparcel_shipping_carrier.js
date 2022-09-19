jQuery(document).ready(function($) {
    var dhlparcel_shipping_current_carrier_request_sequence = 0;
    var dhlparcel_shipping_current_carrier_request_timeout = 0;

    $(document.body).on('dhlparcel_shipping:load_carrier_form', function (e) {
        var data = {
            'carrier_id': dhlparcel_shipping_backoffice_carrier_id
        };

        $.post(dhlparcel_shipping_backoffice_carrier_link, data, function (response) {
            try {
                var form = response.data.view.form;
            } catch (error) {
                alert('Error');
                return;
            }

            $('form#step_carrier_general').after(form);
            resizeWizard();
            $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
            $(document.body).trigger('dhlparcel_shipping:trigger_carrier_temporary_save');
        });


    }).on('dhlparcel_shipping:disable_delivery_option_exclusions', function() {
        if ($('#dhlparcel_shipping_panel_service_options :input').length <= 0) {
            return;
        }

        // Reset services
        $("#dhlparcel_shipping_panel_service_options :input").each(function() {
            $(this).attr('disabled', false);
        });

        // Make sure a delivery option is selected before continuing
        if ($("input:radio[name='dhlparcel_shipping_delivery_options[]']:not(:disabled)").not(':checked')) {
            if (!$("input:radio[name='dhlparcel_shipping_delivery_options[]']:not(:disabled)").filter(':checked').length) {
                $("input:radio[name='dhlparcel_shipping_delivery_options[]']:not(:disabled):first").attr('checked', true);
            }
        }

        var disable_options = $("#dhlparcel_shipping_panel_delivery_options :input:checked:first").data('exclusions');
        $.each(disable_options, function (index, value) {
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:checked").attr('checked', false);
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:enabled").attr('disabled', true);
        });

        $(document.body).trigger('dhlparcel_shipping:disable_service_option_exclusions');

    }).on('dhlparcel_shipping:disable_service_option_exclusions', function() {

        var disable_options_collection = [];
        $("#dhlparcel_shipping_panel_service_options :input:checked").each(function() {
            disable_options = $(this).data('exclusions');
            $.each(disable_options, function (index, value) {
                disable_options_collection.push(value.toString());
            });
        });

        $.each(disable_options_collection, function (index, value) {
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:checked").attr('checked', false);
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:enabled").attr('disabled', true);
        });

    }).on('change', "#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_service_options[]']", function() {
        $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
        $(document.body).trigger('dhlparcel_shipping:trigger_carrier_temporary_save');

    }).on('change', "#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_delivery_options[]']", function() {
        $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
        $(document.body).trigger('dhlparcel_shipping:trigger_carrier_temporary_save');

    }).on('change', '#dhlparcel_shipping_panel_form input[name=dhlparcel_shipping_link]', function() {
        $(document.body).trigger('dhlparcel_shipping:trigger_carrier_temporary_save');

    }).on('dhlparcel_shipping:trigger_carrier_temporary_save', function(e) {
        var sequence = ++dhlparcel_shipping_current_carrier_request_sequence;

        $("#dhlparcel_shipping_ajax_loader").show();

        dhlparcel_shipping_current_carrier_request_timeout = setTimeout(function (sequence) {
            $(document.body).trigger('dhlparcel_shipping:carrier_temporary_save', [sequence]);
        }, 500, sequence);

    }).on('dhlparcel_shipping:carrier_temporary_save', function(e, sequence) {
        var link = $("input[name='dhlparcel_shipping_link']:checked").val() === '1' ? 1 : 0;

        var selected_options = [];
        $("input[name='dhlparcel_shipping_delivery_options[]']:checked").each(function () {
            selected_options.push($(this).val().toString());
        });
        $("input[name='dhlparcel_shipping_service_options[]']:checked").each(function () {
            selected_options.push($(this).val().toString());
        });

        var data = {
            'carrier_id': dhlparcel_shipping_backoffice_carrier_id,
            'link': link,
            'selected_options': selected_options
        };

        $.post(dhlparcel_shipping_backoffice_carrier_temporary_save_link, data, function (response) {
            if (sequence !== dhlparcel_shipping_current_carrier_request_sequence) {
                return;
            }

            $("#dhlparcel_shipping_ajax_loader").hide();
        });

    });

    $(document.body).trigger('dhlparcel_shipping:load_carrier_form');
});
