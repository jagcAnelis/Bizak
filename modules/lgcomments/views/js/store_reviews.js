/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

$(document).on('click', '#module-lgcomments-reviews #submit_review', function () {
    if (checkFields()) {
        sendStoreReview(review_controller_link);
    }
});

function sendStoreReview(file) {
    var lg_score = $('#lg_score').val();
    var lg_nick = $('#lg_nick').val();
    var lg_title = $('#lg_title').val();
    var lg_comment = $('#lg_comment').val().split("\n").join(" ");
    var lg_iso = $('#lg_iso').val();
    var lg_id_customer = $('#lg_id_customer').val();

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
            'send_store_review': 1,
            'rand': new Date().getTime()
        }
    }).success(function (data) {
        if(data.has_error === true) {
            $('.lg-label-error').slideDown(250);
        } else {
            $.fancybox.close();
            alert(send_successfull_msg);
            location.href = location.href;
        }
    }).error(function() {
        $('.lg-label-error').slideDown(250);
    });
}
