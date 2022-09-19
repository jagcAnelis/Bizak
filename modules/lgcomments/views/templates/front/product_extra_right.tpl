{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

<div class="comment_anchor_content" style="{if $prodtopmargin != 0}padding-top:{$prodtopmargin|intval}px;{/if}{if $prodbotmargin != 0}padding-bottom:{$prodbotmargin|intval}px;{/if}">
    {if isset($numberofreviews) && $numberofreviews > 0}
        <img src="{$lgcomments_content_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$averagecomments|round|escape:'htmlall':'UTF-8'}stars.png" alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
        {if $numberofreviews > 1}
            <span class="comment_anchor">{l s='Read the' mod='lgcomments'} {$numberofreviews|escape:'htmlall':'UTF-8'} {l s='reviews' mod='lgcomments'}</span>
        {else}
            <span class="comment_anchor">{l s='Read the review' mod='lgcomments'}</span>
        {/if}
    {else}
        {if $displayzerostar}
            <img src="{$lgcomments_content_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$averagecomments|round|escape:'htmlall':'UTF-8'}stars.png" alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
            <span class="comment_anchor">{l s='No review at the moment' mod='lgcomments'}</span>
        {/if}
    {/if}

    {if isset($numberofreviews) && $numberofreviews > 0}
        <div id="googleRichSnippets">
            {if $ratingscale == 5}
                {l s='Average rating:' mod='lgcomments'}
                <span>{$averagecomments/2|round:1|escape:'quotes':'UTF-8'}</span>/5
            {elseif $ratingscale == 10}
                {l s='Average rating:' mod='lgcomments'}
                <span>{$averagecomments|escape:'quotes':'UTF-8'}</span>/10
            {elseif $ratingscale == 20}
                {l s='Average rating:' mod='lgcomments'}
                <span>{($averagecomments*2)|round:1|escape:'quotes':'UTF-8'}</span>/20
            {else}
                {l s='Average rating:' mod='lgcomments'}
                <span>{$averagecomments|escape:'quotes':'UTF-8'}</span>/10
            {/if}

            {l s='Number of reviews:' mod='lgcomments'} <span>{$numberofreviews|escape:'htmlall':'UTF-8'}</span>
        </div>
    {/if}
</div>
