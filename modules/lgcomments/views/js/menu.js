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
    $('.lgtabcontent').hide();
    $('#'+$('input[name="LGCOMMENTS_SELECTED_MENU"]').val()).show();
    $('.lgmenu').each(function() {
        if ($(this).data('target') == $('input[name="LGCOMMENTS_SELECTED_MENU"]').val()) {
            $(this).removeClass("btn-default");
            $(this).addClass("btn-primary");
        } else {
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-default");
        }
    });

    $(document).on('click', '.lgmenu', function() {
        var target_content = $(this).data('target');
        var selected_target = $('input[name="LGCOMMENTS_SELECTED_MENU"]').detach();

        $('.lgmenu').removeClass("btn-primary");
        $('.lgmenu').addClass("btn-default");
        $(this).removeClass("btn-default");
        $(this).addClass("btn-primary");
        $('.lgtabcontent').each(function() {
            if ($(this).attr('id') != target_content) {
                $(this).hide();
            } else {
                $(this).find('form').append(selected_target);
                $('input[name="LGCOMMENTS_SELECTED_MENU"]').val(target_content);
                $(this).show();
            }
        })
    });

    $(document).on('change' ,'#bgdesign1, #bg_color', function() {
        var url = $("#bgdesignimage").attr("src").split('/');
        url.pop();
        url.push($('#bgdesign1').val()+'-'+$('#bg_color').val()+'.png');
        $("#bgdesignimage").attr("src", url.join('/'));
    });

    $(document).on('change' ,'#stardesign1, #stardesign2, #starsize', function() {
        var url1 = $("#stardesignimage").attr("src").split('/');
        var url2 = $("#starzero").attr("src").split('/');

        url1[url1.length-2] = $('#stardesign2').val();
        url1[url1.length-3] = $('#stardesign1').val();
        url2[url2.length-2] = $('#stardesign2').val();
        url2[url2.length-3] = $('#stardesign1').val();

        $("#stardesignimage").attr("src", url1.join('/'));
        $("#starzero").attr("src", url2.join('/'));
        $("#stardesignimage").attr("width",""+$('#starsize').val()+"");
        $("#starzero").attr("width",""+$('#starsize').val()+"");
    });

    $(document).on('change', '#PS_LGCOMMENTS_DISPLAY_TYPE', function() {
        if ($(this).val() == 2) {
            $('#PS_LGCOMMENTS_DISPLAY_SIDE').prop('disabled', true);
        } else {
            $('#PS_LGCOMMENTS_DISPLAY_SIDE').prop('disabled', false);
        }
    });
});
