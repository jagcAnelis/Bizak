{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{if $reviews|@count > 0}
    {counter start=0 assign=commentCounter}
    {foreach from=$reviews item=lgcomments}{counter}
        <div class="col-xs-12 col-sm-6">
            <div class="lgcomment_block lgcomment_review" itemprop="review" itemscope itemtype="http://schema.org/Review">
                <div class="title" itemprop="headline">{stripslashes($lgcomments.title|truncate:'50':'...'|escape:'quotes':'UTF-8')}</div>
                <div class="rating_img">
                    <img src="{$modules_dir|escape:'htmlall':'UTF-8'}lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$lgcomments.stars|escape:'htmlall':'UTF-8'}stars.png" alt="rating" width="160"  style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important">
                    <span itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                        <meta itemprop="ratingValue" content="{if strpos($lgcomments.rating, '.5')}{$lgcomments.rating|escape:'htmlall':'UTF-8'}{else}{{$lgcomments.rating|intval}|escape:'htmlall':'UTF-8'}{/if}">
                        <meta itemprop="bestRating" content="{$ratingscale|escape:'htmlall':'UTF-8'}">
                        <meta itemprop="worstRating" content="{$worstrating|escape:'htmlall':'UTF-8'}">
                    </span>
                </div>
                <div class="comment small" itemprop="description">{nl2br(stripslashes($lgcomments.comment|escape:'quotes':'UTF-8'))}</div>
                <div class="credits">
                    <span class="name" itemprop="author">{if empty($lgcomments.nick)}{l s='Anonymous' mod='lgcomments'}{else}{$lgcomments.nick|escape:'quotes':'UTF-8'}{/if}</span>
                    {if !is_null($lgcomments.date)}<span class="date" itemprop="datePublished" content="{$lgcomments.date|date_format:"%Y-%m-%d"|escape:'quotes':'UTF-8'}">{$lgcomments.date|date_format:"%d/%m/%Y"|escape:'quotes':'UTF-8'}{/if}</span>
                    <div class="clear clearfix"></div>
                </div>
                {if $lgcomments.answer}
                    <div class="answer"><b>{l s='Answer:' mod='lgcomments'}</b> {nl2br(stripslashes($lgcomments.answer|escape:'quotes':'UTF-8'))}</div>
                {/if}
                <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/LocalBusiness">
                    <meta itemprop="url" content="{$lgcomments_shop_url|escape:'quotes':'UTF-8'}">
                    <meta itemprop="name" content="{Configuration::get('PS_SHOP_NAME')|escape:'quotes':'UTF-8'}">
                    {if isset($price_range) && $price_range}<meta itemprop="priceRange" content="{$price_range|escape:'quotes':'UTF-8'}">{/if}
                    {if isset($lgcomments_logo_url) && $lgcomments_logo_url}
                        {*<span itemprop="image" itemscope itemtype="http://schema.org/URL">
                            <meta itemprop="url" content="{$lgcomments_shop_url|escape:'quotes':'UTF-8'}">
                        </span>*}
                        <meta itemprop="image" content="{$lgcomments_logo_url|escape:'quotes':'UTF-8'}">
                    {/if}
                    {if isset($Shop_phone) && $shop_phone}<meta itemprop="telephone" content="{$shop_phone|escape:'quotes':'UTF-8'}">{/if}
                    {if isset($lgcomments_shop_address) && $lgcomments_shop_address}<meta itemprop="address" content="{$lgcomments_shop_address|escape:'quotes':'UTF-8'}">{/if}
                </span>
            </div>
        </div>
        {if $commentCounter % 2 == 0}
            <div class="lgcomment_linebreak"></div>
        {/if}
    {/foreach}
{else}
{/if}
