/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

/*
$(document).ready(function(){
    $(document).on('change', 'select#lg_score', function () {
        changeStars($(this).val());
    });

    $(document).on('click', '#product #submit_review', function () {
        if (checkFields()) {
            sendProductReview(review_controller_link);
        }
    });

    setPopUp();
});
*/

function ready(fn) {
    if (document.readyState != 'loading'){
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

ready(function(){
    $(document).on('change', 'select#lg_score', function () {
        changeStars($(this).val());
    });

    $(document).on('click', '#product #submit_review', function () {
        if ($(this).hasClass('disabled')) {
            return;
        }

        if (checkFields()) {
            sendProductReview(review_controller_link);
        }
    });
    setPopUp();
});

function setPopUp()
{
    $('.lgcomment_button').fancybox({
        'href': '#form_review_popup',
        'width': 400,
        'height': 'auto',
        'autoSize' : false,
        'tpl': {
            closeBtn : '<a title="'+$('#send_review').data('close')+'" class="fancybox-item fancybox-close" href="javascript:;"></a>'
        }
    });
}

function changeStars(value1) {
    var star = module_dir + 'views/img/stars/' + star_style + '/' + star_color + '/' + value1 + 'stars.png';
    $('#lg_stars').attr('src', star);
}

function checkFields() {
    var filled = true;

    // Check EU GDPR checkbox
    if ($('#form_review_popup').find('input[name="psgdpr_consent_checkbox"]').length > 0) {
        if (!$('#form_review_popup').find('input[name="psgdpr_consent_checkbox"]').prop('checked')) {
            $('#form_review_popup').find('input[name="psgdpr_consent_checkbox"]').parent().find('i').parent().css('border', '2px solid red');
            filled = false;
        } else {

        }
    }

    $('.lg-required').each(function() {
        if($(this).val() === '') {
            $(this).addClass('empty');
            filled = false;
        } else {
            $(this).removeClass('empty');
        }
    });

    return filled;
}

function versionCompare(v1, v2, options) {
    var lexicographical = options && options.lexicographical,
        zeroExtend = options && options.zeroExtend,
        v1parts = v1.split('.'),
        v2parts = v2.split('.');

    function isValidPart(x) {
        return (lexicographical ? /^\d+[A-Za-z]*$/ : /^\d+$/).test(x);
    }

    if (!v1parts.every(isValidPart) || !v2parts.every(isValidPart)) {
        return NaN;
    }

    if (zeroExtend) {
        while (v1parts.length < v2parts.length) v1parts.push("0");
        while (v2parts.length < v1parts.length) v2parts.push("0");
    }

    if (!lexicographical) {
        v1parts = v1parts.map(Number);
        v2parts = v2parts.map(Number);
    }

    for (var i = 0; i < v1parts.length; ++i) {
        if (v2parts.length == i) {
            return 1;
        }

        if (v1parts[i] == v2parts[i]) {
            continue;
        }
        else if (v1parts[i] > v2parts[i]) {
            return 1;
        }
        else {
            return -1;
        }
    }

    if (v1parts.length != v2parts.length) {
        return -1;
    }

    return 0;
}

function sendProductReview(file) {
    var lg_score       = $('#lg_score').val();
    var lg_nick        = $('#lg_nick').val();
    var lg_title       = $('#lg_title').val();
    var lg_comment     = $('#lg_comment').val().split("\n").join(" ");
    var lg_iso         = $('#lg_iso').val();
    var lg_id_customer = $('#lg_id_customer').val();
    var lg_id_product  = $('#lg_id_product').val();

    if (versionCompare(jQuery.fn.jquery, '3.0.0') >= 0) {
        $.ajax({
            type: "POST",
            url: file,
            dataType: 'json',
            cache: false,
            data: {
                'lg_score': lg_score,
                'lg_nick': lg_nick,
                'lg_title': lg_title,
                'lg_comment': lg_comment,
                'lg_iso': lg_iso,
                'lg_id_customer': lg_id_customer,
                'lg_id_product': lg_id_product,
                'send_product_review': 1,
                'rand': new Date().getTime()
            }
        }).done(function (data) {
            if (typeof data.has_error != 'undefined') {
                if(data.has_error === true) {
                    $('.lg-label-error').slideDown(250);
                } else {
                    $.fancybox.close();
                    alert(send_successfull_msg);
                    location.reload(true);
                }
            } else {
                alert('Unknown error');
            }
            $('#product #submit_review').removeClass('disabled');
        }).fail(function() {
            $('#product #submit_review').removeClass('disabled');
            $('.lg-label-error.error').slideDown(250);
        });
    } else {
        $.ajax({
            type: "POST",
            url: file,
            dataType: 'json',
            cache: false,
            data: {
                'lg_score': lg_score,
                'lg_nick': lg_nick,
                'lg_title': lg_title,
                'lg_comment': lg_comment,
                'lg_iso': lg_iso,
                'lg_id_customer': lg_id_customer,
                'lg_id_product': lg_id_product,
                'send_product_review': 1,
                'rand': new Date().getTime()
            }
        }).success(function (data) {
            if (typeof data.has_error != 'undefined') {
                if(data.has_error === true) {
                    $('.lg-label-error').slideDown(250);
                } else {
                    $.fancybox.close();
                    alert(send_successfull_msg);
                    location.reload(true);
                }
            } else {
                alert('Unknown error');
            }
            $('#product #submit_review').removeClass('disabled');
        }).error(function() {
            $('#product #submit_review').removeClass('disabled');
            $('.lg-label-error.error').slideDown(250);
        });
    }
}

