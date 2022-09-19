{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{literal}
<script type="text/javascript">
    function closeinfo(accept)
    {
        var banners = document.getElementsByClassName("lgcookieslaw_banner");
        if( banners ) {
            for (var i = 0; i < banners.length; i++) {
                banners[i].style.display = 'none';
            }
        }

        if (typeof accept != 'undefined' && accept == true) {
            setCookie("{/literal}{$nombre_cookie|escape:'htmlall':'UTF-8'}{literal}", 1, {/literal}{$tiempo_cookie|escape:'htmlall':'UTF-8'}{literal});
        }
    }

    function checkLgCookie()
    {
        return document.cookie.match(/^(.*;)?\s*{/literal}{$nombre_cookie|escape:'htmlall':'UTF-8'}{literal}\s*=\s*[^;]+(.*)?$/);
    }

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    var lgbtnclick = function(){
        var buttons = document.getElementsByClassName("lgcookieslaw_btn_accept");
        if( buttons != null ) {
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].addEventListener("click", function () {
                    closeinfo(true);{/literal}
                    {if $lgcookieslaw_reload}
                    {literal}location.reload(true);{/literal}
                    {/if}
                    {literal}
                });
            }
        }
    };

    window.addEventListener('load',function(){
        if( checkLgCookie() ) {
            closeinfo();
        } else {
            {/literal}
            {if $hidden}
            var banners = document.getElementsByClassName("lgcookieslaw_banner");
            if( banners ) {
                for (var i = 0; i < banners.length; i++) {
                    banners[i].style.display = "table";
                }
            }
            {/if}
            {literal}
            lgbtnclick();
        }
    });

</script>
{/literal}