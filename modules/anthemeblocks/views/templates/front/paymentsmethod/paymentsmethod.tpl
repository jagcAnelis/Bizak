<ul class="anthemeblocks-footer-payments col-md-3">
{foreach from=$an_staticblock->getChildrenBlocks() item=block}
<li>{$block->getContent() nofilter}</li>
{/foreach}
</ul>