{*
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
{extends file=$layout}

{block name='content'}
    <div id="view_wishlist">
        <h1 class="h1">
            {l s='Wishlist' mod='ttproductwishlist'}
        </h1>
        {if $wishlists}
            <p>
                <strong class="dark">
                    {l s='Other wishlists of %1s %2s:' sprintf=[$current_wishlist.firstname, $current_wishlist.lastname] mod='ttproductwishlist'}
                </strong>
                {foreach from=$wishlists item=wishlist name=i}
                    {if $wishlist.id_wishlist != $current_wishlist.id_wishlist}
                        <a href="{$link->getModuleLink('ttproductwishlist', 'view', ['token' => $wishlist.token])|escape:'html':'UTF-8'}" rel="nofollow" title="{$wishlist.name|escape:'html':'UTF-8'}">
                            {$wishlist.name|escape:'htmlall':'UTF-8'}
                        </a>
                        {if !$smarty.foreach.i.last}
                            /
                        {/if}
                    {/if}
                {/foreach}
            </p>
        {/if}

        <div id="products">
            <div class="products row">
                {foreach from=$products item=productItem name=i}
                    {assign var='product' value=$productItem.curProduct}
                    {assign var='wishlist' value=$productItem.wishlistInfo}
                    <article id="wlp_{$product.id_product}_{$product.id_product_attribute}" class="product-miniature js-product-miniature col-xs-12 col-sm-6 col-md-4 col-lg-6 col-xl-4" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
                        <div class="product-container">
                            <div class="thumbnail-container">
                                <div class="thumbnail-inner">
                                    {block name='product_thumbnail'}
                                        <a href="{$product.url}" class="thumbnail product-thumbnail">
                                            <img src = "{$product.cover.bySize.home_default.url}" alt = "{$product.name|escape:'html':'UTF-8'}" />
                                        </a>
                                    {/block}
                                </div>
                            </div>

                            <div class="product-description">
                                {block name='product_name'}
                                    <h1 class="h3 product-title" itemprop="name">
                                        <a href="{$product.url}">
                                            {$product.name}
                                        </a>
                                    </h1>
                                {/block}

                                {block name='product_price_and_shipping'}
                                    {if $product.show_price}
                                        <div class="product-price-and-shipping">
                                            {if $product.has_discount}
                                                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                                                <span class="regular-price">{$product.regular_price}</span>
                                                {if $product.discount_type === 'percentage'}
                                                    <span class="discount-percentage">{$product.discount_percentage}</span>
                                                {/if}
                                            {/if}
                                            <span itemprop="price" class="price">{$product.price}</span>
                                        </div>
                                    {/if}
                                {/block}

                                <p class="form-group">
                                    <label class="wishlist-label" for="quantity_{$product.id_product}_{$product.id_product_attribute}">
                                        {l s='Quantity' mod='ttproductwishlist'}
                                    </label>
                                    <input class="form-control quantity" type="text" id="quantity_{$product.id_product}_{$product.id_product_attribute}" value="{$wishlist.quantity|intval}" size="3" disabled />
                                </p>

                                <p class="form-group">
                                    <label class="wishlist-label">
                                        {l s='Priority' mod='ttproductwishlist'}
                                    </label>
                                    <span class="priority">
                                        {if $wishlist.priority == 0}{l s='High' mod='ttproductwishlist'}{/if}
                                        {if $wishlist.priority == 1}{l s='Medium' mod='ttproductwishlist'}{/if}
                                        {if $wishlist.priority == 2}{l s='Low' mod='ttproductwishlist'}{/if}
                                    </span>
                                </p>

                                <div class="btn_action">
                                    <div class="product-add-to-cart">
                                        <a class="btn btn-primary more" href="{$product.url}" title="{l s='View' mod='ttproductwishlist'}" rel="nofollow">
                                            <span>{l s='View' mod='ttproductwishlist'}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                {/foreach}
            </div>
        </div>
    </div> <!-- #view_wishlist -->
{/block}
