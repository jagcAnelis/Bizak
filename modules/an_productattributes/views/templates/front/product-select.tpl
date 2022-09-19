{**
* 2021 Anvanto
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
*  @author Anvanto <anvantoco@gmail.com>
*  @copyright  2021 Anvanto
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of Anvanto
*}

<script type="text/javascript">
//<![CDATA[
	if (typeof attributeGroups == "undefined") {
	   var attributeGroups = [];
	}
	attributeGroups[{$productId|escape:'htmlall':'UTF-8'}] = JSON.parse('{$attributeGroups|escape:"javascript":"UTF-8" nofilter}');
//]]>
</script>
<div class="an_productattributes-product-selectbox js-an_productattributes-product-selectbox an_productattributes-dropdown">
	<div class="an_productattributes-dropdown-toggler">
		<div class="js-an_productattributes-filter-option">{l s='Select variant' mod='an_productattributes'}</div>
	</div>
	<div class="an_productattributes-dropdown-menu">
		<ul>
			{foreach from=$productData key=k item=v name=fodataOne}
			<li role="option" data-value="{$k|intval}" class="{if !$v.availableForOrder}an_productattributes-select-sold-out{/if}{if $v.default_on == '1'} selected{/if}">
				<span class="js-an_productattributes-text">
				{$countData = count($productData[$k]['comb'])}
				{foreach from=$productData[$k]['comb'] key=k3 item=v3 name=fodata}
					{if $config.display_labels=='1'}
					{$v3['group_name']|escape:'htmlall':'UTF-8'}: 
					{/if}
					{$v3['attribute_name']|escape:'htmlall':'UTF-8'} 
					{if $smarty.foreach.fodata.iteration < $countData}{$config.separator|escape:'htmlall':'UTF-8'}{/if}
				{/foreach}
				</span>
				{if $config.display_prices=='1'}
				<span class="an_productattributes-select-price">
					{$v.prices.price|escape:'htmlall':'UTF-8'}
				</span>				
				{if isset($v.prices.regular_price)}
				<span class="an_productattributes-select-regular_price">
					{$v.prices.regular_price|escape:'htmlall':'UTF-8'}
				</span>
				{/if}
				{/if}
				{if !$v.availableForOrder}
				<span class="an_productattributes-select-sold-out" style="background: {$config.background_sold_out|escape:'htmlall':'UTF-8'}; color: {$config.color_sold_out|escape:'htmlall':'UTF-8'};">{l s='Sold out' mod='an_productattributes'}</span>
				{/if}
				{if isset($v.prices.has_discount)}
				{if $v.prices.discount_type == 'percentage'}
				<span class="an_productattributes-select-sale" style="background: {$config.background_sale|escape:'htmlall':'UTF-8'}; color: {$config.color_sold_out|escape:'htmlall':'UTF-8'};">{$v.prices.discount_percentage|escape:'htmlall':'UTF-8'}
				{/if}
				{if $v.prices.discount_type == 'amount'}
				<span class="an_productattributes-select-sale" style="background: {$config.background_sale|escape:'htmlall':'UTF-8'}; color: {$config.color_sale|escape:'htmlall':'UTF-8'};">{l s='Save' mod='an_productattributes'} {$v.prices.discount_amount|escape:'htmlall':'UTF-8'}
				{/if}				
				</span>
				{/if}				
			</li>
			{/foreach}
		</ul>
	</div>
</div>	
