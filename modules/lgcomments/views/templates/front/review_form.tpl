{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}

<link href="{$lgcomments_content_dir|escape:'htmlall':'UTF-8'}modules/lgcomments/views/css/review_form.css" rel="stylesheet" type="text/css"/>
{capture name=path}
    <a href="{$link->getPageLink('my-account', true)|escape:'htmlall':'UTF-8'}">
        {l s='My account' mod='lgcomments'}</a>
    <span class="navigation-pipe">{$navigationPipe|escape:'htmlall':'UTF-8'}</span>{l s='Ratings and reviews' mod='lgcomments'}
{/capture}
<div id="favoriteproducts_block_account">
    <h2 class="page-heading">{l s='Ratings and reviews' mod='lgcomments'}</h2>
    {if $verify == 1}
        <div>
            {if $voted == 1}
                <p class="alert alert-success">{l s='Thank you very much. Your reviews have been successfully sent, we will publish them soon.' mod='lgcomments'}</p>
            {else}
                <form method="post" action="{$form_action|escape:'htmlall':'UTF-8'}" id="validate-form">
                    <fieldset>
                        <legend class="info-title">{l s='Please insert your nick' mod='lgcomments'}</legend>
                        <table class="std table">
                            <tr class="item">
                                <td class="item" colspan="2">
                                    <label>{l s='Nick' mod='lgcomments'}</label>
                                    <textarea maxlength="255" id="lg_nick" name="lg_nick" style="width:100%;height:25px;">{if !empty($data['lg_nick'])}{$data['lg_nick']|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                    {if isset($errors_store['nick'])}
                                        <div class="alert alert-danger">{$errors_store['nick']|escape:'htmlall':'UTF-8'}</div>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </fieldset>
                    {if $opinionform == 1 || $opinionform == 3}
                        <fieldset>
                            <legend class="info-title">{l s='Please, rate your products' mod='lgcomments'}</legend>
                            <table class="std table">
                                {foreach from=$products item=product}
                                    <tr class="item">
                                        <th class="item" colspan="2">
                                            <img src="{$product.image|escape:'htmlall':'UTF-8'}"
                                                 alt="{$product.product_name|escape:'html':'UTF-8'}" border="1" height="75px">
                                            &nbsp;&nbsp;{$product.product_name|escape:'htmlall':'UTF-8'}
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="history_link bold" style="width:200px;">
                                            <div style="float:left;">
                                                {assign var=index value="product_score_{$product.id_order_detail}_{$product.product_id}"}
                                                {if empty($data[$index])}
                                                    {assign var=score value=10}
                                                {else}
                                                    {assign var=score value=$data[$index]}
                                                {/if}
                                                <select class="score"
                                                        name="product_score_{$product.id_order_detail|escape:'htmlall':'UTF-8'}_{$product.product_id|escape:'htmlall':'UTF-8'}"
                                                        data-who="{$product.id_order_detail|escape:'htmlall':'UTF-8'}_{$product.product_id|escape:'htmlall':'UTF-8'}">
                                                    <option value="0" {if $score==0}selected{/if}>{if $ratingscale == 5}0/5{elseif $ratingscale == 10}0/10{elseif $ratingscale == 20}0/20{else}0/10{/if}</option>
                                                    <option value="1" {if $score==1}selected{/if}>{if $ratingscale == 5}0,5/5{elseif $ratingscale == 10}1/10{elseif $ratingscale == 20}2/20{else}1/10{/if}</option>
                                                    <option value="2" {if $score==2}selected{/if}>{if $ratingscale == 5}1/5{elseif $ratingscale == 10}2/10{elseif $ratingscale == 20}4/20{else}2/10{/if}</option>
                                                    <option value="3" {if $score==3}selected{/if}>{if $ratingscale == 5}1,5/5{elseif $ratingscale == 10}3/10{elseif $ratingscale == 20}6/20{else}3/10{/if}</option>
                                                    <option value="4" {if $score==4}selected{/if}>{if $ratingscale == 5}2/5{elseif $ratingscale == 10}4/10{elseif $ratingscale == 20}8/20{else}4/10{/if}</option>
                                                    <option value="5" {if $score==5}selected{/if}>{if $ratingscale == 5}2,5/5{elseif $ratingscale == 10}5/10{elseif $ratingscale == 20}10/20{else}5/10{/if}</option>
                                                    <option value="6" {if $score==6}selected{/if}>{if $ratingscale == 5}3/5{elseif $ratingscale == 10}6/10{elseif $ratingscale == 20}12/20{else}6/10{/if}</option>
                                                    <option value="7" {if $score==7}selected{/if}>{if $ratingscale == 5}3,5/5{elseif $ratingscale == 10}7/10{elseif $ratingscale == 20}14/20{else}7/10{/if}</option>
                                                    <option value="8" {if $score==8}selected{/if}>{if $ratingscale == 5}4/5{elseif $ratingscale == 10}8/10{elseif $ratingscale == 20}16/20{else}8/10{/if}</option>
                                                    <option value="9" {if $score==9}selected{/if}>{if $ratingscale == 5}4,5/5{elseif $ratingscale == 10}9/10{elseif $ratingscale == 20}18/20{else}9/10{/if}</option>
                                                    <option value="10"  {if $score==10}selected{/if}>{if $ratingscale == 5}5/5{elseif $ratingscale == 10}10/10{elseif $ratingscale == 20}20/20{else}10/10{/if}</option>
                                                </select>
                                            </div>
                                            <div style="float:left;">
                                                <img style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important" alt="rating"
                                                     src="{$modules_dir|escape:'htmlall':'UTF-8'}lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$score|intval}stars.png"
                                                     id="stars_{$product.id_order_detail|escape:'htmlall':'UTF-8'}_{$product.product_id|escape:'htmlall':'UTF-8'}">
                                            </div>
                                            {if isset($errors_products[{$product.product_id}]['score'])}
                                                <div class="alert alert-danger">{$errors_store['score']|escape:'htmlall':'UTF-8'}</div>
                                            {/if}
                                            <div style="clear:both;"></div>
                                        </td>
                                        <td class="history_link bold">{l s='Title:' mod='lgcomments'}
                                            {assign var=index value="product_title_{$product.id_order_detail}_{$product.product_id}"}
                                            <textarea maxlength="50" style="width:100%;height:25px;"
                                                      name="product_title_{$product.id_order_detail|escape:'htmlall':'UTF-8'}_{$product.product_id|escape:'htmlall':'UTF-8'}">{if !empty($data[$index])}{$data[$index]|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                            {if isset($errors_products[{$product.product_id}]['title'])}
                                                <div class="alert alert-danger">{$errors_store['title']|escape:'htmlall':'UTF-8'}</div>
                                            {/if}
                                            <br>
                                            {l s='Comment:' mod='lgcomments'}
                                            {assign var=index value="product_comment_{$product.id_order_detail}_{$product.product_id}"}
                                            <textarea name="product_comment_{$product.id_order_detail|escape:'htmlall':'UTF-8'}_{$product.product_id|escape:'htmlall':'UTF-8'}"
                                                      style="width:100%;height:60px;">{if !empty($data[$index])}{$data[$index]|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                            {if isset($errors_products[{$product.product_id}]['comment'])}
                                                <div class="alert alert-danger">{$errors_store['comment']|escape:'htmlall':'UTF-8'}</div>
                                            {/if}
                                            <br>
                                    </tr>
                                {/foreach}
                            </table>
                        </fieldset>
                        <br/>
                    {/if}
                    {if $opinionform == 1 || $opinionform == 2}
                        <fieldset>
                            <legend class="info-title">{l s='Please rate our shop' mod='lgcomments'}</legend>
                            <table class="std table">
                                <tr class="item">
                                    <th class="item" colspan="2">
                                        <img class="logo" src="{$logo_url|escape:'htmlall':'UTF-8'}"
                                             alt="{$shop_name|escape:'html':'UTF-8'}"
                                             width="150">
                                    </th>
                                </tr>
                                <tr>
                                    <td class="history_link bold" style="width:200px;">
                                        <div style="float:left;">
                                            {if empty($data['score_store'])}
                                                {assign var=score value=10}
                                            {else}
                                                {assign var=score value=$data['score_store']}
                                            {/if}
                                            <select name="score_store" class="score" data-who="store">
                                                <option value="0" {if $score==0}selected{/if}>{if $ratingscale == 5}0/5{elseif $ratingscale == 10}0/10{elseif $ratingscale == 20}0/20{else}0/10{/if}</option>
                                                <option value="1" {if $score==1}selected{/if}>{if $ratingscale == 5}0,5/5{elseif $ratingscale == 10}1/10{elseif $ratingscale == 20}2/20{else}1/10{/if}</option>
                                                <option value="2" {if $score==2}selected{/if}>{if $ratingscale == 5}1/5{elseif $ratingscale == 10}2/10{elseif $ratingscale == 20}4/20{else}2/10{/if}</option>
                                                <option value="3" {if $score==3}selected{/if}>{if $ratingscale == 5}1,5/5{elseif $ratingscale == 10}3/10{elseif $ratingscale == 20}6/20{else}3/10{/if}</option>
                                                <option value="4" {if $score==4}selected{/if}>{if $ratingscale == 5}2/5{elseif $ratingscale == 10}4/10{elseif $ratingscale == 20}8/20{else}4/10{/if}</option>
                                                <option value="5" {if $score==5}selected{/if}>{if $ratingscale == 5}2,5/5{elseif $ratingscale == 10}5/10{elseif $ratingscale == 20}10/20{else}5/10{/if}</option>
                                                <option value="6" {if $score==6}selected{/if}>{if $ratingscale == 5}3/5{elseif $ratingscale == 10}6/10{elseif $ratingscale == 20}12/20{else}6/10{/if}</option>
                                                <option value="7" {if $score==7}selected{/if}>{if $ratingscale == 5}3,5/5{elseif $ratingscale == 10}7/10{elseif $ratingscale == 20}14/20{else}7/10{/if}</option>
                                                <option value="8" {if $score==8}selected{/if}>{if $ratingscale == 5}4/5{elseif $ratingscale == 10}8/10{elseif $ratingscale == 20}16/20{else}8/10{/if}</option>
                                                <option value="9" {if $score==9}selected{/if}>{if $ratingscale == 5}4,5/5{elseif $ratingscale == 10}9/10{elseif $ratingscale == 20}18/20{else}9/10{/if}</option>
                                                <option value="10" {if $score==10}selected{/if}>{if $ratingscale == 5}5/5{elseif $ratingscale == 10}10/10{elseif $ratingscale == 20}20/20{else}10/10{/if}</option>
                                            </select>
                                        </div>
                                        <div style="float:left;">
                                            <img style="width:{$starsize|escape:'htmlall':'UTF-8'}px!important" id="stars_store" alt="rating"
                                                 src="{$modules_dir|escape:'htmlall':'UTF-8'}lgcomments/views/img/stars/{$starstyle|escape:'htmlall':'UTF-8'}/{$starcolor|escape:'htmlall':'UTF-8'}/{$score|intval}stars.png">
                                        </div>
                                        <div style="clear:both;"></div>
                                    </td>
                                    <td class="history_link bold">{l s='Title:' mod='lgcomments'}
                                        <textarea maxlength="50" name="title_store" style="width:100%;height:25px;">{if !empty($data['title_store'])}{$data['title_store']|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                        {if isset($errors_store['title'])}
                                            <div class="alert alert-danger">{$errors_store['title']|escape:'htmlall':'UTF-8'}</div>
                                        {/if}
                                        <br>{l s='Comment:' mod='lgcomments'}
                                        <textarea name="comment_store" style="width:100%;height:60px;">{if !empty($data['comment_store'])}{$data['comment_store']|escape:'htmlall':'UTF-8'}{/if}</textarea>
                                        {if isset($errors_store['title'])}
                                            <div class="alert alert-danger">{$errors_store['comment']|escape:'htmlall':'UTF-8'}</div>
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                    {/if}
                    <input type="hidden" name="sendcomments" value="1"/>
                    {if isset($lgcomments_id_module)}
                        {hook h='displayGDPRConsent' mod='psgdpr' id_module=$lgcomments_id_module}
                    {/if}
                    <input type="submit" id="sendcomments" value="{l s='Send' mod='lgcomments'}" class="button btn btn-default"/>
                </form>
            {/if}
        </div>
    {elseif $verify == 2}
        <p class="alert alert-warning">{l s='This order has already been rated and reviewed.' mod='lgcomments'}</p>
    {else}
        <p class="alert alert-warning">{l s='An error occurred while checking your identity. Please get in touch with the store admin in order to fix the problem.' mod='lgcomments'}</p>
    {/if}
</div>