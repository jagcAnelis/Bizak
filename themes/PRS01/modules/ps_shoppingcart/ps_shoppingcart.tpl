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
<div id="_desktop_cart">
  <div class="blockcart cart-preview {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header">
        <a rel="nofollow">
        <i class="material-icons shopping-cart">shopping_cart</i>
        <span class="hidden-sm-down">{l s='Cart' d='Shop.Theme.Checkout'}</span>
        <span class="cart-products-count">{$cart.products_count}</span>
        </a>
    </div>
	<div class="cart_block block exclusive">
	<div class="block_content">
	<div class="cart_block_list">
		{if $cart.products}
		{foreach from=$cart.products item='products'}
			<div class="products">
			<div class="img">
		{block name='product_thumbnail'}
			<a href="{$products.url}" class="thumbnail product-thumbnail">
				<img
				src = "{$products.cover.bySize.small_default.url}"
				alt = "{$products.cover.legend}"
				data-full-size-image-url = "{$products.cover.large.url}"
			/>
			</a>
		{/block}
		</div>
		<div class="cart-info">
			<h2 class="h2 productname" itemprop="name">
				<a href="{$products.url}">{$products.name|truncate:18:'...'}</a>
			</h2>
		<div class="ttPrice">
			<span class="quantity">{$products.quantity}X</span>
			<span class="price">{$products.price}</span>
		</div>
			 {foreach from=$products.attributes item="value"}
				<div class="product-line-info">
					<span class="value">{$value}</span>
				</div>
			{/foreach}
		</div>
			<p class="remove_link">
				<a rel="nofollow" href="{$products.remove_from_cart_url}"><i class="material-icons">&#xE5CD;</i></a>
			</p>	
		</div>
		{/foreach}
			<div class="cart-prices">
				<span class="total pull-left">
					{l s='Total:' d='Shop.Theme.Checkout'}
				</span>
				{if $cart.totals.total.amount}
					<span class="amount pull-right">
						{Product::convertAndFormatPrice($cart.totals.total.amount)}
					</span>
				{else}
					<span class="amount pull-right">
						{Product::convertAndFormatPrice(0.00)}
					</span>
				{/if}
			</div>
			<div class="cart-buttons">
				 <a rel="nofollow" href="{$cart_url}" class="btn-primary">
					{l s='Check out' d='Shop.Theme.Checkout'} <i class="ion-chevron-right"></i>
				</a>
			</div>
		{else}
		<p class="no-item">
			{l s='No products in the cart.' d='Shop.Theme.Checkout'}
		</p>
		{/if}
	</div>
	</div>
	</div>
  </div>
</div>
