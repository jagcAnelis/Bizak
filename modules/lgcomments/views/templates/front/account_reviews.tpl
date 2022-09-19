{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}">
        {l s='My account' mod='lgcomments'}
    </a>
    <span class="navigation-pipe">
        {$navigationPipe|escape:'quotes':'UTF-8'}
    </span>
    <span class="navigation_page">
        {l s='My reviews' mod='lgcomments'}
    </span>
{/capture}

<h1 class="page-heading">{l s='My reviews' mod='lgcomments'}</h1>

<div class="row addresses-lists">
    <div class="col-md-12">
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
                        <td>{$lgreview.date_add|date_format:"$dateformat"|escape:'html':'UTF-8'}</td>
                        <td>{$lgreview.total_paid_tax_incl|round:2|escape:'html':'UTF-8'} {$lgreview.sign|escape:'html':'UTF-8'}</td>
                        <td>
                            {if $lgreview.voted}
                            <span style="background-color:grey;color:white;padding:4px;border-radius:5px;line-height:25px;">{l s='Already sent' mod='lgcomments'}
                                <span>
                            {else}
                                    <a href="{$lgreview.link|escape:'html':'UTF-8'}"
                                       target="_blank"><span
                                                style="background-color:green;color:white;padding:4px;border-radius:5px;line-height:25px;">{l s='Click here' mod='lgcomments'}
                                            <span></a>
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </table>
        {else}
            <p class="alert alert-warning">
                {l s='You haven\'t received any opinion requests at the moment.' mod='lgcomments'}
            </p>
        {/if}
    </div>
</div>

<ul class="footer_links clearfix">
    <li>
        <a class="btn btn-default button button-small" href="{$base_dir|escape:'quotes':'UTF-8'}"
           title="{l s='Home' mod='lgcomments'}">
            <span><i class="icon-chevron-left"></i> {l s='Home' mod='lgcomments'}</span>
        </a>
    </li>
    <li>
        <a class="btn btn-default button button-small" href="{$link->getPageLink('my-account')|escape:'quotes':'UTF-8'}"
           title="{l s='My account' mod='lgcomments'}">
            <span><i class="icon-chevron-left"></i> {l s='My account' mod='lgcomments'}</span>
        </a>
    </li>
</ul>
