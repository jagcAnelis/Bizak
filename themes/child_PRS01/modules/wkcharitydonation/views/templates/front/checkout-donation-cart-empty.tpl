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
 {*extends file='checkout/cart.tpl'*}
{extends file='checkout/cart-empty.tpl'}

{block name='content' append}
  {hook h='displayCrossSellingShoppingCart'}
{/block}
{block name="charity_and_donation"}
          <div class="card">
            <div class="card-block">
              <h1 class="h1">{l s='DONATIONS AND CHARITY' mod='wkcharitydonation'}</h1>
            </div>
            <hr class="separator">
            <div class="charity-block">
              {foreach $checkoutDonations as $checkoutDonation}

                <form class="row donation-block" method="POST" action="{$cart_url}">
                  <div class="{if isset($columnLayout) && $columnLayout == 0}col-xs-12{else}col-sm-7{/if}">
                    <div class="donation-title">
                      <strong>{if $checkoutDonation['product_visibility'] == 1}<a href="{$checkoutDonation['link']}" class="label">{/if}{$checkoutDonation['name'][$id_current_lang]}{if $checkoutDonation['product_visibility'] == 1}</a>{/if}</strong>
                    </div>
                    <div class="donation-description">
                      {$checkoutDonation['description'][$id_current_lang] nofilter}
                    </div>
                  </div>
                  <div class="{if isset($columnLayout) && $columnLayout == 0}col-xs-12{else}col-sm-3{/if} donation-price-div">
                    <div class="row">
                      <div class="col-sm-12">
                        {if ($checkoutDonation['price_type']) == 1}
                          <strong>{$checkoutDonation['displayPrice']}</strong>
                          <input type="hidden" value={$checkoutDonation['id_donation_info']} name="id_donation_info" class="id-donation-info">
                        {else}
                          <div class="input-group">
                            <span class="input-group-addon">{$currency_sign}</span>
                            <input type="text" class="input-group form-control donation-price" name="donation_price" value="{$checkoutDonation['price']}">
                            <input type="hidden" value={$checkoutDonation['id_donation_info']} name="id_donation_info" class="id-donation-info">
                          </div>
                        {/if}
                      </div>
                      <div class="col-sm-12">
                        <i><p class="text-danger price-error hide"></p></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-2 donation-btn">
                    <input type="hidden" name="add-donation-to-cart">
                    <button type="submit" class="btn btn-primary btn-sm donation-btn-text submitDonationForm">{l s='DONATE' mod='wkcharitydonation'}</button>
                  </div>
                </form>
                {if ($checkoutDonation['price_type']) == 2}
                  <div class="donation-note">
                  <span class="text-danger">{l s='Note' mod='wkcharitydonation'}: </span>{l s='Minimum amount for this donation is' mod='wkcharitydonation'} {$checkoutDonation['displayPrice']}
                  </div>
                {/if}
              {/foreach}
            </div>
          </div>
        {/block}

{block name='continue_shopping' append}
  <a class="label" href="{$urls.pages.index}">
    <i class="material-icons">chevron_left</i>{l s='Continue shopping' d='Shop.Theme.Actions'}
  </a>
{/block}

{block name='cart_actions'}
  <div class="checkout text-sm-center card-block">
    <button type="button" class="btn btn-primary disabled" disabled>{l s='Checkout' d='Shop.Theme.Actions'}</button>
  </div>
{/block}

{block name='continue_shopping'}{/block}
{block name='cart_voucher'}{/block}
{block name='display_reassurance'}{/block}
