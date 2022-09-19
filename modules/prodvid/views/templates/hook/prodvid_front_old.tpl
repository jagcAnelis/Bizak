{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{foreach from=$prodvid_data item=d}
	<div class="prodvid-block-old page-product-box" data-prodvid-id="{$d.prodvid_position|escape:'htmlall'}">
		{if $d.prodvid_title|escape:'htmlall' != ''}<h3 class="page-product-heading title">{$d.prodvid_title|escape:'htmlall'}</h3>{/if}
		<!-- full description -->
		<div class="rte embed_code">{$d.prodvid_embed_code nofilter} {* HTML must not be escaped *}</div>
	</div>
{/foreach}