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

{block name="price-by-customer"}
    {if !$configuration.is_catalog}
        {if $price_type == $price_by_customer}
            <div class="row donation-price-block">
                <div class="col-xs-12 col-md-10 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon donation-price-input-addon">{$currency_sign}</span>
                        <input type="text" form="add-to-cart-or-refresh" class="input-group form-control" id="donation-price-input" name="donation_price" value="{$minimum_price}" placeholder="{$minimum_price}">
                    </div>
                </div>
                <i><p class="col-xs-12 text-danger price-error hide"></p></i>
                <div class="col-xs-12 text-muted" id="donation-price-note">
                    {l s='You can’t donate less than ' mod='wkcharitydonation'}{$currency_sign}{$minimum_price}.
                </div>
            </div>
            <hr id="donation-block-seperator">
        {/if}
        <input type="hidden" form="add-to-cart-or-refresh" value={$id_donation_info} name="id_donation_info" class="id-donation-info">
        <button type="button" class="btn btn-primary donation-add-to-cart" {if !$product.add_to_cart_url}disabled{/if}>
            <i class="material-icons shopping-cart">&#xE547;</i>
            {l s='Add to cart' mod='wkcharitydonation'}
        </button>
    {/if}
{/block}

{block name='product_availability'}
    <div>
        <span id="product-availability">
        {if $product.show_availability && $product.availability_message}
            {if $product.availability == 'available'}
            <i class="material-icons rtl-no-flip product-available">&#xE5CA;</i>
            {elseif $product.availability == 'last_remaining_items'}
            <i class="material-icons product-last-items">&#xE002;</i>
            {else}
            <i class="material-icons product-unavailable">&#xE14B;</i>
            {/if}
            {$product.availability_message}
        {/if}
        </span>
    </div>
{/block}