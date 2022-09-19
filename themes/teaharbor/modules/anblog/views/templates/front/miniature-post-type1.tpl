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


<article class="blog-item blog-item-type-1">
	<div class="blog-image-container">
		{if $config->get('listing_show_title','1')}
			<h4 class="title">
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}">{$blog.title|escape:'html':'UTF-8'}</a>
			</h4>
		{/if}
		<div class="blog-meta">
				{if $config->get('listing_show_hit','1')}	
				<span class="blog-hit">
					<i class="material-icons">visibility</i> <span> {$blog.hits|intval} {l s='Views' d='Shop.Theme.Global'}</span>
				   
				</span>
			{/if}
			
			{if $config->get('listing_show_created','1')}
				<span class="blog-created">
					<svg 
					xmlns="http://www.w3.org/2000/svg"
					xmlns:xlink="http://www.w3.org/1999/xlink"
					width="14px" height="14px">
					<path fill-rule="evenodd"  fill="rgb(198, 198, 198)"
					d="M7.123,0.500 C8.917,0.500 10.541,1.230 11.721,2.403 C12.895,3.583 13.625,5.206 13.625,7.000 C13.625,8.794 12.895,10.417 11.721,11.597 C10.541,12.771 8.917,13.500 7.123,13.500 C5.328,13.500 3.704,12.771 2.530,11.597 C1.350,10.417 0.625,8.794 0.625,7.000 C0.625,5.206 1.350,3.583 2.529,2.403 C3.704,1.230 5.328,0.500 7.123,0.500 L7.123,0.500 ZM10.437,6.645 C10.631,6.645 10.786,6.805 10.786,7.000 C10.786,7.195 10.631,7.355 10.437,7.355 L7.133,7.355 L7.123,7.355 C6.993,7.355 6.878,7.280 6.818,7.175 L6.813,7.170 L6.813,7.170 L6.808,7.160 L6.808,7.160 L6.808,7.155 L6.808,7.155 L6.803,7.145 L6.803,7.145 L6.798,7.135 L6.798,7.135 L6.798,7.130 L6.798,7.130 L6.793,7.119 L6.793,7.119 L6.788,7.115 L6.788,7.115 L6.788,7.104 L6.788,7.104 L6.783,7.095 L6.783,7.095 L6.783,7.089 L6.783,7.089 L6.777,7.079 L6.777,7.079 L6.777,7.069 L6.777,7.069 L6.777,7.064 L6.777,7.059 L6.772,7.054 L6.772,7.054 L6.772,7.044 L6.772,7.044 L6.772,7.034 L6.772,7.034 L6.772,7.025 L6.772,7.025 L6.772,7.020 L6.772,7.020 L6.772,7.010 L6.772,7.010 L6.772,7.000 L6.772,7.000 L6.772,2.768 C6.772,2.573 6.927,2.419 7.122,2.419 C7.317,2.419 7.477,2.573 7.477,2.768 L7.477,6.645 L10.437,6.645 ZM11.221,2.903 C10.171,1.854 8.722,1.210 7.123,1.210 C5.523,1.210 4.074,1.854 3.024,2.903 C1.980,3.952 1.330,5.401 1.330,7.000 C1.330,8.599 1.980,10.047 3.024,11.097 C4.074,12.146 5.523,12.791 7.123,12.791 C8.722,12.791 10.171,12.146 11.221,11.097 C12.270,10.048 12.920,8.599 12.920,7.000 C12.920,5.401 12.271,3.952 11.221,2.903 L11.221,2.903 Z"/>
					</svg>
					<time class="date" datetime="{strtotime($blog.date_add)|date_format:"%Y"|escape:'html':'UTF-8'}">
						{assign var='blog_day' value=strtotime($blog.date_add)|date_format:"%e"}{l s=$blog_day d='Shop.Theme.Global'}/{assign var='blog_month' value=strtotime($blog.date_add)|date_format:"%m"}{l s=$blog_month d='Shop.Theme.Global'}/{assign var='blog_year' value=strtotime($blog.date_add)|date_format:"%Y"}{l s=$blog_year d='Shop.Theme.Global'}
					</time>
				</span>
			{/if}
			
			{if isset($blog.comment_count)&&$config->get('listing_show_counter','1')}
				<span class="blog-ctncomment">
					<i class="material-icons">comment</i> <span> {$blog.comment_count|intval} {l s='comments' d='Shop.Theme.Global'}</span>
				   
				</span>
			{/if}

			{if $config->get('listing_show_author','1')&&!empty($blog.author)}
				<span class="blog-author">
					<i class="material-icons">person</i> <span>{l s='Posted By' d='Shop.Theme.Global'}:</span>
					<a href="{$blog.author_link|escape:'html':'UTF-8'}" title="{$blog.author|escape:'html':'UTF-8'}">{$blog.author|escape:'html':'UTF-8'}</a> 
				</span>
			{/if}
			
			{if $config->get('listing_show_category','1')}
				<span class="blog-cat"> 
					<i class="material-icons">list</i> <span>{l s='In' d='Shop.Theme.Global'}:</span>
					<a href="{$blog.category_link|escape:'html':'UTF-8'}" title="{$blog.category_title|escape:'html':'UTF-8'}">{$blog.category_title|escape:'html':'UTF-8'}</a>
				</span>
			{/if}
		</div>
		{if $blog.preview_url && $config->get('listing_show_image',1)}
			<div class="blog-image">
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}"><img src="{$blog.preview_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" alt="" class="img-fluid" /></a>
			</div>
		{elseif $blog.thumb_url && $config->get('listing_show_image',1)}
			<div class="blog-image">
				<img src="{$blog.thumb_url|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" alt="" class="img-fluid" />
			</div>
		{/if}
	</div>
	<div class="blog-info">
		{if $config->get('listing_show_description','1')}
			<div class="blog-shortinfo">
				{$blog.description|strip_tags:'UTF-8'|truncate:160:'...' nofilter}{* HTML form , no escape necessary *}
			</div>
		{/if}
		{if $config->get('listing_show_readmore',1)}
			<p>
				<a href="{$blog.link|escape:'html':'UTF-8'}" title="{$blog.title|escape:'html':'UTF-8'}" class="more btn btn-primary">{l s='Read more' d='Shop.Theme.Global'}</a>
			</p>
		{/if}
	</div>

</article>
