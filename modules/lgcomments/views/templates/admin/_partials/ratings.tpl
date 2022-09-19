{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="general-config" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=4" target="_blank">
                    <span class="lglarge">
                        <i class="icon-star"></i>&nbsp;{l s='Ratings' mod='lgcomments'}
                        &nbsp;<img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend><br>
            <h3><label>&nbsp;&nbsp;{l s='Choose the design of your stars:' mod='lgcomments'}</label></h3>
            <div class="lgoverflow">
                <table class="table" style="max-width:1000px;">
                    <tr>
                        <td class="lgupper lgcenter lgbold">{l s='Style' mod='lgcomments'}</td>
                        <td class="lgupper lgcenter lgbold">{l s='Color' mod='lgcomments'}</td>
                        <td class="lgupper lgcenter lgbold">{l s='Size' mod='lgcomments'}</td>
                        <td class="lgupper lgcenter lgbold">{l s='Preview' mod='lgcomments'}</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="stardesign1" id="stardesign1" onchange="changeStar(this.value);">
                                {foreach $stars.designs as $design}
                                    <option value="{$design.key|escape:'htmlall':'UTF-8'}" {if $selected_star_design == $design.key}selected{/if}>
                                        {$design.name|escape:'htmlall':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <select name="bg_color" id="stardesign2" onchange="changeStar(this.value);">
                                {foreach $stars.colours as $colour}
                                    <option value="{$colour.key|escape:'htmlall':'UTF-8'}" {if $selected_star_colour == {$colour.key}}selected{/if}>
                                        {$colour.name|escape:'htmlall':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <select name="starsize" id="starsize" onchange="changeStar(this.value);">
                                {foreach $stars.sizes as $size}
                                    <option value="{$size|intval}" {if $selected_star_size == $size}selected{/if}>
                                        {$size|escape:'htmlall':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="lgcenter">
                            <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/stars/{$selected_star_design|escape:'htmlall':'UTF-8'}/{$selected_star_colour|escape:'htmlall':'UTF-8'}/9stars.png"
                                 id="stardesignimage" class="lgwidget lgcenter" width="{$selected_star_size|escape:'htmlall':'UTF-8'}">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Choose your rating scale (front-office only)' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <select id="PS_LGCOMMENTS_SCALE" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_SCALE">
                    {foreach $stars.scales as $scale}
                        <option value="{$scale.key|intval}" {if $selected_star_scale == $scale.key}selected{/if} onclick="enableField()">
                            {$scale.name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <label class="lgfloat">&nbsp;&nbsp;{l s='Display stars for the products without review' mod='lgcomments'}&nbsp;&nbsp;</label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_ZEROSTAR" id="lgcomments_display_zerostar_on"
                           value="1" {if $zero_star}checked{/if} />
                    <label for="lgcomments_display_zerostar_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_ZEROSTAR" id="lgcomments_display_zerostar_off"
                           value="0" {if !$zero_star}checked{/if} />
                    <label for="lgcomments_display_zerostar_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
                <span class="lgfloat" style="margin-left:20px;">
                    <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/stars/{$selected_star_design}/{$selected_star_colour}/0stars.png"
                         id="starzero" class="lgwidget lgcenter" width="{$selected_star_size|intval}">
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Margin around stars (product list)' mod='lgcomments'}&nbsp;</label>
                </span>
                <span class="lgfloat">{l s='Top' mod='lgcomments'}&nbsp;</span>
                <span class="lgfloat lgbutton input-group">
                    <input class="validate" integer="true" id="PS_LGCOMMENTS_CATTOPMARGIN" type="text" name="PS_LGCOMMENTS_CATTOPMARGIN" value="{$cat_top_margin|escape:'htmlall':'UTF-8'}"/>
                    <span class="input-group-addon">px</span>
                </span>
                <span class="lgfloat">&nbsp;&nbsp;{l s='Bottom' mod='lgcomments'}&nbsp;</span>
                <span class="lgfloat lgbutton input-group">
                    <input class="validate" integer="true" id="PS_LGCOMMENTS_CATBOTMARGIN" type="text" name="PS_LGCOMMENTS_CATBOTMARGIN" value="{$cat_bottom_margin|escape:'htmlall':'UTF-8'}"/>
                    <span class="input-group-addon">px</span>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Margin around stars (top of product sheet)' mod='lgcomments'}&nbsp;</label>
                </span>
                <span class="lgfloat">{l s='Top' mod='lgcomments'}&nbsp;</span>
                <span class="lgfloat lgbutton input-group">
                    <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_PRODTOPMARGIN" type="text" name="PS_LGCOMMENTS_PRODTOPMARGIN" value="{$pro_top_margin|escape:'htmlall':'UTF-8'}"/>
                    <span class="input-group-addon">px</span>
                </span>
                <span class="lgfloat">&nbsp;&nbsp;{l s='Bottom' mod='lgcomments'}&nbsp;</span>
                <span class="lgfloat lgbutton input-group">
                    <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_PRODBOTMARGIN" type="text" name="PS_LGCOMMENTS_PRODBOTMARGIN" value="{$pro_bottom_margin|escape:'htmlall':'UTF-8'}"/>
                    <span class="input-group-addon">px</span>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message general-config-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <div class="lgclear"></div>
            <br>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsGeneral">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>
