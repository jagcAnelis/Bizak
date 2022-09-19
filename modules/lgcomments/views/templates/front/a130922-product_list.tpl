{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

{if isset($number_of_reviews) && $number_of_reviews > 0}
    <span itemtype="http://schema.org/Product" itemscope>
        <meta itemprop="name" content="{$productname|escape:'quotes':'UTF-8'}">
        <meta itemprop="description" content="{$productdescription|strip_tags:false|escape:'quotes':'UTF-8'}">
        {if (isset($productsku) && $productsku)}<meta itemprop="sku" content="{$productsku|escape:'quotes':'UTF-8'}">{/if}
        {if (isset($productbrand) && $productbrand)}<meta itemprop="brand" content="{$productbrand|escape:'quotes':'UTF-8'}">{/if}

        <div class="stars-container" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <div style="{if $cattopmargin != 0}padding-top:{$cattopmargin|intval}px;{/if}{if $catbotmargin != 0}padding-bottom:{$catbotmargin|intval}px;{/if} display:table; margin: {$cattopmargin|escape:'htmlall':'UTF-8'}px auto {$catbotmargin|escape:'htmlall':'UTF-8'}px auto;">
                <a href="{$productlink|escape:'htmlall':'UTF-8'}#idTab798" class="comment_anchor">
                    <img src="{$path_lgcomments|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$averagestars|escape:'htmlall':'UTF-8'}stars.png"
                         alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
                    {if $number_of_reviews == 1}
                        <span style="width:100px; text-align:center;">{l s='1 review' mod='lgcomments'}</span>
                    {/if}
                    {if $number_of_reviews > 1}
                        <span style="width:100px; text-align:center;">{$number_of_reviews|escape:'htmlall':'UTF-8'} {l s='reviews' mod='lgcomments'}</span>
                    {/if}
                </a>
            </div>

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

            <meta itemprop="ratingCount" content="{$number_of_reviews|escape:'quotes':'UTF-8'}">
        </div>
    </span>
{/if}

{if $displayzerostar && !$number_of_reviews}
    <div class="stars-container">
        <div style="display:table; margin: {$cattopmargin|escape:'htmlall':'UTF-8'}px auto {$catbotmargin|escape:'htmlall':'UTF-8'}px auto;">
            <img src="{$path_lgcomments|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/0stars.png"
                 alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
            <span style="width:100px; text-align:center;">{l s='0 review' mod='lgcomments'}</span>
        </div>
    </div>
{/if}
