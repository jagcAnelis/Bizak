<div class="categoriesproduct-block-2 container">
{*	<h2 class='decor_line'>{$an_staticblock->title|escape:'htmlall':'UTF-8'}</h2> *}
	<div class="anthemeblocks-categoriesproduct-2 row">
	{foreach from=$an_staticblock->getChildrenBlocks() item=block}
		{$block->getContent() nofilter}
	{/foreach}
	</div>
</div>