{if $an_staticblock->link!=''}
<a href="{$an_staticblock->link}">
	{/if}
	{if $an_staticblock->getImageLink() != ''}
	<img width="auto" height="auto"
	src="{$an_staticblock->getImageLink()}" alt="{$an_staticblock->title|escape:'htmlall':'UTF-8'}">
	{/if}
	<div class="anthemeblocks-homeslider-desc">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration=".75s" data-wow-delay="0.25s">{$an_staticblock->title|escape:'htmlall':'UTF-8'}</h2>
			<div class="block-text wow fadeInUp" data-wow-duration=".75s" data-wow-delay="0.5s">
			{$an_staticblock->content nofilter}
			</div>
			<div class="slider-line wow fadeInUp" data-wow-duration=".75s" data-wow-delay="0.5s"></div>
			{if $an_staticblock->link!='' and $an_staticblock->formdata->additional_field_item_button == 0}
			<div class="btn-box">
				<button class="btn btn-primary wow fadeInUp" data-wow-duration=".75s" data-wow-delay="1s">{l s='Learn more' mod='anthemeblocks'}</button>
			</div>
			{/if}
		</div>
	</div>
	{if $an_staticblock->link != '' }
</a>
{/if}