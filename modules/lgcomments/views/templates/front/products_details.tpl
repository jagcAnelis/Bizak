{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{if isset($text_only_mode) && $text_only_mode}
    {l s='Y un comentario sobre los productos siguientes:' mod='lgcomments'}
    {foreach from=$products item=product name=products}
        {$product.product_name|escape:'html':'UTF-8'}
        ---
        {$product.score|escape:'htmlall':'UTF-8'}/10 - {$nick|escape:'htmlall':'UTF-8'}

        {$product.title|escape:'htmlall':'UTF-8'}

        {$product.comment|escape:'htmlall':'UTF-8'}
        {if !$smarty.foreach.products.last}
            {* NO QUITAR ESTO, CREA UN SALTO EN EL FICHERO DE TEXTO MENOS PARA LA ULTIMA LINEA *}
        {/if}
    {/foreach}
{else}
    <p>
        {l s='Y un comentario sobre los productos siguientes:' mod='lgcomments'}
        <br>
        <br>
        {foreach from=$products item=product name=products}
            <b>{$product.product_name|escape:'html':'UTF-8'}</b>
            <hr>
            <b>{$product.score|escape:'htmlall':'UTF-8'}/10</b> <b>{$nick|escape:'htmlall':'UTF-8'}</b>
            <br>
            <b>{$product.title|escape:'htmlall':'UTF-8'}</b>
            <br>
            {$product.comment|escape:'htmlall':'UTF-8'}
            <br>
            {if !$smarty.foreach.products.last}
            <br>
            {/if}
        {/foreach}
    </p>
{/if}