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

.notification_cookie {
    background:{$an_modal_cookie_background};
    opacity:{$an_modal_cookie_opacity}%;
    {if $an_modal_cookie_width}width:{$an_modal_cookie_width}px;{/if}
    {if $an_modal_cookie_height}height:{$an_modal_cookie_height}px;{/if}
    {if $an_modal_cookie_position == 'bl' || !$an_modal_cookie_position}
    bottom: 30px;
    left: 30px;
    {/if}
    {if $an_modal_cookie_position == 'br'}
    bottom: 30px;
    right: 30px;
    {/if}
    {if $an_modal_cookie_position == 'tl'}
    top: 30px;
    left: 30px;
    {/if}
    {if $an_modal_cookie_position == 'tr'}
    top: 30px;
    right: 30px;
    {/if}
}

.notification_cookie a, .notification_cookie span {
    color: {$an_modal_cookie_links_color};
}
.notification_cookie p,
.notification_cookie {
    color: {$an_modal_cookie_text_color};
}
