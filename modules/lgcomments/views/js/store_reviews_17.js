/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

function abrir(url) {
    open(url,'','top=300,left=300,width=600,height=600,scrollbars=yes') ;
}

$(document).ready(function() {
    $(document).on('change', '#lgcomments_score_store', function() {
        var value = $(this).val();
        var module = $(this).data('module');
        var style  = $(this).data('stars-style');
        var color  = $(this).data('stars-color');
        $('#lgcomments_stars_store').attr('src',module+'lgcomments/views/img/stars/'+style+'/'+color+'/'+value+'stars.png');
    });

    $('.lgcomment_pagination a.js-search-link:not(.disabled)').on('click', function(e) {
    });

    $("#send_store_review").fancybox({
        'href'   : '#form_review',
        'autoScale':'true'
    });
});

$(document).ajaxSuccess(function (event, xhr, settings) {
    if (settings.url.indexOf('?page=') >= 0 &&
        xhr.responseJSON.html &&
        xhr.responseJSON.html.indexOf('lgcomment_block') >= 0) {
        $('#main').parent().html(xhr.responseJSON.html);
    }
});
