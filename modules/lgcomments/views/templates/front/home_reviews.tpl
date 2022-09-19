{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

<script type="text/javascript">
var lgcomments_owl = {$sliderblocks|intval};
</script>

{if $ps16 && $displayslider && $allreviews}
    <div class="row" style="padding: 10px;">
        <div id="w-title">
            <a href="{$link->getModuleLink('lgcomments','reviews')|escape:'htmlall':'UTF-8'}">{l s='Last reviews' mod='lgcomments'}</a>
        </div>
        <div id="w-more">
            <a href="{$link->getModuleLink('lgcomments','reviews')|escape:'htmlall':'UTF-8'}">{l s='see more' mod='lgcomments'}
                &gt;&gt;
            </a>
        </div>
    </div>
    <div{if $displaysnippets && $numerocomentarios} itemscope itemtype="http://schema.org/LocalBusiness"{/if}>
        {if $displaysnippets && $numerocomentarios}
            <meta itemprop="image" content="{if isset($ps16) && $ps16}{$logo_url|escape:'htmlall':'UTF-8'}{else}{$base_url|escape:'quotes':'UTF-8'}{$shop.logo|escape:'htmlall':'UTF-8'}{/if}">
            <meta itemprop="name" content="{if isset($shop.name)}{$shop.name|escape:'quotes':'UTF-8'}{else}{$shop_name|escape:'quotes':'UTF-8'}{/if}"/>
            <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                {if $address_street1}
                    <meta itemprop="streetAddress" content=" {if $address_street1}{$address_street1|escape:'quotes':'UTF-8'}{/if}{if $address_street2}{if $address_street1}, {/if}{$address_street2|escape:'quotes':'UTF-8'}{/if}"/>
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
        <div id="lgcomments-owl" class="owl-carousel owl-theme">
            {foreach from=$allreviews item=lgcomment}
                <div class="item">
                    <div class="slide-container"{if $displaysnippets && $numerocomentarios} itemprop="review" itemscope itemtype="http://schema.org/Review"{/if}>
                        <div class="slide-title"{if $displaysnippets && $numerocomentarios} itemprop="name"{/if}>{{$lgcomment.title nofilter}|truncate:'50':'...'}{* HTML CONTENT *}</div>
                        <div class="slide-thumbnail">
                            <img src="{$lgcomments_content_dir|escape:'htmlall':'UTF-8'}/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$lgcomment.stars|escape:'htmlall':'UTF-8'}stars.png" alt="rating" style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
                        </div>
                        <div class="slide-comment"{if $displaysnippets && $numerocomentarios} itemprop="description"{/if}>
                            {{$lgcomment.comment|strip_tags}|truncate:'200':'...'}
                        </div>
                        <span class="slide-name"{if $displaysnippets && $numerocomentarios}  itemprop="author"{/if}>{if empty($lgcomment.nick)}{l s='Anonymous' mod='lgcomments'}{else}{$lgcomment.nick|escape:'quotes':'UTF-8'}{/if}</span>
                        <span class="slide-date"{if $displaysnippets && $numerocomentarios}  itemprop="datePublished" content="{$lgcomment.date|date_format:"%Y-%m-%d"|escape:'quotes':'UTF-8'}"{/if}>{$lgcomment.date|date_format:"$dateformat"|escape:'htmlall':'UTF-8'}</span>
                        {if $displaysnippets && $numerocomentarios}
                            <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="rating-hidden">
                                <meta itemprop="ratingValue" content="{if strpos($lgcomment.rating, '.5')}{$lgcomment.rating|escape:'htmlall':'UTF-8'}{else}{{$lgcomment.rating|intval}|escape:'htmlall':'UTF-8'}{/if}">
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
                        {/if}
                    </div>
                </div>
            {/foreach}
        </div>
    </div>
{/if}
