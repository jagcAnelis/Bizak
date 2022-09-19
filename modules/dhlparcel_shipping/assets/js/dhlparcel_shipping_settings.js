jQuery(document).ready(function($) {
    console.log('scripts ready to go');

    $(document.body).on('dhlparcel_shipping:add_test_authenticate_button', function(e) {
        if ($('#DHLPARCEL_SHIPPING_TEST_AUTHENTICATE_BUTTON').length > 0) {
            $('#DHLPARCEL_SHIPPING_TEST_AUTHENTICATE_BUTTON')
                .closest('.form-group')
                .removeClass('hide')
                .html(dhlparcel_shipping_authenticate_template);
        }

    }).on('click', '#dhlparcel_shipping_settings_authenticate', function(e) {
        e.preventDefault();

        var user_id = $("#DHLPARCEL_SHIPPING_API_USER_ID").val();
        var key = $("#DHLPARCEL_SHIPPING_API_KEY").val();

        var data = $.extend(true, $(this).data(), {
            user_id: user_id,
            key: key
        });

        $('#dhlparcel_shipping_settings_authenticate').html(dhlparcel_shipping_authenticate_loading_message);
        // Disable button
        $('#dhlparcel_shipping_settings_authenticate').removeClass('dhlparcel_shipping_authenticate_button_success');
        $('#dhlparcel_shipping_settings_authenticate').removeClass('dhlparcel_shipping_authenticate_button_error');
        $('#dhlparcel_shipping_settings_authenticate').prop("disabled", true);

        $.post(dhlparcel_shipping_backoffice_ajax_authenticate, data, function (response) {

            try {
                var status = response.status;
                var message = response.message;
                var accounts = response.data.accounts;
            } catch (error) {
                alert('Error');
                return;
            }

            // Enable button
            $('#dhlparcel_shipping_settings_authenticate').prop("disabled", false);

            var dhlparcel_shipping_account_area = $('input#DHLPARCEL_SHIPPING_API_ACCOUNT_ID').parent();
            dhlparcel_shipping_account_area.children('div.dhlparcel_shipping_settings_suggestion_info').remove();
            dhlparcel_shipping_account_area.children('div.dhlparcel_shipping_settings_suggestion_accounts').remove();

            $('#dhlparcel_shipping_settings_authenticate').html(message);

            if (status === 'success') {
                $('#dhlparcel_shipping_settings_authenticate').addClass('dhlparcel_shipping_authenticate_button_success');

                if (!$.isEmptyObject(accounts)) {
                    dhlparcel_shipping_account_area.append('<div class="dhlparcel_shipping_settings_suggestion_info">' + dhlparcel_shipping_authenticate_accounts_message + '</div>');
                    $.each(accounts, function (index, value) {
                        dhlparcel_shipping_account_area.append('<div class="dhlparcel_shipping_settings_suggestion_accounts" data-account-id="' + value.toString() + '">' + value.toString() + '</div>');
                    });

                    // Autofill account if empty
                    if ($('input#DHLPARCEL_SHIPPING_API_ACCOUNT_ID').val().length === 0) {
                        var value = accounts[0];
                        $('input#DHLPARCEL_SHIPPING_API_ACCOUNT_ID').val(value);
                    }
                }

            } else {
                $('#dhlparcel_shipping_settings_authenticate').addClass('dhlparcel_shipping_authenticate_button_error');
            }

        }, 'json');

    }).on('click', '.dhlparcel_shipping_settings_suggestion_accounts', function(e) {
        var account_id = $(this).data('account-id');
        $('input#DHLPARCEL_SHIPPING_API_ACCOUNT_ID').val(account_id);

    }).on('dhlparcel_shipping:add_shipping_method_jump_button', function(e) {
        if ($('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_JUMP_BUTTON').length > 0) {
            $('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_JUMP_BUTTON')
                .closest('.form-group')
                .removeClass('hide')
                .html(dhlparcel_shipping_shipping_method_jump_template);
        }

    }).on('click', '#dhlparcel_shipping_settings_shipping_method_jump', function(e) {
        e.preventDefault();

        var dhlparcel_shipping_window = window.open(dhlparcel_shipping_shipping_method_jump_link, '_blank');
        if (dhlparcel_shipping_window) {
            dhlparcel_shipping_window.focus();
        } else {
            window.location.href = dhlparcel_shipping_shipping_method_jump_link;
        }

    }).on('dhlparcel_shipping:add_shipping_method_payment_button', function(e) {
        if ($('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_PAYMENT_BUTTON').length > 0) {
            $('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_PAYMENT_BUTTON')
                .closest('.form-group')
                .removeClass('hide')
                .html(dhlparcel_shipping_shipping_method_payment_template);
        }

    }).on('click', '#dhlparcel_shipping_settings_shipping_method_payment', function(e) {
        e.preventDefault();

        var dhlparcel_shipping_window = window.open(dhlparcel_shipping_shipping_method_payment_link, '_blank');
        if (dhlparcel_shipping_window) {
            dhlparcel_shipping_window.focus();
        } else {
            window.location.href = dhlparcel_shipping_shipping_method_payment_link;
        }

    }).on('dhlparcel_shipping:add_shipping_method_reset_button', function(e) {
        if ($('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_RESET_BUTTON').length > 0) {
            $('#DHLPARCEL_SHIPPING_SHIPPING_METHOD_RESET_BUTTON')
                .closest('.form-group')
                .removeClass('hide')
                .html(dhlparcel_shipping_shipping_method_reset_template);
        }

    }).on('click', '#dhlparcel_shipping_settings_shipping_method_reset', function(e) {
        e.preventDefault();

        if (confirm(dhlparcel_shipping_shipping_method_reset_message)) {
            $.post(dhlparcel_shipping_shipping_method_reset_link, [], function (response) {
                try {
                    var message = response.message;
                } catch (error) {
                    alert('Error');
                    return;
                }

                alert(message);
            });
        }
    }).on('dhlparcel_shipping:shipping_update_custom_reference', function () {
        var toggle_reference = $('select#DHLPARCEL_SHIPPING_AUTO_DEFAULT_REFERENCE').val() === '3'
        $('input#DHLPARCEL_SHIPPING_AUTO_DEFAULT_REFERENCE_CUSTOM').parents('.form-group').toggle(toggle_reference)

        var toggle_reference2 = $('select#DHLPARCEL_SHIPPING_AUTO_DEFAULT_REFERENCE2').val() === '3'
        $('input#DHLPARCEL_SHIPPING_AUTO_DEFAULT_REFERENCE2_CUSTOM').parents('.form-group').toggle(toggle_reference2)

    }).on('change', 'select.dhlparcel_shipping_service_option_reference', function () {
        $(document.body).trigger('dhlparcel_shipping:shipping_update_custom_reference');
    });

    $(document.body).trigger('dhlparcel_shipping:add_test_authenticate_button');
    $(document.body).trigger('dhlparcel_shipping:add_shipping_method_jump_button');
    $(document.body).trigger('dhlparcel_shipping:add_shipping_method_payment_button');
    $(document.body).trigger('dhlparcel_shipping:add_shipping_method_reset_button');
    $(document.body).trigger('dhlparcel_shipping:shipping_update_custom_reference');
});
