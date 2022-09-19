{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='product_miniature_item'}

<article class="product-miniature js-product-miniature col-sm-4" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
	<div class="thumbnail-container">
		<div class="ttproduct-image">
			{block name='product_thumbnail'}
				{if $product.cover}
					<a href="{$product.url}" class="thumbnail product-thumbnail">
						<img
							class="ttproduct-img1"
							src = "{$product.cover.bySize.home_default.url}"
							alt = "{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
							data-full-size-image-url = "{$product.cover.large.url}"
						>
						{hook h="displayTtproductImageHover" id_product=$product.id_product home='home_default' large='large_default'}
					</a>
				{else}
					<a href="{$product.url}" class="thumbnail product-thumbnail">
						<img
							class="ttproduct-img1"
							src = "{$urls.no_picture_image.bySize.home_default.url}"
						>
					</a>
				{/if}
			{/block}
			
			
			{block name='product_reviews'}
				{hook h='displayProductListReviews' product=$product}
			{/block}

			<div class="ttproducthover">
			<div class="tt-button-container">
				{include file='catalog/_partials/customize/button-cart.tpl' product=$product}
			</div>
			
			 {hook h='displayTtWishListButton' product=$product}
			 {hook h='displayTtCompareButton' product=$product}

			 {block name='quick_view'}
			<div class="quick-view-block">
				<a href="#" class="quick-view btn" data-link-action="quickview" title="{l s='Quick view'}">
					<i class="material-icons search">&#xE8B6;</i>
					<span> {l s='Quick view' d='Shop.Theme.Actions'}</span>
				</a>
			</div>
			{/block}
			</div>
		</div>
		
		<div class="ttproduct-desc">
			<div class="product-description">
				{block name='product_name'}
				  {if $page.page_name == 'index'}
					<span class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name}</a></span>
				  {else}
					<span class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name}</a></span>
				  {/if}
				{/block}
		
				{block name='product_description_short'}
					<div class="product-desc-short" itemprop="description">{$product.description_short|strip_tags:'UTF-8'|truncate:96:'...' nofilter}</div>
				{/block}

				{block name='product_price_and_shipping'}
					{if $product.show_price}
						<div class="product-price-and-shipping">
							<span itemprop="price" class="price">{$product.price}</span>
							{if $product.has_discount}
								{hook h='displayProductPriceBlock' product=$product type="old_price"}
								<span class="regular-price">{$product.regular_price}</span>

								{assign var="stringPrice" value="{$product.price}"}
								{assign var="productPriceSplit" value=","|explode:$stringPrice}
								{$productPriceSplit = '.'|implode:$productPriceSplit}


								{assign var="stringRegularPrice" value="{$product.regular_price}"}
								{assign var="productRegularPriceSplit" value=","|explode:$stringRegularPrice}
								{$productRegularPriceSplit = '.'|implode:$productRegularPriceSplit}



								{assign var="stringDiscount" value="{math equation=" 100 * (x - y) / x " x={$productRegularPriceSplit|floatval} y={$productPriceSplit|floatval} format=""}"}
								{assign var="productDiscounted" value="."|explode:$stringDiscount}

								<div class="flex-div">
									<div class="flex-discount">
										<span class="discountPrice">Ahorra {$productDiscounted[0]}%<span>
									</div>

									{foreach from=$product.flags item=flag}
										<div class="flex-new">
											<img src="../../../../../../img/icons/flag-new/flag-new.png" class="{$flag.type}"/>
										</div>
									{/foreach}
								</div>
								
							{else}
								<div class="flex-div">
									{foreach from=$product.flags item=flag}
										<div>
											<img src="../../../../../../img/icons/flag-new/flag-new.png" class="{$flag.type}"/>
										</div>
									{/foreach}
								</div>
							{/if}

							{hook h='displayProductPriceBlock' product=$product type="before_price"}
							<span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
							{hook h='displayProductPriceBlock' product=$product type='unit_price'}
							{hook h='displayProductPriceBlock' product=$product type='weight'}
						</div>
					{/if}
				{/block}
				
			</div>
			<div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
				{block name='product_variants'}
					{if $product.main_variants}
						{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
					{/if}
				{/block}
			</div>

		</div>
	</div>
	<div  class="product-add-to-cart">
		{if !$configuration.is_catalog}
			<form action="{$urls.pages.cart}" method="post" class="add-to-cart-or-refresh">
				<div class="product-quantity" style="display:none;">
					<input type="number" name="id_product" value="{$product.id_product}" class="product_page_product_id">
					<input type="number" name="id_customization" value="0" class="product_customization_id">
					<input type="hidden" name="token" value="{$static_token}" class="tt-token">
					<input type="number" name="qty" class="quantity_wanted input-group" value="{$product.minimal_quantity}" min="{$product.minimal_quantity}"/>
				</div>
				{if $product.quantity > 0 && $product.quantity >= $product.minimal_quantity || $product.allow_oosp}
					<button class="button ajax_add_to_cart_button add-to-cart btn btn-default" data-button-action="add-to-cart" title="{l s='Add to cart'}" {if !$product.add_to_cart_url}
					disabled
				{/if}>
					<strong class="btn-text-purchase">{l s='AÑADIR' d='Shop.Theme.Actions'}</strong>
					<img src="../../../../../../img/purchase_cart.svg" />
					</button>
				{else}
					<button class="button ajax_add_to_cart_button add-to-cart-disable btn btn-default" title="{l s='Agotado'}">
						<span>{l s='Agotado'}</span>
					</button>
				{/if} 
			</form>
		{/if}
	</div>

</article>
{/block}
