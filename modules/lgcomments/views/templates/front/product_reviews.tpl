{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

<section id="lgcomment" class="page-product-box">
    <h3 class="idTabHrefShort page-product-heading">{l s='Reviews' mod='lgcomments'}</h3>
    {* Todo: Esto está puesto para 5 estrallas pero para puntuaciones de 10 o 20 quizás debería variar *}
    {if $productfilter and $numlgcomments > $productfilternb}
        <div class="lgcomment_summary">
            <div class="commentfiltertitle"><span style="text-transform:uppercase;font-weight:bold;">{l s='Filter reviews' mod='lgcomments'}</span></div>
            <div class="commentfilter" data-filter="five-stars"><span {if $fivestars == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/10stars.png" width="80%"> ({$fivestars|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilter" data-filter="four-stars"><span {if $fourstars == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/8stars.png" width="80%">({$fourstars|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilter" data-filter="three-stars"><span {if $threestars == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/6stars.png" width="80%">({$threestars|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilter" data-filter="two-stars"><span {if $twostars == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/4stars.png" width="80%">({$twostars|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilter" data-filter="one-star"><span {if $onestar == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/2stars.png" width="80%">({$onestar|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilter" data-filter="zero-star"><span {if $zerostar == 0}style="pointer-events: none;"{/if}><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/0stars.png" width="80%">({$zerostar|escape:'htmlall':'UTF-8'})</span></div>
            <div class="commentfilterreset"><span><i class="icon-refresh"></i>  {l s='Reset' mod='lgcomments'}</span></div>
            <div style="clear:both;"></div>
        </div><br>
    {/if}

    {if $productform}
        <div class="content-button">
            <p class="lgcomment_button">
                <span id="send_review" data-close="{l s='close' mod='lgcomments'}">
                    <i class="icon-pencil"></i> {l s='Click here to leave a review' mod='lgcomments'}
                </span>
            </p>
        </div>
    {/if}

    {if isset($numberofreviews) && $numberofreviews > 0}
        <span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            {if $ratingscale == 5}
                <meta itemprop="ratingValue" content="{$averagecomments/2|escape:'quotes':'UTF-8'}">
                <meta itemprop="bestRating" content="5">
                <meta itemprop="worstRating" content="0">
            {elseif $ratingscale == 10}
                <meta itemprop="ratingValue" content="{$averagecomments|escape:'quotes':'UTF-8'}">
                <meta itemprop="bestRating" content="10">
                <meta itemprop="worstRating" content="0">
            {elseif $ratingscale == 20}
                <meta itemprop="ratingValue" content="{$averagecomments*2|escape:'quotes':'UTF-8'}">
                <meta itemprop="bestRating" content="20">
                <meta itemprop="worstRating" content="0">
            {else}
                <meta itemprop="ratingValue" content="{$averagecomments|escape:'quotes':'UTF-8'}">
                <meta itemprop="bestRating" content="10">
                <meta itemprop="worstRating" content="0">
            {/if}

            <meta itemprop="ratingCount" content="{$numberofreviews|escape:'quotes':'UTF-8'}">
        </span>
    {/if}

    {foreach from=$lgcomments item=lgcomment}
        <div class="productComment row" itemprop="review" itemscope itemtype="http://schema.org/Review" data-filter="{if $lgcomment.stars >= 10}five-stars{elseif $lgcomment.stars >= 8 and $lgcomment.stars < 10}four-stars{elseif $lgcomment.stars >= 6 and $lgcomment.stars < 8}three-stars{elseif $lgcomment.stars >= 4 and $lgcomment.stars < 6}two-stars{elseif $lgcomment.stars >= 2 and $lgcomment.stars < 4}one-star{elseif $lgcomment.stars >= 0 and $lgcomment.stars < 2}zero-star{/if}">
            <div class="col-lg-2 col-md-3 col-sm-12 info-block">
                <div class="row">
                    <div class="col-sm-12">
                        <img src="{$modules_dir|escape:'htmlall':'UTF-8'}views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$lgcomment.stars|escape:'htmlall':'UTF-8'}stars.png"
                             alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
                        <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="rating-hidden">
                            <meta itemprop="ratingValue" content="{$lgcomment.rating|escape:'htmlall':'UTF-8'}">
                            {if $ratingscale == 5}
                                <meta itemprop="bestRating" content="5">
                                <meta itemprop="worstRating" content="0">
                            {elseif $ratingscale == 10}
                                <meta itemprop="bestRating" content="10">
                                <meta itemprop="worstRating" content="0">
                            {elseif $ratingscale == 20}
                                <meta itemprop="bestRating" content="20">
                                <meta itemprop="worstRating" content="0">
                            {else}
                                <meta itemprop="bestRating" content="10">
                                <meta itemprop="worstRating" content="0">
                            {/if}
                        </span>
                    </div>
                    <div class="col-sm-12 nick" itemprop="author">
                        {$lgcomment.nick|escape:'quotes':'UTF-8'}
                    </div>
                    <div class="col-sm-12 date" itemprop="datePublished" content="{$lgcomment.date|date_format:"%Y-%m-%d"|escape:'quotes':'UTF-8'}">
                        {$lgcomment.date|date_format:"$dateformat"|escape:'quotes':'UTF-8'}
                    </div>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-12 content-block">
                <div class="row">
                    <div class="col-md-12 title" itemprop="name">
                        {stripslashes($lgcomment.title|escape:'quotes':'UTF-8')}
                    </div>
                    <div class="col-md-12 comment" itemprop="description">
                        {$lgcomment.comment nofilter}{* HTML CONTENT *}
                    </div>
                    {if $lgcomment.answer && $lgcomment.answer != '<p>0</p>'}
                        <div class="col-md-12 answer">
                            {l s='Answer:' mod='lgcomments'} {$lgcomment.answer nofilter}{* HTML CONTENT *}
                        </div>
                    {/if}
                </div>
            </div>
        </div>
    {/foreach}

    {if $defaultdisplay < $numlgcomments}
        <div id="more_less">
            <button class="button btn btn-default button button-small" id="displayMore">
                <span><i class="icon-plus-square"></i> {l s='Display more' mod='lgcomments'}</span>
            </button>
            <button class="button btn btn-default button button-small" id="displayLess">
                <span><i class="icon-minus-square"></i> {l s='Display less' mod='lgcomments'}</span>
            </button>
        </div>
    {/if}

    {include file="./form_review_popup.tpl"}
</section>
