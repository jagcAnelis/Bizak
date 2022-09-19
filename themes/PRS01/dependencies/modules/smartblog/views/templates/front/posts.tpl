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
 
{extends file=$layout_details}
{block name='head_seo'}
    <title>{$meta_title}</title>
{/block}

{block name='content'}
<section id="main">
    <div id="content" class="block">
        <div itemtype="#" itemscope="" id="sdsblogArticle" class="blog-post">
            <div itemprop="articleBody">
                    <div id="lipsum" class="articleContent">
                        {assign var="activeimgincat" value='0'}
                        {$activeimgincat = $smartshownoimg} 
                        {if ($post_img != "no" && $activeimgincat == 0) || $activeimgincat == 1}
                            <a id="post_images" href="{$link->getMediaLink($smart_module_dir)}/smartblog/views/img/{$post_img}-single-default.jpg">
                                <img src="{$link->getMediaLink($smart_module_dir)}/smartblog/views/img/{$post_img}-single-default.jpg" alt="{$meta_title}">
                            </a>
                        {/if}
                    </div>
                    <div class="page-item-title">
                        <h1>{$meta_title}</h1>
                    </div>
                    <div class="post-info">
                        {assign var="catOptions" value=null}
                        {$catOptions.id_category = $id_category}
                        {$catOptions.slug = $cat_link_rewrite}
                        <span>
                            {l s='Posted by ' mod='smartblog'} 
                            {if $smartshowauthor ==1}&nbsp;
                                <span class="author" itemprop="author"><i class="material-icons user">&#xE7FF;</i>&nbsp;&nbsp;{if $smartshowauthorstyle != 0}{$firstname} {$lastname}{else}{$lastname} {$firstname}{/if}</span>&nbsp;&nbsp;
                            {/if}
                            <span class="dateCreated" itemprop="dateCreated"><i class="material-icons calendar">&#xE916;</i>&nbsp;&nbsp;{$created|date_format}</span>&nbsp;&nbsp;
                        <span class="articleSection" itemprop="articleSection"><a href="{smartblog::GetSmartBlogLink('smartblog_category',$catOptions)}"><i class="material-icons tags">&#xE54E;</i>&nbsp;&nbsp;{$title_category}</a></span>&nbsp;&nbsp;
                        <span class="blogcomment"><i class="material-icons comments">&#xE0B9;</i>&nbsp;&nbsp;{if $countcomment != ''}{$countcomment}{else}{l s='0' mod='smartblog'}{/if}{l s=' Comments' mod='smartblog'}</span>
                        </span>
                        <a title="" style="display:none" itemprop="url" href="#"></a>
                    </div>
                <div class="sdsarticle-des">
                    {$content nofilter}
                </div>
                {if $tags != ''}
                    <div class="sdstags-update">
                        <span class="tags"><b>{l s='Tags:' mod='smartblog'} </b> 
                            {foreach from=$tags item=tag}
                                {assign var="options" value=null}
                                {$options.tag = $tag.name|urlencode}
                                <a title="tag" href="{smartblog::GetSmartBlogLink('smartblog_tag',$options)|escape:'html':'UTF-8'}">{$tag.name}</a>
                            {/foreach}
                        </span>
                    </div>
                {/if}
            </div>

            <div class="sdsarticleBottom">
                {$HOOK_SMART_BLOG_POST_FOOTER}
            </div>
        </div>

        {if $countcomment != ''}
            <div id="articleComments">
                <h3 class="commenttitle">{if $countcomment != ''}{$countcomment}{else}{l s='0' mod='smartblog'}{/if}{l s=' Comments' mod='smartblog'}<span></span></h3>
                <div id="comments">      
                    <ul class="commentList">
                        {$i=1}
                        {foreach from=$comments item=comment}
                            {include file='module:smartblog/views/templates/front/comment_loop.tpl' childcommnets=$comment}
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/if}
    </div>
    {if Configuration::get('smartenablecomment') == 1}
        {if $comment_status == 1}
            <div class="smartblogcomments" id="respond">
                <h4 class="comment-reply-title" id="reply-title">
                    {l s='Leave a Reply'  mod='smartblog'}
                    <small>
                        <a style="display: none;" href="/wp/sellya/sellya/this-is-a-post-with-preview-image/#respond" id="cancel-comment-reply-link" rel="nofollow">{l s='Cancel Reply'  mod='smartblog'}</a>
                    </small>
                </h4>
                <section id="commentInput" class="page-content card card-block">
                    <form action="" method="post" id="commentform">
                        <div class="form-group row">
                            <label class="col-md-5 form-control-label required">
                                {l s='Name:' mod='smartblog'}
                            </label>
                            <div class="col-md-7">
                                <input class="form-control inputName grey" name="name" value="" maxlength="255" required="" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-control-label required">
                                {l s='E-mail:' mod='smartblog'}
                                <span class="note">{l s='(Not Published)' mod='smartblog'}</span>
                            </label>
                            <div class="col-md-7">
                                <input class="form-control inputMail grey" name="mail" value="" maxlength="255" required="" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-control-label required">
                                {l s='Website:' mod='smartblog'}
                                <span class="note">{l s='(Site url with' mod='smartblog'}http://)</span>
                            </label>
                            <div class="col-md-7">
                                <input class="form-control grey" name="website" value="" maxlength="255" required="" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-5 form-control-label required">
                                {l s='Comment:'  mod='smartblog'}
                            </label>
                            <div class="col-md-7">
                                <textarea class="form-control inputContent grey" name="comment" rows="8" cols="50" required=""></textarea>
                            </div>
                        </div>
                        {if Configuration::get('smartcaptchaoption') == '1'}
                            <div class="form-group row">
                                <label class="col-md-5">
                                </label>
                                <div class="col-md-7">
                                    <img src="{$link->getMediaLink($smart_module_dir)}/smartblog/classes/CaptchaSecurityImages.php?width=100&height=40&characters=5">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-5 form-control-label required">
                                    {l s='Type Code' mod='smartblog'}
                                </label>
                                <div class="col-md-7">
                                    <input class="form-control smartblogcaptcha grey" name="smartblogcaptcha" value="" maxlength="255" required="" type="text">
                                </div>
                            </div>
                        {/if}
                        <input type='hidden' name='comment_post_ID' value='1478' id='comment_post_ID' />
                        <input type='hidden' name='id_post' value='{$id_post}' id='id_post' />
                        <input type='hidden' name='comment_parent' id='comment_parent' value='0' />
                        <div class="right">
                            <div class="submit">
                                <input type="submit" name="addComment" id="submitComment" class="bbutton btn btn-primary" value="Submit">
                            </div>
                        </div>
                    </form>
                </section>
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