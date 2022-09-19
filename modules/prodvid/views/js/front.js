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
    $('.page-content').after($("div[data-prodvid-id='2']"));
    $('.product-additional-info').prepend($("div[data-prodvid-id='3']"));

    if($("div[data-prodvid-id='4']").length > 0) {
        $(".product-information  .tabs .nav-tabs").append('<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#product-videos" role="tab" aria-controls="product-videos">Videos</a></li>');
        $(".tab-content").append('<div class="tab-pane fade in" id="product-videos" role="tabpanel"></div>');
        $("#product-videos").append($("div[data-prodvid-id='4']"));
        $("div[data-prodvid-id='4']").removeClass('prodvid-block');
        $("div[data-prodvid-id='4'] iframe").css('max-width', '100%');
        $("div[data-prodvid-id='4'] h3").css('text-align', 'center');
        $("div[data-prodvid-id='4'] h3").css('margin-bottom', '1rem');
        $("div[data-prodvid-id='4'] h3").css('margin-top', '1.5rem');
    }

    var i = 1;
    $("div[data-prodvid-id='1']").each(function(index) {
        $(".product-images").append('<li class="thumb-container"><button type="button" class="btn btn-info prodvid-video-icon" data-toggle="modal" data-target="#prodvid_video_'+i+'"><i class="material-icons">play_circle_filled</i></button></li>');
        $('body').append('<div class="modal fade" id="prodvid_video_'+i+'" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-body prodvid_body_'+i+'"></div></div></div></div>');
        $("h3", this).appendTo($('.prodvid_body_'+i));
        $(".embed_code", this).appendTo($('.prodvid_body_'+i)).removeClass('col-lg-12');
        $(this).remove();
        i++;
    });
});
