{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="menubar">
    <fieldset>
        <a id="button-general-config" data-target="general-config" class="button btn btn-default lgmenu ">
            <i class="icon-star"></i>&nbsp;{l s='Ratings' mod='lgcomments'}
        </a>
        <a id="button-store-widget" data-target="store-widget" class="button btn btn-default lgmenu">
            <i class="icon-picture-o"></i>&nbsp;{l s='Store widget' mod='lgcomments'}
        </a>
        <a id="button-homepage" data-target="homepage" class="button btn btn-default lgmenu">
            <i class="icon-play-circle-o"></i>&nbsp;{l s='Homepage slider' mod='lgcomments'}
        </a>
        <a id="button-review-page" data-target="review-page" class="button btn btn-default lgmenu">
            <i class="icon-comment-o"></i>&nbsp;{l s='Store review page' mod='lgcomments'}
        </a>
        <a id="button-product-reviews" data-target="product-reviews" class="button btn btn-default lgmenu">
            <i class="icon-comment"></i>&nbsp;{l s='Product reviews' mod='lgcomments'}
        </a>
        <a id="button-rich-snippets" data-target="rich-snippets" class="button btn btn-default lgmenu">
            <i class="icon-google"></i>&nbsp;{l s='Google Rich Snippets' mod='lgcomments'}
        </a><br><br>
        <a id="button-send-email" data-target="send-email" class="button btn btn-default lgmenu">
            <i class="icon-envelope"></i>&nbsp;{l s='Send emails' mod='lgcomments'}
        </a>
        <a id="button-configure-email" data-target="configure-email" class="button btn btn-default lgmenu">
            <i class="icon-wrench"></i>&nbsp;{l s='Configure emails' mod='lgcomments'}
        </a>
        <a id="button-order-list" data-target="order-list" class="button btn btn-default lgmenu">
            <i class="icon-shopping-cart"></i>&nbsp;{l s='Corresponding orders' mod='lgcomments'}
        </a>
        <a id="button-upload-store" data-target="upload-store" class="button btn btn-default lgmenu">
            <i class="icon-comments-o"></i>&nbsp;{l s='Import store reviews' mod='lgcomments'}
        </a>
        <a id="button-upload-products" data-target="upload-products" class="button btn btn-default lgmenu">
            <i class="icon-comments"></i>&nbsp;{l s='Import product reviews' mod='lgcomments'}
        </a>
        <a id="button-manage-reviews" data-target="manage-reviews" class="button btn btn-default lgmenu">
            <i class="icon-pencil"></i>&nbsp;{l s='Manage reviews' mod='lgcomments'}
        </a>
    </fieldset>
    <input type="hidden" id ="LGCOMMENTS_SELECTED_MENU" name="LGCOMMENTS_SELECTED_MENU" value="{$LGCOMMENTS_SELECTED_MENU|escape:'htmlall':'UTF-8'}">
</div>