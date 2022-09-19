<div class="anthemeblocks-categoriesproduct-item">
	{if $an_staticblock->link!=''}
	<a href="{$an_staticblock->link}">
	{/if}
		
		<div class="categoriesproduct-image">
			{if $an_staticblock->getImageLink() != ''}
			<img width="auto" height="auto"
			src="{$an_staticblock->getImageLink()}" alt="{$an_staticblock->title|escape:'htmlall':'UTF-8'}">
			{/if}
			<div class="categoriesproduct-content">
              <h3>{$an_staticblock->title|escape:'htmlall':'UTF-8'}</h3>
			  {* {$an_staticblock->content nofilter} *}
              <div class="banner-p">
                <p class="">{l s='Learn more' mod='anthemeblocks'}</p>
              </div> 
			</div>

		</div>
		
	{if $an_staticblock->link != '' }
	</a>
	{/if}
</div>