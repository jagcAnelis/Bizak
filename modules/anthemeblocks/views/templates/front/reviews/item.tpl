{if $an_staticblock->link!=''}
<a href="{$an_staticblock->link}">
{/if}
	<div class="anthemeblocks-reviews-desc">
		{$an_staticblock->content nofilter}
		<div class="review_name">
		{if $an_staticblock->getImageLink() != ''}
		<img width="auto" height="auto"
            src="{$an_staticblock->getImageLink()}" class="man" alt="{$an_staticblock->title|escape:'htmlall':'UTF-8'}">
		{/if}
		<h2>{$an_staticblock->title|escape:'htmlall':'UTF-8'}</h2>
		</div>
	</div>
{if $an_staticblock->link != '' }
</a>
{/if}