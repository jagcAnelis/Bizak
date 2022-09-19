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

{extends file="helpers/form/form.tpl"}

{block name="input"}
    {if $input.type == 'number' or $input.type == 'float'}
        <div class='input-group'>
        <input type="number"
            id="{if isset($input.id)}{$input.id|intval}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
            name="{$input.name|escape:'htmlall':'UTF-8'}"
            class="form-control"
            value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
            {if isset($input.size)} size="{$input.size|intval}"{/if}
            {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
            {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
            {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
            {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
            {if isset($input.required) && $input.required} required="required" {/if}
            {if isset($input.max)} max="{$input.max}"{/if}
            {if isset($input.min)} min="{$input.min}"{/if}
            {if isset($input.step)} step="{$input.step|floatval}"{/if}
            {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'htmlall':'UTF-8'}"{/if} />
            {if !empty($input.suffix)}
            <span class="input-group-addon">
                {$input.suffix|escape:'htmlall':'UTF-8'}
            </span>
            {/if}
        </div>
	{elseif $input.type == 'textarea'}
		<textarea
				id="{if isset($input.id)}{$input.id|intval}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
				name="{$input.name|escape:'htmlall':'UTF-8'}"
				class="form-control"
				rows="{$input.rows|escape:'htmlall':'UTF-8'}">{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
	{else}
		{$smarty.block.parent}
		{if isset($input.typeSub) AND $input.typeSub == 'exSelect'}
		<span>
			<div class="form-check">
			  <input class="form-check-input exSelect-apply" type="checkbox" value="1" id="exSelect-apply-{$input.name|escape:'htmlall':'UTF-8'}_apply" name="{$input.name|escape:'htmlall':'UTF-8'}_apply">
			  <label class="form-check-label" for="exSelect-apply-{$input.name|escape:'htmlall':'UTF-8'}_apply">
				{l s='Allow to you the option' mod='an_theme'}
			  </label>
			</div>
		</span>
		{/if}
    {/if}
{/block}
