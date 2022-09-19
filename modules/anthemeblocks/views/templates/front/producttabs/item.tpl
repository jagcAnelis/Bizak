{if version_compare($smarty.const._PS_VERSION_, '1.7.0.0', '<')}
    {foreach from=$an_staticblock->products item="product"}
        {include file='$tpl_dir./product-list.tpl' product=$product}
    {/foreach}
{else}
	{foreach from=$an_staticblock->products item="product"}
		{include file='catalog/_partials/miniatures/product.tpl' product=$product}
	{/foreach}
{/if}