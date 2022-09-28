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
{$image_limit = Module::getInstanceByName('an_theme')->getParam('segmentedviewsettinds_imagelimit')}
{assign currency_code Context::getContext()->currency->iso_code}
{block name='product_miniature_item'}   {* $product.images|@debug_print_var nofilter *}
  <article class="product-miniature js-product-miniature
     {if Module::isEnabled('an_theme') and  Module::getInstanceByName('an_theme')->getParam('product_productMobileRow')}
        product-mobile-row
    {/if}
	{if Module::isEnabled('an_theme')}
	js-img-view-type
	type-{Module::getInstanceByName('an_theme')->getParam('product_productImageChange')}
	{/if}
	{if isset($smarty.cookies.an_collection_view) and isset($page) and $page.page_name == 'category'}
	col-lg-{$smarty.cookies.an_collection_view}
	{/if}
" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}" itemscope itemtype="http://schema.org/Product">
    <div class="thumbnail-container ">

           {***** movido por margen inferior en productos relacionados, ventas cruzadas, misma categoria, etc... ******}
           {block name='product_flags'}
		      <a href="{$product.url}">
		        <ul class="product-flags">
		          {foreach from=$product.flags item=flag}
		            <li class="product-flag {$flag.type}">{$flag.label}</li>
		          {/foreach}
		          {if $product.has_discount}
		            {if $product.discount_type === 'percentage'}
		              <li class="product-flag discount-percentage">{$product.discount_percentage}</li>
		             {else}
		                <li class="product-flag discount-percentage">
		                    {l s='- %amount%' d='Shop.Theme.Catalog' sprintf=['%amount%' => $product.discount_to_display]}
		                </li>
		              {/if}
		          {/if}
		        </ul>
		      </a>
      		{/block}
			{***** 0922 Anelis ******}
			<div class="thumbnail-container-image" style="{if isset($image_types)}{foreach from=$image_types item=type}{if $type.name == 'home_default'}min-height: {$type.height}px;{/if}{/foreach}{/if} min-height: {if $product.cover.bySize.home_default.height}{if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;{else}250px{/if}">
		{if isset($product->ean13) AND $product->ean13}
        <meta itemprop="gtin13" content="{l s='EAN Code:'}{$product->ean13}">
        {/if}
        {if isset($product->isbn) AND $product->isbn}
        <meta itemprop="gtin13" content="{l s='ISBN Code:'}{$product->isbn}">
        {/if}
        {if isset($product->upc) AND $product->upc}
        <meta itemprop="gtin13" content="{l s='UPC Code:'}{$product->upc}">
        {/if}
        {if isset($product.cover.large.url) AND $product.cover.large.url}
        <meta itemprop="image" content="{$product.cover.large.url}">
        {/if}
        {if isset($product.id_manufacturer) AND $product.id_manufacturer}
        <meta itemprop="brand" content="{Manufacturer::getnamebyid($product.id_manufacturer)}">
        {/if}
        {if isset($product.description_short) AND $product.description_short}
        <meta itemprop="description" content="{$product.description_short|strip_tags:'UTF-8'}">
        {/if}
		<meta itemprop="sku" content="{$product.id_product}">
		
		{block name='product_thumbnail'}
		 {if count($product.images) < 1 }
            <a href="{$product.url}" class="thumbnail product-thumbnail">
                <img
                    src = "{Context::getContext()->shop->getBaseURL(true)}img/p/{Context::getContext()->language->iso_code}.jpg"
                    alt = "{$product.cover.legend}"
                    {if isset($image_types)}
                    {foreach from=$image_types item=type}
                        {if $type.name == 'home_default'}
                        data-width="{$type.width}"
                        data-height="{$type.height}"
                        {/if}
                    {/foreach}
                    {/if}

                    data-full-size-image-url = "{$product.cover.large.url}"
                >
            </a>
        {else}
        {if Module::isEnabled('an_theme')}
	        {if Module::getInstanceByName('an_theme')->getParam('product_productImageChange') == 'standart'}
	        <a href="{$product.url}" class="thumbnail product-thumbnail"
  			 style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">
  				 <img
  					 src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  						{$urls.base_url}/modules/an_theme/views/img/loading.svg
  						{else}
							{if isset($page) and $page.page_name == 'category'}
								{if isset($smarty.get.view)}
									{if $smarty.get.view == 'col2'}
										{$product.cover.bySize.catalog_large.url}
									{/if}
									{if $smarty.get.view == 'col3'}
										{$product.cover.bySize.catalog_medium.url}
									{/if}
									{if $smarty.get.view == 'col4'}
										{$product.cover.bySize.catalog_small.url}
									{/if}
									{if $smarty.get.view == 'row'}
										{$product.cover.bySize.catalog_medium.url}
									{/if}
								{else}
									{if isset($smarty.cookies.an_collection_view)}
											{if $smarty.cookies.an_collection_view == 3}
													{$product.cover.bySize.catalog_small.url}
											{elseif $smarty.cookies.an_collection_view == 4}
													{$product.cover.bySize.catalog_medium.url}
											{elseif $smarty.cookies.an_collection_view == 6}
													{$product.cover.bySize.catalog_large.url}
											{else}
													{$product.cover.bySize.catalog_medium.url}
											{/if}
									{else}
												{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
													{$product.cover.bySize.catalog_small.url}
											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
													{$product.cover.bySize.catalog_medium.url}
											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
													{$product.cover.bySize.catalog_large.url}
											{else}
													{$product.cover.bySize.catalog_medium.url}
											{/if}
									{/if}
								{/if}
  						 {else}
  								 {$product.cover.bySize.home_default.url}
  						 {/if}
  						{/if}"
  						{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  						data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  						{/if}
  					 data-catalog-small="{$product.cover.bySize.catalog_small.url}"
  					 data-catalog-medium="{$product.cover.bySize.catalog_medium.url}"
  					 data-catalog-large="{$product.cover.bySize.catalog_large.url}"
  					 alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
  					 data-full-size-image-url="{$product.cover.large.url}"
  					 class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if}"
  					 data-width="{$product.cover.bySize.home_default.width}"
  					 data-height="{$product.cover.bySize.home_default.height}"
  					 content="{$product.cover.bySize.home_default.url}"
  					 data-src="{if isset($page) and $page.page_name == 'category'}
  								 {if isset($smarty.cookies.an_collection_view)}
  										 {if $smarty.cookies.an_collection_view == 3}
  												 {$product.cover.bySize.catalog_small.url}
  										 {elseif $smarty.cookies.an_collection_view == 4}
  												 {$product.cover.bySize.catalog_medium.url}
  										 {elseif $smarty.cookies.an_collection_view == 6}
  												 {$product.cover.bySize.catalog_large.url}
  										 {else}
  												 {$product.cover.bySize.catalog_medium.url}
  										 {/if}
  								 {else}
  										 {if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  												 {$product.cover.bySize.catalog_small.url}
  										 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  												 {$product.cover.bySize.catalog_medium.url}
  										 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  												 {$product.cover.bySize.catalog_large.url}
  										 {else}
  												 {$product.cover.bySize.catalog_medium.url}
  										 {/if}
  								 {/if}
  						 {else}
  								 {$product.cover.bySize.home_default.url}
  						 {/if}"
  				 >
  		 </a>
  {elseif Module::getInstanceByName('an_theme')->getParam('product_productImageChange') == 'hover'}
  <a href="{$product.url}" class="thumbnail product-thumbnail"
  					style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">
  					{assign "imgcount" "1"}
                     {foreach from=$product.images item=image name=foo}
                     {if ($image.id_image == $product.cover.id_image) or $imgcount}
                       {if ($image.id_image == $product.cover.id_image)}{$imgcount=$imgcount+1}{/if}
                       {$imgcount=$imgcount-1}
  							<img    width="{$image.bySize.home_default.width}"
                                    height="{$image.bySize.home_default.height}"
  									class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if} img_hover_change {if $image.id_image == $product.cover.id_image} cover {/if} {if $smarty.foreach.foo.total == 1 } only_one {/if}"
  									data-full-size-image-url="{$image.bySize.large_default.url}"
  									src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  									{$urls.base_url}/modules/an_theme/views/img/loading.svg
  									{else}
									  {if isset($page) and $page.page_name == 'category'}
										{if isset($smarty.get.view)}
											{if $smarty.get.view == 'col2'}
												{$product.cover.bySize.catalog_large.url}
											{/if}
											{if $smarty.get.view == 'col3'}
												{$product.cover.bySize.catalog_medium.url}
											{/if}
											{if $smarty.get.view == 'col4'}
												{$product.cover.bySize.catalog_small.url}
											{/if}
											{if $smarty.get.view == 'row'}
												{$product.cover.bySize.catalog_medium.url}
											{/if}
										{else}
  											{if isset($smarty.cookies.an_collection_view)}
  													{if $smarty.cookies.an_collection_view == 3}
  															{$image.bySize.catalog_small.url}
  													{elseif $smarty.cookies.an_collection_view == 4}
  															{$image.bySize.catalog_medium.url}
  													{elseif $smarty.cookies.an_collection_view == 6}
  															{$image.bySize.catalog_large.url}
  													{else}
  															{$image.bySize.catalog_medium.url}
  													{/if}
  											{else}
  													{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  															{$image.bySize.catalog_small.url}
  													{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  															{$image.bySize.catalog_medium.url}
  													{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  															{$image.bySize.catalog_large.url}
  													{else}
  															{$image.bySize.catalog_medium.url}
  													{/if}
											  {/if}
										{/if}
  									{else}
  											{$image.bySize.home_default.url}
  									{/if}
  							{/if}"
  							{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  							data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  							{/if}
  							data-catalog-small="{$image.bySize.catalog_small.url}"
  							data-catalog-medium="{$image.bySize.catalog_medium.url}"
  							data-catalog-large="{$image.bySize.catalog_large.url}"
  							alt="{$image.legend}"
  							data-width="{$image.bySize.home_default.width}"
  							data-height="{$image.bySize.home_default.height}"
  							content="{$image.bySize.home_default.url}"
  							data-src="{if isset($page) and $page.page_name == 'category'}
  									{if isset($smarty.cookies.an_collection_view)}
  											{if $smarty.cookies.an_collection_view == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif $smarty.cookies.an_collection_view == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif $smarty.cookies.an_collection_view == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{else}
  											{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{/if}
  							{else}
  									{$image.bySize.home_default.url}
  						{/if}"
  						>
  				    {/if}
  					{/foreach}
  					</a>
  {elseif Module::getInstanceByName('an_theme')->getParam('product_productImageChange') == 'hover-slider'}
  <a href="{$product.url}" class="thumbnail product-thumbnail hover_slider"
  											style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">

  	<ul {if $product.images|@count == 1} class="only_one_item"{/if}>
  		<li class="cover-item">
  			<div class="hover-slider-img">
  				<img
  															 src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  																{$urls.base_url}/modules/an_theme/views/img/loading.svg
  																{else}
  																	 {if isset($page) and $page.page_name == 'category'}
  																		 {if isset($smarty.cookies.an_collection_view)}
  																				 {if $smarty.cookies.an_collection_view == 3}
  																						 {$product.cover.bySize.catalog_small.url}
  																				 {elseif $smarty.cookies.an_collection_view == 4}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {elseif $smarty.cookies.an_collection_view == 6}
  																						 {$product.cover.bySize.catalog_large.url}
  																				 {else}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {/if}
  																		 {else}
  																					 {if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  																						 {$product.cover.bySize.catalog_small.url}
  																				 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  																						 {$product.cover.bySize.catalog_large.url}
  																				 {else}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {/if}
  																		 {/if}
  																 {else}
  																		 {$product.cover.bySize.home_default.url}
  																 {/if}
  																{/if}"
  																{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  																data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  																{/if}
  															 data-catalog-small="{$product.cover.bySize.catalog_small.url}"
  															 data-catalog-medium="{$product.cover.bySize.catalog_medium.url}"
  															 data-catalog-large="{$product.cover.bySize.catalog_large.url}"
  															 alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
  															 data-full-size-image-url="{$product.cover.large.url}"
  															 class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if}  hover-slider-image"
  															 data-width="{$product.cover.bySize.home_default.width}"
  															 data-height="{$product.cover.bySize.home_default.height}"
  															 content="{$product.cover.bySize.home_default.url}"
  															 data-src="{if isset($page) and $page.page_name == 'category'}
  																		 {if isset($smarty.cookies.an_collection_view)}
  																				 {if $smarty.cookies.an_collection_view == 3}
  																						 {$product.cover.bySize.catalog_small.url}
  																				 {elseif $smarty.cookies.an_collection_view == 4}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {elseif $smarty.cookies.an_collection_view == 6}
  																						 {$product.cover.bySize.catalog_large.url}
  																				 {else}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {/if}
  																		 {else}
  																				 {if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  																						 {$product.cover.bySize.catalog_small.url}
  																				 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  																						 {$product.cover.bySize.catalog_large.url}
  																				 {else}
  																						 {$product.cover.bySize.catalog_medium.url}
  																				 {/if}
  																		 {/if}
  																 {else}
  																		 {$product.cover.bySize.home_default.url}
  																 {/if}"
  														 >
  			</div>
  		</li>
  							{$image_limit_other = $image_limit}
  							{foreach from=$product.images item=image name=hoverslider}
  									{if $smarty.foreach.hoverslider.iteration == $image_limit and $image.id_image == $product.cover.id_image }
  										{$image_limit_other = $image_limit-1}
  									{elseif $smarty.foreach.hoverslider.iteration > $image_limit and $image.id_image == $product.cover.id_image }
  											{$image_limit_other = $image_limit-1}
  									{/if}
  							{/foreach}
  		{foreach from=$product.images item=image name=hoverslider}
  									{if $image.id_image != $product.cover.id_image and $smarty.foreach.hoverslider.iteration <= $image_limit_other}
  				<li class="no-cover-item">
  					<div class="hover-slider-img">
  						{if Module::getInstanceByName('an_theme')->getParam('segmentedviewsettinds_textonlastimg') == 1}
  							{if $smarty.foreach.hoverslider.iteration == $image_limit_other and ($product.images|@count-$image_limit)>0}
  								<div class="more-images">
  									{$product.images|@count-$image_limit}
  									{if ($product.images|@count-$image_limit) == 1}
  										{l s='more image' d='Shop.Theme.Actions'}
  									{else}
  										{l s='more images' d='Shop.Theme.Actions'}
  									{/if}
  								</div>
  							{/if}
  						{/if}
  						<img
  																					class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if}  hover-slider-image"
  																					data-full-size-image-url="{$image.bySize.large_default.url}"
  																					src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  																					{$urls.base_url}/modules/an_theme/views/img/loading.svg
  																					{else}
  																					{if isset($page) and $page.page_name == 'category'}
  																							{if isset($smarty.cookies.an_collection_view)}
  																									{if $smarty.cookies.an_collection_view == 3}
  																											{$image.bySize.catalog_small.url}
  																									{elseif $smarty.cookies.an_collection_view == 4}
  																											{$image.bySize.catalog_medium.url}
  																									{elseif $smarty.cookies.an_collection_view == 6}
  																											{$image.bySize.catalog_large.url}
  																									{else}
  																											{$image.bySize.catalog_medium.url}
  																									{/if}
  																							{else}
  																									{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  																											{$image.bySize.catalog_small.url}
  																									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  																											{$image.bySize.catalog_medium.url}
  																									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  																											{$image.bySize.catalog_large.url}
  																									{else}
  																											{$image.bySize.catalog_medium.url}
  																									{/if}
  																							{/if}
  																					{else}
  																							{$image.bySize.home_default.url}
  																					{/if}
  																			{/if}"
  																			{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  																			data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  																			{/if}
  																			data-catalog-small="{$image.bySize.catalog_small.url}"
  																			data-catalog-medium="{$image.bySize.catalog_medium.url}"
  																			data-catalog-large="{$image.bySize.catalog_large.url}"
  																			alt="{$image.legend}"
  																			data-width="{$image.bySize.home_default.width}"
  																			data-height="{$image.bySize.home_default.height}"
  																			content="{$image.bySize.home_default.url}"
  																			data-src="{if isset($page) and $page.page_name == 'category'}
  																					{if isset($smarty.cookies.an_collection_view)}
  																							{if $smarty.cookies.an_collection_view == 3}
  																									{$image.bySize.catalog_small.url}
  																							{elseif $smarty.cookies.an_collection_view == 4}
  																									{$image.bySize.catalog_medium.url}
  																							{elseif $smarty.cookies.an_collection_view == 6}
  																									{$image.bySize.catalog_large.url}
  																							{else}
  																									{$image.bySize.catalog_medium.url}
  																							{/if}
  																					{else}
  																							{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  																									{$image.bySize.catalog_small.url}
  																							{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  																									{$image.bySize.catalog_medium.url}
  																							{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  																									{$image.bySize.catalog_large.url}
  																							{else}
  																									{$image.bySize.catalog_medium.url}
  																							{/if}
  																					{/if}
  																			{else}
  																					{$image.bySize.home_default.url}
  																		{/if}"
  																		>
  					</div>
  				</li>
  			{/if}
  		{/foreach}
  	</ul>
  </a>
  {else}
  <div class="slider_product-wrapper">
  					<div class="slider-product-item">
  					 <a href="{$product.url}" class="thumbnail product-thumbnail"
  					 style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">
  						<img
  						src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  									{$urls.base_url}/modules/an_theme/views/img/loading.svg
  							{else}
  									{if isset($page) and $page.page_name == 'category'}
  											{if isset($smarty.cookies.an_collection_view)}
  													{if $smarty.cookies.an_collection_view == 3}
  															{$product.cover.bySize.catalog_small.url}
  													{elseif $smarty.cookies.an_collection_view == 4}
  															{$product.cover.bySize.catalog_medium.url}
  													{elseif $smarty.cookies.an_collection_view == 6}
  															{$product.cover.bySize.catalog_large.url}
  													{else}
  															{$product.cover.bySize.catalog_medium.url}
  													{/if}
  											{else}
  													{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  															{$product.cover.bySize.catalog_small.url}
  													{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  															{$product.cover.bySize.catalog_medium.url}
  													{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  															{$product.cover.bySize.catalog_large.url}
  													{else}
  															{$product.cover.bySize.catalog_medium.url}
  													{/if}
  											{/if}
  									{else}
  											{$product.cover.bySize.home_default.url}
  									{/if}
  							{/if}"
  								{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  							data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  							{/if}
  							data-catalog-small="{$product.cover.bySize.catalog_small.url}"
  							data-catalog-medium="{$product.cover.bySize.catalog_medium.url}"
  							data-catalog-large="{$product.cover.bySize.catalog_large.url}"
  						 alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
  						 data-full-size-image-url="{$product.cover.large.url}"
  						 class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if} slider_product cover"
  						 data-width="{$product.cover.bySize.home_default.width}"
  						 data-height="{$product.cover.bySize.home_default.height}"
  						 content="{$product.cover.bySize.home_default.url}"
  						 data-src="{if isset($page) and $page.page_name == 'category'}
  							{if isset($smarty.cookies.an_collection_view)}
  									{if $smarty.cookies.an_collection_view == 3}
  											{$product.cover.bySize.catalog_small.url}
  									{elseif $smarty.cookies.an_collection_view == 4}
  											{$product.cover.bySize.catalog_medium.url}
  									{elseif $smarty.cookies.an_collection_view == 6}
  											{$product.cover.bySize.catalog_large.url}
  									{else}
  											{$product.cover.bySize.catalog_medium.url}
  									{/if}
  							{else}
  										{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  											{$product.cover.bySize.catalog_small.url}
  									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  											{$product.cover.bySize.catalog_medium.url}
  									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  											{$product.cover.bySize.catalog_large.url}
  									{else}
  											{$product.cover.bySize.catalog_medium.url}
  									{/if}
  							{/if}
  						{else}
  							{$product.cover.bySize.home_default.url}
  						{/if}"
  						>
  					</a>
  				 </div>
  					 {foreach from=$product.images item=image}
  						 {if $image.id_image != $product.cover.id_image}
  						 <div class="slider-product-item">
  						 <a href="{$product.url}" class="thumbnail product-thumbnail"
  						 style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">
  							 <img
  							 class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if} slider_product not_cover"
  								data-full-size-image-url="{$image.bySize.large_default.url}"
  								src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  									{$urls.base_url}/modules/an_theme/views/img/loading.svg
  									{else}
  											{if isset($page) and $page.page_name == 'category'}
  									{if isset($smarty.cookies.an_collection_view)}
  											{if $smarty.cookies.an_collection_view == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif $smarty.cookies.an_collection_view == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif $smarty.cookies.an_collection_view == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{else}
  												{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{/if}
  							{else}
  									{$image.bySize.home_default.url}
  							{/if}
  							{/if}"
  							{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  						data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  						{/if}
  							data-catalog-small="{$image.bySize.catalog_small.url}"
  							data-catalog-medium="{$image.bySize.catalog_medium.url}"
  							data-catalog-large="{$image.bySize.catalog_large.url}"
  							alt="{$image.legend}"
  							data-width="{$image.bySize.home_default.width}"
  							data-height="{$image.bySize.home_default.height}"
  							content="{$image.bySize.home_default.url}"
  							data-src="{if isset($page) and $page.page_name == 'category'}
  									{if isset($smarty.cookies.an_collection_view)}
  											{if $smarty.cookies.an_collection_view == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif $smarty.cookies.an_collection_view == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif $smarty.cookies.an_collection_view == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{else}
  											{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  													{$image.bySize.catalog_small.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  													{$image.bySize.catalog_medium.url}
  											{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  													{$image.bySize.catalog_large.url}
  											{else}
  													{$image.bySize.catalog_medium.url}
  											{/if}
  									{/if}
  							{else}
  									{$image.bySize.home_default.url}
  							{/if}"
  							 >
  							</a>
  						 </div>
  							{/if}
  						 {/foreach}
  				 </div>
  {/if}
  {else}
  <a href="{$product.url}" class="thumbnail product-thumbnail"
  style="height: {if isset($page) and $page.page_name == 'category'}{if isset($smarty.cookies.an_collection_view)|strip}{if $smarty.cookies.an_collection_view == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif $smarty.cookies.an_collection_view == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif $smarty.cookies.an_collection_view == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{else}{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}{$product.cover.bySize.catalog_small.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}{$product.cover.bySize.catalog_medium.height|strip}{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}{$product.cover.bySize.catalog_large.height|strip}{else}{$product.cover.bySize.catalog_medium.height|strip}{/if}{/if}{else}{$product.cover.bySize.home_default.height|strip}{/if}px;">
  		<img
  		src="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  				 {$urls.base_url}/modules/an_theme/views/img/loading.svg
  				 {else}
  						{if isset($page) and $page.page_name == 'category'}
  							{if isset($smarty.cookies.an_collection_view)}
  									{if $smarty.cookies.an_collection_view == 3}
  											{$product.cover.bySize.catalog_small.url}
  									{elseif $smarty.cookies.an_collection_view == 4}
  											{$product.cover.bySize.catalog_medium.url}
  									{elseif $smarty.cookies.an_collection_view == 6}
  											{$product.cover.bySize.catalog_large.url}
  									{else}
  											{$product.cover.bySize.catalog_medium.url}
  									{/if}
  							{else}
  										{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  											{$product.cover.bySize.catalog_small.url}
  									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  											{$product.cover.bySize.catalog_medium.url}
  									{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  											{$product.cover.bySize.catalog_large.url}
  									{else}
  											{$product.cover.bySize.catalog_medium.url}
  									{/if}
  							{/if}
  					{else}
  							{$product.cover.bySize.home_default.url}
  					{/if}
  				 {/if}"
  				 {if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')}
  				 data-lazy-gif="{$urls.base_url}/modules/an_theme/views/img/loading.svg"
  				 {/if}
  				data-catalog-small="{$product.cover.bySize.catalog_small.url}"
  				data-catalog-medium="{$product.cover.bySize.catalog_medium.url}"
  				data-catalog-large="{$product.cover.bySize.catalog_large.url}"
  			alt="{if !empty($product.cover.legend)}{$product.cover.legend}{else}{$product.name|truncate:30:'...'}{/if}"
  			data-full-size-image-url="{$product.cover.large.url}"
  			class="{if Module::getInstanceByName('an_theme')->getParam('product_lazyLoad')} b-lazy {/if} "
  			data-width="{$product.cover.bySize.home_default.width}"
  			data-height="{$product.cover.bySize.home_default.height}"
  			content="{$product.cover.bySize.home_default.url}"
  			data-src="{if isset($page) and $page.page_name == 'category'}
  					{if isset($smarty.cookies.an_collection_view)}
  							{if $smarty.cookies.an_collection_view == 3}
  									{$product.cover.bySize.catalog_small.url}
  							{elseif $smarty.cookies.an_collection_view == 4}
  									{$product.cover.bySize.catalog_medium.url}
  							{elseif $smarty.cookies.an_collection_view == 6}
  									{$product.cover.bySize.catalog_large.url}
  							{else}
  									{$product.cover.bySize.catalog_medium.url}
  							{/if}
  					{else}
  								{if Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 3}
  									{$product.cover.bySize.catalog_small.url}
  							{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 4}
  									{$product.cover.bySize.catalog_medium.url}
  							{elseif Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 6}
  									{$product.cover.bySize.catalog_large.url}
  							{else}
  									{$product.cover.bySize.catalog_medium.url}
  							{/if}
  					{/if}
  				{/if}"
  		>
  </a>
  		{/if}
  		{/if}
  		{/block}

			<div class="highlighted-informations{if !$product.main_variants} no-variants{/if} hidden-sm-down">
		  {if Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('product_showquickview') == 1}
		  	{block name='quick_view'}
			  <a class="quick-view" href="#" data-link-action="quickview">
				<i class="material-icons search">&#xE8B6;</i> {l s='' d='Shop.Theme.Actions'}
			  </a>
			{/block}
			
			{/if}

			{if !Module::isEnabled('an_productattributes')}
			{block name='product_variants'}
			  {if $product.main_variants}
				{include file='catalog/_partials/variant-links.tpl' variants=$product.main_variants}
			  {/if}
			{/block}
			{/if}
		  </div>
		</div>
		
      <div class="product-description">

    

        {block name='product_name'}
          <h3 class="h3 product-title" itemprop="name"><a href="{$product.url}">{$product.name|truncate:30:'...'}</a></h3>
        {/block}
		
		{**********  cambio de ubicacion anelis   ********}
		<div class="stars">
		    {block name='product_reviews'}
            {hook h='displayProductListReviews' product=$product}
        {/block}
        </div>
		{**********  cambio de ubicacion anelis   ********}


		{if Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('product_imageQuickLookBar') == 1}
		<div class="product-miniature-images-all">
		{foreach from=$product.images item=image}
		  {if $image.id_image != $product.cover.id_image}
		  <a href="{$product.url}" class="">
			<img
			  class="product-miniature-images-all-img"
			  src="{$image.bySize.slider_photo.url}"
			  alt="{$image.legend}"
			>
		   </a>
		   {/if}
		  {/foreach}
		</div>
		{/if}
		
        {if Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('product_shortdescription') == 1}
        {$max_length = Module::getInstanceByName('an_theme')->getParam('product_shortdescriptionlength')}
	        {block name='product_description_short'}
	          	<p class="an_short_description" id="an_short_description_{$product.id}">
	          		{$product.description_short|strip_tags:'UTF-8'|truncate:$max_length:'...'}
	        	</p>
	        {/block}
        {/if}
        {block name='product_price_and_shipping'}
          {if $product.show_price}
            <div class="product-price-and-shipping" itemprop="offers" itemscope itemtype="http://schema.org/Offer" priceValidUntil="">
							<meta itemprop="priceCurrency" content="{$currency.iso_code}">
							<meta itemprop="url" content="{$product.url}">
							<link itemprop="availability" href="http://schema.org/InStock">
              {if $product.has_discount}
                {hook h='displayProductPriceBlock' product=$product type="old_price"}
                <span class="sr-only">{l s='Regular price' d='Shop.Theme.Catalog'}</span>
                <span class="regular-price">{$product.regular_price}</span>
              {/if}
              {hook h='displayProductPriceBlock' product=$product type="before_price"}
              <span class="sr-only">{l s='Price' d='Shop.Theme.Catalog'}</span>
							<span class="price" itemprop="price" content="{$product.price_tax_exc}">
								<span class="money" {if isset($currency_code)}data-currency-{$currency_code|lower}="{$product.price}"{/if}>{$product.price}</span>
							</span>

              {hook h='displayProductPriceBlock' product=$product type='unit_price'}

              {hook h='displayProductPriceBlock' product=$product type='weight'}
                {if isset($product.product_attribute_minimal_quantity)}
					{$min_quantity = $product.product_attribute_minimal_quantity}
				{else}
					{$min_quantity = $product.minimal_quantity}
				{/if}
				{if Module::isEnabled('an_theme')}
		            {if Module::getInstanceByName('an_theme')->getParam('product_addtocart') == 'button' or Module::getInstanceByName('an_theme')->getParam('product_addtocart') == 'qtyandbutton' }
						<div class="atc_div">
		                {if Module::getInstanceByName('an_theme')->getParam('product_addtocart') == 'qtyandbutton'}
							<input name="qty" type="number" min="{$min_quantity}" max="{$product.quantity}" class="form-control atc_qty" value="{$min_quantity}"/>
		                {/if}
							<button class="add_to_cart btn btn-primary btn-sm {if $product.availability == 'unavailable'}disabled{/if}  " onclick="mypresta_productListCart.add({literal}$(this){/literal});">
								{l s='Add to cart' d='Shop.Theme.Actions'}
							</button>
						</div>
		            {/if}
		        {/if}
            </div>
          {/if}

        {/block}

       
      </div>

    </div>
  </article>
{/block}
