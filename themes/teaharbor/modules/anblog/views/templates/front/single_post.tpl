{*
* 2018 Anvanto
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
*  @author Anvanto (anvantoco@gmail.com)
*  @copyright  2018 anvanto.com

*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="page.tpl"}

	{block name="left_column"}
		{if $config->get('show_in_post')}
		<div class="row">
		<div id="left-column" class="col-xs-12 col-sm-4 col-md-3">
            <div class="anblog_left_mobile-cover"></div>
            <div class="anblog_left_mobile-modal">
                <div id="anblog_left_wrapper">
                        <div class="mobile-menu-header">
                        <div class="anblog_left_mobile-btn-close">
                            <svg
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="16px" height="16px">
                            <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                            d="M16.002,0.726 L15.274,-0.002 L8.000,7.273 L0.725,-0.002 L-0.002,0.726 L7.273,8.000 L-0.002,15.274 L0.725,16.002 L8.000,8.727 L15.274,16.002 L16.002,15.274 L8.727,8.000 L16.002,0.726 Z"/>
                            </svg>
                        </div>
                    </div>
                    {Module::getInstanceByName('anblog')->hookDisplayLeftColumn(array()) nofilter}
                </div>
            </div>
		</div>
		{/if}
	{/block}

	{block name="content_wrapper"}
		{if !$config->get('show_in_post')}
		<div class="row">
		{/if}
		<div id="content-wrapper" class="left-column right-column {if $config->get('show_in_post')}col-sm-12 col-md-9{else}col-sm-12 col-md-12{/if}">
		    {if $config->get('show_in_blog')}
                <div class="hidden-md-up">
                    <button id="anblog_left_toggler" class="btn btn-secondary">
                        <svg
                        xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="16px" height="4px">
                        <image  x="0px" y="0px" width="16px" height="4px"  xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAECAMAAACwak/eAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAS1BMVEUAAAAmIyQmIyQmIyQmIyQmJCUmIyQmJCQmIyQmJCQmIyQmJCQmIyQlIyQmJCUmIyQmJCUmJCQmJCUlIyQmIyQmJCQmJCUlIyT///8VIQx0AAAAFHRSTlMAX/PXIT7oPu+SwcGSktc+6NfzX4D2ZO4AAAABYktHRBibaYUeAAAAB3RJTUUH4wsSETMJQZd5WgAAAD9JREFUCNcVy8ERgCAMAMFLwIiIggrYf6fG784soiHCYrZCDCqk3jfIY+5QxpscZnHoM8Pxg+jppVqrXq77+QA/HgImmGTStAAAAABJRU5ErkJggg==" />
                        </svg>
                        {l s='Show sidebar' d='Shop.Theme.Global'}
                    </button>
                </div>
                {/if}

		<section id="main">
			{if isset($error)}
					<div id="blogpage">
						<div class="blog-detail">
							<div class="alert alert-warning">{l s='Sorry, no posts has been posted in the blog yet, but it will be done soon' d='Shop.Theme.Global'}</div>
						</div>
					</div>
				{else}
				<div id="blogpage">
					<article class="blog-detail">
						{if $is_active}
							<h1 class="blog-title">{$blog->meta_title|escape:'html':'UTF-8'}</h1>
							<div class="blog-meta">
								{if isset($blog->hits)&&$config->get('item_show_hit','1')}
								<span class="blog-hit">
									<i class="material-icons">visibility</i><span>{$blog->hits|intval} {l s='Views' d='Shop.Theme.Global'}</span>
									
								</span>
								{/if}
								{if $config->get('item_show_created','1')}
								<span class="blog-created">
									<svg 
									xmlns="http://www.w3.org/2000/svg"
									xmlns:xlink="http://www.w3.org/1999/xlink"
									width="14px" height="14px">
									<path fill-rule="evenodd"  fill="rgb(198, 198, 198)"
									d="M7.123,0.500 C8.917,0.500 10.541,1.230 11.721,2.403 C12.895,3.583 13.625,5.206 13.625,7.000 C13.625,8.794 12.895,10.417 11.721,11.597 C10.541,12.771 8.917,13.500 7.123,13.500 C5.328,13.500 3.704,12.771 2.530,11.597 C1.350,10.417 0.625,8.794 0.625,7.000 C0.625,5.206 1.350,3.583 2.529,2.403 C3.704,1.230 5.328,0.500 7.123,0.500 L7.123,0.500 ZM10.437,6.645 C10.631,6.645 10.786,6.805 10.786,7.000 C10.786,7.195 10.631,7.355 10.437,7.355 L7.133,7.355 L7.123,7.355 C6.993,7.355 6.878,7.280 6.818,7.175 L6.813,7.170 L6.813,7.170 L6.808,7.160 L6.808,7.160 L6.808,7.155 L6.808,7.155 L6.803,7.145 L6.803,7.145 L6.798,7.135 L6.798,7.135 L6.798,7.130 L6.798,7.130 L6.793,7.119 L6.793,7.119 L6.788,7.115 L6.788,7.115 L6.788,7.104 L6.788,7.104 L6.783,7.095 L6.783,7.095 L6.783,7.089 L6.783,7.089 L6.777,7.079 L6.777,7.079 L6.777,7.069 L6.777,7.069 L6.777,7.064 L6.777,7.059 L6.772,7.054 L6.772,7.054 L6.772,7.044 L6.772,7.044 L6.772,7.034 L6.772,7.034 L6.772,7.025 L6.772,7.025 L6.772,7.020 L6.772,7.020 L6.772,7.010 L6.772,7.010 L6.772,7.000 L6.772,7.000 L6.772,2.768 C6.772,2.573 6.927,2.419 7.122,2.419 C7.317,2.419 7.477,2.573 7.477,2.768 L7.477,6.645 L10.437,6.645 ZM11.221,2.903 C10.171,1.854 8.722,1.210 7.123,1.210 C5.523,1.210 4.074,1.854 3.024,2.903 C1.980,3.952 1.330,5.401 1.330,7.000 C1.330,8.599 1.980,10.047 3.024,11.097 C4.074,12.146 5.523,12.791 7.123,12.791 C8.722,12.791 10.171,12.146 11.221,11.097 C12.270,10.048 12.920,8.599 12.920,7.000 C12.920,5.401 12.271,3.952 11.221,2.903 L11.221,2.903 Z"/>
									</svg>
									<time class="date" datetime="{strtotime($blog->date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
										{assign var='blog_day' value=strtotime($blog->date_add)|date_format:"%e"}{l s=$blog_day d='Shop.Theme.Global'}/{assign var='blog_month' value=strtotime($blog->date_add)|date_format:"%m"}{l s=$blog_month d='Shop.Theme.Global'}/{assign var='blog_year' value=strtotime($blog->date_add)|date_format:"%Y"}{l s=$blog_year d='Shop.Theme.Global'}
									</time>
								</span>
								{/if}

								{if isset($blog_count_comment)&&$config->get('item_show_counter','1')}
								<span class="blog-ctncomment">
									<i class="material-icons">comment</i><span>{$blog_count_comment|intval} {l s='comments' d='Shop.Theme.Global'}</span>
									
								</span>
								{/if}

								{if $config->get('item_show_author','1')}
								<span class="blog-author">
									<i class="material-icons">person</i> <span>{l s='Posted By' d='Shop.Theme.Global'}: </span>
									<a href="{$blog->author_link|escape:'html':'UTF-8'}" title="{$blog->author|escape:'html':'UTF-8'}">{$blog->author|escape:'html':'UTF-8'}</a>
								</span>
								{/if}

								{if $config->get('item_show_category','1')}
								<span class="blog-cat"> 
									<i class="material-icons">list</i> <span>{l s='In' d='Shop.Theme.Global'}: </span>
									<a href="{$blog->category_link|escape:'html':'UTF-8'}" title="{$blog->category_title|escape:'html':'UTF-8'}">{$blog->category_title|escape:'html':'UTF-8'}</a>
								</span>
								{/if}

								
							
							</div>

							{if $blog->preview_url && $config->get('item_show_image','1')}
								<div class="blog-image">
									<img src="{$blog->preview_url|escape:'html':'UTF-8'}" title="{$blog->meta_title|escape:'html':'UTF-8'}" class="img-fluid" />
								</div>
							{/if}

							<div class="blog-description">
								{if $config->get('item_show_description',1)}
									{$blog->content nofilter}{* HTML form , no escape necessary *}
								{/if}
								
							</div>
							
							{if $tags}
							<div class="blog-tags">
							
								{foreach from=$tags item=tag name=tag}
									<a href="{$tag.link|escape:'html':'UTF-8'}" title="{$tag.tag|escape:'html':'UTF-8'}">{$tag.tag|escape:'html':'UTF-8'}</a>
								{/foreach}
								
							</div>
							{/if}
							
                        {if $blog->products}
                        {*
                            <div class="products-grid col-xs-12 col-sm-12 col-md-12">
                                {foreach from=$blog->products item=product name=products}
                                    <div class="product-item col-xs-12 col-sm-4 col-md-3">
                                        <div class="product-thumbnail"><a href="{$product.link}" title="{$product.name}"><img class="img-fluid" src="{if isset($product.cover.bySize.menu_default)}{$product.cover.bySize.menu_default.url}{else} {$product.cover.bySize.home_default.url}{/if}" alt="{$product.cover.legend}" /></a></div>
                                        <div class="product-information-dropdown">
                                            <h5 class="product-name"><a href="{$product.link}" title="{$product.name}">{$product.name}</a></h5>
                                            {if $product.show_price}
                                                <div class="product-price-and-shipping"><span class="price product-price">{$product.price}</span>
                                                    {if $product.has_discount}<span class="regular-price">{$product.regular_price}</span>{/if}</div>
                                            {/if}
                                        </div>
                                    </div>
                                {/foreach}
                            </div>
                            *}
                            <section id="products" class="featured-products featured-products-box clearfix">
                                <div class="products 	products-mobile-row">
                                {foreach from=$blog->products item=product name=products}
                                    {include file="catalog/_partials/miniatures/product.tpl" product=$product}
                                {/foreach}
                                </div>
                            </section>
                        {/if}

							<div class="social-share">
								{include file="module:anblog/views/templates/front/_social.tpl" social_code=$config->get('social_code','')}
							</div>
							

							
							
						{if $config->get('item_show_listcomment','1') == 1}
							<div class="blog-comment-block clearfix">
								
								{if $config->get('item_comment_engine','local')=='facebook'}
									{include file="module:anblog/views/templates/front/_facebook_comment.tpl"}
								{elseif $config->get('item_comment_engine','local')=='diquis'}
									{include file="module:anblog/views/templates/front/_diquis_comment.tpl"}
								{elseif ($config->get('google_captcha_site_key') && $config->get('google_captcha_site_key')) || !$config->get('google_captcha_status')}
									{include file="module:anblog/views/templates/front/_local_comment.tpl"}
								{/if}
							{elseif $config->get('item_show_listcomment','1') == 0 && $config->get('item_show_formcomment','1') == 1 && (($config->get('google_captcha_site_key') && $config->get('google_captcha_site_key')) || !$config->get('google_captcha_status') ) }
								<div class="blog-comment-block clearfix">
									{include file="module:anblog/views/templates/front/_local_comment.tpl"}
								</div>
							{/if}
						{else}
							<div class="alert alert-warning">{l s='Sorry, This blog is not avariable. May be this was unpublished or deleted.' d='Shop.Theme.Global'}</div>
						{/if}

					</article>
				</div>
				
				<div class="hidden-xl-down hidden-xl-up datetime-translate">
					{l s='Sunday' d='Shop.Theme.Global'}
					{l s='Monday' d='Shop.Theme.Global'}
					{l s='Tuesday' d='Shop.Theme.Global'}
					{l s='Wednesday' d='Shop.Theme.Global'}
					{l s='Thursday' d='Shop.Theme.Global'}
					{l s='Friday' d='Shop.Theme.Global'}
					{l s='Saturday' d='Shop.Theme.Global'}
					
					{l s='January' d='Shop.Theme.Global'}
					{l s='February' d='Shop.Theme.Global'}
					{l s='March' d='Shop.Theme.Global'}
					{l s='April' d='Shop.Theme.Global'}
					{l s='May' d='Shop.Theme.Global'}
					{l s='June' d='Shop.Theme.Global'}
					{l s='July' d='Shop.Theme.Global'}
					{l s='August' d='Shop.Theme.Global'}
					{l s='September' d='Shop.Theme.Global'}
					{l s='October' d='Shop.Theme.Global'}
					{l s='November' d='Shop.Theme.Global'}
					{l s='December' d='Shop.Theme.Global'}
				</div>
			{/if}
		</section>
	{/block}