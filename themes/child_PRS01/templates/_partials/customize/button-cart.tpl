{**
 * 2007-2018 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}

<div class="product-add-to-cart">
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
					<span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
				</button>
			{else}
				<button class="button ajax_add_to_cart_button add-to-cart-disable btn btn-default" title="{l s='Out of stock'}">
				<span>{l s='out of stock'}</span>
			</button>
			{/if}
		</form>
	{/if}
</div>

