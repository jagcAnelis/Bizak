jQuery(document).ready(function($) {

    var dhlparcel_shipping_current_request_sequence = 0;
    var dhlparcel_shipping_current_request_timeout = 0;
    var dhlparcel_shipping_current_request_level = 0;

    // Set panel as first (move panel to top)
    // TODO: check depending on setting whether to do this or not
    $("#dhlparcel_shipping_panel_container").parent().prepend($("#dhlparcel_shipping_panel_container"));

    $(document.body).on('click', '#dhlparcel_shipping_panel_create', function(e) {
        e.preventDefault();
        var size = $('.dhlparcel_shipping_panel_size:checked:not(:disabled)').val();
        if (typeof size === "undefined") {
            // TODO update alert to a more user friendly user feedback
            alert('Select a label');
            return;
        }

        var order_id = $("form#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_order_id']").val();
        var selected_options = [];
        var selected_options_data = {};
        $("input[name='dhlparcel_shipping_delivery_options[]']:checked, input[name='dhlparcel_shipping_service_options[]']:checked").each(function () {
            var selected_option = $(this).val().toString();
            selected_options.push(selected_option);

            $('.dhlparcel_shipping_panel_delivery_option_input, .dhlparcel_shipping_panel_service_option_input')
                .filter('[data-option-input="' + selected_option + '"]')
                .find('input.dhlparcel_shipping_panel_option_data')
                .each(function() {
                    selected_options_data[selected_option] = $(this).val().toString();
                });
        });

        var business = $("input[name='dhlparcel_shipping_is_business']:checked").val() === '1' ? 1 : 0;

        var data = {
            'order_id': order_id,
            'business': business,
            'selected_options': selected_options,
            'selected_options_data': selected_options_data,
            'size': size
        };

        $.post(dhlparcel_shipping_backoffice_ajax_panel_create, data, function (response) {

            try {
                var notifications = response.data.view.notifications;
                var labels = response.data.view.labels;
                var extra = response.data.extra;
            } catch (error) {
                alert('Error');
                return;
            }

            $('#dhlparcel_shipping_panel_notifications_container').html(notifications);
            $('#dhlparcel_shipping_panel_labels_container').html(labels);

            if (extra !== null) {
                $('#shipping #shipping_table span.shipping_number_show:first').fadeOut(function() {
                    $(this).text(extra.tracking_number).fadeIn();
                });
            }

        }, 'json');

    }).on('click', '.dhlparcel_shipping_panel_label_action', function(e) {
        if ($(this).data('type') !== 'delete') {
            return;
        }

        e.preventDefault();

        var data = {
            'label_id': $(this).data('label-id')
        };

        $.post(dhlparcel_shipping_backoffice_ajax_panel_delete, data, function (response) {

            try {
                var notifications = response.data.view.notifications;
                var labels = response.data.view.labels;
            } catch (error) {
                alert('Error');
                return;
            }

            $('#dhlparcel_shipping_panel_notifications_container').html(notifications);
            $('#dhlparcel_shipping_panel_labels_container').html(labels);

        }, 'json');

    }).on('change', "#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_service_options[]']", function() {
        $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
        $(document.body).trigger('dhlparcel_shipping:refresh_form', [1]);

    }).on('change', "#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_delivery_options[]']", function() {
        $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
        $(document.body).trigger('dhlparcel_shipping:refresh_form', [1]);

    }).on('change', '#dhlparcel_shipping_panel_form input[name=dhlparcel_shipping_is_business]', function() {
        $(document.body).trigger('dhlparcel_shipping:refresh_form', [3]);

    }).on('dhlparcel_shipping:refresh_form', function(e, new_request_level) {
        if (dhlparcel_shipping_current_request_level > new_request_level) {
            // Current request loads more things, ignore this request
            return;
        }

        dhlparcel_shipping_current_request_level = new_request_level;
        var sequence = ++dhlparcel_shipping_current_request_sequence;

        if (dhlparcel_shipping_current_request_timeout) {
            clearTimeout(dhlparcel_shipping_current_request_timeout);
        }

        // TODO Disable certain boxes based on level
        if (new_request_level >= 1) {
            $("#dhlparcel_shipping_panel_sizes :input").attr("disabled", true);
        }
        if (new_request_level >= 2) {
            $("#dhlparcel_shipping_panel_service_options :input").attr("disabled", true);
        }
        if (new_request_level >= 3) {
            $("#dhlparcel_shipping_panel_delivery_options :input").attr("disabled", true);
        }

        // Set time based on level
        var time = 1100;
        if (new_request_level === 1) {
            time = 1100;
        } else if (new_request_level === 2 ) {
            time = 550;
        } else if (new_request_level >= 3 ) {
            time = 0;
        }

        dhlparcel_shipping_current_request_timeout = setTimeout(function(sequence) {
            $(document.body).trigger('dhlparcel_shipping:refresh_form_call', [new_request_level, sequence]);
        }, time, sequence);

    }).on('dhlparcel_shipping:disable_delivery_option_exclusions', function() {
        // Don't do anything on level 3+ request
        if (dhlparcel_shipping_current_request_level >= 3) {
            return;
        }

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

        // // Hide all delivery and service input
        $('.dhlparcel_shipping_panel_delivery_option_input').hide();

        // Show input if available
        if ($("input[name='dhlparcel_shipping_delivery_options[]']:checked:first").length > 0) {
            $('.dhlparcel_shipping_panel_delivery_option_input').filter('[data-option-input="' +
                $("input[name='dhlparcel_shipping_delivery_options[]']:checked:first").val().toString() +
                '"]').show();
        }

        var disable_options = $("#dhlparcel_shipping_panel_delivery_options :input:checked:first").data('exclusions');
        $.each(disable_options, function (index, value) {
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:checked").attr('checked', false);
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:enabled").attr('disabled', true);
        });

        $(document.body).trigger('dhlparcel_shipping:disable_service_option_exclusions');

    }).on('dhlparcel_shipping:disable_service_option_exclusions', function() {
        // Don't do anything on level 2+ request
        if (dhlparcel_shipping_current_request_level >= 2) {
            return;
        }

        // Hide all service input
        $('.dhlparcel_shipping_panel_service_option_input').hide();

        var disable_options_collection = [];
        $("#dhlparcel_shipping_panel_service_options :input:checked").each(function() {
            disable_options = $(this).data('exclusions');
            $.each(disable_options, function (index, value) {
                disable_options_collection.push(value.toString());
            });
            // Show input if available
            $('.dhlparcel_shipping_panel_service_option_input').filter('[data-option-input="'+$(this).val().toString()+'"]').show();
        });

        $.each(disable_options_collection, function (index, value) {
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:checked").attr('checked', false);
            $("#dhlparcel_shipping_panel_service_options :input[value='" + value + "']:enabled").attr('disabled', true);
        });

    }).on('dhlparcel_shipping:select_default_size', function() {
        if ($('input:radio[name=dhlparcel_shipping_size]').length <= 0) {
            return;
        }

        if ($('input:radio[name=dhlparcel_shipping_size]:not(:disabled)').not(':checked')) {
            if (!$('input:radio[name=dhlparcel_shipping_size]:not(:disabled)').filter(':checked').length) {
                $('input:radio[name=dhlparcel_shipping_size]:not(:disabled):first').attr('checked', true);
            }
        }

    }).on('dhlparcel_shipping:refresh_form_call', function(e, level, sequence) {
        var order_id = $("form#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_order_id']").val();
        var selected_options = [];
        var selected_options_data = {};
        $("input[name='dhlparcel_shipping_delivery_options[]']:checked, input[name='dhlparcel_shipping_service_options[]']:checked").each(function () {
            var selected_option = $(this).val().toString();
            selected_options.push(selected_option);

            $('.dhlparcel_shipping_panel_delivery_option_input, .dhlparcel_shipping_panel_service_option_input')
                .filter('[data-option-input="' + selected_option + '"]')
                .find('input.dhlparcel_shipping_panel_option_data')
                .each(function() {
                    selected_options_data[selected_option] = $(this).val().toString();
                });
        });

        var business = $("input[name='dhlparcel_shipping_is_business']:checked").val() === '1' ? 1 : 0;
        var data = {
            'level': level,
            'order_id': order_id,
            'business': business,
            'selected_options': selected_options,
            'selected_options_data': selected_options_data
        };

        $.post(dhlparcel_shipping_backoffice_ajax_panel, data, function (response) {

            if (sequence !== dhlparcel_shipping_current_request_sequence) {
                // Request has been replaced, ignore this output
                return;
            }

            try {
                var delivery_options = response.data.view.delivery_options;
                var service_options = response.data.view.service_options;
                var sizes = response.data.view.sizes;
            } catch (error) {
                alert('Error');
                return;
            }

            // Reset
            dhlparcel_shipping_current_request_level = 0;

            // Based on level, replace parts of the form
            if (level >= 3) {
                // Update shipping methods
                $('#dhlparcel_shipping_panel_delivery_options_container').html(delivery_options);
            }

            if (level >= 2) {
                // Update services
                $('#dhlparcel_shipping_panel_service_options_container').html(service_options);
            }

            if (level >= 1) {
                // Update sizes
                $('#dhlparcel_shipping_panel_sizes_container').html(sizes);

                $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
                $(document.body).trigger('dhlparcel_shipping:select_default_size');
            }

        }, 'json');

    }).on('click', '#dhlparcel_shipping_panel_header', function(e) {
        $('#dhlparcel_shipping_panel_container').toggleClass('dhlparcel_shipping_panel_minimize');
    });

    $(document.body).trigger('dhlparcel_shipping:disable_delivery_option_exclusions');
    $(document.body).trigger('dhlparcel_shipping:select_default_size');

});
