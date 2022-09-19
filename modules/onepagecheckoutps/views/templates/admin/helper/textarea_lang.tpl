{*
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * @author    PresTeamShop SAS (Registered Trademark) <info@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *
 * @category  PrestaShop
 * @category  Module
*}

{foreach from=$languages item="language" name="f_languages"}
    <div class="translatable-field lang_{$language.id_lang|intval} {if $language.id_lang ne $paramsBack.DEFAULT_LENGUAGE}hide{/if} row top-xs end-xs">
{*        <div class="col-xs-12 col-md nopadding input-group-lg translatable-input">*}
        <div class="col-xs-12 col-md-9 nopadding input-group-lg translatable-input">
            <textarea autocomplete="off" class="form-control" rows="3" id="{$input_name|escape:'htmlall':'UTF-8'}_{$language.id_lang|intval}" name="{$input_name|escape:'htmlall':'UTF-8'}_{$language.id_lang|intval}">{if isset($input_value) and isset($input_value[$language.id_lang])}{$input_value[$language.id_lang]|escape:'html':'UTF-8'}{/if}</textarea>
        </div>
{*        <div class="col-sm-2 translatable-flags nopadding-xs nopadding-right">*}
        <div class="col-xs-3 translatable-flags nopadding-right">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                <i class="fa-pts fa-pts-flag nohover"></i>
                {$language.iso_code|escape:'htmlall':'UTF-8'}
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                {foreach from=$languages item=flag_language}
                    <li>
                        <a class="change-language" for="lang_{$flag_language.id_lang|intval}">
                            {$flag_language.name|escape:'htmlall':'UTF-8'}
                            {if $flag_language.id_lang eq $language.id_lang}
                                <i class="fa-pts fa-pts-flag-checkered nohover"></i>
                            {/if}
                        </a>
                    </li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}