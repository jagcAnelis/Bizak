{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
{foreach $products as $product}
<p>
    <img style="vertical-align: middle;margin-right: 15px;" src="{$product.image_path|escape:'htmlall':'UTF-8'}">
    {$product.product_name|escape:'htmlall':'UTF-8'}
</p>
{/foreach}
