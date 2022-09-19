{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<nav class="pagination">
    <div class="col-md-4">
        {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=['%from%' => $pagination.items_shown_from ,'%to%' => $pagination.items_shown_to, '%total%' => $pagination.total_items] mod='lgcomments'}
    </div>
    <div class="col-md-6">
        <ul class="page-list clearfix text-xs-center">
            {foreach from=$pagination.pages item="page"}
                <li {if $page.current} class="current" {/if}>
                    {if $page.type === 'spacer'}
                        <span class="spacer">&hellip;</span>
                    {else}
                        <a rel="nofollow"
                           href="{$page.url|escape:'htmlall':'UTF-8'}"
                           class="{if $page.type === 'previous'}lgcomments_previous {elseif $page.type === 'next'}lgcomments_next {/if}{['disabled' => !$page.clickable, 'js-lgcomments-link' => true]|classnames}">
                            {if $page.type === 'previous'}
                                <i class="material-icons">&#xE314;</i>
                                {l s='Previous' d='Shop.Theme.Actions' mod='lgcomments'}
                            {elseif $page.type === 'next'}
                                {l s='Next' d='Shop.Theme.Actions' mod='lgcomments'}
                                <i class="material-icons">&#xE315;</i>
                            {else}
                                {$page.page|escape:'htmlall':'UTF-8'}
                            {/if}
                        </a>
                    {/if}
                </li>
            {/foreach}
        </ul>
    </div>
</nav>
