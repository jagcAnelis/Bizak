{if version_compare($smarty.const._PS_VERSION_, '1.7.0.0', '<')}

	{foreach from=$an_staticblock->getChildrenBlocks() item=block name=fonavcontent}
		{$block->getContent() nofilter}	
	{/foreach}

{else}
<div class="anthemeblocks-producttabs">
<div class="container">
	<ul class="nav nav-tabs">
		{foreach from=$an_staticblock->getChildrenBlocks() item=block name=fonavname}
		<li class="nav-item">
			<a class="nav-link{if $smarty.foreach.fonavname.iteration=='1'} active{/if}" data-toggle="tab" href="#anthemeblocks-producttabs{$block->id nofilter}">
				{$block->title|escape:'htmlall':'UTF-8'}
			</a>
		</li>
		{/foreach}
	</ul>
	<div class="tab-content" id="tab-content" style="padding-top: 10px;">	
	{foreach from=$an_staticblock->getChildrenBlocks() item=block name=fonavcontent}
		<div class="tab-pane fade in{if $smarty.foreach.fonavcontent.iteration=='1'} active{/if}" id="anthemeblocks-producttabs{$block->id nofilter}">
			<section class="featured-products clearfix">
				<div class="products">
					{$block->getContent() nofilter}	
				</div>
			</section>
		</div>
	{/foreach}
	</div>
</div>
</div>
{/if}