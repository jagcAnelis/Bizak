{*
* 2021 Anvanto
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses.
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
*  @author Anvanto <anvantoco@gmail.com>
*  @copyright  2021 Anvanto
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of Anvanto
*}

<div class="notification_cookie">
    <div class="notification_cookie-content">
        {$an_modal_cookie_text nofilter}{* HTML, no escape required *}
        <div class="notification_cookie-action">
            {if $an_modal_cookie_privacy_link}
                <a href="{$an_modal_cookie_privacy_link}" class="notification_cookie-link">{$an_modal_cookie_privacy}</a>
            {/if}
            <span class="notification_cookie-accept">{$an_modal_cookie_accept}<i class="material-icons">done</i></span>
        </div>
    </div>
</div>