{**
 * 2007-2017 PrestaShop
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 
{if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header1'))} 
    <div class="site_configuration_imp">
        <a class="site_configuration wow pulse"></a>
    </div>
{/if}

<div id="_desktop_cart">
 
  <div class="blockcart cart-preview js-sidebar-cart-trigger {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <div class="header">
        <a class="blockcart-link" rel="nofollow" href="{$cart_url}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26.458333 33.0729175" x="0px" y="0px"><path d="m2.1332616 273.45266a.52920428.52921798 0 0 0 -.122469 1.04747l1.978635.4346.508479 2.42a.52920428.52921798 0 0 0 .0011.005l2.521739 11.41994a.52920428.52921798 0 0 0 .516746.41497h14.2819194a.52920428.52921798 0 1 0 0-1.05832h-13.8566744l-.486778-2.205h15.0642774a.52920428.52921798 0 0 0 .516749-.41495l1.801389-8.15711a.52920428.52921798 0 0 0 -.51675-.64338c-6.299049.00016-12.598071 0-18.8970074-.003l-.489877-2.33112a.52920428.52921798 0 0 0 -.404614-.40823l-2.311939-.50746a.52920428.52921798 0 0 0 -.104901-.014zm3.540249 4.31911c6.0031104.003 12.0062374.003 18.0092354.003l-1.567818 7.09877h-14.8730794zm5.2496604 12.54029c-1.0354384 0-1.8845774.85272-1.8845874 1.88876-.000011 1.03601.849134 1.88875 1.8845874 1.88875 1.03545 0 1.884595-.85274 1.884584-1.88875-.00001-1.03604-.84915-1.88876-1.884584-1.88876zm7.590014 0c-1.035437 0-1.884576.85272-1.884586 1.88876-.00001 1.03601.849135 1.88875 1.884586 1.88875 1.03545 0 1.884595-.85274 1.884584-1.88875-.000009-1.03604-.849148-1.88876-1.884584-1.88876zm-7.590014 1.05832c.461674 0 .826276.36454.826282.83044.000005.46587-.364603.83044-.826282.83044-.46168 0-.826291-.36457-.826285-.83044.000005-.4659.36461-.83044.826285-.83044zm7.590014 0c.461673 0 .826278.36454.826282.83044.000005.46587-.364602.83044-.826282.83044s-.826288-.36457-.826283-.83044c.000004-.4659.364609-.83044.826283-.83044z" transform="translate(0 -270.54165)"/></svg>
          <div class="cart-products-block">
              <span class="cart-products-count">{$cart.products_count}{l s=' items' d='Shop.Theme.Checkout'}</span>
          <span class="cart-products-text">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</span>
          </div>
        </a>
        
    </div>
    <div class="cart-dropdown js-cart-source hidden-xs-up">
      <div class="cart-dropdown-wrapper">
        <div class="cart-title">
          <h4 class="text-center">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</h4>
        </div>
        {if $cart.products}
          <ul class="cart-items">
            {foreach from=$cart.products item=product}
              <li class="cart-product-line">{include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}</li>
            {/foreach}
          </ul>
          <div class="cart-bottom">
            <div class="cart-subtotals">
              {foreach from=$cart.subtotals item="subtotal"}
                {if $subtotal}
                <div class="total-line {$subtotal.type}">
                  <span class="label">{$subtotal.label}</span>
                  <span class="value price">{$subtotal.value}</span>
                </div>
                {/if}
              {/foreach}
            </div>
            <hr>
            <div class="cart-total total-line">
              <span class="label">{$cart.totals.total.label}</span>
              <span class="value price price-total">{$cart.totals.total.value}</span>
            </div>
                 
            <div class="cart-summary-line cart-total">
                <span class="label">{$cart.totals.total_including_tax.label}</span>
                <span class="value">{$cart.totals.total_including_tax.value}</span>
            </div>
           
            <div class="cart-action">
              <div class="text-center">
                <a href="{$cart_url}" class="btn btn-primary">{l s='Proceed to checkout' d='Shop.Theme.Actions'}<i class="caret-right"></i></a>
              </div>
            </div>
          </div>
        {else}
          <div class="no-items">
            {l s='There are no more items in your cart' d='Shop.Theme.Checkout'}
          </div>
        {/if}
      </div>
    </div>

  </div>
</div>
