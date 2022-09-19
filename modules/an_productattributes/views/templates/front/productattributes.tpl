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

<div class="an_productattributes">
<form method="post" class="an_productattributesForm" action="{$cart|escape:'htmlall':'UTF-8'}">
<input name="token" value="{$token|escape:'htmlall':'UTF-8'}" type="hidden">
<input name="id_product" value="{$productId|escape:'htmlall':'UTF-8'}" type="hidden">
{*<input name="id_customization" value="0" placeholder="" type="hidden"> *}
{if $config.display_quantity!='1'}
<input name="qty" type="hidden" value="{$minimal_quantity|escape:'htmlall':'UTF-8'}" min="{$minimal_quantity|escape:'htmlall':'UTF-8'}">
{/if}

	
	{if $config.type_view=='standart'}
	<div class="js-an_productattributes-standart">
		{include file='./product-variants.tpl'}
	</div>
	{/if}
	{if $config.type_view=='select' AND isset($productData) AND count($productData)>0}
	<div class="an_productattributes-select js-an_productattributes-select">
		{include file='./product-select.tpl'}
	</div>
	{/if}
			
	{if isset($configuration.is_catalog) AND !$configuration.is_catalog AND $config.display_add_to_cart=='1'}
	<div class="an_productattributes-qty-add clearfix">
		
		{if $config.display_quantity=='1'}
		<div class="an_productattributes-qty-container">
		  <input type="number" name="qty" value="{$minimal_quantity|escape:'htmlall':'UTF-8'}" class="input-group form-control an_productattributes-qty" min="{$minimal_quantity|escape:'htmlall':'UTF-8'}" {if $order_out_of_stock} data-max="{$quantity|escape:'htmlall':'UTF-8'}" {/if} aria-label="Quantity" style="display: block;" >
		</div>
		{/if}
		
		<div class="an_productattributes-add">
		  <button class="btn btn-primary js-an_productattributes-add-to-cart" data-button-action="add-to-cart" type="submit" {if $availableForOrder !='1'} disabled="disabled"{/if} data-status="{$availableForOrder}">
			<i class="material-icons shopping-cart"></i>
			{l s='Add to cart' d='Shop.Theme.Actions'}
		  </button>
		</div>
	</div>
	{/if}
</form>
</div>