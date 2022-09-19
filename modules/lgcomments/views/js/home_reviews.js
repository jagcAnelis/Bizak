/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

window.addEventListener('load',function(){
    var owl = $('#lgcomments-owl.owl-carousel');
    owl.owlCarousel({
        nav:true,
        items:(typeof lgcomments_owl == 'undefined' ? 4 : lgcomments_owl),
        loop:true,
        responsiveClass:true,
        margin:10,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        dots: false,
        navElement:'div',
        responsive:{
            0:{
                items:1,
            },
            576:{
                items:2,
            },
            768:{
                items:3,
            },
            992:{
                items:(typeof lgcomments_owl == 'undefined' ? 4 : lgcomments_owl),
            }
        }
    });
});