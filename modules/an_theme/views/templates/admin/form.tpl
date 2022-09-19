{*
* 2020 Anvanto
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
*  @author Anvanto <anvantoco@gmail.com>
*  @copyright  2020 Anvanto
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of Anvanto
*}

{include file='./suggestions.tpl'}

{if count($tabs) > 1}
    <ul class="nav nav-tabs an_theme col-sm-2 col-xs-12">
		{foreach from=$tabs item=_tab key=i}
        <li{if $id_tab == $i} class="active"{/if}><a href="{$_tab.link|escape:'quotes':'UTF-8'}">{$_tab.legend.title|escape:'htmlall':'UTF-8'}</a></li>
		{/foreach}
		<li><a  class="an_theme-menu-moreproducts" href="https://bit.ly/3p8nRO3" target="_blank">See more products</a></li>
    </ul>
{/if}

<div class="tab-content an_theme col-sm-10 col-xs-12">
    {if is_array($tab)}
        <div id="tab" class="tab-pane{if isset($tab.legend.class)} active {$tab.legend.class|escape:'htmlall':'UTF-8'}{/if}">
        {$tab.form}
        </div>
    {else}
        {$tab}
    {/if}
</div>
