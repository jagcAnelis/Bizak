{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="rich-snippets" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=10" target="_blank">
                    <span class=lglarge">
                        <i class="icon-google"></i>&nbsp;{l s='Google Rich Snippets' mod='lgcomments'}&nbsp;
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend>
            <br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Enable snippets for product reviews' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SNIPPETS2" id="lgcomments_display_snippets2_on"
                           value="1" {if $PS_LGCOMMENTS_DISPLAY_SNIPPETS2}checked{/if} />
                    <label for="lgcomments_display_snippets2_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SNIPPETS2" id="lgcomments_display_snippets2_off"
                           value="0" {if !$PS_LGCOMMENTS_DISPLAY_SNIPPETS2}checked{/if} />
                    <label for="lgcomments_display_snippets2_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Enable snippets for shop reviews' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SNIPPETS" id="lgcomments_display_snippets_on"
                           value="1" {if $PS_LGCOMMENTS_DISPLAY_SNIPPETS}checked{/if} />
                    <label for="lgcomments_display_snippets_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SNIPPETS" id="lgcomments_display_snippets_off"
                           value="0" {if !$PS_LGCOMMENTS_DISPLAY_SNIPPETS}checked{/if} />
                    <label for="lgcomments_display_snippets_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                &nbsp;&nbsp;{l s='Choose the price range (for homepage snippets)' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <select id="PS_LGCOMMENTS_PRICE_RANGE" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_PRICE_RANGE">
                    <option value="{$currency|escape:'htmlall':'UTF-8'}" {if $PS_LGCOMMENTS_PRICE_RANGE == $currency}selected{/if}>
                        {$currency|escape:'htmlall':'UTF-8'} - {l s='Low' mod='lgcomments'}
                    </option>
                    <option value="{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}" {if $PS_LGCOMMENTS_PRICE_RANGE == ($currency|cat:$currency)}selected{/if}>
                        {$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'} - {l s='Moderate' mod='lgcomments'}
                    </option>
                    <option value="{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}" {if $PS_LGCOMMENTS_PRICE_RANGE == ($currency|cat:$currency|cat:$currency)|escape:'htmlall':'UTF-8'}selected{/if}>
                        {$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'} - {l s='High' mod='lgcomments'}
                    </option>
                    <option value="{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}" {if $PS_LGCOMMENTS_PRICE_RANGE == ($currency|cat:$currency|cat:$currency|cat:$currency)|escape:'htmlall':'UTF-8'}selected{/if}>
                        {$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'}{$currency|escape:'htmlall':'UTF-8'} - {l s='Very high' mod='lgcomments'}
                    </option>
                </select>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="alert alert-info">
                <u><b>{l s='About Google Rich Snippets:' mod='lgcomments'}</b></u><br>
                <ol>
                    <li>
                        {l s='Please note that the snippets don\'t appear immediately on Google search results.
                            You need to wait until Google bots visit your shop, take the snippets into account' mod='lgcomments'}
                    </li>
                    <li>
                        {l s='You can search on Google the website named "Structured Data Testing Tool - Google" to test your snippets.
                            Submit your urls and make sure there is no error and only one snippet per page.' mod='lgcomments'}
                    </li>
                </ol>
            </div>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsSnippets">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </form>
    </fieldset>
</div>