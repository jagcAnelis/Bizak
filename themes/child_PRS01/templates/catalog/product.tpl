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
* @author PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2018 PrestaShop SA
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
{extends file=$layout}

{block name='head_seo' prepend}
    <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
    <meta property="og:type" content="product">      
    <meta property="og:url" content="{$urls.current_url}">
    <meta property="og:title" content="{$page.meta.title}">
    <meta property="og:site_name" content="{$shop.name}">
    <meta property="og:description" content="{$page.meta.description}">
    <meta property="og:image" content="{$product.images[0]['bySize']['facebook_share'].url}">
    <meta property="og:image:width" content="{$product.images[0]['bySize']['facebook_share'].width}">
    <meta property="og:image:height" content="{$product.images[0]['bySize']['facebook_share'].height}">
    <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
    <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
    <meta property="product:price:amount" content="{$product.price_amount}">
    <meta property="product:price:currency" content="{$currency.iso_code}">
    {if isset($product.weight) && ($product.weight != 0)}
        <meta property="product:weight:value" content="{$product.weight}">
        <meta property="product:weight:units" content="{$product.weight_unit}">
    {/if}
{/block}

{block name='content'}
    <div class="only-mobile display">
        {block name='page_header'}
            <h1 class="h1 tt-producttitle productTitle mb" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
        {/block}

        {block name='product_prices'}
            {include file='catalog/_partials/product-prices.tpl'}
        {/block}
    </div>
    <section id="main" itemscope itemtype="https://schema.org/Product">
        <meta itemprop="url" content="{$product.url}">

        <div class="row">
            <div class="col-md-6 pb-left-column">
                {block name='page_content_container'}
                    <section class="page-content" id="content">
                        {block name='page_content'}
                            
                            <div id="productThumbnail">
                                {block name='product_cover_thumbnails'}
                                    {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                                {/block}
                            <div>
                            <div class="scroll-box-arrows">
                                <i class="material-icons left">&#xE314;</i>
                                <i class="material-icons right">&#xE315;</i>
                            </div>

                        {/block}
                    </section>
                {/block}
            </div>
            <div class="col-md-6 pb-right-column">
                {block name='page_header_container'}
                    <div class="hidden-mobile">
                        {block name='page_header'}
                            <h1 class="h1 tt-producttitle productTitle" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
                        {/block}

                        {block name='product_prices'}
                            {include file='catalog/_partials/product-prices.tpl'}
                        {/block}
                    </div>
                    
                    <div id="productDesc">
                        {$product.description nofilter}
                    </div>
                {/block}


                <div class="product-actions">
                    <div id="productPageATC">
                    {block name='product_buy'}
                        <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                            <input type="hidden" name="token" value="{$static_token}">
                            <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                            <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

                            {block name='product_variants'}
                                {include file='catalog/_partials/product-variants.tpl'}
                            {/block}

                            {block name='product_pack'}
                                {if $packItems}
                                    <section class="product-pack">
                                        {foreach from=$packItems item="product_pack"}
                                            {block name='product_miniature'}
                                                {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                                            {/block}
                                        {/foreach}
                                    </section>
                                {/if}
                            {/block}

                            {block name='product_discounts'}
                                {include file='catalog/_partials/product-discounts.tpl'}
                            {/block}

                            {block name='product_add_to_cart'}
                                {include file='catalog/_partials/product-add-to-cart.tpl'}
                            {/block}
                    </div>
                            <div class="flex-wishlist">
                                <div class="flex-wishlistItem">
                                    {if isset($wishlists) && count($wishlists) > 1}
                                        <div class="wishlist">
                                            {foreach name=wl from=$wishlists item=wishlist}
                                                {if $smarty.foreach.wl.first}
                                                    <a class="wishlist_button_list" tabindex="0" data-toggle="popover" data-trigger="focus" title="{l s='Añadir a la wishlist' mod='ttproductwishlist'}" data-placement="bottom"><span>{l s='Añadir a la wishlist' mod='ttproductwishlist'}</span></a>
                                                    <div hidden class="popover-content">
                                                    <div class="cluetipblock">
                                                {/if}
                                                <a title="{$wishlist.name|escape:'html':'UTF-8'}"  data-dismiss="modal"  value="{$wishlist.id_wishlist}" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', '{$product.id_product_attribute|intval}', 1, '{$wishlist.id_wishlist}');">
                                                    <span>
                                                        <img src="../../../../../img/icons/whislist.svg"/> <div class="flex-wishlistLink">{l s='Add to %s' sprintf=[$wishlist.name] mod='ttproductwishlist'}</div>
                                                    </span>
                                                </a>
                                                {if $smarty.foreach.wl.last}
                                                    </div>
                                                    </div>
                                                {/if}
                                                {foreachelse}
                                                <a href="#" id="wishlist_button_nopop"  data-dismiss="modal"  onclick="WishlistCart('wishlist_block_list', 'add', '{$id_product|intval}', $('#idCombination').val(), document.getElementById('quantity_wanted').value); return false;" data-rel="nofollow"  title="{l s='Añadir a la wishlist' mod='ttproductwishlist'}" class="btn btn-primary">
                                                    <span><img src="../../../../../img/icons/whislist.svg"/> <div class="flex-wishlistLink">{l s='Añadir a la wishlist' mod='ttproductwishlist'}</div>  </span>
                                                </a>
                                            {/foreach}
                                        </div>
                                    {else}
                                        <div class="wishlist">
                                            <a class="addToWishlist wishlistProd_{$product.id_product|intval}" href="#"  data-dismiss="modal" data-rel="{$product.id_product|intval}" title="{l s='Añadir a la wishlist' mod='ttproductwishlist'}" onclick="WishlistCart('wishlist_block_list', 'add', '{$product.id_product|intval}', '{$product.id_product_attribute|intval}', 1); return false;">
                                                <span>
                                                    <img class="empty" src="../../../../../img/icons/whislist.svg"/>
                                                    <img class="filled" src="../../../../../img/icons/whislist-filled.svg"/>
                                                    <div class="flex-wishlistLink">{l s='Añadir a la wishlist' mod='ttproductwishlist'}</div></span>
                                            </a>
                                        </div>
                                    {/if}

                                </div>
                                <div class="flex-wishlistItem">
                                    {block name='product_additional_info'}
                                        {include file='catalog/_partials/product-additional-info.tpl'}
                                    {/block}
                                </div>
                            </div>

                            {* Input to refresh product HTML removed, block kept for compatibility with themes *}
                            {block name='product_refresh'}{/block}

                        </form>
                    {/block}

                </div>
                <hr>
                <div class="flexDropdown">
                    <span class="dropdownTitle">Detalles</span>
                    <span class="navbar-toggler collapse-icons details-collapser">
                        <a class="arrow-align-left" data-toggle="collapse" href="#collapseDetalles">
                            <img class="image-collapsed" src="../../../../../img/icons/dropdown-icon/down/down.png" />
                            <img class="image-not-collapsed" src="../../../../../img/icons/dropdown-icon/up/up.png" />
                        </a>
                    </span>
                </div>


                <div class="collapse" id="collapseDetalles">
                    <div>
                        <ul class="dropdownList">
                            {block name="product_information"}
                                <span class="label product-brand listItem">{l s='Brand' d='Shop.Theme.Catalog'}:</span>
                                <span class="product-brand-name listItem">
                                    <a href="{$product_brand_url}">{$product_manufacturer->name}</a>
                                </span>
                                {if isset($product.reference_to_display)}
                                    <div class="product-reference listItem">
                                        <span class="label product-brand listItem">{l s='Reference' d='Shop.Theme.Catalog'}: </span>
                                        <span class="product-brand-name listItem" itemprop="sku">{$product.reference_to_display}</span>
                                    </div>
                                {/if}
                                {if isset($product.ean13)}
                                    <div class="product-reference listItem">
                                        <span class="label product-brand listItem">{l s='Ean' d='Shop.Theme.Catalog'}: </span>
                                        <span class="product-brand-name listItem" itemprop="sku">{$product.ean13}</span>
                                    </div>
                                {/if}

                                <div class="end-product-information"></div>
                            {/block}

                            {block name='product_features'}
                                {if $product.grouped_features}
                                    <div class="list-of-features">
                                        <ul>
                                            {foreach from=$product.grouped_features item=feature}
                                                <li class="product-feature-name listItem">{$feature.name}:
                                                    <span class="product-feature-value listItem">{$feature.value nofilter}</span>
                                                </li>
                                            {/foreach}
                                        </ul>
                                    </div>
                                {/if}
                            {/block}
                        </ul>
                    </div>
                </div>
                <hr>

                {* EVALUACIONES-----------------------------------------------------------------------------------------------------------*}

                            <div class="flexDropdown">
                                <div class="dropdownTitle">
                                    Evaluaciones
                                </div>
                                
                                

                                <span class="navbar-toggler collapse-icons evaluations-collapser">
                                    <a class="arrow-align-left" data-toggle="collapse" href="#collapseEvaluacions">
                                        <img class="image-collapsed" src="../../../../../img/icons/dropdown-icon/down/down.png" />
                                        <img class="image-not-collapsed" class src="../../../../../img/icons/dropdown-icon/up/up.png" />
                                    </a>
                                </span>
                            </div>
                            <hr>
                           <div class="collapse" id="collapseEvaluacions">
                               {block name='product_comment'}
                                    {foreach from=$product.extraContent item=extra key=extraKey}
                                        <li class="">
                                            <a
                                                class=""
                                                data-toggle=""
                                                href="#extra-{$extraKey}"
                                                role=""
                                                aria-controls="extra-{$extraKey}">{$extra.title}
                                            </a>
                                        </li>
                                    {/foreach}
                                
                                
                                    

                                {/block}

                                {foreach from=$product.extraContent item=extra key=extraKey}
                                    <div id="vsbl" class="tab-pane fade in {$extra.attr.class}" id="extra-{$extraKey}" role="tabpanel" {foreach $extra.attr as $key => $val} {$key}="{$val}"{/foreach}>
                                        {$extra.content nofilter}
                                    </div>
                                {/foreach}

                                {block name='product_comment_tab_content'}
                                    {capture name='displayTtProductTabContent'}{hook h='displayTtProductTabContent'}{/capture}
                                    {if $smarty.capture.displayTtProductTabContent}
                                        {$smarty.capture.displayTtProductTabContent nofilter}
                                    {/if}
                                {/block}

                            </div>


                <div class="product-information">
                    {block name='product_description_short'}
                        <div id="product-description-short-{$product.id}" itemprop="description">{$product.description_short nofilter}</div>
                    {/block}

                    {if $product.is_customizable && count($product.customizations.fields)}
                        {block name='product_customization'}
                            {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                        {/block}
                    {/if}



                </div>
            </div>
        </div>


        {block name='product_footer'}
            {hook h='displayFooterProduct' product=$product category=$category}
        {/block}

        {block name='product_images_modal'}
            {include file='catalog/_partials/product-images-modal.tpl'}
        {/block}

        {block name='page_footer_container'}
            <footer class="page-footer">
                {block name='page_footer'}
                    <!-- Footer content -->
                {/block}
            </footer>
        {/block}
    </section>

{/block}
