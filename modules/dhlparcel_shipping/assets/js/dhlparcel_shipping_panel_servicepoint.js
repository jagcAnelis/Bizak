var dhlparcel_shipping_panel_servicepoint_timeout = null;
var dhlparcel_shipping_panel_servicepoint_timeout_search = null;

jQuery(document).ready(function($) {

    $(document.body).on('click', '#dhlparcel_shipping_panel_servicepoint_change_button', function(e) {
        e.preventDefault();
        $('#dhlparcel_shipping_panel_servicepoint_preview').hide();
        $('#dhlparcel_shipping_panel_servicepoint_select').show();

        $(document.body).trigger('dhlparcel_shipping:panel_servicepoint_trigger');

    }).on('keyup', '#dhlparcel_shipping_panel_servicepoint_search', function(e) {
        e.preventDefault();
        $(document.body).trigger('dhlparcel_shipping:panel_servicepoint_trigger');

    }).on('dhlparcel_shipping:panel_servicepoint_trigger', function(e) {
        clearTimeout(dhlparcel_shipping_panel_servicepoint_timeout);
        dhlparcel_shipping_panel_servicepoint_timeout = setTimeout(function () {
            $(document.body).trigger('dhlparcel_shipping:panel_servicepoint_search');
        }, 500);

    }).on('dhlparcel_shipping:panel_servicepoint_search', function(e) {
        dhlparcel_shipping_panel_servicepoint_timeout_search = $('#dhlparcel_shipping_panel_servicepoint_search').val().toString();

        if ($('#dhlparcel_shipping_panel_servicepoint_search').val().toString() === '') {
            return;
        }

        var order_id = $("form#dhlparcel_shipping_panel_form input[name='dhlparcel_shipping_order_id']").val();

        // Make AJAX call to get the view for div
        var data = {
            'order_id': order_id,
            'search': dhlparcel_shipping_panel_servicepoint_timeout_search
        };

        $.post(dhlparcel_shipping_backoffice_ajax_panel_servicepoint, data, function (response) {
            if ($('#dhlparcel_shipping_panel_servicepoint_search').val().toString() !== dhlparcel_shipping_panel_servicepoint_timeout_search) {
                // Input is already old, don't show
                return;
            }

            try {
                list = response.data.view.list;
            } catch (error) {
                alert('Error');
                return;
            }

            $('#dhlparcel_shipping_panel_servicepoint_select_list').html(list);
        }, 'json');

    }).on('click', 'div.dhlparcel_shipping_panel_servicepoint_location', function(e) {
        e.preventDefault();

        var servicepoint_name = $(this).find('strong').html();
        var servicepoint_description = $(this).find('span').html();

        $('#dhlparcel_shipping_panel_servicepoint_input').val($(this).data('servicepoint-id'));
        $('#dhlparcel_shipping_panel_servicepoint_select_list').html('');
        $('#dhlparcel_shipping_panel_servicepoint_select').hide();
        $('#dhlparcel_shipping_panel_servicepoint_preview_text').html(servicepoint_name);
        $('#dhlparcel_shipping_panel_servicepoint_preview_description').html(servicepoint_description);
        $('#dhlparcel_shipping_panel_servicepoint_preview').show();
    });

});
