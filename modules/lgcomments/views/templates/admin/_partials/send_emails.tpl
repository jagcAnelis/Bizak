{*
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 *  @author    Línea Gráfica E.C.E. S.L.
 *  @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 *  @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *             https://www.lineagrafica.es/licenses/license_es.pdf
 *             https://www.lineagrafica.es/licenses/license_fr.pdf
 *}
<div id="send-email" class="lgtabcontent">
    <form action="" method="post">
        <fieldset>
            <legend>
                <a href="{$module_path|escape:'htmlall':'UTF-8'}readme/readme_{$iso_lang|escape:'htmlall':'UTF-8'}.pdf#page=11" target="_blank">
                    <span class="lglarge">
                        <i class="icon-envelope"></i> {l s='Send emails' mod='lgcomments'}
                        <img src="{$module_path|escape:'htmlall':'UTF-8'}views/img/info.png">
                    </span>
                </a>
            </legend>
            <div class="alert alert-info">
                <br>
                <h3>
                    <label>
                        {l s='Sending emails to your customers is very easy. You just need to:' mod='lgcomments'}
                    </label>
                </h3>
                <p>
                    {l s='1 - Go to the tab' mod='lgcomments'}
                    <span class="lgbold lgupper">{l s='Configure emails' mod='lgcomments'}</span>
                    {l s='and choose for which orders you want to send emails.' mod='lgcomments'}
                </p>
                <p>
                    {l s='2 - Go to the tab' mod='lgcomments'}
                    <span class="lgbold lgupper">{l s='Corresponding orders' mod='lgcomments'}</span>
                    {l s='and make sure that you have orders that correspond' mod='lgcomments'}
                    {l s='(emails are sent only for the orders that correspond to' mod='lgcomments'}
                    <span class="lgunderline">{l s='ALL the selected criteria' mod='lgcomments'}</span>)
                </p>
                <p>
                    {l s='3 - Then click on the' mod='lgcomments'}
                    <span class="lgbold lgupper">{l s='Cron URL' mod='lgcomments'}</span>
                    {l s='below, you will get a blank page and the emails will be automatically sent' mod='lgcomments'}
                    {l s='(if an email has already been sent for an order, it won\'t be sent again).' mod='lgcomments'}

                </p>
                <p>
                    {l s='4 - You will get a confirmation at the email indicated below' mod='lgcomments'}
                    {l s='telling you for which orders the emails have been sent.' mod='lgcomments'}
                </p>
                <p>
                    {l s='5 - Wait until your customers leave a comment,' mod='lgcomments'}
                    {l s='you will receive a notification by email every time a comment is posted.' mod='lgcomments'}
                </p>
                <p>
                    {l s='6 - Once you have received comments, you can publish them' mod='lgcomments'}
                    <a href="{$lgcommentsStoreReviewsUrl|escape:'htmlall':'UTF-8'}" target="_blank">
                        {l s='from this page (for shop reviews)' mod='lgcomments'}
                    </a>
                    {l s='and' mod='lgcomments'}
                    <a href="{$lgcommentsProductsReviewsUrl|escape:'htmlall':'UTF-8'}" target="_blank">
                        {l s='from this page (for product reviews)' mod='lgcomments'}
                    </a>
                </p>
            </div><br>
            <div style="font-size:16px;">
                <h3 class="lgoverflow">
                    <label for="lgcomments_cron_url">
                        <i class="icon-chevron-circle-right"></i>{l s='Cron URL:' mod='lgcomments'}
                    </label>
                    <a href="{$cron_url|escape:'htmlall':'UTF-8'}" target="_blank">
                        <span class="lglowercase">{$cron_url|escape:'htmlall':'UTF-8'}</span>
                    </a>
                </h3>
            </div>
            <br>
            <div class="alert alert-info"><br>
                <h3>
                    <label>{l s='No emails sent?' mod='lgcomments'}</label>
                </h3>
                <ol>
                    <li>
                        {l s='Go to the tab' mod='lgcomments'}
                        <span class="lgbold lgupper">{l s='Corresponding orders' mod='lgcomments'}</span>
                        {l s='that correspond to the selected criteria.' mod='lgcomments'}
                    </li>
                    <li>
                        <a href="{$lgcomments_email_config_link|escape:'htmlall':'UTF-8'}" target="_blank">
                            {l s='Click here' mod='lgcomments'}
                            {l s='and test your email configuration.' mod='lgcomments'}
                        </a>
                    </li>
                    <li>
                        {l s='Connect to your FTP, enter the folder' mod='lgcomments'}
                        <span class="lgbold">/modules/lgcomments/mails/</span>
                        {l s='and make sure to have a template folder for all your language codes.' mod='lgcomments'}
                    </li>
                </ol>
            </div>
            <br><br>
            <h3>
                <span class="lgfloat">
                    <label><i class="icon-bell"></i>{l s='Enable email alerts' mod='lgcomments'}</label>
                </span>
                <span class="switch prestashop-switch fixed-width-lg lgfloat" style="margin-right:10px;">
                <input type="radio" name="PS_LGCOMMENTS_EMAIL_ALERTS" id="lgcomments_email_alerts_on" value="1"{if $PS_LGCOMMENTS_EMAIL_ALERTS} checked{/if} />
                <label for="lgcomments_email_alerts_on" class="lgbutton">{l s='Yes' mod='lgcomments'}</label>
                <input type="radio" name="PS_LGCOMMENTS_EMAIL_ALERTS" id="lgcomments_email_alerts_off" value="0"{if !$PS_LGCOMMENTS_EMAIL_ALERTS} checked{/if} />
                <label for="lgcomments_email_alerts_off" class="lgbutton">{l s='No' mod='lgcomments'}</label>
                <a class="slide-button btn"></a>
                </span>
                <span class="lgfloat">
                    <input id="lgcomments_email_cron2" type="text" name="PS_LGCOMMENTS_EMAIL_CRON" value="{$PS_LGCOMMENTS_EMAIL_CRON|escape:'htmlall':'UTF-8'}" />
                </span>
            </h3>
            <div class="lgclear"></div>
            <div class="alert alert-info">
                {l s='You will get an email every time the cron is executed' mod='lgcomments'}
                {l s='and every time a comment is written by a customer' mod='lgcomments'}
            </div><br><br>
            <h3>
                <label for="lgcomments_subject">
                    <i class="icon-flag"></i> {l s='Alert subjects:' mod='lgcomments'}
                </label>
            </h3>
            <div class="lgoverflow">
                <table class="table">
                    <tr>
                        <th>{l s='Confirmation of emails sent' mod='lgcomments'}</th>
                        <th>{l s='New reviews received' mod='lgcomments'}</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="subjectcron" value="{$PS_LGCOMMENTS_SUBJECT_CRON|escape:'htmlall':'UTF-8'}">
                        </td>
                        <td>
                            <input type="text" name="subjectreviews" value="{$PS_LGCOMMENTS_SUBJECT_NEWREVIEWS|escape:'htmlall':'UTF-8'}">
                        </td>
                    </tr>
                </table>
                <div class="alert alert-info">
                    {l s='Choose the subject of the emails that will be sent to the email address above' mod='lgcomments'}
                    {l s='every time the cron is executed or a new review is sent.' mod='lgcomments'}
                </div>
            </div>
            <div class="lgclear"></div><br>
            <button class="button btn btn-default" type="submit" name="submitLGCommentsSend">
                <i class="process-icon-save"></i>{l s='Save' mod='lgcomments'}
            </button>
        </fieldset>
    </form>
</div>