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
<div id="_desktop_user_info">
  <div class="user-info">
      {if $logged}
        <a
        class="account dropdown-item
        {if $an_width_on_mobile =='992'}
          hidden-lg-up
        {else}
          hidden-md-up
        {/if}
        "
        href="{$my_account_url}"
        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
        > 
          <span>{$customerName|truncate:20:'...'}</span>
        </a>
        <div class="signin dropdown js-dropdown
        {if $an_width_on_mobile =='992'}
          hidden-md-down
        {else}
          hidden-sm-down
        {/if}
        ">
          <button data-toggle="dropdown" class="hidden-sm-down btn-unstyle" aria-haspopup="true" aria-expanded="false" aria-label="{l s='Logout dropdown' d='Shop.Theme.Global'}">
            <span class="expand-more">{$customerName|truncate:20:'...'}</span>
            <i class="material-icons expand-more">keyboard_arrow_down</i>
          </button>
        <ul class="dropdown-menu  
          " aria-labelledby="signin-label">
          <li>
              <a
                class="logout dropdown-item"
                href="{$my_account_url}"
                rel="nofollow"
              >
                {l s='My profile' d='Shop.Theme.Actions'}
              </a>
            </li>
          <li>
            <a
              class="logout dropdown-item"
              href="{$logout_url}"
              rel="nofollow"
            >
              {l s='Sign out' d='Shop.Theme.Actions'}
            </a>
          </li>
        </ul>
      </div>
      {else}
        <a
          href="{$my_account_url}"
          title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
          rel="nofollow"
          class="user_info_icon"
        >
        {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header1'
        or Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header2' 
        or Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header3'
        or Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header4'
        or Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header5'))}
            <svg class="svg_user_info_icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve"><g><path d="M50,50.3c9.9,0,17.9-8,17.9-17.9s-8-17.9-17.9-17.9s-17.9,8-17.9,17.9S40.1,50.3,50,50.3z M50,20.5   c6.6,0,11.9,5.3,11.9,11.9S56.6,44.3,50,44.3S38.1,39,38.1,32.4S43.4,20.5,50,20.5z"/><path d="M50,53.8c-16.9,0-30.7,13.8-30.7,30.7v1h6v-1c0-13.6,11.1-24.7,24.7-24.7c13.6,0,24.7,11.1,24.7,24.7v1h6v-1   C80.7,67.6,66.9,53.8,50,53.8z"/></g></svg>
         {/if}

          <span class="account-login">{l s='Sign in' d='Shop.Theme.Actions'}</span>
        </a>
      {/if}
  </div>
</div>
