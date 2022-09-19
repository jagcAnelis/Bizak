{*
    * We offer the best and most useful modules PrestaShop and modifications for your online store.
    *
    * We are experts and professionals in PrestaShop
    *
    * @author    PresTeamShop.com <support@presteamshop.com>
    * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
    * @license   see file: LICENSE.txt
    * @category  PrestaShop
    * @category  Module
*}

<div id="opc_cart">
    <div id="cart_header">
        <h3>
            {l s='My order' mod='onepagecheckoutps'}
        </h3>
    </div>
    <div id="cart_body">
        <div class="col-xs-12">
            {if $OPC.Cart.cart.products}
                <ul class="cart-items">
                {foreach from=$OPC.Cart.cart.products item=product}
                    <li class="cart-item">
                    {block name='cart_detailed_product_line'}
                        {include file='module:onepagecheckoutps/views/templates/front/checkout/cart/_partials/cart-detailed-product-line.tpl'}
                    {/block}
                    </li>
                    {if is_array($product.customizations) && $product.customizations|count >1}<hr>{/if}
                {/foreach}
                </ul>
            {else}
            <span class="no-items">{l s='There are no more items in your cart' d='Shop.Theme.Checkout'}</span>
            {/if}
        </div>

        <!-- shipping informations -->
        {block name='hook_shopping_cart_footer'}
            {hook h='displayShoppingCartFooter'}
        {/block}

        <div class="col-xs-12">
            {block name='cart_summary'}
            <div class="card cart-summary">

              {block name='hook_shopping_cart'}
                {hook h='displayShoppingCart'}
              {/block}

              {block name='cart_totals'}
                {*{include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}*}
              {/block}

              {block name='cart_actions'}
                {*{include file='checkout/_partials/cart-detailed-actions.tpl' cart=$cart}*}
              {/block}

            </div>
          {/block}

          {block name='hook_reassurance'}
            {hook h='displayReassurance'}
          {/block}
        </div>
    </div>
    <div id="cart_footer"></div>
</div>