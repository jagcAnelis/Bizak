{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="configure-email" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=12" target="_blank">
                    <span class="lglarge"><i class="icon-wrench"></i>
                        {l s='Configure emails' mod='lgcomments'}
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend><br>
            <h3>
                <span class="lgfloat">
                <label for="lgcomments_groups">
                    <i class="icon-users"></i>
                    {l s='Send emails to the selected groups of customers only' mod='lgcomments'}
                </label>
                </span>
            </h3>
            <div>
                <table class="table">
                    {foreach $customerGroups as $cGroup}
                    <tr>
                        <td width="50px;">
                            <input type="checkbox" name="group{$cGroup['id_group']|escape:'htmlall':'UTF-8'}" value="1"{if $cGroup['checked']} checked="checked"{/if} />
                        </td>
                        <td><span>{$cGroup['name']|escape:'htmlall':'UTF-8'}</span></td>
                    </tr>
                    {/foreach}
                </table>
            </div>
            <div class="alert alert-info">
                {l s='This feature prevents groups of customers to leave a review about their orders.' mod='lgcomments'}
            </div>
            <div class="lgclear"></div><br><br>
            <h3>
                <span class="lgfloat">
                <label for="lgcomments_shops">
                    <i class="icon-sitemap"></i>
                    {l s='Send emails to the customers of the selected shops only' mod='lgcomments'}
                </label>
                </span>
            </h3>
            <div>
                <table class="table">
                    {foreach $shopList as $shop}
                    <tr>
                        <td width="50px;">
                            <input type="checkbox" name="shop{$shop['id_shop']|escape:'htmlall':'UTF-8'}" value="1"{if $shop['checked']} checked="checked"{/if}>
                        </td>
                        <td><span>{$shop['name']|escape:'htmlall':'UTF-8'}</span></td>
                    </tr>
                    {/foreach}
                </table>
            </div>
            <div class="alert alert-info">
                {l s='This feature prevents you from sending emails to the customers' mod='lgcomments'}
                {l s='of a shop on which the module is disabled (multistore mode).' mod='lgcomments'}
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <span class="lgfloat">
                    <label>
                        <i class="icon-check-square-o"></i>
                        {l s='Send emails only to the customers who have tick the box:' mod='lgcomments'}
                    </label>
                </span>
                <select id="PS_LGCOMMENTS_BOXES" class="lgfloat fixed-width-xl" name="PS_LGCOMMENTS_BOXES">
                    <option{if Configuration::get('PS_LGCOMMENTS_BOXES') == 1} selected="selected"{/if} value="1">
                        {l s='All customers' mod='lgcomments'}
                    </option>
                    <option{if Configuration::get('PS_LGCOMMENTS_BOXES') == 2} selected="selected"{/if} value="2">
                        {l s='Newsletters' mod='lgcomments'}
                    </option>
                    <option{if Configuration::get('PS_LGCOMMENTS_BOXES') == 3} selected="selected"{/if} value="3">
                        {l s='Opt-in' mod='lgcomments'}
                    </option>
                    <option{if Configuration::get('PS_LGCOMMENTS_BOXES') == 4} selected="selected"{/if} value="4">
                        {l s='Newsletters + Opt-in' mod='lgcomments'}
                    </option>
                </select>
            </h3>
            <div class="alert alert-info">
                {l s='This feature prevents the module to send emails to the customers who have not' mod='lgcomments'}
                {l s='accepted to receive newsletters or partner offers.' mod='lgcomments'}
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <span class="lgfloat">
                    <label for="lgcomments_days">
                        <i class="icon-calendar"></i>
                        {l s='Send emails only for the orders that are more than' mod='lgcomments'}
                    </label>
                </span>
                <span class="lgfloat lgdays">
                    <input class="validate" integer="true" nonempty="true" id="lgcomments_dias" type="text" name="lgcomments_dias" value="{Configuration::get('PS_LGCOMMENTS_DIAS')|intval}"  required />
                </span>
                <span class="lgfloat"><label>{l s='days old and less than' mod='lgcomments'}</label></span>
                <span class="lgfloat lgdays">
                    <input class="validate" integer="true" nonempty="true" id="lgcomments_dias2" type="text" name="lgcomments_dias2" value="{Configuration::get('PS_LGCOMMENTS_DIAS2')|intval}"  required />
                </span>
                <span class="lgfloat"><label>{l s='days old' mod='lgcomments'}</label></span>
            </h3>
            <div class="alert alert-info lgclear">
                {l s='This feature prevents customers to leave a review for very recent and old orders.' mod='lgcomments'}
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <span class="lgfloat">
                <label>
                    <i class="icon-calendar"></i>
                    {l s='Send emails a second time' mod='lgcomments'}
                </label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat">
                <input type="radio" name="lgcomments_email_twice" id="lgcomments_email_twice_on" value="1"{if Configuration::get('PS_LGCOMMENTS_EMAIL_TWICE') == 1} checked="checked"{/if} />
                <label for="lgcomments_email_twice_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                <input type="radio" name="lgcomments_email_twice" id="lgcomments_email_twice_off" value="0"{if Configuration::get('PS_LGCOMMENTS_EMAIL_TWICE') == 0} checked="checked"{/if} />
                <label for="lgcomments_email_twice_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                <a class="slide-button btn"></a>
                </span>
                <span class="lgfloat">
                    <label>{l s='at least' mod='lgcomments'}</label>
                </span>
                <span class="lgfloat lgdays">
                    <input class="validate" integer="true" nonempty="true" id="lgcomments_days_after" type="text" name="lgcomments_days_after" value="{Configuration::get('PS_LGCOMMENTS_DAYS_AFTER')|intval}" required />
                </span>
                <span class="lgfloat">
                    <label>{l s='days after the first emails were sent' mod='lgcomments'}</label>
                </span>
            </h3>
            <div class="alert alert-info lgclear">
                {l s='This feature allows you to send a second time' mod='lgcomments'}
                {l s='the opinion request emails that have already been sent.' mod='lgcomments'}
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <span class="lgfloat">
                    <label for="lgcomments_estados">
                        <i class="icon-history"></i>
                        {l s='Send emails only for the orders with the current selected status:' mod='lgcomments'}
                    </label>
                </span>
            </h3>
            <div class="alert alert-info">
                {l s='This feature prevents customers to leave a review' mod='lgcomments'}
                {l s='for orders that they haven\'t received yet.' mod='lgcomments'}
            </div>
            <div>
                <table class="table">
                    {foreach $orderStatus as $estado}
                    <tr>
                        <td width="50px;">
                            <input type="checkbox" name="estado{$estado['id_order_state']|escape:'htmlall':'UTF-8'}" value="1"{if $estado['checked']} checked="checked"{/if}>
                        </td>
                        <td>
                            <span style="background-color:{$estado['color']|escape:'htmlall':'UTF-8'}" class="lgstatus">
                                {$estado['name']|escape:'htmlall':'UTF-8'}
                            </span>
                        </td>
                    </tr>
                    {/foreach}
                </table>
            </div>
            <div class="lgclear"></div><br><br><br>
            <h3>
                <label for="lgcomments_subject">
                    <i class="icon-flag"></i> {l s='Choose the email subjects for your customers:' mod='lgcomments'}
                </label>
            </h3>
            <div class="alert alert-info">
                {l s='Choose the subject of the emails sent to your customers to ask for their opinions,' mod='lgcomments'}
                {l s='to say thank you and to send an answer to their review.' mod='lgcomments'}
            </div>
            <div class="lgoverflow">
                <table class="table">
                    <tr>
                        <th>{l s='Language' mod='lgcomments'}</th>
                        <th colspan="2">{l s='Opinion request email' mod='lgcomments'}</th>
                        <th colspan="2">{l s='Thank you email' mod='lgcomments'}</th>
                        <th colspan="2">{l s='Opinion answer email' mod='lgcomments'}</th>
                    </tr>
                    {foreach $langs as $lang}
                    <tr>
                        <td class="lgbutton">{$lang['name']|escape:'htmlall':'UTF-8'}</td>
                        <td>
                            <input type="text" name="subject{$lang['iso_code']|escape:'htmlall':'UTF-8'}" value="{$subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT']|escape:'htmlall':'UTF-8'}">
                        </td>
                        <td>
                            <a href="{$module_path|escape:'htmlall':'UTF-8'}mails/{$lang['iso_code']|escape:'htmlall':'UTF-8'}/opinion-request.html" target="_blank">{l s='Template' mod='lgcomments'} ({$lang['iso_code']|escape:'htmlall':'UTF-8'}))</a>
                        </td>
                        <td>
                            <input type="text" name="subject2{$lang['iso_code']|escape:'htmlall':'UTF-8'}" value="{$subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT2']|escape:'htmlall':'UTF-8'}">
                        </td>
                        <td class="lgbutton">
                            <a href="{$module_path|escape:'htmlall':'UTF-8'}mails/{$lang['iso_code']|escape:'htmlall':'UTF-8'}/thank-you.html" target="_blank">{l s='Template' mod='lgcomments'} ({$lang['iso_code']|escape:'htmlall':'UTF-8'})</a>
                        </td>
                        <td>
                            <input type="text" name="subject3{$lang['iso_code']|escape:'htmlall':'UTF-8'}" value="{$subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT3']|escape:'htmlall':'UTF-8'}">
                        </td>
                        <td class="lgbutton">
                            <a href="{$module_path|escape:'htmlall':'UTF-8'}mails/{$lang['iso_code']|escape:'htmlall':'UTF-8'}/send-answer.html" target="_blank">{l s='Template' mod='lgcomments'} ({$lang['iso_code']|escape:'htmlall':'UTF-8'})</a>
                        </td>
                    </tr>
                    {/foreach}
                    <tr>
                        <th>{l s='FTP Location' mod='lgcomments'}</th>
                        <th colspan="2">/modules/lgcomments/mails/../opinion-request.html</th>
                        <th colspan="2">/modules/lgcomments/mails/../thank-you.html</th>
                        <th colspan="2">/modules/lgcomments/mails/../send-answer.html</th>
                    </tr>
                </table>
                <br>
            </div>
            <div class="lgclear"></div>
            <div class="alert alert-info">
                <u><b>{l s='About email templates:' mod='lgcomments'}</b></u>
                <br>
                {l s='The variables {shop_name}, {shop_logo} and {storename} are specific to your shop,' mod='lgcomments'}
                {l s='the variables {firstname} and {link} are specific to each customer' mod='lgcomments'}
                {l s='and the variables {object}, {stars}, {title}, {comment} and {answer}' mod='lgcomments'}
                {l s='are specific to each review.' mod='lgcomments'}
                <br>
                {l s='These variables will be automatically substituted by the corresponding data' mod='lgcomments'}
                {l s='before sending the opinion email to your customers' mod='lgcomments'}
                <br>
                {l s='To modify the content of these emails,' mod='lgcomments'}
                {l s='connect to your FTP and follow the paths indicated above' mod='lgcomments'}
            </div>
            <div class="lgclear"></div>
            <br>
            <div class="lgcomment_validate_error_message configure-email-errors alert danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
            </div>
            <div class="lgclear"></div><br><br>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsConfigure">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>
