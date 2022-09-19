{**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*}

{extends file="helpers/list/list_header.tpl"}

{if isset($stats_page) && $stats_page == 'viewwk_donation_stats'}
    {block name="leadin"}
        <div class="panel kpi-container">
            <div class="row">
                <div class="col-xs-6 col-sm-4 box-stats color1" >
                    <div class="kpi-content">
                        <i class="icon-shopping-cart"></i>
                        <span class="title">{l s='Total Donations' mod='wkcharitydonation'}</span>
                        <span class="value">{$total_donations|escape:'html':'UTF-8'}</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 box-stats color3" >
                    <div class="kpi-content">
                        <i class="icon-money"></i>
                        <span class="title">{l s='Total Donation Amount' mod='wkcharitydonation'}</span>
                        <span class="value">{$total_amount|escape:'html':'UTF-8'}</span>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-4 box-stats color4" >
                    <a href="#start_products">
                        <div class="kpi-content">
                            <i class="icon-user"></i>
                            <span class="title">{l s='Total Customers' mod='wkcharitydonation'}</span>
                            <span class="value">{$total_customer|escape:'html':'UTF-8'}</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    {/block}
{/if}