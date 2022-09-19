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

{block name='product_description_short'}
  <div class="product-desc-short" itemprop="description">{$product.description_short|strip_tags:'UTF-8'|truncate:96:'...' nofilter}</div>
{/block}

{block name='product_price_and_shipping'}
  {if $product.show_price}
    
      {if $product.has_discount}
      <div class="product-price-and-shipping ">
        <div class="full-price">
          <span itemprop="price" class="priceProduct">{$product.price}</span>
        

        {hook h='displayProductPriceBlock' product=$product type="old_price"}
        <span class="regularPriceProduct">{$product.regular_price}</span>
       </div>
          {assign var="stringPrice" value="{$product.price}"}
          {assign var="productPriceSplit" value=","|explode:$stringPrice}
          {$productPriceSplit = '.'|implode:$productPriceSplit}


          {assign var="stringRegularPrice" value="{$product.regular_price}"}
          {assign var="productRegularPriceSplit" value=","|explode:$stringRegularPrice}
          {$productRegularPriceSplit = '.'|implode:$productRegularPriceSplit}



          {assign var="stringDiscount" value="{math equation=" 100 * (x - y) / x " x={$productRegularPriceSplit|floatval} y={$productPriceSplit|floatval} format=""}"}
          {assign var="productDiscounted" value="."|explode:$stringDiscount}
        
        <div class="flexDivProduct">
          <div class="flexDiscountProduct">
            <span class="discountPrice">Ahorra {$productDiscounted[0]}%<span>
          </div>
          
              {foreach from=$product.flags item=flag}
                <li id="relativeList" class="product-flag {$flag.type}"><img src="../../../../../../img/icons/flag-new/flag-new.png" class="{$flag.type}"/></li>
              {/foreach}
                 
        </div>
      </div>  
      {else}
        <div class="product-price-and-shipping globalPriceDiv">
          <div class="full-price">
            <span itemprop="price" class="priceProduct2">{$product.price}</span>
          </div> 
            <div >
            {foreach from=$product.flags item=flag}
              <li id="relativeList" class="product-flag {$flag.type}"><img src="../../../../../../img/icons/flag-new/flag-new.png" class="{$flag.type}"/></li>
            {/foreach}
              
          </div>
        </div>
      {/if}

      
    
  {/if}
{/block}
