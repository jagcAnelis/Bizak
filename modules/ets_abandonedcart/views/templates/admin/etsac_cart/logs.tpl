{*
* 2007-2022 ETS-Soft
*
* NOTICE OF LICENSE
*
* This file is not open source! Each license that you purchased is only available for 1 wesite only.
* If you want to use this file on more websites (or projects), you need to purchase additional licenses.
* You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please, contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2022 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
<h4 class="ets_abancart_title">{l s='Reminder log' mod='ets_abandonedcart'}</h4>
<div class="content_reminder">
	<h4>{l s='Sent reminder(s)' mod='ets_abandonedcart'}: </h4>
    {if isset($LOGs) && $LOGs || isset($cartLogs) && $cartLogs}
		<ul class="ets_abancart_log">
			{if isset($LOGs) && $LOGs}
                {foreach from=$LOGs item="LOG"}
    				<li class="ets_abancart_item">
    					{foreach from=$LOG item='L'}
    						<span>{$L nofilter}</span><br>
    					{/foreach}
    				</li>
    			{/foreach}
            {/if}
		</ul>
	{else}
		<p class="alert alert-info">{l s='There is no sent reminder' mod='ets_abandonedcart'}</p>
	{/if}
	{if empty($tracking) && empty($recover_cart)}
		<h4>{l s='Next reminder(s)' mod='ets_abandonedcart'}: </h4>
	{/if}
	{if isset($next_mails_time) && $next_mails_time|count > 0}
		<ul class="ets_abancart_next_mails_time ets_abancart_log">
			{foreach from=$next_mails_time item='rmd'}
				<li class="ets_abancart_item">
					<span>{l s='Reminder name' mod='ets_abandonedcart'}: {$rmd.reminder_name|escape:'html':'UTF-8'}</span><br>
					<span>{l s='Next sending time' mod='ets_abandonedcart'}: <strong>{dateFormat date=$rmd.next_mail_time full=1}</strong></span>
				</li>
			{/foreach}
		</ul>
	{elseif empty($tracking) && empty($recover_cart)}
		<p class="alert alert-info">{l s='There are no next reminders' mod='ets_abandonedcart'}</p>
	{/if}
</div>