{*
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
*}

<div class="alert alert-info" role="alert">
	<h3>{l s='Product Video Pro' mod='prodvid'}</h3>
	<p>
		<strong>{l s='You can add embed code for any video through here for this product.' mod='prodvid'}</strong><br/>
        {l s='Use the buttons on the right below to add more videos or to delete existing ones.' mod='prodvid'}<br/>
        {l s='To attach embed code to other parts of the shop, please use the configuration screen.' mod='prodvid'}
	</p>
</div>
<div class="alert alert-warning" role="alert">
	<h3>{l s='Note' mod='prodvid'}</h3>
	<p>{l s='Value in any field is set to nothing if you enter invalid data.' mod='prodvid'}</p>
</div>

<div class="container prodvidContainer">
	{foreach from=$prodvid_data  item=d}
		<div class="form-inline row">
			<div class="form-group col-lg-3">
				<label>Title:</label><br />
				<input type="text" class="form-control" id="email" value="{$d.prodvid_title}" placeholder="Enter Title" name="PRODVID_TITLE[]">
				<p><small>Leave empty for no title</small></p>
			</div>
			<div class="form-group col-lg-3">
				<label>Embed Code:</label><br />
				<textarea class="form-control" placeholder="Enter Embed Code" name="PRODVID_EMBED_CODE[]">{$d.prodvid_embed_code}</textarea>
			</div>
			<div class="form-group col-lg-3">
				<label>Position:</label><br />
				<select name="PRODVID_POSITION[]" class="form-control">
					<option>Select an option</option>
					<!--<option value="1" {if $d.prodvid_position == 1}selected="selected"{/if}>Left Column</option>
					<option value="2" {if $d.prodvid_position == 2}selected="selected"{/if}>Right Column</option>
					<option value="3" {if $d.prodvid_position == 3}selected="selected"{/if}>Additional Info</option>
					<option value="4" {if $d.prodvid_position == 4}selected="selected"{/if}>Product Footer</option>
					<option value="5" {if $d.prodvid_position == 5}selected="selected"{/if}>Header</option>
					<option value="6" {if $d.prodvid_position == 6}selected="selected"{/if}>Page Footer</option>-->
					<option value="1" {if $d.prodvid_position == 1}selected="selected"{/if}>Thumbnail in Slider</option>
					<option value="2" {if $d.prodvid_position == 2}selected="selected"{/if}>Below Slider</option>
					<option value="3" {if $d.prodvid_position == 3}selected="selected"{/if}>Below Product Buttons</option>
					<option value="4" {if $d.prodvid_position == 4}selected="selected"{/if}>New Tab with Product Details</option>
				</select>
			</div>
			<div class="form-group col-lg-2">
				<label>Enabled:</label><br />
				<select name="PRODVID_ENABLED[]" class="form-control">
					<option>Select an option</option>
					<option value="0" {if $d.prodvid_enabled == 0}selected="selected"{/if}>No</option>
					<option value="1" {if $d.prodvid_enabled == 1}selected="selected"{/if}>Yes</option>
				</select>
			</div>
			<div class="form-group col-lg-1">
				<br /><button type="button" class="btn btn-danger btn-sm pull-right prodvidDeleteBtn">Delete</button>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<hr />
				</div>
			</div>
		</div>
	{/foreach}
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-5"></div>
		<div class="col-lg-2">
			<button type="button" id="prodvidAddVid" class="btn btn-lg btn-success btn-block">Add Video</button>
		</div>
		<div class="col-lg-5"></div>
	</div>
</div>
