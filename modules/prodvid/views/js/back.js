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

var prodvidNewVid = '<div class="form-inline row">' +
    '<div class="form-group col-lg-3">' +
    '<label>Title:</label><br />' +
'<input type="text" class="form-control" placeholder="Enter Title" name="PRODVID_TITLE[]">' +
    '<p><small>Leave empty for no title</small></p>' +
'</div>' +
'<div class="form-group col-lg-3">' +
    '<label>Embed Code:</label><br />' +
'<textarea class="form-control" placeholder="Enter Embed Code" name="PRODVID_EMBED_CODE[]"></textarea>' +
'</div>' +
'<div class="form-group col-lg-3">' +
    '<label>Position:</label><br />' +
'<select name="PRODVID_POSITION[]" class="form-control">' +
'<option>Select an option</option>' +
'<option value="1">Thumbnail in Slider</option>' +
'<option value="2">Below Slider</option>' +
'<option value="3">Below Product Buttons</option>' +
'<option value="4">New Tab with Product Details</option>' +
'</select>' +
'</div>' +
'<div class="form-group col-lg-2">' +
    '<label>Enabled:</label><br />' +
'<select name="PRODVID_ENABLED[]" class="form-control">'+
    '<option>Select an option</option>'+
'<option value="0" {if $d.prodvid_enabled == 0}selected="selected"{/if}>No</option>'+
'<option value="1" {if $d.prodvid_enabled == 1}selected="selected"{/if}>Yes</option>'+
'</select>' +
    '</div>' +
    '<div class="form-group col-lg-1">' +
    '<br /><button type="button" class="btn btn-danger btn-sm pull-right prodvidDeleteBtn">Delete</button>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-lg-12">' +
    '<hr />' +
    '</div>' +
    '</div>' +
    '</div>';

document.addEventListener('DOMContentLoaded', function(event) {
    $(document).on('click', '.prodvidDeleteBtn', function(){
        $(this).parent().parent().remove();
    });

    $(document).on('click', '#prodvidAddVid', function(){
        $('.prodvidContainer').append(prodvidNewVid);
    });
});