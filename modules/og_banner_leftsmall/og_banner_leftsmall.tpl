{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<a class="custom-banner left-small small" href="{$banner_link}">
    {if isset($banner_img)}
        <img class="desktop" src="{$banner_img}" alt="{$banner_desc}" title="{$banner_desc}">
        {if isset($banner_img_mobile)}
            <img class="mobile" src="{$banner_img_mobile}" alt="{$banner_desc}" title="{$banner_desc}">
        {/if}
        <div class="image-text">
            {$banner_text}
        </div>
        {if $banner_button == 1}
            <button class="image-link" href="{$banner_link}">
                VER MÁS
            </button>
        {/if}
    {/if}
</a>



