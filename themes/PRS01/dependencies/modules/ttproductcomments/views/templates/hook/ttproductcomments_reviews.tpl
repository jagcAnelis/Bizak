{*
*  @author    Templatetrip
*  @copyright 2015-2017 Templatetrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
{if (isset($nbComments) && $nbComments > 0)}
<div class="hook-reviews">
    <div class="comments_note" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
        <div class="star_content clearfix">
            {section name="i" start=0 loop=5 step=1}
                {if $averageTotal le $smarty.section.i.index}
                    <div class="star"></div>
                {else}
                    <div class="star star_on"></div>
                {/if}
            {/section}
            <meta itemprop="worstRating" content = "0" />
            <meta itemprop="ratingValue" content = "{if isset($ratings.avg)}{$ratings.avg|round:1|escape:'html':'UTF-8'}{else}{$averageTotal|round:1|escape:'html':'UTF-8'}{/if}" />
            <meta itemprop="bestRating" content = "5" />
        </div>
        {if isset($nbCommentsCounter) && $nbCommentsCounter}
            <span class="nb-comments"><span itemprop="reviewCount">{$nbComments}</span> {l s='Review(s)' mod='ttproductcomments'}</span>
        {/if}
    </div>
	</div>
{/if}
