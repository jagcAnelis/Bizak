{**
 * 2007-2018 PrestaShop
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
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
 
<div itemtype="#" itemscope="" class="sdsarticleCat clearfix">
	<div id="smartblogpost-{$post.id_post}">
			{assign var="options" value=null}
			{$options.id_post = $post.id_post} 
			{$options.slug = $post.link_rewrite}
			<div class="row">
		<div class="articleContent col-sm-5 col-xs-5">
			<a itemprop="url" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" title="{$post.meta_title}" class="imageFeaturedLink">
				{assign var="activeimgincat" value='0'}
				{$activeimgincat = $smartshownoimg} 
				{if ($post.post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
					<img itemprop="image" alt="{$post.meta_title}" src="{$link->getMediaLink($smart_module_dir)}/smartblog/views/img/{$post.post_img}-single-default.jpg" class="imageFeatured">
				{/if}
			</a>
		</div>
		<div class="blog_desc col-sm-7 col-xs-7">
		<div class="sdsarticleHeader">
			<p class='sdstitle_block'><a title="{$post.meta_title}" href='{smartblog::GetSmartBlogLink('smartblog_post',$options)}'>{$post.meta_title}</a></p>
		</div>
		{assign var="options" value=null}
		{$options.id_post = $post.id_post}
		{$options.slug = $post.link_rewrite}
		{assign var="catlink" value=null}
		{$catlink.id_category = $post.id_category}
		{$catlink.slug = $post.cat_link_rewrite}
		<span class="blogdetail">
			<span class="ttpost">{l s='Posted by' mod='smartblog'} </span>
			{if $smartshowauthor ==1}&nbsp;
				<span class="author" itemprop="author"><i class="material-icons user">&#xE7FF;</i>&nbsp;&nbsp;{if $smartshowauthorstyle != 0}{$post.firstname} {$post.lastname}{else}{$post.lastname} {$post.firstname}{/if}</span>&nbsp;&nbsp;
			{/if}
			<span class="articleSection" itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catlink)}"><i class="material-icons tags">&#xE54E;</i>&nbsp;&nbsp;{if $title_category != ''}{$title_category}{else}{$post.cat_name}{/if}</a></span>&nbsp;&nbsp;
			<span class="blogcomment"><a title="{$post.totalcomment} Comments" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}#articleComments"><i class="material-icons comments">&#xE0B9;</i>&nbsp;&nbsp;{$post.totalcomment} {l s=' Comments' mod='smartblog'}</a></span>
			{if $smartshowviewed ==1}&nbsp;
				<span class="viewed"><i class="material-icons eye">&#xE417;</i>&nbsp;&nbsp;{l s=' views' mod='smartblog'} ({$post.viewed})</span>
			{/if}
		</span>
		<div class="sdsarticle-des">
			<span itemprop="description" class="clearfix">
				<div id="lipsum">
					{$post.short_description|escape:'htmlall':'UTF-8'|truncate:300:'...'}
				</div>
			</span>
		</div>
		<div class="sdsreadMore">
			{assign var="options" value=null}
			{$options.id_post = $post.id_post}  
			{$options.slug = $post.link_rewrite}  
			<span class="more">
				<a title="{$post.meta_title}" href="{smartblog::GetSmartBlogLink('smartblog_post',$options)}" class="r_more">{l s='Read more' mod='smartblog'}</a>
			</span>
		</div>
		</div>
		</div>
	</div>
</div>