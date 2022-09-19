{if $an_staticblock->link!=''}
<a href="{$an_staticblock->link}">
{/if}
<img src="{$an_staticblock->getImageLink()}" alt="{$an_staticblock->title|escape:'htmlall':'UTF-8'}">
{if $an_staticblock->link!=''}
</a>
{/if}