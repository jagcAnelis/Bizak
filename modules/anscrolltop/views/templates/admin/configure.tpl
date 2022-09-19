{*
* 2020 Anvanto
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
*  @author Anvanto <anvantoco@gmail.com>
*  @copyright  2020 Anvanto
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of Anvanto
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Preview' mod='anscrolltop'}</h3>
	<div id="scrolltopbtn" style="position: static">
		<img id="an_scrolltop-img" src="{$an_scrolltop_icon|escape:'htmlall':'UTF-8'}" class="an_scrolltop-svg invisible" data-color="{$an_scrolltop_svg_color}" data-width="{$an_scrolltop_svg_width}" />
	</div>
</div>
{foreach from=$errors item=error}
<div class="bootstrap">
	<div class="module_error alert alert-danger">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{$error|escape:'htmlall':'UTF-8'}
	</div>
</div>
{/foreach}