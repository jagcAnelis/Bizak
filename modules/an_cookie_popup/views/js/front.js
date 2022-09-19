/**
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
 */
 
$(document).ready(function(){
    if (getCookie('an_cookie_banner')!=1) {
        $('.notification_cookie').show();
    }
    $('.notification_cookie-accept').on('click', function () {
		let date = new Date(Date.now() + 86400e3 * 360 * 3);
		date = date.toUTCString();		
		
        document.cookie = "an_cookie_banner=1; path=/; expires=" + date;
        $('.notification_cookie').hide();
    });

    function getCookie(name)
    {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }
});
