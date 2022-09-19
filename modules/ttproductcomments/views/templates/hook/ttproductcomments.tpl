{*
*  @author    Templatetrip
*  @copyright 2015-2017 Templatetrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
<div class="tab-pane " id="product-comment">
    <div id="product_comments_block_tab">
        {if $comments}
            {if (!$too_early AND ($logged OR $allow_guests))}
                <p class="align_center">
                    <a id="new_comment_tab_btn" class="open-comment-form" href="javascript:void(0);">
                        <span class="reviewSpan">{l s='Escribir una evaluación' mod='ttproductcomments'}</span>
                    </a>
                </p>
            {/if}
            {foreach from=$comments item=comment}
                {if $comment.content}
                    <div class="comment" itemprop="review" itemscope itemtype="https://schema.org/Review">
                        <div class="row">
                            <div class="comment_author">
                                
                                   
                               
                                <div class="comment_author_infos">
                                    <strong itemprop="author" class="authorReview">{$comment.customer_name|escape:'html':'UTF-8'}</strong>
                                    <meta itemprop="datePublished" content="{$comment.date_add|escape:'html':'UTF-8'|substr:0:10}" />
                                    <em class="dateReview">{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</em>
                                    <div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                        {section name="i" start=0 loop=5 step=1}
                                            {if $comment.grade le $smarty.section.i.index}
                                                <div class="star"></div>
                                            {else}
                                                <div class="star star_on"></div>
                                            {/if}
                                        {/section}
                                        <meta itemprop="worstRating" content = "0" />
                                        <meta itemprop="ratingValue" content = "{$comment.grade|escape:'html':'UTF-8'}" />
                                        <meta itemprop="bestRating" content = "5" />
                                    </div>
                                </div>
                            </div> <!-- .comment_author -->
                            <div class="comment_details">
                                <span itemprop="name" class="title_block">
                                    <span class="titleReview">{$comment.title}</span>
                                </span>
                                <p class="commentReview" itemprop="reviewBody">{$comment.content}</p>
                                <ul>
                                    {if $comment.total_advice > 0}
                                        <li class="comment_helpful">
                                            {l s='%1$d out of %2$d people found this review useful.' sprintf=[$comment.total_useful,$comment.total_advice] mod='ttproductcomments'}
                                        </li>
                                    {/if}
                                    {if $logged}
                                        {if !$comment.customer_advice && $commentUsefull}
                                            <li>
                                                <div class="comment_helpful">
                                                    {l s='Was this comment useful to you?' mod='ttproductcomments'}
                                                    <button class="usefulness_btn btn btn-default usefull" data-is-usefull="1" data-id-product-comment="{$comment.id_product_comment}">
                                                        <span>{l s='Yes' mod='ttproductcomments'}</span>
                                                    </button>
                                                    <button class="usefulness_btn btn btn-default notusefull" data-is-usefull="0" data-id-product-comment="{$comment.id_product_comment}">
                                                        <span>{l s='No' mod='ttproductcomments'}</span>
                                                    </button>
                                                </div>
                                            </li>
                                        {/if}
                                        {if !$comment.customer_report && $commentReport}
                                            <li>
                                                <span class="report_btn" data-id-product-comment="{$comment.id_product_comment}">
                                                    {l s='Report abuse' mod='ttproductcomments'}
                                                </span>
                                            </li>
                                        {/if}
                                    {/if}
                                </ul>
                            </div><!-- .comment_details -->
                        </div>
                    </div> <!-- .comment -->
                {/if}
            {/foreach}
        {else}
            {if (!$too_early AND ($logged OR $allow_guests))}
                <p class="align_center">
                <p class="noReviewP">Se la primera persona en comentar.</p>
                    <a id="new_comment_tab_btn" class="open-comment-form" href="javascript:void(0);">
                        <span class="reviewSpan">{l s='Escribir una evaluación' mod='ttproductcomments'}</span>
                    </a>
                </p>
            {else}
                <p class="align_center">{l s='No customer reviews for the moment.' mod='ttproductcomments'}</p>
            {/if}
        {/if}
    </div> <!-- #product_comments_block_tab -->
</div>
