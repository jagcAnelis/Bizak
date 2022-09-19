{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="manage-reviews" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=16" target="_blank">
                    <span class="lglarge"><i class="icon-pencil"></i>
                        {l s='Manage reviews' mod='lgcomments'}
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend><br>
            <div>
                <p class="clear">
                    <a href="{$lgcommentsStoreReviewsUrl|escape:'htmlall':'UTF-8'}" target="_blank">
                        <button class="button btn btn-default" type="button">
                            <i class="icon-pencil"></i>
                            {l s='Click here to manage your store reviews' mod='lgcomments'}
                        </button>
                    </a>
                    <a href="{$lgcommentsProductsReviewsUrl|escape:'htmlall':'UTF-8'}" target="_blank">
                        <button class="button btn btn-default" type="button">
                            <i class="icon-pencil"></i>
                            {l s='Click here to manage your product reviews' mod='lgcomments'}
                        </button>
                    </a>
                </p>
                <div class="lgclear"></div><br><br><br>
                <h3>
                    <span class="lgfloat">
                        <label>
                            {l s='Require validation before publishing comments' mod='lgcomments'}
                        </label>
                    </span>
                    <span class="switch prestashop-switch fixed-width-lg lgfloat">
                        <input type="radio" name="lgcomments_validation" id="lgcomments_validation_on" value="1"{if $PS_LGCOMMENTS_VALIDATION == 1 } checked="checked"{/if} />
                        <label for="lgcomments_validation_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                        <input type="radio" name="lgcomments_validation" id="lgcomments_validation_off" value="0"{if $PS_LGCOMMENTS_VALIDATION == 0 } checked="checked"{/if} />
                        <label for="lgcomments_validation_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </h3>
                <div class="alert alert-info lgclear">
                    {l s='Enable this option if you want to check' mod='lgcomments'}
                    {l s='and validate the comments before publishing them.' mod='lgcomments'}
                    {l s='Disable this option if you want to publish the comments' mod='lgcomments'}
                    {l s='automatically without any validation.' mod='lgcomments'}
                </div>
                <div class="lgclear"></div><br><br>
                <h3>
                    <span class="lgfloat">
                        <label>{l s='Allow customers to write:' mod='lgcomments'}</label>
                    </span>
                    <select id="PS_LGCOMMENTS_OPINION_FORM" class="lgfloat fixed-width-xl"
                            name="PS_LGCOMMENTS_OPINION_FORM">
                        <option {if $PS_LGCOMMENTS_OPINION_FORM == 1} selected="selected"{/if} value="1">
                        {l s='Store and product reviews' mod='lgcomments'}
                        </option>
                        <option {if $PS_LGCOMMENTS_OPINION_FORM == 2} selected="selected"{/if} value="2">
                        {l s='Store reviews only' mod='lgcomments'}
                        </option>
                        <option {if $PS_LGCOMMENTS_OPINION_FORM == 3} selected="selected"{/if} value="3">
                        {l s='Product reviews only' mod='lgcomments'}
                        </option>
                    </select>
                </h3>
                <br>

                {* REPARACION DE COMENTARIOS DE LA TIENDA DE VERSIONES ANTIGUAS *}
                <h3>
                    <span class="lgfloat">
                        <label>{l s='Repair Anonymous store reviews from old module versions:' mod='lgcomments'}</label>
                    </span>
                </h3>
                <div class="alert alert-info lgclear">
                    {l s='Select what do you want to use when Nick field is not filled (This usually happen when you upgrade from early versions to 1.5.13 version of the module).' mod='lgcomments'}
                </div>
                <div>
                    <select id="LGCOMMENTS_NICK_OPTIONS_STORE_REPAIR" class="lgfloat fixed-width-xl" name="LGCOMMENTS_NICK_OPTIONS_STORE_REPAIR">
                        <option value="0">{l s='Select an option' mod='lgcomments'}</option>
                        <option value="1">{l s='Anonymous' mod='lgcomments'}</option>
                        <option value="2">{l s='Compound Fisrtname and Lastname. Ex for (Firstname: Jhnon, Lastname: Doe): J. Doe ' mod='lgcomments'}</option>
                        <option value="3">{l s='Force a name' mod='lgcomments'}</option>
                    </select>
                    <div id="lgcomments_force_nick_store_repair_container" style="display: none">
                        <span class="lgfloat">
                            <label>
                                <i class="icon-key"></i>
                                {l s='Nick' mod='lgcomments'}
                            </label>
                        </span>
                        <input type="text" id="LGCOMMENTS_FORCED_NICK_STORE_REPAIR" name="LGCOMMENTS_FORCED_NICK_STORE_REPAIR" class="lgfloat fixed-width-xl" />
                    </div>
                </div>
                <div>
                    <span class="btn btn-default" id="lgcomments_force_nick_store_repair_button">
                        <i class="icon-pencil"></i>
                        {l s='Repair' mod='lgcomments'}
                    </span>
                </div>
                <div class="lgclear"></div><br><br><br>

                {* REPARACION DE COMENTARIOS DE LA TIENDA DE VERSIONES ANTIGUAS *}
                <h3>
                    <span class="lgfloat">
                        <label>{l s='Repair Anonymous products reviews from old module versions:' mod='lgcomments'}</label>
                    </span>
                </h3>
                <div class="alert alert-info lgclear">
                    {l s='Select what do you want to use when Nick field is not filled (This usually happen when you upgrade from early versions to 1.5.13 version of the module).' mod='lgcomments'}
                </div>
                <div>
                    <select id="LGCOMMENTS_NICK_OPTIONS_PRODUCT_REPAIR" class="lgfloat fixed-width-xl" name="LGCOMMENTS_NICK_OPTIONS_PRODUCT_REPAIR">
                        <option value="0"> {l s='Select an option' mod='lgcomments'}</option>
                        <option value="1"> {l s='Anonymous' mod='lgcomments'}</option>
                        <option value="2"> {l s='Compound Fisrtname and Lastname. Ex for (Firstname: Jhnon, Lastname: Doe): J. Doe ' mod='lgcomments'}</option>
                        <option value="3"> {l s='Force a name' mod='lgcomments'}</option>
                    </select>
                    <div id="lgcomments_force_nick_product_repair_container" style="display: none">
                        <span class="lgfloat">
                            <label>
                                <i class="icon-key"></i>
                                {l s='Nick' mod='lgcomments'}
                            </label>
                        </span>
                        <input type="text" id="LGCOMMENTS_FORCED_NICK_PRODUCT_REPAIR" name="LGCOMMENTS_FORCED_NICK_PRODUCT_REPAIR" class="lgfloat fixed-width-xl" />
                    </div>
                </div>
                <div>
                    <span class="btn btn-default" id="lgcomments_force_nick_product_repair_button">
                        <i class="icon-pencil"></i>
                        {l s='Repair' mod='lgcomments'}
                    </span>
                </div>
                <div class="lgclear"></div><br><br><br>

                <button class="button btn btn-default" type="submit" name="submitLGCommentsManage">
                    <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
                </button>
            </div>
        </fieldset>
    </form>
</div>
