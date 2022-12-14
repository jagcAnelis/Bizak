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

{if !$OPC.General.isLogged && $OPC.General.socialNetworkList}
<div id="opc_login_social" class="mb-1">
    <span class="d-block text-center title mb-1">
        {l s='For faster checkout, login or register using your social account' mod='onepagecheckoutps'}
    </span>
    <div class="buttons-content">
        {foreach from=$OPC.General.socialNetworkList key='name' item='network'}
            <button
                type="button"
                class="btn btn-sm btn-{$name|escape:'html':'UTF-8'}"
                data-link-to-connect="{$network.linkToConnect|escape:'htmlall':'UTF-8'}"
            >
                <img src="{$OPC.General.Directories.img|escape:'html':'UTF-8'}social/icon-{$name|lower|escape:'html':'UTF-8'}.png" alt="{$name|escape:'htmlall':'UTF-8'}">
                <span class="network-name">
                    {$name|escape:'html':'UTF-8'}
                </span>
            </button>
        {/foreach}
    </div>
    <div class="or-block row">
        <div class="col-5 col-xs-5">
            <hr>
        </div>
        <div class="col-2 col-xs-2 text-center">
            <span class="or-text">
                {l s='Or' mod='onepagecheckoutps'}
            </span>
        </div>
        <div class="col-5 col-xs-5">
            <hr>
        </div>
    </div>
</div>
{/if}