{*
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

{if $products}
    {if !$refresh}
        <div class="wishlistLinkTop">
            <p class="wishlisturl form-group">
                <label>{l s='Permalink' mod='ttproductwishlist'}:</label>
                <input type="text" class="form-control" value="{$link->getModuleLink('ttproductwishlist', 'view', ['token' => $token_wish])|escape:'html':'UTF-8'}" readonly="readonly"/>
            </p>
            <div id="showSendWishlist">
                <a class="btn btn-primary send" href="#" onclick="$('#ttsendwishlist-modal').modal('show');" title="{l s='Send this wishlist' mod='ttproductwishlist'}">
                    <span>{l s='Send this wishlist' mod='ttproductwishlist'}</span>
                </a>
            </div>
        </div>
    {/if}
    <div id="products" class="wlp_bought">
        <div class="products">
		<div class="row">
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
                            <a class="lnkdel" href="javascript:;" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='ttproductwishlist'}">
                                <i class="fa fa-times-circle"></i>
                            </a>
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
                                <label for="quantity_{$product.id_product}_{$product.id_product_attribute}">
                                    {l s='Quantity' mod='ttproductwishlist'}
                                </label>
                                <input class="form-control" type="text" id="quantity_{$product.id_product}_{$product.id_product_attribute}" value="{$wishlist.quantity|intval}" size="3" />
                            </p>

                            <p class="form-group">
                                <label for="priority_{$product.id_product}_{$product.id_product_attribute}">
                                    {l s='Priority' mod='ttproductwishlist'}:
                                </label>
                                <select id="priority_{$product.id_product}_{$product.id_product_attribute}" class="form-control grey">
                                    <option value="0"{if $wishlist.priority eq 0} selected="selected"{/if}>
                                        {l s='High' mod='ttproductwishlist'}
                                    </option>
                                    <option value="1"{if $wishlist.priority eq 1} selected="selected"{/if}>
                                        {l s='Medium' mod='ttproductwishlist'}
                                    </option>
                                    <option value="2"{if $wishlist.priority eq 2} selected="selected"{/if}>
                                        {l s='Low' mod='ttproductwishlist'}
                                    </option>
                                </select>
                            </p>

                            {if $wishlists|count > 1}
                                {foreach name=wl from=$wishlists item=wishlist}
                                    {if $smarty.foreach.wl.first}
                                        <p class="form-group">
                                            <label for="move_{$product.id_product}_{$product.id_product_attribute}">
                                                {l s='Move' mod='ttproductwishlist'}
                                            </label>
                                            <select id="move_{$product.id_product}_{$product.id_product_attribute}" class="form-control">
                                                <option>
                                                    {l s='---' mod='ttproductwishlist'}
                                                </option>
                                    {/if}
                                    {if $id_wishlist != {$wishlist.id_wishlist}}
                                                        <option title="{$wishlist.name|escape:'html':'UTF-8'}" value="{$wishlist.id_wishlist}" onclick="wishlistProductChange({$product.id_product}, {$product.id_product_attribute}, '{$id_wishlist}', '{$wishlist.id_wishlist}');">
                                                                {l s='Move to %s'|sprintf:$wishlist.name mod='ttproductwishlist'}
                                                        </option>
                                    {/if}
                                    {if $smarty.foreach.wl.last}
                                            </select>
                                        </p>
                                    {/if}
                                {/foreach}
                            {/if}

                            <div class="btn_action">
                                <a class="btn btn-primary"  href="javascript:;" onclick="WishlistProductManage('wlp_bought_{$product.id_product_attribute}', 'update', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Save' mod='ttproductwishlist'}">
                                    <span>{l s='Save' mod='ttproductwishlist'}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            {/foreach}
        </div>
		</div>
    </div>
    <div id="ttsendwishlist-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h2 class="modal-title">
                        {l s='Send Wishlist' mod='ttproductwishlist'}
                    </h2>
                </div>
                <div class="modal-body">
                    <form method="post" class="wl_send box unvisible" onsubmit="return (false);">
                        <fieldset>
                            <div class="form-group row">
                                <label for="email1" class="col-md-3 form-control-label required">
                                    {l s='Email' mod='ttproductwishlist'}1
                                </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="email1" id="email1" type="email">
                                </div>
                            </div>
                            {section name=i loop=11 start=2}
                                <div class="form-group row">
                                    <label for="email{$smarty.section.i.index}" class="col-md-3 form-control-label required">
                                        {l s='Email' mod='ttproductwishlist'}{$smarty.section.i.index}
                                    </label>
                                    <div class="col-md-9">
                                        <input class="form-control" name="email{$smarty.section.i.index}" id="email{$smarty.section.i.index}" type="email">
                                    </div>
                                </div>
                            {/section}
                        </fieldset>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Close' mod='ttproductwishlist'}</button>
                    <button id="submitTtWishlist" class="btn btn-primary" type="submit" name="submitWishlist" onclick="WishlistSend('wl_send', '{$id_wishlist}', 'email');">
                        <i class="fa fa-spinner fa-pulse"></i>
                        <span>{l s='Send' mod='ttproductwishlist'}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
{else}
    <p class="alert alert-warning">
        {l s='No products' mod='ttproductwishlist'}
    </p>
{/if}
