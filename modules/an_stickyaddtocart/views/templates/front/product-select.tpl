{**
* 2019 Anvanto
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author Anvanto (anvantoco@gmail.com)
*  @copyright  2019 anvanto.com

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<script type="text/javascript">
//<![CDATA[
	if (typeof attributeGroups == "undefined") {
	   var attributeGroups = [];
	}
	attributeGroups[{$productId|escape:'htmlall':'UTF-8'}] = JSON.parse('{$attributeGroups|escape:"javascript":"UTF-8" nofilter}');
//]]>
</script>
<div class="an_stickyAddToCart-product-selectbox js-an_stickyAddToCart-product-selectbox an_stickyAddToCart-dropdown">
	<div class="an_stickyAddToCart-dropdown-toggler">
		<div class="js-an_stickyAddToCart-filter-option">{l s='Select variant' mod='an_stickyAddToCart'}</div>
	</div>
	<div class="an_stickyAddToCart-dropdown-menu">
		<ul>
			{foreach from=$productData key=k item=v name=fodataOne}
			<li role="option" data-value="{$k|intval}" class="{if !$v.availableForOrder}an_stickyAddToCart-select-sold-out{/if}{if $v.default_on == '1'} selected{/if}">
				<span class="js-an_stickyAddToCart-text">
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
				<span class="an_stickyAddToCart-select-price">
					{$v.prices.price|escape:'htmlall':'UTF-8'}
				</span>				
				{if isset($v.prices.regular_price)}
				<span class="an_stickyAddToCart-select-regular_price">
					{$v.prices.regular_price|escape:'htmlall':'UTF-8'}
				</span>
				{/if}
				{/if}
				{if !$v.availableForOrder}
				<span class="an_stickyAddToCart-select-sold-out" style="background: {$config.background_sold_out|escape:'htmlall':'UTF-8'}; color: {$config.color_sold_out|escape:'htmlall':'UTF-8'};">{l s='Sold out' mod='an_stickyAddToCart'}</span>
				{/if}
				{if isset($v.prices.has_discount)}
				{if $v.prices.discount_type == 'percentage'}
				<span class="an_stickyAddToCart-select-sale" style="background: {$config.background_sale|escape:'htmlall':'UTF-8'}; color: {$config.color_sold_out|escape:'htmlall':'UTF-8'};">{$v.prices.discount_percentage|escape:'htmlall':'UTF-8'}
				{/if}
				{if $v.prices.discount_type == 'amount'}
				<span class="an_stickyAddToCart-select-sale" style="background: {$config.background_sale|escape:'htmlall':'UTF-8'}; color: {$config.color_sale|escape:'htmlall':'UTF-8'};">{l s='Save' mod='an_stickyAddToCart'} {$v.prices.discount_amount|escape:'htmlall':'UTF-8'}
				{/if}				
				</span>
				{/if}				
			</li>
			{/foreach}
		</ul>
	</div>
</div>	
