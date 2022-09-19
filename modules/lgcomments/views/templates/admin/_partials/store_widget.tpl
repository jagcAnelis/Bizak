{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="store-widget" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=5" target="_blank">
                    <span class="lglarge">
                        <i class="icon-picture-o"></i>&nbsp;{l s='Store widget' mod='lgcomments'}&nbsp;
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend>
            <br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Display the store widget' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY" id="lgcomments_display_on" value="1"
                           {if $PS_LGCOMMENTS_DISPLAY}checked{/if} />
                    <label for="lgcomments_display_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY" id="lgcomments_display_off" value="0"
                           {if !$PS_LGCOMMENTS_DISPLAY}checked{/if} />
                    <label for="lgcomments_display_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;
                        {l s='Where to display the widget' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <select id="PS_LGCOMMENTS_DISPLAY_TYPE" class="lgfloat fixed-width-xl"
                        name="PS_LGCOMMENTS_DISPLAY_TYPE" style="margin-right:10px;">
                    {foreach $store_widget.available_places as $place}
                        <option onclick="enableField()" value="{$place.key|intval}" {if $display_type == $place.key}selected{/if}>
                            {$place.name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
                <select id="PS_LGCOMMENTS_DISPLAY_SIDE" class="lgfloat fixed-width-xl"
                        name="PS_LGCOMMENTS_DISPLAY_SIDE" {if $display_type == 2}disabled{/if}>
                    {foreach $store_widget.available_positions as $position}
                        <option value="{$position.key|intval}" {if $display_side == $position.key}selected{/if}>
                            {$position.name|escape:'htmlall':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </h3>
            {*
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;
                        {l s='Which hook to use for widget' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <select id="PS_LGCOMMENTS_WIDGET_HOOK" class="lgfloat fixed-width-xl"
                        name="PS_LGCOMMENTS_WIDGET_HOOK" style="margin-right:10px;">
                    {foreach $moduleHook as $mHook}
                        {if (in_array('displayFooter', $mHook))}
                            <option value="displayFooter" {if $PS_LGCOMMENTS_WIDGET_HOOK == 'displayFooter'}selected{/if}>displayFooter</option>
                        {/if}
                        {if (in_array('displayLeftColumn', $mHook))}
                            <option value="displayLeftColumn" {if $PS_LGCOMMENTS_WIDGET_HOOK == 'displayLeftColumn'}selected{/if}>displayLeftColumn</option>
                        {/if}
                        {if (in_array('displayRightColumn', $mHook))}
                            <option value="displayRightColumn" {if $PS_LGCOMMENTS_WIDGET_HOOK == 'displayRightColumn'}selected{/if}>displayRightColumn</option>
                        {/if}
                    {/foreach}
                </select>
            </h3>
            <div class="alert alert-info">
                <p>{l s='The widget is currently inserted into the hook:' mod='lgcomments'}
                    <b>{$PS_LGCOMMENTS_WIDGET_HOOK}</b>.&nbsp;
                    {l s='Check that the active theme in your store uses this hook on the pages you want to show the widget' mod='lgcomments'}
                </p>
            </div>
            *}
            <div class="lgclear"></div>
            <script type="text/javascript">
                function disableField() {
                    document.getElementById("PS_LGCOMMENTS_DISPLAY_SIDE").disabled=true;
                    document.getElementById("lgcomments_display_cross_on").disabled=true;
                    document.getElementById("lgcomments_display_cross_off").disabled=true;
                }
                function enableField() {
                    document.getElementById("PS_LGCOMMENTS_DISPLAY_SIDE").disabled=false;
                    document.getElementById("lgcomments_display_cross_on").disabled=false;
                    document.getElementById("lgcomments_display_cross_off").disabled=false;
                }
            </script>
            <div class="lgclear"></div><br><br>
            <h3>
                <label>&nbsp;&nbsp;{l s='Choose the design of your store widget:' mod='lgcomments'}</label>
            </h3>
            <div class="lgoverflow">
                <table class="table" style="max-width:1000px;">
                    <tr>
                        <td class="lgupper lgcenter lgbold">{l s='Style' mod='lgcomments'}</td>
                        <td class="lgupper lgcenter lgbold">{l s='Color' mod='lgcomments'}</td>
                        <td class="lgupper lgcenter lgbold">{l s='Preview' mod='lgcomments'}</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="bgdesign1" id="bgdesign1">
                                {foreach $store_widget.available_designs as $design}
                                    <option value="{$design.key|escape:'htmlall':'UTF-8'}" {if $bg_design == $design.key}selected{/if}>
                                        {$design.name|escape:'htmlall':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                        <td>
                            <select name="bg_color" id="bg_color">
                                {foreach $store_widget.available_colours as $colour}
                                    <option value="{$colour.key|escape:'htmlall':'UTF-8'}" {if $bg_color == $colour.key}selected{/if}>
                                        {$colour.name|escape:'htmlall':'UTF-8'}
                                    </option>
                                {/foreach}
                            </select>
                        </td>
                        <td class="lgcenter">
                            <img src="{$module_path|escape:'htmlall':'UTF-8'}/views/img/bg/{$bg_design}-{$bg_color}.png" id="bgdesignimage" class="lgwidget">
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                    &nbsp;&nbsp;{l s='Display a cross to hide the widget' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="lgcomments_display_cross" id="lgcomments_display_cross_on"
                        value="1" {if $cross}checked{/if} />
                    <label for="lgcomments_display_cross_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="lgcomments_display_cross" id="lgcomments_display_cross_off"
                        value="0" {if !$cross}checked{/if} />
                    <label for="lgcomments_display_cross_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                    &nbsp;&nbsp;{l s='Color of the widget title' mod='lgcomments'}&nbsp;&nbsp;
                    </label>
                </span>
                <span class="lgbutton lgfloat">
                    <input class="validate jscolor" hexadecimal="true" minlength=3" maxlength="6" id="widget_text_color" name="widget_text_color" type="text" value="{$text_color|escape:'htmlall':'UTF-8'}" />
                </span>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message store-widget-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsWidget">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>