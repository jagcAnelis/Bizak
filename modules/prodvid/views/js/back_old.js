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

var prodvidNewVid = '<tr>'+
'<td>'+
'<input type="text" class="form-control" value="" placeholder="Enter Title" name="PRODVID_TITLE[]">'+
'<p><small>Leave empty for no title</small></p>'+
'</td>'+
'<td>'+
'<textarea class="form-control" placeholder="Enter Embed Code" name="PRODVID_EMBED_CODE[]"></textarea>'+
'</td>'+
'<td>'+
'<select name="PRODVID_POSITION[]" class="form-control">'+
'<option>Select an option</option>'+
'<option value="1" {if $d.prodvid_position == 1}selected="selected"{/if}>Left Column</option>'+
'<option value="2" {if $d.prodvid_position == 2}selected="selected"{/if}>Right Column</option>'+
'<option value="3" {if $d.prodvid_position == 3}selected="selected"{/if}>Additional Info</option>'+
'<option value="4" {if $d.prodvid_position == 4}selected="selected"{/if}>Below Product Info</option>'+
'<option value="5" {if $d.prodvid_position == 5}selected="selected"{/if}>Add to Slider</option>'+
'</select>'+
'</td>'+
'<td>'+
    '<select name="PRODVID_ENABLED[]" class="form-control">'+
    '<option>Select an option</option>'+
'<option value="0" {if $d.prodvid_enabled == 0}selected="selected"{/if}>No</option>'+
'<option value="1" {if $d.prodvid_enabled == 1}selected="selected"{/if}>Yes</option>'+
'</select>'+
'</td>'+
'<td>'+
'<button type="button" class="btn btn-danger btn-sm pull-right prodvidDeleteBtn">Delete</button>'+
'</td>'+
'</tr>';

var al = '<div class="alert alert-danger">'+
    '<button type="button" class="close" data-dismiss="alert">×</button>'+
'Please enter embed code in Product Video Pro'+
'</div>';

$(document).ready(function() {

    $(document).on('click', '.prodvidDeleteBtn', function(){
        $(this).parent().parent().remove();
    });

    $(document).on('click', '#prodvidAddVid', function(){
        $('.prodvid_body').append(prodvidNewVid);
    });

    $("#product_form").submit(function() {
        var con = true;
        $("textarea[name='PRODVID_EMBED_CODE[]']").each(function(i) {
            if($(this).val() == '') {
                con = false;
            }
        });
        if(!con) {
            $("#ajax_confirmation").before(al);
            return false;
        }
    })
});