{*
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
*}
{if $product.quantity <= $config.stockProgressBarMaxValue}
{if $config.stockProgressBarColor!=''}
<style>
.an_hurry_up-progress-fill { background-color: {$config.stockProgressBarColor} !important; }
</style>
{/if}
    <div class="an_hurry_up">
        <div class="an_hurry_up-text">
            {if $product.quantity > 0}
                {$config.title_left|escape:'htmlall':'UTF-8'}
                <span class="an_hurry_up-count">{$product.quantity}</span>
                {$config.title_right|escape:'htmlall':'UTF-8'}
            {else}
                {$config.title_noitems|escape:'htmlall':'UTF-8'}
            {/if}
        </div>
		{if isset($config.show_line) AND $config.show_line=='1'}
        <div class="an_hurry_up-progress" >
            <div class="an_hurry_up-progress-fill" data-max="{$config.stockProgressBarMaxValue}"></div>
        </div>
		{/if}
    </div>
{/if}
