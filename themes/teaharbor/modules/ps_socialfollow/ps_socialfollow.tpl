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

{block name='block_social'}
  <div class="block-social">
    <ul>
      {foreach from=$social_links item='social_link'}
        <li class="{$social_link.class}">
           <a href="{$social_link.url}" target="_blank">
               {if $social_link.class=="linkedin"}
                 <span class="anicon-linkedin">
              <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12"><path class="cls-1" d="M12.52,7.29a2.6,2.6,0,0,0-2.35,1.29h0V7.49H7.74v8h2.49v-4c0-1,.2-2,1.49-2S13,10.67,13,11.6v3.9H15.5V11.1C15.5,9,15,7.29,12.52,7.29ZM3.7,15.5H6.19v-8H3.7Zm1.24-12A1.45,1.45,0,1,0,6.38,4.94,1.45,1.45,0,0,0,4.94,3.5Z" transform="translate(-3.5 -3.5)"></path></svg>
             </span>
           {else}
             <span class="anicon-{$social_link.class}"></span>
           {/if}
            <span class="block-social-label">{$social_link.label}</span>
          </a>
        </li>
      {/foreach}
    </ul>
  </div>
{/block}
