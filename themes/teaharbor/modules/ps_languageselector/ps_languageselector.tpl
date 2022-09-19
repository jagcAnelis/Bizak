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
<div id="_desktop_language_selector" class="lang_and_сr">
  <div class="language-selector-wrapper">
    <span id="language-selector-label" class="
     lang_and_сr_label">{l s='Language:' d='Shop.Theme.Global'}</span>
    <div class="language-selector dropdown js-dropdown">
      <button data-toggle="dropdown" class="
            {if $an_width_on_mobile =='992'}
              hidden-md-down
            {else}
              hidden-sm-down
            {/if}
       btn-unstyle" aria-haspopup="true" aria-expanded="false" aria-label="{l s='Language dropdown' d='Shop.Theme.Global'}">
        <span class="expand-more">{$current_language.name_simple}</span>
        <i class="material-icons expand-more">keyboard_arrow_down</i>
      </button>
      <ul class="dropdown-menu 
            {if $an_width_on_mobile =='992'}
              hidden-md-down
            {else}
              hidden-sm-down
            {/if}
      " aria-labelledby="language-selector-label">
        {foreach from=$languages item=language}
          <li {if $language.id_lang == $current_language.id_lang} class="current" {/if}>
            <a href="{url entity='language' id=$language.id_lang}" class="dropdown-item">{$language.name_simple}</a>
          </li>
        {/foreach}
      </ul>
      <select class="link 
        {if $an_width_on_mobile =='992'}}
            hidden-lg-up
          {else}
            hidden-md-up
          {/if}
      " aria-labelledby="language-selector-label">
        {foreach from=$languages item=language}
          <option value="{url entity='language' id=$language.id_lang}"{if $language.id_lang == $current_language.id_lang} selected="selected"{/if}>{$language.name_simple}</option>
        {/foreach}
      </select>
    </div>
     <div class="mobile_item_wrapper
        {if $an_width_on_mobile =='992'}
          hidden-lg-up
        {else}
          hidden-md-up
        {/if}
        ">
          <span>{$current_language.name_simple}</span>
          <span class="mobile-toggler">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink"
            width="4px" height="7px">
            <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
              d="M3.930,3.339 L0.728,0.070 C0.683,0.023 0.630,-0.000 0.570,-0.000 C0.511,-0.000 0.458,0.023 0.412,0.070 L0.069,0.421 C0.023,0.468 -0.000,0.521 -0.000,0.582 C-0.000,0.643 0.023,0.697 0.069,0.743 L2.769,3.500 L0.069,6.256 C0.023,6.303 -0.000,6.357 -0.000,6.418 C-0.000,6.479 0.023,6.532 0.069,6.579 L0.412,6.930 C0.458,6.977 0.511,7.000 0.570,7.000 C0.630,7.000 0.683,6.976 0.728,6.930 L3.930,3.661 C3.976,3.615 3.999,3.561 3.999,3.500 C3.999,3.439 3.976,3.385 3.930,3.339 Z"/>
            </svg>
          </span>
        </div>
        <div class="adropdown-mobile">
            {foreach from=$languages item=language}
             <a href="{url entity='language' id=$language.id_lang}" class="">{$language.name_simple}</a>
          {/foreach}
        </div>
  </div>
</div>
