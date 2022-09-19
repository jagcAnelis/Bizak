{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="review-page" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=7" target="_blank">
                    <span class="lglarge">
                        <i class="icon-comment-o"></i>&nbsp;{l s='Store review page' mod='lgcomments'}&nbsp;
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend>
            <br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Allow customers to leave reviews directly from the store review page' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_STORE_FORM" id="lgcomments_store_form_on" value="1"
                        {if $store_form}checked{/if} />
                    <label for="lgcomments_store_form_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_STORE_FORM" id="lgcomments_store_form_off" value="0"
                       {if !$store_form}checked{/if} />
                    <label for="lgcomments_store_form_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Display summary and filter' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="lgcomments_store_filter" id="lgcomments_store_filter_on" value="1"
                        {if $PS_LGCOMMENTS_STORE_FILTER}checked{/if} />
                    <label for="lgcomments_store_filter_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="lgcomments_store_filter" id="lgcomments_store_filter_off" value="0"
                       {if !$PS_LGCOMMENTS_STORE_FILTER}checked{/if} />
                    <label for="lgcomments_store_filter_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">&nbsp;&nbsp;
                    {l s='Multilingual shop: Display the store reviews by language' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_LANGUAGE" id="PS_LGCOMMENTS_DISPLAY_LANGUAGE_on"
                       value="1" {if $PS_LGCOMMENTS_DISPLAY_LANGUAGE}checked{/if} />
                <label for="PS_LGCOMMENTS_DISPLAY_LANGUAGE_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                <input type="radio" name="PS_LGCOMMENTS_DISPLAY_LANGUAGE" id="PS_LGCOMMENTS_DISPLAY_LANGUAGE_off"
                       value="0" {if !$PS_LGCOMMENTS_DISPLAY_LANGUAGE}checked{/if} />
                <label for="PS_LGCOMMENTS_DISPLAY_LANGUAGE_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Text color of the store review blocks' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat lgbutton">
                    <input class="validate jscolor" hexadecimal="true" minlength="3" maxlength="6" id="PS_LGCOMMENTS_TEXTCOLOR2" name="PS_LGCOMMENTS_TEXTCOLOR2" type="text" value="{$PS_LGCOMMENTS_TEXTCOLOR2|escape:'htmlall':'UTF-8'}"/>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Background color of the store review blocks' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat lgbutton">
                    <input class="validate jscolor" hexadecimal="true" minlength="3" maxlength="6" id="PS_LGCOMMENTS_BACKCOLOR2" name="PS_LGCOMMENTS_BACKCOLOR2" type="text" value="{$PS_LGCOMMENTS_BACKCOLOR2|escape:'htmlall':'UTF-8'}"/>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Number of store reviews displayed by page' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat lgbutton">
                    <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_PER_PAGE" type="text" name="PS_LGCOMMENTS_PER_PAGE" value="{$PS_LGCOMMENTS_PER_PAGE|escape:'htmlall':'UTF-8'}"/>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Order of the shop reviews ' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <select id="PS_LGCOMMENTS_DISPLAY_ORDER" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_DISPLAY_ORDER">
                    <option value="1" {if $PS_LGCOMMENTS_DISPLAY_ORDER == 1}selected{/if}>{l s='Ascending' mod='lgcomments'}</option>
                    <option value="2" {if $PS_LGCOMMENTS_DISPLAY_ORDER == 2}selected{/if}>{l s='Descending' mod='lgcomments'}</option>
                </select>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message review-page-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsStore">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>