/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @category  PrestaShop Module
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 Knowband
 * @license   see file: LICENSE.txt
 */

$(document).ready(function () {
    //$('#birthday_coupon_allowed_country').addClass('col-sm-9');
    $('#birthday_coupon_allowed_country').removeClass('fixed-width-xl');
    $("#birthday_coupon_allowed_country").select2({
        placeholder: select_country,
        allowClear: true,
        dropdownAutoWidth: true
    });
    $('#discount_track_from_date').datepicker();
    $('#discount_track_to_date').datepicker();
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
    });
    $('input[name="birthday_coupon[type_birthday]"]').parent().parent().parent().after('<div class="alert alert-warning col-sm-9 occasion_warning">' + occasion_warning + '</div>');
    //js for discount type start
    var select_discount_type = $("input[name='birthday_coupon[apply_discount_type]']:checked").val();
    if (select_discount_type == '1') {
        applyDiscountInPersent();
    }
    if (select_discount_type == '2') {
        applyDiscountInAmount();
    }
    if (select_discount_type == '3') {
        applyDiscountInNone();
    }

    $("input[name='birthday_coupon[apply_discount_type]']:radio").change(function () {
        if (this.value == '1') {
            applyDiscountInPersent();
        }
        if (this.value == '2') {
            applyDiscountInAmount();
        }
        if (this.value == '3') {
            applyDiscountInNone();
        }
    });

    if ($("input[name='birthday_coupon[cron_type]']:checked").val() == '1') {
        $('#cron_instructions').hide();
    } else {
        $('#cron_instructions').show();
    }
    $("input[name='birthday_coupon[cron_type]']:radio").change(function () {
        if (this.value == '1') {
            $('#cron_instructions').hide();
        }
        if (this.value == '2') {
            $('#cron_instructions').show();
        }

    });

    function applyDiscountInPersent() {
        $("input[name='birthday_coupon[discount_percent_value]']").parent().parent().parent().show();

        $("input[name='birthday_coupon[discount_amount_value]']").parent().parent().parent().hide();
        $("select[name='birthday_coupon[discount_amount_currency]']").parent().parent().hide();
        $("input[name='birthday_coupon[discount_tax_included]']").parent().parent().parent().hide();
        $("input[name='birthday_coupon[send_free_gift]']").parent().parent().parent().hide();
    }
    function applyDiscountInAmount() {
        $("input[name='birthday_coupon[discount_amount_value]']").parent().parent().parent().show();
        $("select[name='birthday_coupon[discount_amount_currency]']").parent().parent().show();
        $("input[name='birthday_coupon[discount_tax_included]']").parent().parent().parent().show();

        $("input[name='birthday_coupon[discount_percent_value]']").parent().parent().parent().hide();
        $("input[name='birthday_coupon[send_free_gift]']").parent().parent().parent().hide();
    }
    function applyDiscountInNone() {
        $("input[name='birthday_coupon[send_free_gift]']").parent().parent().parent().show();

        $("input[name='birthday_coupon[discount_amount_value]']").parent().parent().parent().hide();
        $("select[name='birthday_coupon[discount_amount_currency]']").parent().parent().hide();
        $("input[name='birthday_coupon[discount_tax_included]']").parent().parent().parent().hide();
        $("input[name='birthday_coupon[discount_percent_value]']").parent().parent().parent().hide();
    }
    //js for discount type end
    /*js for email template change on chnage()*/
    $("#ddl_email_templates").change(function () {
        $.ajax({
            type: "POST",
            url: ajax_controller_url,
            data: {ajax: true, method: 'getEmailContentByTemplateName', template_name: $('option:selected', this).val()},
            dataType: 'json',
            beforeSend: function () {
            },
            complete: function () {
            },
            success: function (json) {
                if (json['status'] == 'true')
                {
                    $(json['content']).each(function () {
                        tinyMCE.get('optn_email_content_' + this.id_lang).setContent(this.body);
                    });
                } else {
                    $(json['content']).each(function () {
                        tinyMCE.get('optn_email_content_' + this).setContent('<p></p>');
                    });
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

            }
        });
    });
    /*end of js email template change*/

    /*Knowband validation start*/
    $('#birthday_coupon_configuration_form_submit_btn').click(function () {
        var is_error = false;
        $('.kb_error_message').remove();
        $('#birthday_coupon_form input').removeClass('kb_error_field');
        $('#birthday_coupon_form input').parent().removeClass('kb_error_field');

        $("input[name^=main_title_]").each(function () {
            var main_title_err = velovalidation.checkMandatory($(this), 128);
            if (main_title_err != true)
            {
                $(this).closest('.form-group').parent().find('.kb_error_message').remove();
                is_error = true;
                $(this).addClass('kb_error_field');
                $(this).closest('.form-group').parent().append('<span class="kb_error_message">' + main_title_err + ' ' + all_lang_req + '</span>');
            }
        });

        var prefix_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[prefix]"]'), 32);
        if (prefix_mandatory_err != true)
        {
            is_error = true;
            $('input[name="birthday_coupon[prefix]"]').addClass('kb_error_field');
            $('input[name="birthday_coupon[prefix]"]').after('<span class="kb_error_message">' + prefix_mandatory_err + '</span>');
        }

        var validity_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[validity]"]'));
        if (validity_mandatory_err != true) {
            is_error = true;
            $('input[name="birthday_coupon[validity]"]').addClass('kb_error_field');
            $('input[name="birthday_coupon[validity]"]').parent().after('<span class="kb_error_message">' + validity_mandatory_err + '</span>');
        } else {
            var validity_positive_err = velovalidation.isNumeric($('input[name="birthday_coupon[validity]"]'), true);
            if (validity_positive_err != true) {
                is_error = true;
                $('input[name="birthday_coupon[validity]"]').addClass('kb_error_field');
                $('input[name="birthday_coupon[validity]"]').parent().after('<span class="kb_error_message">' + validity_positive_err + '</span>');
            } else
            {
                var validity_between_err = velovalidation.isBetween($('input[name="birthday_coupon[validity]"]'), 1, 250);
                if (validity_between_err != true) {
                    is_error = true;
                    $('input[name="birthday_coupon[validity]"]').addClass('kb_error_field');
                    $('input[name="birthday_coupon[validity]"]').parent().after('<span class="kb_error_message">' + validity_between + '</span>');
                }
            }
        }

        var minimum_amount_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[minimum_amount]"]'));
        if (minimum_amount_mandatory_err != true) {
            is_error = true;
            $('input[name="birthday_coupon[minimum_amount]"]').addClass('kb_error_field');
            $('input[name="birthday_coupon[minimum_amount]"]').parent().after('<span class="kb_error_message">' + minimum_amount_mandatory_err + '</span>');
        } else {
            var minimum_amount_positive_err = velovalidation.isNumeric($('input[name="birthday_coupon[minimum_amount]"]'), true);
            if (minimum_amount_positive_err != true) {
                is_error = true;
                $('input[name="birthday_coupon[minimum_amount]"]').addClass('kb_error_field');
                $('input[name="birthday_coupon[minimum_amount]"]').parent().after('<span class="kb_error_message">' + minimum_amount_positive_err + '</span>');
            } else
            {
                var minimum_amount_between_err = velovalidation.isBetween($('input[name="birthday_coupon[minimum_amount]"]'), 0, 99999999);
                if (minimum_amount_between_err != true) {
                    is_error = true;
                    $('input[name="birthday_coupon[minimum_amount]"]').addClass('kb_error_field');
                    $('input[name="birthday_coupon[minimum_amount]"]').parent().after('<span class="kb_error_message">' + minimum_amount_between + '</span>');
                }
            }
        }

        var apply_discount_type = $("input[name='birthday_coupon[apply_discount_type]']:checked").val();
        if (apply_discount_type == '1') {
            var discount_percent_value_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[discount_percent_value]"]'));
            if (discount_percent_value_mandatory_err != true) {
                is_error = true;
                $('input[name="birthday_coupon[discount_percent_value]"]').addClass('kb_error_field');
                $('input[name="birthday_coupon[discount_percent_value]"]').parent().after('<span class="kb_error_message">' + discount_percent_value_mandatory_err + '</span>');
            } else {
                var discount_percent_value_positive_err = velovalidation.isNumeric($('input[name="birthday_coupon[discount_percent_value]"]'), true);
                if (discount_percent_value_positive_err != true) {
                    is_error = true;
                    $('input[name="birthday_coupon[discount_percent_value]"]').addClass('kb_error_field');
                    $('input[name="birthday_coupon[discount_percent_value]"]').parent().after('<span class="kb_error_message">' + discount_percent_value_positive_err + '</span>');
                } else
                {
                    var discount_percent_value_between_err = velovalidation.isBetween($('input[name="birthday_coupon[discount_percent_value]"]'), 1, 100);
                    if (discount_percent_value_between_err != true) {
                        is_error = true;
                        $('input[name="birthday_coupon[discount_percent_value]"]').addClass('kb_error_field');
                        $('input[name="birthday_coupon[discount_percent_value]"]').parent().after('<span class="kb_error_message">' + discount_percent_value_between + '</span>');
                    }
                }
            }
        }
        if (apply_discount_type == '2') {
            var discount_amount_value_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[discount_amount_value]"]'));
            if (discount_amount_value_mandatory_err != true) {
                is_error = true;
                $('input[name="birthday_coupon[discount_amount_value]"]').parent().addClass('kb_error_field');
                $('input[name="birthday_coupon[discount_amount_value]"]').parent().after('<span class="kb_error_message">' + discount_amount_value_mandatory_err + '</span>');
            } else {
                var discount_amount_value_positive_err = velovalidation.isNumeric($('input[name="birthday_coupon[discount_amount_value]"]'), true);
                if (discount_amount_value_positive_err != true) {
                    is_error = true;
                    $('input[name="birthday_coupon[discount_amount_value]"]').parent().addClass('kb_error_field');
                    $('input[name="birthday_coupon[discount_amount_value]"]').parent().after('<span class="kb_error_message">' + discount_amount_value_positive_err + '</span>');
                }
            }
        }

        var number_of_days_mandatory_err = velovalidation.checkMandatory($('input[name="birthday_coupon[number_of_days]"]'));
        if (number_of_days_mandatory_err != true) {
            is_error = true;
            $('input[name="birthday_coupon[number_of_days]"]').addClass('kb_error_field');
            $('input[name="birthday_coupon[number_of_days]"]').parent().after('<span class="kb_error_message">' + number_of_days_mandatory_err + '</span>');
        } else {
            var number_of_days_positive_err = velovalidation.isNumeric($('input[name="birthday_coupon[number_of_days]"]'), true);
            if (number_of_days_positive_err != true) {
                is_error = true;
                $('input[name="birthday_coupon[number_of_days]"]').addClass('kb_error_field');
                $('input[name="birthday_coupon[number_of_days]"]').parent().after('<span class="kb_error_message">' + number_of_days_positive_err + '</span>');
            } else
            {
                var number_of_days_between_err = velovalidation.isBetween($('input[name="birthday_coupon[number_of_days]"]'), 1, 100);
                if (number_of_days_between_err != true) {
                    is_error = true;
                    $('input[name="birthday_coupon[number_of_days]"]').addClass('kb_error_field');
                    $('input[name="birthday_coupon[number_of_days]"]').parent().after('<span class="kb_error_message">' + number_of_days_between + '</span>');
                }
            }
        }
        if (is_error) {
            return false;
        }
        $('#birthday_coupon_form').submit();
    });
    /*Knowband validation end*/

    /*Knowband validation start*/
    $('#birthday_coupon_email_setting_submit_btn_1').click(function () {
        var is_error = false;
        $('.kb_error_message').remove();
        $('#birthday_coupon_email_setting_form input').removeClass('kb_error_field');
        $('#birthday_coupon_email_setting_form input').parent().removeClass('kb_error_field');

        $("input[name^=birthday_coupon_email_subject_]").each(function () {
            var birthday_coupon_email_subject_err = velovalidation.checkMandatory($(this), 128);
            if (birthday_coupon_email_subject_err != true)
            {
                $(this).closest('.form-group').parent().find('.kb_error_message').remove();
                is_error = true;
                $(this).addClass('kb_error_field');
                $(this).closest('.form-group').parent().append('<span class="kb_error_message">' + birthday_coupon_email_subject_err + ' ' + all_lang_req + '</span>');
            }
        });

        var first_err_flag_top = 0;
        $("[name^=birthday_coupon_email_content]").each(function () {
            var email_err1 = tinyMCE.get($(this).attr("id")).getContent().trim();
            $(this).addClass('kb_error_field');
            if (email_err1 == '') {
                if (first_err_flag_top == 0) {
                    $(this).addClass('kb_error_field');
                    $('<span class="kb_error_message ">' + empty_field_error + ' ' + all_lang_req + '</span>').insertAfter($('textarea[name^="birthday_coupon_email_content"]'));
                }
                first_err_flag_top = 1;
                is_error = true;
            }
        });
        if (is_error) {
            return false;
        }
        $('#birthday_coupon_email_setting_form').submit();
    });
    /*Knowband validation end*/

    /*Knowband validation start*/
    $('#birthday_coupon_country_restriction_submit_btn_2').click(function () {
        var is_error = false;
        $('.kb_error_message').remove();
        $('#birthday_coupon_country_restriction_form input').removeClass('kb_error_field');
        $('#birthday_coupon_country_restriction_form input').parent().removeClass('kb_error_field');
        var enable = $("input[name='birthday_coupon[enable_country_restriction]']:checked").val();
        if (enable != 0) {
            var options = $('#birthday_coupon_allowed_country > option:selected');
            if (options.length == 0) {
                is_error = true;
                $('select[name="birthday_coupon[allowed_country][]"]').addClass('kb_error_field');
                $('select[name="birthday_coupon[allowed_country][]"]').after('<span class="kb_error_message">' + select_country_err + '</span>');
                $('#s2id_birthday_coupon_allowed_country').parent().addClass('kb_error_field');
            }
        }
        if (is_error) {
            return false;
        }
        $('#birthday_coupon_country_restriction_form').submit();
    });
    /*Knowband validation end*/

    /*Knowband validation start*/
    $('#birthday_coupon_category_restriction_submit_btn_3').click(function () {
        var is_error = false;
        $('.kb_error_message').remove();
        $('#category_set input').removeClass('kb_error_field');
        $('#category_set input').parent().removeClass('kb_error_field');
        var enable = $("input[name='birthday_coupon[enable_category_restriction]']:checked").val();
        if (enable != 0) {
            var options = $('#birthday_coupon_allowed_category > option:selected');
            var presta_cat = '';
            $('#prestashop_category').find(":input[type=checkbox]").each(function ()
            {
                if ($(this).prop("checked") == true) {
                    presta_cat = '1';
                }

            });
            if (presta_cat == '') {
                is_error = true;
                $('#prestashop_category').parent().addClass('kb_error_field');
                $('#prestashop_category').parent().parent().append('<span class="kb_error_message">' + pretsa_cat_error + '</span>');
            }
        }
        if (is_error) {
            return false;
        }
        $('#category_set').submit();
    });
    /*Knowband validation end*/
});
