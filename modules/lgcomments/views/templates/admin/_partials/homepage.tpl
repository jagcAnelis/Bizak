{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="homepage" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=6" target="_blank">
                    <span class="lglarge"><i class="icon-play-circle-o"></i>
                        &nbsp;{l s='Homepage slider' mod='lgcomments'}
                        &nbsp;<img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend><br>
            <h3>
                <label class="lgfloat">
                    &nbsp;&nbsp;{l s='Display store review slider' mod='lgcomments'}&nbsp;&nbsp;
                </label>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SLIDER" id="lgcomments_display_slider_on"
                           value="1" {if $display_slider}checked{/if} />
                    <label for="lgcomments_display_slider_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                    <input type="radio" name="PS_LGCOMMENTS_DISPLAY_SLIDER" id="lgcomments_display_slider_off"
                           value="0" {if !$display_slider}checked{/if} />
                    <label for="lgcomments_display_slider_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                    <a class="slide-button btn"></a>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Number of review blocks in the slider' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <select id="PS_LGCOMMENTS_SLIDER_BLOCKS" class="lgfloat fixed-width-xl"
                        name="PS_LGCOMMENTS_SLIDER_BLOCKS">
                    <option value="1" {if $slider_blocks == 1}selected{/if}>1</option>
                    <option value="2" {if $slider_blocks == 2}selected{/if}>2</option>
                    <option value="3" {if $slider_blocks == 3}selected{/if}>3</option>
                    <option value="4" {if $slider_blocks == 4}selected{/if}>4</option>
                    <option value="5" {if $slider_blocks == 5}selected{/if}>5</option>
                    <option value="6" {if $slider_blocks == 6}selected{/if}>6</option>
                </select>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='Display the last' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
                <span class="lgfloat lgbutton">
                    <input class="validate" integer="true" nonempty="true" id="PS_LGCOMMENTS_SLIDER_TOTAL" type="text" value="{$slider_total|escape:'htmlall':'UTF-8'}" name="PS_LGCOMMENTS_SLIDER_TOTAL" />
                </span>
                <span class="lgfloat">
                    <label>&nbsp;&nbsp;{l s='reviews in the slider' mod='lgcomments'}&nbsp;&nbsp;</label>
                </span>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <div style="display: inline-block; width: 100%; border-botto: 0;">
                    <label class="lgfloat">
                        &nbsp;&nbsp;{l s='Owl slider compatibility' mod='lgcomments'}
                    </label>
                    <span class="switch prestashop-switch fixed-width-lg lgfloat">
                        <input type="radio" name="PS_LGCOMMENTS_OWLCAROUSEL_DISABLED" id="lgcomments_owlcarousel_disabled_on"
                               value="1" {if $owlcarousel_disabled}checked{/if} />
                        <label for="lgcomments_owlcarousel_disabled_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                        <input type="radio" name="PS_LGCOMMENTS_OWLCAROUSEL_DISABLED" id="lgcomments_owlcarousel_disabled_off"
                               value="0" {if !$owlcarousel_disabled}checked{/if} />
                        <label for="lgcomments_owlcarousel_disabled_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                </div>
                <div class="alert alert-info">
                    {l s='If your theme insert a the owlCarousel plugin and have problems with your sliders please check this option' mod='lgcomments'}
                </div>
            </h3>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                        &nbsp;&nbsp;
                        {l s='If you want to show the slider anywhere, just enter the following code in your theme' mod='lgcomments'}:&nbsp;&nbsp;
                    </label>
                    <span class="lgimportant">{l s='{hook h=\'displayLgStoreCommentSlider\' mod=\'lgcomments\'}' mod='lgcomments'}</span>
                </span>
            </h3>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message homepage-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsHomepage">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>