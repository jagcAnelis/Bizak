{**
* 2007-2018 PrestaShop
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
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

 <section class="categories container">
	<div class="products">
			{if $categories}
	 <ul id="ttcategorylogo-carousel" class="product_list">
		{foreach from=$categories.children item=brand}
	<li>
	<div class="brand-image">
		<div>
			<a href="{$link->getCategoryLink($brand.id, $brand.link)}" title="{$brand.name}">
				<img src="{$link->getCatImageLink($brand.link_rewrite, $brand.image)|escape:'html':'UTF-8'}" alt="{$brand.name|escape:'html':'UTF-8'}"/>
			</a>
		</div>
	</li>
	{/foreach}
	</ul>
	{else}
	<p>{l s='No brand' mod='ttbrandlogo'}</p>
	{/if}
	</div>
</section>