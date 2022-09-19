{*
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

{assign var="tt_cnt" value="1"}
{assign var="tt_total" value="0"}
{foreach from=$products item="product"}
	{$tt_total = $tt_total+1}
{/foreach}

<section class="ttbestseller-products clearfix col-sm-4">
  <h3 class="tab-title">{l s='Best Sellers' mod='ttbestsellers'}</h3>
	  <div class="ttbestseller-content products">
		{foreach from=$products item="product"}
			{if $tt_total > 8}
						<!-- Start TemplateTrip 2 product slide code -->
						{if $tt_cnt % 2 != 0}
						<ul>
							<li class="bestsellerli">
								<ul>
								<li class="item">
						{/if}
					{/if}
						<!-- End TemplateTrip 2 product slide code -->
		
						{include file="catalog/_partials/miniatures/product.tpl" product=$product}
		
						<!-- Start TemplateTrip 2 product slide code -->
					{if $tt_total > 8}
						{if $tt_cnt % 2 == 0}
								</li>
								</ul>
							</li>
							</ul>
						{/if}
						{/if}
		
						{$tt_cnt = $tt_cnt+1}
						<!-- End TemplateTrip 2 product slide code -->
				{/foreach}
				{if $tt_total > 8}
					{if $tt_cnt % 2 == 0}
							</li>
							</ul>
						</li>
						</ul>
					{/if}
				{/if}
	  </div>
	   <!-- Left and right controls -->
   <div class="allproduct"><a href="{$allBestSellers}">{l s='All best sellers' mod='ttbestsellers'}</a></div>
</section>
