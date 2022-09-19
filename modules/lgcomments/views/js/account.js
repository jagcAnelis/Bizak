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
    $("select.score").on("change", function () {
        changeStars($(this).val(), $(this).data('who'));
    });
    $("#validate-form").validate();
});

function changeStars(rate, who) {
    var star = module_dir + 'views/img/stars/' + star_style + '/' + star_color + '/' + rate + 'stars.png';
    $('#stars_' + who).attr('src', star);
}
