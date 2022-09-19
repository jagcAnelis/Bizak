/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
$(document).ready(function() {
    $(".prodvid-block-old[data-prodvid-id='1']").appendTo($(".pb-left-column"));
    var i = 1;
    $("div[data-prodvid-id='5']").each(function(index) {
        $("#thumbs_list_frame .last").before('<li><button type="button" class="btn btn-info prodvid-video-icon" data-toggle="modal" data-target="#prodvid_video_'+i+'"><i class="icon icon-play"></i></button></li>');
        $('body').append('<div class="modal fade" id="prodvid_video_'+i+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-body prodvid_body_'+i+'"></div></div></div></div>');
        $("h3", this).appendTo($('.prodvid_body_'+i));
        $(".embed_code", this).appendTo($('.prodvid_body_'+i)).removeClass('col-lg-12');
        $(this).remove();
        i++;
    });
});
