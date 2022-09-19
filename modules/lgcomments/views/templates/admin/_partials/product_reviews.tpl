{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="product-reviews" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=8" target="_blank">
                    <span class="lglarge"><i class="icon-comment"></i>&nbsp;{l s='Product reviews' mod='lgcomments'}&nbsp;
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend>
            <br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Display the product reviews' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_COMMENTS" id="PS_LGCOMMENTS_DISPLAY_COMMENTS_on"
                       value="1" {if $PS_LGCOMMENTS_DISPLAY_COMMENTS}checked{/if} />
                    <label for="PS_LGCOMMENTS_DISPLAY_COMMENTS_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_COMMENTS" id="PS_LGCOMMENTS_DISPLAY_COMMENTS_off"
                       value="0" {if !$PS_LGCOMMENTS_DISPLAY_COMMENTS}checked{/if} />
                    <label for="PS_LGCOMMENTS_DISPLAY_COMMENTS_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="alert alert-info">
                {l s='You need to have received and enabled at least one review for a product
                        to be able to see the \"Reviews\" section on its product sheet.' mod='lgcomments'}
                &nbsp;<a href="{$lgcommentsProductsReviewsUrl|escape:'htmlall':'UTF-8'}" target="_blank">
                    {l s='You can check it here.' mod='lgcomments'}
                </a>
            </div>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                &nbsp;&nbsp;{l s='How to display the reviews on the product sheets' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <select id="PS_LGCOMMENTS_TAB_CONTENT" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_TAB_CONTENT">
                    <option value="1" {if $PS_LGCOMMENTS_TAB_CONTENT == 1}selected{/if}>{l s='In a new block' mod='lgcomments'}</option>
                    <option value="2" {if $PS_LGCOMMENTS_TAB_CONTENT == 2}selected{/if}>{l s='In a new tab' mod='lgcomments'}</option>
                    <option value="3" {if $PS_LGCOMMENTS_TAB_CONTENT == 3}selected{/if}>{l s='In a new tab prestashop 1.7 ( only themes compatible with 1.7 tabs )' mod='lgcomments'}</option>
                </select>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Position of average stars in product page' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <select id="PS_LGCOMMENTS_STARST_POSITION" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_STARST_POSITION">
                    <option value="2" {if $PS_LGCOMMENTS_STARST_POSITION == 2}selected{/if}>{l s='In guarantees block' mod='lgcomments'}</option>
                    <option value="1" {if $PS_LGCOMMENTS_STARST_POSITION == 1}selected{/if}>{l s='In prices block' mod='lgcomments'}</option>
                    <option value="3" {if $PS_LGCOMMENTS_STARST_POSITION == 3}selected{/if}>{l s='In information column' mod='lgcomments'}</option>
                </select>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Allow customers to leave reviews directly from the product sheets' mod='lgcomments'}
                    &nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_PRODUCT_FORM" id="lgcomments_product_form_on"
                           value="1" {if $PS_LGCOMMENTS_PRODUCT_FORM}checked{/if} />
                    <label for="lgcomments_product_form_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_PRODUCT_FORM" id="lgcomments_product_form_off"
                           value="0" {if !$PS_LGCOMMENTS_PRODUCT_FORM}checked{/if} />
                    <label for="lgcomments_product_form_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Display filter' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_PRODUCT_FILTER" id="lgcomments_product_filter_on"
                           value="1" {if $PS_LGCOMMENTS_PRODUCT_FILTER}checked{/if} />
                    <label for="lgcomments_product_filter_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_PRODUCT_FILTER" id="lgcomments_product_filter_off"
                           value="0" {if !$PS_LGCOMMENTS_PRODUCT_FILTER}checked{/if} />
                    <label for="lgcomments_product_filter_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='only when a product has more than' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat lgbutton">
                <input class="validate" integer="true" nonempty="true" id="lgcomments_product_filter_nb" type="text" name="PS_LGCOMMENTS_PRODUCT_FILTER_NB" value="{$PS_LGCOMMENTS_PRODUCT_FILTER_NB|escape:'htmlall':'UTF-8'}" />
                </span>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='reviews' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                        &nbsp;&nbsp;{l s='Number of product reviews displayed by default' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <span class="lgfloat lgbutton">
                <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_DISPLAY_DEFAULT" type="text" name="PS_LGCOMMENTS_DISPLAY_DEFAULT" value="{$PS_LGCOMMENTS_DISPLAY_DEFAULT|escape:'htmlall':'UTF-8'}" />
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                        &nbsp;&nbsp;{l s='Number of extra product reviews (display more)' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <span class="lgfloat lgbutton">
                    <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_DISPLAY_MORE" type="text" value="{$PS_LGCOMMENTS_DISPLAY_MORE|escape:'htmlall':'UTF-8'}" name="PS_LGCOMMENTS_DISPLAY_MORE"/>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Order of the product reviews ' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <select id="PS_LGCOMMENTS_DISPLAY_ORDER2" class="lgfloat fixed-width-xl"
                        name="PS_LGCOMMENTS_DISPLAY_ORDER2">
                    <option value="1" {if $PS_LGCOMMENTS_DISPLAY_ORDER2 == 1}selected{/if}>{l s='Ascending' mod='lgcomments'}</option>
                    <option value="2" {if $PS_LGCOMMENTS_DISPLAY_ORDER2 == 2}selected{/if}>{l s='Descending' mod='lgcomments'}</option>
                </select>
            </h3>
            <div class="lgclear"></div>
            <br><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Multilingual shop: Display the product reviews by language' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_LANGUAGE2" id="lgcomments_display_language2_on"
                           value="1" {if $PS_LGCOMMENTS_DISPLAY_LANGUAGE2}checked{/if} />
                    <label for="lgcomments_display_language2_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_LANGUAGE2" id="lgcomments_display_language2_off"
                           value="0" {if !$PS_LGCOMMENTS_DISPLAY_LANGUAGE2}checked{/if} />
                    <label for="lgcomments_display_language2_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message product-reviews-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <div class="lgclear"></div>
            <br>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsProducts">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>