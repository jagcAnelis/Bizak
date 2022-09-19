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

{if !is_null($an_hu_ipa)}
    <input type="hidden" id="an_hu_ipa" name="an_hu_ipa" value="{$an_hu_ipa|escape:'htmlall':'UTF-8'}"/>
{/if}
<input type="hidden" id="an_hu_url" name="an_hu_url" value="{{$link->getModuleLink('an_hurry_up', 'hurry', [], true)|escape:'html':'UTF-8'}}"/>

