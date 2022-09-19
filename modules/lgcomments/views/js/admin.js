/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

$(document).ready(function(){
    $('.lgtabcontent').each(function(){
        $(this).find('form').validate({
            errorContainer: '.'+$(this).attr('id')+'-errors',
            errorLabelContainer: '.lgcomment_validate_error_message ul',
            wrapper: "li",
            errorPlacement: function(error, element) {
                error.appendTo(element.siblings('.lgcomment_validate_error_message'));
            }
        });
    });
    $('.lgcomment_validate_error_message button.close').on('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).parent().hide();
    });

    $(document).on('change', '#LGCOMMENTS_NICK_OPTIONS', function() {
        if (parseInt($(this).val()) == 3) {
            $('#lgcomments_force_nick_container').show();
        } else {
            $('#lgcomments_force_nick_container').hide();
        }
    });

    $(document).on('change', '#LGCOMMENTS_NICK_OPTIONS_STORE', function() {
        if (parseInt($(this).val()) == 3) {
            $('#lgcomments_force_nick_store_container').show();
        } else {
            $('#lgcomments_force_nick_store_container').hide();
        }
    });

    $(document).on('change', '#LGCOMMENTS_NICK_OPTIONS_PRODUCT_REPAIR', function() {
        if (parseInt($(this).val()) == 3) {
            $('#lgcomments_force_nick_product_repair_container').show();
        } else {
            $('#lgcomments_force_nick_product_repair_container').hide();
        }
    });

    $(document).on('change', '#LGCOMMENTS_NICK_OPTIONS_STORE_REPAIR', function() {
        if (parseInt($(this).val()) == 3) {
            $('#lgcomments_force_nick_store_repair_container').show();
        } else {
            $('#lgcomments_force_nick_store_repair_container').hide();
        }
    });

    $(document).on('click', '#lgcomments_force_nick_store_repair_button', function() {
        var nocache  = new Date().getTime();
        var url      = currentIndex+'&rand='+nocache;
        var whattodo = $('#LGCOMMENTS_NICK_OPTIONS_STORE_REPAIR').val();
        var nick     = $('#LGCOMMENTS_FORCED_NICK_STORE_REPAIR').val();
        var params   = {
            'ajax': 1,
            'controller': 'AdminModules',
            'module_name': 'lgcomments',
            'configure': 'lgcomments',
            'action': 'forceNickRepairStore',
            'whattodo': whattodo,
            'nick' : nick,
            'token': lgcomments_token,
            'auth_token': lgcomments_auth_token,
            'rand': new Date().getTime()
        };

        $.ajax({
            url: url,
            method: 'post',
            dataType: 'json',
            cache: false,
            data: params,
            success: function (response) {
                showSuccessMessage(response.message);
            },
            error: function (response) {
                if (typeof response != "undefined") {
                    if (typeof response.status != "undefined"
                        && response.status == 400
                        && typeof response.responseJSON.status != "undefined"
                        && typeof response.responseJSON.error != "undefined"
                        && typeof response.responseJSON.error.message != "undefined"
                    ) {
                        showErrorMessage(response.responseJSON.error.message);
                    } else {
                        showErrorMessage(lgcomments_error_unknown_error);
                    }
                } else {
                    showErrorMessage(lgcomments_error_unknown_error);
                }
            }
        });
    });

    $(document).on('click', '#lgcomments_force_nick_product_repair_button', function() {
        var nocache  = new Date().getTime();
        var url      = currentIndex+'&rand='+nocache;
        var whattodo = $('#LGCOMMENTS_NICK_OPTIONS_PRODUCT_REPAIR').val();
        var nick     = $('#LGCOMMENTS_FORCED_NICK_PRODUCT_REPAIR').val();
        var params   = {
            'ajax': 1,
            'controller': 'AdminModules',
            'module_name': 'lgcomments',
            'configure': 'lgcomments',
            'action': 'forceNickRepairProducts',
            'whattodo': whattodo,
            'nick' : nick,
            'token': lgcomments_token,
            'auth_token': lgcomments_auth_token,
            'rand': new Date().getTime()
        }

        $.ajax({
            url: url,
            method: 'post',
            dataType: 'json',
            cache: false,
            data: params,
            success: function (response) {
                showSuccessMessage(response.message);
            },
            error: function (response) {
                if (typeof response != "undefined") {
                    if (typeof response.status != "undefined"
                        && response.status == 400
                        && typeof response.responseJSON.status != "undefined"
                        && typeof response.responseJSON.error != "undefined"
                        && typeof response.responseJSON.error.message != "undefined"
                    ) {
                        showErrorMessage(response.responseJSON.error.message);
                    } else {
                        showErrorMessage(lgcomments_error_unknown_error);
                    }
                } else {
                    showErrorMessage(lgcomments_error_unknown_error);
                }
            }
        });
    });
});