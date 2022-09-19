<ul class="anthemeblocks-footer-payments">
{foreach from=$an_staticblock->getChildrenBlocks() item=block}
<li>{$block->getContent() nofilter}</li>
{/foreach}
</ul>