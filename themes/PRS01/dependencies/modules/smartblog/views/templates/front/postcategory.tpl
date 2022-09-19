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
 
{extends file=$layout_category}
{block name='head_seo'}
    <title> {if $title_category != ''}{$title_category}{else}{l s='All Blog News' mod='smartblog'}{/if}</title>
{/block}

{block name='content'}
<section id="main">
    {if $postcategory == ''}
        {if $title_category != ''}
            <p class="error">{l s='No Post in Category' mod='smartblog'}</p>
        {else}
            <p class="error">{l s='No Post in Blog' mod='smartblog'}</p>
        {/if}
    {else}
        {if $smartdisablecatimg == '1'}
            {assign var="activeimgincat" value='0'}
            {$activeimgincat = $smartshownoimg} 
            {if $title_category != ''}        
                {foreach from=$categoryinfo item=category}
                    <div id="sdsblogCategory">
                        {if ($cat_image != "no" && $activeimgincat == 0) || $activeimgincat == 1}
                            <img alt="{$category.meta_title}" src="{$link->getMediaLink($smart_module_dir)}/smartblog/views/img/category/{$cat_image}-single-default.jpg" class="imageFeatured">
                        {/if}
                        <div class="catDesc">{$category.description|escape:'htmlall':'UTF-8'}</div>
                    </div>
                {/foreach}  
            {/if}
        {/if}
        <div id="smartblogcat" class="block">
            {foreach from=$postcategory item=post}
                {include file='module:smartblog/views/templates/front/category_loop.tpl' postcategory=$postcategory}
            {/foreach}
        </div>
        {if !empty($pagenums)}
            <div class="blog_pagination">
                <div class="pagination">
                    <div class="results pagination-left">{l s='Showing' mod='smartblog'} {if $limit_start!=0}{$limit_start}{else}1{/if} {l s='to' mod='smartblog'} {if $limit_start+$limit >= $total}{$total}{else}{$limit_start+$limit}{/if} {l s='of' mod='smartblog'} {$total} ({$c} {l s='Pages' mod='smartblog'})</div>
                    <ul class="pagination_bottom page-list text-sm-center">
                        {for $k=0 to $pagenums}
                            {if $title_category != ''}
                                {assign var="options" value=null}
                                {$options.page = $k+1}
                                {$options.id_category = $id_category}
                                {$options.slug = $cat_link_rewrite}
                            {else}
                                {assign var="options" value=null}
                                {$options.page = $k+1}
                            {/if}
                            {if ($k+1) == $c}
                                <li class="current"><a class="disabled">{$k+1}</a></li>
                            {else}
                                {if $title_category != ''}
                                    <li><a class="" href="{smartblog::GetSmartBlogLink('smartblog_category_pagination',$options)}">{$k+1}</a></li>
                                {else}
                                    <li><a class="" href="{smartblog::GetSmartBlogLink('smartblog_list_pagination',$options)}">{$k+1}</a></li>
                                {/if}
                            {/if}
                        {/for}
                    </ul>
                </div>
            </div>
        {/if}
    {/if}
    {if isset($smartcustomcss)}
        <style>
            {$smartcustomcss}
        </style>
    {/if}
</section>
{/block}