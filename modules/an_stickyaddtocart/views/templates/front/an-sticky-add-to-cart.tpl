<div class="an_stickyAddToCart an_stickyAddToCart js-an_stickyAddToCart" data-id-product="{$product.id_product}" data-hidden="1">
<form method="post" class="an_stickyAddToCartForm" action="{$cart|escape:'htmlall':'UTF-8'}">
<input name="token" value="{$token|escape:'htmlall':'UTF-8'}" type="hidden">
<input name="id_product" value="{$productId|escape:'htmlall':'UTF-8'}" type="hidden">
{*<input name="id_customization" value="0" placeholder="" type="hidden"> *}
{if $config.display_quantity!='1'}
<input name="qty" type="hidden" value="{$minimal_quantity|escape:'htmlall':'UTF-8'}" min="{$minimal_quantity|escape:'htmlall':'UTF-8'}">
{/if}
		
	<div class="container">
	    <div class="row an_stickyAddToCart-content   {if $config.type_view=='standart'}content-standart{/if}">
            <div class="an_stickyAddToCart-imageblock col-xs-2 col-xl-1">
                <div class="an_stickyAddToCart-container-image">
                    <img class="js-an_stickyAddToCart-image an_stickyAddToCart-image" src="{$cover.small.url}" alt="" title="" >
                </div>
            </div>

            <div class="an_stickyAddToCart-price col-xs-3 col-xl-2">
                <h3 class="product-title">{$product.name|truncate:30:'...'}</h3>
                <div class="an_stickyAddToCart-product-price-and-shipping product-price-and-shipping">
					{if $product.has_discount}
                    <span class="an_stickyAddToCart-regular-price regular-price">{$product.regular_price}</span>
					{/if}
                    <span class="an_stickyAddToCart-price-price price">{$product.price}</span>
                </div>

            </div>

            {if $config.type_view=='standart'}
            <div class="col-xs-4 col-xl-6 an_stickyAddToCart-standart">
                <div class="js-an_stickyAddToCart-standart">
                    {include file='./product-variants.tpl'}
                </div>
            </div>
            {/if}



            <div class="an_stickyAddToCart-btnadd {if $config.type_view=='standart'}col-xs-3{/if}  {if $config.type_view=='select' AND isset($productData) AND count($productData)>0}col-xs-7 col-xl-9{/if}">
                {if $config.type_view=='select' AND isset($productData) AND count($productData)>0}
                <div class="an_stickyAddToCart-select js-an_stickyAddToCart-select">
                    {include file='./product-select.tpl'}
                </div>
                {/if}
                {if isset($configuration.is_catalog) AND !$configuration.is_catalog AND $config.display_add_to_cart=='1'}
                <div class="an_stickyAddToCart-qty-add clearfix">

                    {if $config.display_quantity=='1'}
                    <div class="an_stickyAddToCart-qty-container an_stickyAddToCart-qty-container">
                      <input id="an_stickyAddToCart_qty" type="number" name="qty" value="{$minimal_quantity|escape:'htmlall':'UTF-8'}" class="input-group form-control an_stickyAddToCart-qty" min="{$minimal_quantity|escape:'htmlall':'UTF-8'}" {if $order_out_of_stock} data-max="{$quantity|escape:'htmlall':'UTF-8'}" {/if} aria-label="Quantity" style="display: block;" >
                    </div>
                    {/if}

                    <div class="an_stickyAddToCart-add ">
                      <button data-animation="{$config.effect}" data-interval="{$config.interalEffect}" class="btn btn-primary js-an_stickyAddToCart-add-to-cart" data-button-action="add-to-cart" type="submit" {if $availableForOrder !='1'} disabled="disabled"{/if} data-status="{$availableForOrder}">
                        {l s='Add to cart' d='Shop.Theme.Actions'}
                      </button>
                    </div>
                </div>
                {/if}
            </div>
        </div>
	</div>
</form>	
</div>