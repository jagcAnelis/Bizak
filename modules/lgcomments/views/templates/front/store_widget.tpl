{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

{if !isset($smarty.cookies.reviewWidget)}
    {if is_object($link)}
        {assign var='reviews_link' value=$link->getModuleLink('lgcomments','reviews')|escape:'htmlall':'UTF-8'}
        <!-- lgcomments -->
        <div id="widget_block" class="{if $footer_mode}widget_footer {/if}{$display_side|escape:'htmlall':'UTF-8'}">
        {if $displaycross == 1}
            <div  class="close_widget_block" style="top:{$top7|escape:'htmlall':'UTF-8'}px;right:{$right7|escape:'htmlall':'UTF-8'}px;">
                <img src="{$path_lgcomments|escape:'html':'UTF-8'}/views/img/close.png" alt="close" onclick="closewidget();">
            </div>
        {/if}
            <div class="block_content" style="background:url({$path_lgcomments|escape:'htmlall':'UTF-8'}/views/img/bg/{$bgdesign1|escape:'htmlall':'UTF-8'}-{$bgdesign2|escape:'htmlall':'UTF-8'}.png) no-repeat center center;background-size:100%;width:{$bgwidth|escape:'htmlall':'UTF-8'}px;height:{$bgheight|escape:'htmlall':'UTF-8'}px;margin: 0 auto;padding:0px;">
                <div style="position:absolute;width:1px;height:1px;">
                    <div{if $rotate0 > 0} class="rotate"{/if} style="position:relative;
                            width:{$width0|escape:'htmlall':'UTF-8'}px;
                            top:{$top0|escape:'htmlall':'UTF-8'}px;
                            left:{$left0|escape:'htmlall':'UTF-8'}px;
                            color:#{$widgettextcolor|escape:'htmlall':'UTF-8'};
                            text-align:{$textalign0|escape:'htmlall':'UTF-8'};
                            font-family:{$fontfamily0|escape:'htmlall':'UTF-8'};
                            font-size:{$fontsize0|escape:'htmlall':'UTF-8'}px;
                            font-weight:{$fontweight0|escape:'htmlall':'UTF-8'};
                            line-height:{$lineheight0|escape:'htmlall':'UTF-8'}px;
                            text-transform:uppercase;">
                        <a href="{$reviews_link|escape:'htmlall':'UTF-8'}" style="color:#{$widgettextcolor|escape:'htmlall':'UTF-8'};">{l s='Customer Reviews' mod='lgcomments'}</a>
                    </div>
                </div>
                {if $bgdesign1 != 'vertical' && $bgdesign1 != 'horizontal'}
                    <div style="position:absolute;width:1px;height:1px;">
                        <div style="position:relative;
                                    width:{$width1|escape:'htmlall':'UTF-8'}px;
                                    top:{$top1|escape:'htmlall':'UTF-8'}px;
                                    left:{$left1|escape:'htmlall':'UTF-8'}px;
                                    color:{$color1|escape:'htmlall':'UTF-8'};
                                    text-align:{$textalign1|escape:'htmlall':'UTF-8'};
                                    font-family:{$fontfamily1|escape:'htmlall':'UTF-8'};
                                    font-size:{$fontsize1|escape:'htmlall':'UTF-8'}px;
                                    font-weight:{$fontweight1|escape:'htmlall':'UTF-8'};">
                            {$numericRating|escape:'htmlall':'UTF-8'}
                        </div>
                    </div>
                    <div style="position:absolute;width:1px;height:1px;">
                        <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/LocalBusiness">
                            <meta itemprop="image" content="{if isset($ps16) && $ps16}{$logo_url|escape:'htmlall':'UTF-8'}{else}{$base_url|escape:'quotes':'UTF-8'}{$shop.logo|escape:'htmlall':'UTF-8'}{/if}">
                            <meta itemprop="name" content="{Configuration::get('PS_SHOP_NAME')|escape:'quotes':'UTF-8'}">
                            <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                {if $address_street1}
                                    <meta itemprop="streetAddress" content=" {if $address_street1}{$address_street1|escape:'quotes':'UTF-8'}, {/if} {if $address_street2}{$address_street2|escape:'quotes':'UTF-8'}{/if}"/>
                                {/if}
                                {if $address_zip}
                                    <meta itemprop="postalCode" content="{$address_zip|escape:'quotes':'UTF-8'}"/>
                                {/if}
                                {if $address_city}
                                    <meta itemprop="addressLocality" content="{$address_city|escape:'quotes':'UTF-8'}"/>
                                {/if}
                                {if $address_state}
                                    <meta itemprop="addressRegion" content="{$address_state|escape:'quotes':'UTF-8'}"/>
                                {/if}
                                {if $address_country}
                                    <meta itemprop="addressCountry" content="{$address_country|escape:'quotes':'UTF-8'}"/>
                                {/if}
                            </span>
                            {if $address_phone}
                                <meta itemprop="telephone" content="{$address_phone|escape:'quotes':'UTF-8'}"/>
                            {/if}
                            {if $price_range}
                                <meta itemprop="priceRange" content="{$price_range|escape:'quotes':'UTF-8'}"/>
                            {/if}
                            <meta itemprop="url" content="{$lgcomments_shop_url|escape:'quotes':'UTF-8'}">

                            {if $numerocomentarios && $numerocomentarios > 0}
                                <span itemprop="aggregateRating" itemscope="itemscope" itemtype="http://schema.org/AggregateRating">
                                    {if $ratingscale == 5}
                                        <meta content="{$mediacomentarios2/2|escape:'quotes':'UTF-8'}" itemprop="ratingValue"/>
                                        <meta content="5" itemprop="bestRating"/>
                                    {elseif $ratingscale == 10}
                                        <meta content="{$mediacomentarios2|escape:'quotes':'UTF-8'}" itemprop="ratingValue"/>
                                        <meta content="10" itemprop="bestRating"/>
                                    {elseif $ratingscale == 20}
                                        <meta content="{$mediacomentarios2*2|escape:'quotes':'UTF-8'}" itemprop="ratingValue"/>
                                        <meta content="20" itemprop="bestRating"/>
                                    {else}
                                        <meta content="{$mediacomentarios2|escape:'quotes':'UTF-8'}" itemprop="ratingValue"/>
                                        <meta content="10" itemprop="bestRating"/>
                                    {/if}

                                    <meta content="{Configuration::get('PS_SHOP_NAME')|escape:'quotes':'UTF-8'}" itemprop="itemReviewed"/>
                                    <meta content="{$numerocomentarios|escape:'quotes':'UTF-8'}" itemprop="ratingCount"/>
                                </span>
                            {/if}

                            <div id="reviewSlide" style="position:relative;
                                width:{$width2|escape:'htmlall':'UTF-8'}px;
                                top:{$top2|escape:'htmlall':'UTF-8'}px;
                                left:{$left2|escape:'htmlall':'UTF-8'}px;
                                color:{$color2|escape:'htmlall':'UTF-8'};
                                text-align:{$textalign2|escape:'htmlall':'UTF-8'};
                                font-family:{$fontfamily2|escape:'htmlall':'UTF-8'};
                                font-size:{$fontsize2|escape:'htmlall':'UTF-8'}px;
                                font-weight:{$fontweight2|escape:'htmlall':'UTF-8'};
                                vertical-align:middle">
                                {foreach from=$comentarioazar item=randomReview}
                                    <div class="review" style="display:none;">{stripslashes($randomReview.comment|strip_tags|truncate:'50':'...'|escape:'htmlall':'UTF-8')}</div>
                                    <div class="reviewSnippets" itemprop="review" itemscope itemtype="http://schema.org/Review">
                                        <meta itemprop="description" content="{{$randomReview.comment|strip_tags}|truncate:'200':'...'}">
                                        <meta itemprop="author" content="{if empty($randomReview.nick)}{l s='Anonymous' mod='lgcomments'}{else}{$randomReview.nick|escape:'quotes':'UTF-8'}{/if}">
                                        <meta itemprop="datePublished" content="{$randomReview.date|date_format:"%Y-%m-%d"|escape:'quotes':'UTF-8'}">
                                        <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="rating-hidden">
                                            <meta itemprop="ratingValue" content="{if strpos($randomReview.stars, '.5')}{$randomReview.stars|escape:'htmlall':'UTF-8'}{else}{{$randomReview.stars|intval}|escape:'htmlall':'UTF-8'}{/if}">
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
                                {/foreach}
                            </div>
                        </span>
                    </div>
                {/if}
                <div style="position:absolute;width:1px;height:1px;">
                    <div {if $rotate3 > 0}class="rotate"{/if} style="position:relative;
                            width:{$width3|escape:'htmlall':'UTF-8'}px;
                            top:{$top3|escape:'htmlall':'UTF-8'}px;
                            left:{$left3|escape:'htmlall':'UTF-8'}px;">
                        <a href="{$reviews_link|escape:'htmlall':'UTF-8'}"><img style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important" src="{$path_lgcomments|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$mediacomentarios|escape:'htmlall':'UTF-8'|round:0}stars.png" alt="rating"></a>
                    </div>
                </div>
                {if $bgdesign1 != 'vertical' && $bgdesign1 != 'horizontal'}
                    <div style="position:absolute;width:1px;height:1px;">
                        <div style="position:relative;
                                width:{$width4|escape:'htmlall':'UTF-8'}px;
                                top:{$top4|escape:'htmlall':'UTF-8'}px;
                                left:{$left4|escape:'htmlall':'UTF-8'}px;
                                text-align:{$textalign4|escape:'htmlall':'UTF-8'};
                                font-family:{$fontfamily4|escape:'htmlall':'UTF-8'};
                                font-size:{$fontsize4|escape:'htmlall':'UTF-8'}px;
                                font-weight:{$fontweight4|escape:'htmlall':'UTF-8'};">
                            <a href="{$reviews_link|escape:'htmlall':'UTF-8'}">{l s='see more' mod='lgcomments'}</a>
                        </div>
                    </div>
                {/if}
            </div>
        </div>
        <!-- /lgcomments -->
    {/if}
{/if}
