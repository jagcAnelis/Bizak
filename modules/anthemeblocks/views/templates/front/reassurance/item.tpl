<div class="anthemeblocks-reassurance-item">
    <a {if $an_staticblock->link <> ''} href="{$an_staticblock->link}" {else} href="#" {/if}>
        <img width="auto" height="auto"
        src="{$an_staticblock->getImageLink()}" alt="{$an_staticblock->title|escape:'htmlall':'UTF-8'}">
        <div class="reassurance-item-text">
            <h6>{$an_staticblock->title|escape:"htmlall":"UTF-8"}</h6>
            {$an_staticblock->content nofilter}            
        </div>
    </a>   
</div>