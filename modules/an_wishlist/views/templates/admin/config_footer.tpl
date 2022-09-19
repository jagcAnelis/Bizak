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
*  International Registered Trademark & Property of ETS-Soft
*}

<div class="panel col-lg-12">
    <div class="panel-heading">
        <span>Top products</span>
    </div>
    <div class="panel-body">
       <table class="table">
	   <thead>
	   <tr>
			<th width="70">Photo</th>
			<th>Name</th>
			<th>Wishlist</th>
	   </tr>
	   </thead>
	   {foreach from=$topProducts item=item}
	   <tr>
			<td><img src="{$item.image|escape:'htmlall':'UTF-8'}" style="max-width: 50px;" alt="" /></td>
			<td><a href="{$item.link|escape:'htmlall':'UTF-8'}" target="_blank">{$item.name|escape:'htmlall':'UTF-8'}</a></td>
			<td>{$item.count_wishlist|intval}</td>
	   </tr>
	   {/foreach}
	   </table>
    </div>
</div>
