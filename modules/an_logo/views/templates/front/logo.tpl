<div class="col-md-2 hidden-sm-down" id="_desktop_logo">
	<a href="{$urls.base_url|escape:'html':'UTF-8'}">
		{if $an_logo_view_type == 'svg' && $an_logo_img}
			<img class="logo img-responsive" src="{$an_logo_img|escape:'html':'UTF-8'}" alt="{$shop.name|escape:'html':'UTF-8'}">
		{elseif $an_logo_view_type == 'svg_text' && $svg_textarea}
			{$svg_textarea nofilter}
		{else}
			<img class="logo img-responsive" src="{$shop.logo|escape:'html':'UTF-8'}" alt="{$shop.name|escape:'html':'UTF-8'}">
		{/if}
	</a>
</div>