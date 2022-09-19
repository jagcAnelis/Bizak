{if $page.page_name=='index'}
<div class="anthemeblocks-reviews owl-carousel owl-theme{if $an_staticblock->formdata && $an_staticblock->formdata->additional_field_reviews_mobile=='0'}  anthemeblocks-reviews-hide-mobile{/if}" id="anthemeblocks-reviews_{$an_staticblock->id}" {if $an_staticblock->getImageLink() != ''}style="background-image: url({$an_staticblock->getImageLink()});"{/if} {if $an_staticblock->formdata} data-nav="{$an_staticblock->formdata->additional_field_reviews_nav}" data-dots="{$an_staticblock->formdata->additional_field_reviews_dots}" data-loop="{$an_staticblock->formdata->additional_field_reviews_loop}"   data-autoplay="{$an_staticblock->formdata->additional_field_reviews_autoplay}" data-autoplaytimeout="{$an_staticblock->formdata->additional_field_reviews_autoplayTimeout}"{/if}>
{foreach from=$an_staticblock->getChildrenBlocks() item=block}
	<div class="item container">
		{$block->getContent() nofilter}
	</div>
{/foreach}
</div>
{/if}