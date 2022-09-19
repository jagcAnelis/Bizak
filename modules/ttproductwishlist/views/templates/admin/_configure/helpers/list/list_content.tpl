{*
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*}

{extends file="helpers/list/list_content.tpl"}

{block name="tt_content"}
    {if isset($params.type) && $params.type == 'priority'}
        <span class="label label-default">{$priority[$tr.$key]}</span>
    {elseif isset($params.type) && $params.type == 'image'}
        <img src="{$tr.$key}"/>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
