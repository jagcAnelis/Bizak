{*
* 2007-2020 ETS-Soft
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses.
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please, contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2020 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
{if $sc_configs}
    <div class="ets_crosssell_block ets_crosssell_block_search_page block products_block featured-products ets_crosssell_{$name_page|escape:'html':'UTF-8'} layout_list clearfix ">
        <ul>
            {foreach from= $sc_configs item='sc_config'}
                <li class="ets_crosssell_list_blocks asd">
                    <h4 class="ets_crosssell_title">{$sc_config.tab_name|escape:'html':'UTF-8'}</h4>
                    <div class="tab_content{if $sc_config.sub_categories} ets_crosssell_has_sub{/if}" id="tab-content-{$name_page|escape:'html':'UTF-8'}-{$sc_config.tab|escape:'html':'UTF-8'}">
                        {hook h=$sc_config.hook name_page=$name_page id_product=$id_product}
                    </div>
                    <div class="clearfix"></div>
                </li>
            {/foreach}
        </ul>
    </div>
{/if}
