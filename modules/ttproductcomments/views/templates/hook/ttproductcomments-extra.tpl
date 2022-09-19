{*
*  @author    Templatetrip
*  @copyright 2015-2017 Templatetrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}
{if (($nbComments == 0 && $too_early == false && ($logged || $allow_guests)) || ($nbComments != 0))}
    <div id="product_comments_block_extra" {if $nbComments != 0} itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"{/if}>
    {if $nbComments != 0}
        <span> {l s='Evaluaciones' mod='ttproductcomments'} ({$nbComments})</span>
    {else}
        <span> {l s='Evaluaciones' mod='ttproductcomments'}</span>
    {/if}
        {if $nbComments != 0}
            <div class="comments_note">
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
            </div>
        {/if}
        <div class="comments_advices">
            
            {if ($too_early == false AND ($logged OR $allow_guests))}
                <a class="open-comment-form writeEvaluation" href="javascript:void(0);">Escribir una evaluación</a>
            {/if}
        </div>
    </div>
{/if}
<!--  /Module ProductComments -->
