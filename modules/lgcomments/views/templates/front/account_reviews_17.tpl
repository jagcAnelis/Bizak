{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{extends file="file:{$tpl_dir}templates/customer/page.tpl"}
{block name='page_content'}
<header>
    <h1>{l s='My reviews' mod='lgcomments'}</h1>
</header>
    <aside id="notifications">
        <div class="container">
            {if !$lgreviews}
                <article class="alert alert-warning" role="alert">
                    <ul>
                        <li>{l s='You haven\'t received any opinion requests at the moment.' mod='lgcomments'}</li>
                    </ul>
                </article>
            {/if}
        </div>
    </aside>
    {if $lgreviews}
        <table class="std table">
            <tr class="item">
                <th>{l s='Order ID' mod='lgcomments'}</th>
                <th>{l s='Order reference' mod='lgcomments'}</th>
                <th>{l s='Order date' mod='lgcomments'}</th>
                <th>{l s='Total price' mod='lgcomments'}</th>
                <th>{l s='Opinion form' mod='lgcomments'}</th>
            </tr>
            {foreach from=$lgreviews item=lgreview}
                <tr class="item">
                    <td>{$lgreview.id_order|escape:'html':'UTF-8'}</td>
                    <td>{$lgreview.reference|escape:'html':'UTF-8'}</td>
                    <td>{$lgreview.date_add|date_format:"%d/%m/%Y"|escape:'html':'UTF-8'}</td>
                    <td>{Tools::displayPrice($lgreview.total_paid_tax_incl|round:2|escape:'html':'UTF-8')}</td>
                    <td>
                        {if $lgreview.voted}
                            <span style="background-color:grey;color:white;padding:4px;border-radius:5px;line-height:25px;">{l s='Already sent' mod='lgcomments'}</span>
                        {else}
                            <a href="{$lgreview.link|escape:'html':'UTF-8'}"
                               target="_blank">
                                <span style="background-color:green;color:white;padding:4px;border-radius:5px;line-height:25px;">{l s='Click here' mod='lgcomments'}</span>
                            </a>
                        {/if}
                    </td>
                </tr>
            {/foreach}
        </table>
    {/if}
{/block}
