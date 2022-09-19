{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

{capture name=path}{l s='Customer reviews' mod='lgcomments'}{/capture}
<div class="lgcomments_store_reviews">
    <h1 class="page-heading">{l s='Customer reviews about' mod='lgcomments'}  {$shop_name|escape:'htmlall':'UTF-8'}</h1>
    {if $storefilter and $numerocomentarios}
    <div class="lgcomment_summary">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <table>
                    <tr>
                        <th colspan="4">
                            <div class="title">{l s='Summary' mod='lgcomments'}</div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            {*<img class="logo" src="{$logo_url|escape:'htmlall':'UTF-8'}" alt="{$shop_name|escape:'html':'UTF-8'}" width="100">*}
                        </td>
                        <td>
                            <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/positive.png" alt="positive" width="25">
                        </td>
                        <td>
                            <p class="small"><b>{(($stars.fivestars+$stars.fourstars)/$numerocomentarios*100)|round:1|escape:'htmlall':'UTF-8'}%</b></p>
                        </td>
                        <td>
                            <p class="small">({($stars.fivestars+$stars.fourstars)|escape:'htmlall':'UTF-8'} {l s='reviews' mod='lgcomments'})</p>
                        </td>
                    </tr>
                    <tr>
                        {if $ratingscale == 5}
                            <td>
                                <p class="small">{l s='Average rating:' mod='lgcomments'} <b>{$mediacomentarios2/2|escape:'htmlall':'UTF-8'}/5</b></p>
                            </td>
                        {elseif $ratingscale == 10}
                            <td>
                                <p class="small">{l s='Average rating:' mod='lgcomments'} <b>{$mediacomentarios2|escape:'htmlall':'UTF-8'}/10</b></p>
                            </td>
                        {elseif $ratingscale == 20}
                            <td>
                                <p class="small">{l s='Average rating:' mod='lgcomments'} <b>{($mediacomentarios2*2)|round:0|escape:'htmlall':'UTF-8'}/20</b></p>
                            </td>
                        {else}
                            <td>
                                <p class="small">{l s='Average rating:' mod='lgcomments'} <b>{$mediacomentarios2|escape:'htmlall':'UTF-8'}/10</b></p>
                            </td>
                        {/if}
                        <td>
                            <p class="small"><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/neutral.png" alt="neutral" width="25"></p>
                        </td>
                        <td>
                            <p class="small"><b>{(($stars.threestars+$stars.twostars)/$numerocomentarios*100)|round:1|escape:'htmlall':'UTF-8'}%</b></p>
                        </td>
                        <td>
                            <p class="small">({($stars.threestars+$stars.twostars)|escape:'htmlall':'UTF-8'} {l s='reviews' mod='lgcomments'})</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="small">{l s='Number of reviews:' mod='lgcomments'} {$numerocomentarios|escape:'htmlall':'UTF-8'}</p>
                        </td>
                        <td>
                            <p class="small"><img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/negative.png" alt="negative" width="25"></p>
                        </td>
                        <td>
                            <p class="small"><b>{(($stars.onestar+$stars.zerostar)/$numerocomentarios*100)|round:1|escape:'htmlall':'UTF-8'}%</b></p>
                        </td>
                        <td>
                            <p class="small">({($stars.onestar+$stars.zerostar)|escape:'htmlall':'UTF-8'} {l s='reviews' mod='lgcomments'})</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-12 col-sm-6">
                <table>
                    <tr>
                        <th colspan="2">
                            <div class="title">
                                {l s='Filter reviews' mod='lgcomments'}
                                <span class="small">
                                    <a href="{$star_link|escape:'htmlall':'UTF-8'}">
                                        (<i class="icon-refresh"></i>
                                        {l s='Reset' mod='lgcomments'}&nbsp;)
                                    </a>
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=five" {if $stars.fivestars == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/10stars.png" width="100">
                                    ({$stars.fivestars|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=four" {if $stars.fourstars == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/8stars.png" width="100">
                                    ({$stars.fourstars|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=three" {if $stars.threestars == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/6stars.png" width="100">
                                    ({$stars.threestars|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=two" {if $stars.twostars == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/4stars.png" width="100">
                                    ({$stars.twostars|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=one" {if $stars.onestar == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/2stars.png" width="100">
                                    ({$stars.onestar|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                        <td>
                            <p class="small">
                                <a href="{$star_link|escape:'htmlall':'UTF-8'}?star=zero" {if $stars.zerostar == 0}style="pointer-events: none;"{/if}>
                                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}/lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/0stars.png" width="100">
                                    ({$stars.zerostar|escape:'htmlall':'UTF-8'})
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    {/if}
    {if $storeform}
        <div class="content-button">
            <p class="lgcomment_button">
                <a id="send_review">
                    <i class="icon-pencil"></i> {l s='Click here to leave a review' mod='lgcomments'}</a>
            </p>
        </div>
    {/if}
    <div class="lgcomment_reviews">
        <div class="row">
            {include './store_reviews_comments.tpl' reviews=$reviews}
        </div>
    </div>
    <div class="clear clearfix row">
        <div class="content_sortPagiBar clearfix col-xs-12">
            <div class="bottom-pagination-content clearfix">
            {include file="$tpl_dir./pagination.tpl"}
            </div>
        </div>
    </div>
    {include file="./form_review_popup.tpl"}
</div>
