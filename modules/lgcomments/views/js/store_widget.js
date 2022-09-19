/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 */

function closewidget()
{
    $('#widget_block').hide();
    var now = new Date();
    var time = now.getTime();
    var expireTime = time + 1200*36000;
    now.setTime(expireTime);
    document.cookie = 'reviewWidget=hide_review_widget;expires='+now.toGMTString()+';path=/';
}

$(document).ready(function() {
    $('#reviewSlide').rotation({fadeSpeed:1000, pauseSpeed:2000});
});
(function($){
    $.fn.extend({
        rotation: function(options) {
            var config = {
                fadeSpeed: 1000,
                pauseSpeed: 2000,
                child:null
            };
            var options = $.extend(config, options);
            return this.each(function() {
                var o =options;
                var obj = $(this);
                var items = $(obj.children('div.review'), obj);
                items.each(function() {
                    $(this).hide();
                })
                if (!o.child) {
                    var next = $(obj).children('div.review:first');
                } else {
                    var next = o.child;
                }
                $(next).fadeIn(o.fadeSpeed, function() {
                    $(next).delay(o.pauseSpeed).fadeOut(o.fadeSpeed, function() {
                        var next = $(this).next();
                        if (next.length == 0) {
                            next = $(obj).children('div.review:first');
                        }
                        $(obj).rotation({child : next, fadeSpeed : o.fadeSpeed, pauseSpeed : o.pauseSpeed});
                    })
                });
            });
        }
    });
})(jQuery);
