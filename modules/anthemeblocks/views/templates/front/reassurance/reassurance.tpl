{if $page.page_name=='index'||$page.page_name=='cart'||$page.page_name=='product'}
<div class="anthemeblocks-reassurance">
    <div class="container">
    <div class="canthemeblocks-reassurance-abs row">
    <div class="canthemeblocks-reassurance-w">
  {foreach from=$an_staticblock->getChildrenBlocks() item=block}
    {$block->getContent() nofilter}
  {/foreach}
    </div>
    </div>
    </div>
</div>
{/if}