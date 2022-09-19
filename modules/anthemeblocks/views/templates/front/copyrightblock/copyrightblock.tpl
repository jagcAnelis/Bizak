<div class="an_copyright">
    {if $an_staticblock->link!=''}
    <a href="{$an_staticblock->link}">
    {/if}
    {str_replace('{year}', date('Y'), $an_staticblock->content) nofilter}
    {if $an_staticblock->link != '' }
    </a>
    {/if}
</div>