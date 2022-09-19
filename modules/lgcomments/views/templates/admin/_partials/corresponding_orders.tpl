{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="order-list" class="lgtabcontent">
    <fieldset>
        <legend>
            <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=12" target="_blank">
                <span class="lglarge"><i class="icon-shopping-cart"></i>
                    {l s='Corresponding orders' mod='lgcomments'}
                    <img src="{$module_path|escape:'htmlall':'UTF-8'}/views/img/info.png">
                </span>
            </a>
        </legend>
        <h2>{l s='ORDER CRITERIA:' mod='lgcomments'}</h2><br>
        <h3 class="lgoverflow">
            <i class="icon-caret-right"></i>
            {l s='Customer groups' mod='lgcomments'}
            {if $allGroups}
                {foreach $allGroups as $group}
                    - <span class="lgsilverbutton">{$group['name']|escape:'htmlall':'UTF-8'}</span>
                {/foreach}
            {else}
                <span class="lgred">
                    {l s='No customer group selected.' mod='lgcomments'}
                    {l s='You must select at least one customer group in the module' mod='lgcomments'}
                </span>
            {/if}
        </h3>
        <h3 class="lgoverflow">
            <i class="icon-caret-right"></i>
            {l s='Shops' mod='lgcomments'}:
            {if $allShops}
                {foreach $allShops as $shop}
                    - <span class="lgsilverbutton">
                        {$shop['name']|escape:'htmlall':'UTF-8'}
                    </span>
                {/foreach}
            {else}
                <span class="lgred">
                    {l s='No shop selected.' mod='lgcomments'}
                    {l s='You must select at least one shop in the module' mod='lgcomments'}
                </span>
            {/if}
        </h3>
        <h3 class="lgoverflow">
            <i class="icon-caret-right"></i>
            {l s='Boxes checked' mod='lgcomments'}
            {if Configuration::get('PS_LGCOMMENTS_BOXES') == 2 }
                <span class="lgsilverbutton">
                    {l s='Newsletters' mod='lgcomments'}
                </span>
            {elseif Configuration::get('PS_LGCOMMENTS_BOXES') == 3 }
                <span class="lgsilverbutton">
                    {l s='Opt-in' mod='lgcomments'}
                </span>
            {elseif Configuration::get('PS_LGCOMMENTS_BOXES') == 4 }
                <span class="lgsilverbutton">
                    {l s='Newsletters + Opt-in' mod='lgcomments'}
                </span>
            {else}
                <span class="lgsilverbutton">
                    {l s='All customers' mod='lgcomments'}
                </span>
            {/if}
        </h3>
        <h3>
            <i class="icon-caret-right"></i>
                {l s='Date' mod='lgcomments'} : {l s='from' mod='lgcomments'}
                <span class="lgsilverbutton">
                    {$date2|escape:'htmlall':'UTF-8'}
                </span>
                {l s='to' mod='lgcomments'}
                <span class="lgsilverbutton">
                    {$date1|escape:'htmlall':'UTF-8'}
                </span>
        </h3>
        <h3>
            <i class="icon-caret-right"></i>
            {l s='Send emails a second time' mod='lgcomments'}:
            {if (Configuration::get('PS_LGCOMMENTS_EMAIL_TWICE')) }
                <span class="list-action-enable action-enabled" style="display:inline;margin:0;">
                    <i class="icon-check"></i>
                </span>
                {l s='only for the orders whose first email was sent before' mod='lgcomments'}
                <span class="lgsilverbutton">
                    {$date3|escape:'htmlall':'UTF-8'}
                </span>
            {else}
                <span class="list-action-enable action-disabled" style="display:inline;margin:0;">
                    <i class="icon-remove"></i>
                </span>
            {/if}
        </h3>
        <h3 class="lgoverflow">
            <i class="icon-caret-right"></i>
            {l s='Status' mod='lgcomments'}:
            {if ($allStatus)}
                {foreach $allStatus as $status}
                - <span style="background-color:{$status['color']|escape:'htmlall':'UTF-8'};" class="lgstatus">
                    {$status['name']|escape:'htmlall':'UTF-8'}
                </span>
                {/foreach}
            {else}
                <span class="lgred">
                    {l s='No status selected. You must select at least one status in the module' mod='lgcomments'}
                </span>
            {/if}
        </h3>
        <br>
        {if $orderList}
        <div class="lgoverflow">
            <table border="1" class="table">
                <tr>
                    <th class="lgupper" colspan="5" style="text-align:center;">{l s='Order' mod='lgcomments'}</th>
                    <th class="lgupper" colspan="4" style="text-align:center;">{l s='Customer' mod='lgcomments'}</th>
                    <th class="lgupper" colspan="3" style="text-align:center;">{l s='Email' mod='lgcomments'}</th>
                </tr>
                <tr>
                    <th class="lgupper">{l s='ID' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Reference' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Date' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Status' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Shop' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Customer' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Group(s)' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Newsletter' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Opt-in' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Email sent once?' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Email sent twice?' mod='lgcomments'}</th>
                    <th class="lgupper">{l s='Review already written?' mod='lgcomments'}</th>
                </tr>
                {foreach $orderList as $order}
                <tr>
                    <td>{$order['id_order']|escape:'htmlall':'UTF-8'}</td>
                    <td>{$order['reference']|escape:'htmlall':'UTF-8'}</td>
                    <td>{date($date_format_full, strtotime($order['date_add']|escape:'htmlall':'UTF-8'))}</td>
                    <td>
                        <span style="background-color:{$order['color']|escape:'htmlall':'UTF-8'}" class="lgstatus">
                            {$order['statusname']|escape:'htmlall':'UTF-8'}
                        </span>
                    </td>
                    <td>
                        {foreach $order['shops'] as $shop}
                        <span class="lgsilverbutton">{$shop['name']|escape:'htmlall':'UTF-8'}</span>
                        {/foreach}
                    </td>
                    <td>{$order['customer']|escape:'htmlall':'UTF-8'}</td>
                    <td>
                        {foreach $order['groups'] as $group}
                        - <span class="lgsilverbutton">{$group['name']|escape:'htmlall':'UTF-8'}</span>
                        {/foreach}
                    </td>
                    <td class="lgcenter">
                        {if ($order['newsletter']) }
                        <span class="list-action-enable action-enabled">
                            <i class="icon-check"></i>
                        </span>
                        {else}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-remove"></i>
                        </span>
                        {/if}
                    </td>
                    <td class="lgcenter">
                        {if $order['optin']}
                        <span class="list-action-enable action-enabled">
                            <i class="icon-check"></i>
                        </span>
                        {else}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-remove"></i>
                        </span>
                         {/if}
                    </td>
                    <td class="lgcenter">
                        {if ($order['date_email']) }
                        <span class="list-action-enable action-enabled">
                            <i class="icon-check"></i>
                        </span> {$order['date_email_formated']|escape:'htmlall':'UTF-8'}
                        {else}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-remove"></i>
                        </span>
                        {/if}
                    </td>
                    <td class="lgcenter">
                        {if $order['sent'] == 2}
                        <span class="list-action-enable action-enabled">
                            <i class="icon-check"></i>
                        </span> {$order['date_email2_formated']|escape:'htmlall':'UTF-8'}
                        {elseif $order['sent'] == 0 or $order['voted']|escape:'htmlall':'UTF-8'}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-minus"></i>
                        </span>
                        {else}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-remove"></i>
                        </span>
                        {/if}
                    </td>
                    <td class="lgcenter">
                        {if $order['voted']}
                        <span class="list-action-enable action-enabled">
                            <i class="icon-check"></i>
                        </span>
                        {elseif $order['sent'] == 0}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-minus"></i>
                        </span>
                        {else}
                        <span class="list-action-enable action-disabled">
                            <i class="icon-remove"></i>
                        </span>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </table>
        </div>
        {else}
            <span class="lgred">
                <h2>
                    {l s='You don\'t have any order that corresponds to the criteria above.' mod='lgcomments'}
                    {l s='Please modify your settings and expand your range of selection.' mod='lgcomments'}
                </h2>
            </span>
        {/if}
    </fieldset>
</div>