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
 * @category  PrestaShop
 * @category  Module
*}

<div>
    {l s='Go to the' mod='onepagecheckoutps'}

    <a target="_blank" href="https://presteamshop.atlassian.net/wiki/spaces/{$paramsBack.PREFIX_MODULE|escape:'htmlall':'UTF-8'}/pages/{if $paramsBack.ISO_LANG_BACKOFFICE_SHOP eq 'es'}1059258500{else}1087438875{/if}/Facebook">{l s='user guide' mod='onepagecheckoutps'}</a> >
    {l s='option "How to create a Facebook application?"' mod='onepagecheckoutps'}
    <br/><br/>

    <b>* {l s='Site URL' mod='onepagecheckoutps'}</b>:
    <input class="disabled" style="width: 100%;" type="text" onclick="this.focus();this.select();" value="{$paramsBack.LINK->getPageLink('index', false)|escape:'htmlall':'UTF-8'}"></input>

    <b>* {l s='App Domains' mod='onepagecheckoutps'}</b>:
    <input class="disabled" style="width: 100%;" type="text" onclick="this.focus();this.select();" value="{$paramsBack.SHOP->domain|escape:'htmlall':'UTF-8'}"></input>

    <b>* {l s='Valid OAuth Redirect URIs' mod='onepagecheckoutps'}:</b>
    <br />
    {foreach $paramsBack.LANGUAGES item='language'}
        <input type="text" value="{$paramsBack.LINK->getModuleLink('onepagecheckoutps', 'login', ['sv' => 'Facebook'], null, $language.id_lang)|escape:'htmlall':'UTF-8'}"/>
    {/foreach}
</div>