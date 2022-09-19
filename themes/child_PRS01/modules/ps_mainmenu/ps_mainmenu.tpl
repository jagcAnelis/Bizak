{assign var=_counter value=0}
{function name="menu" nodes=[] depth=0 parent=null}
    {if $nodes|count}
      <ul class="top-menu" {if $depth == 0}id="top-menu"{/if} data-depth="{$depth}">
        {foreach from=$nodes item=node}
            <li class="{$node.type}{if $node.current} current {/if}" id="{$node.page_identifier}">
            {assign var=_counter value=$_counter+1}
              <a
                class="{if $depth >= 0}dropdown-item{/if}{if $depth === 1} dropdown-submenu{/if}"
                data-depth="{$depth}"
                href="{$node.url}"
                {if $depth === 0} onclick="selectCategoryFromName(this, event)" {/if}

                {if $node.open_in_new_window} target="_blank" {/if}
              >
                {if $node.children|count}
                  {* Cannot use page identifier as we can have the same page several times *}
                  {assign var=_expand_id value=10|mt_rand:100000}
                  <span class="float-xs-right hidden-md-up custom-menu-toggler"">
                    <span class="navbar-toggler collapse-icons">
                      <img src="/themes/child_PRS01/assets/img/red/mobile-menu-dropdown.svg" alt="select category">
                    </span>
                  </span>
                {/if}
                {$node.label}
              </a>
              {if $node.children|count}
              <div {if $depth === 0} class="popover sub-menu js-sub-menu collapse"{else} class="collapse"{/if} id="top_sub_menu_{$_expand_id}">
					{menu nodes=$node.children depth=$node.depth parent=$node}
					{if $node.image_urls|count}
						<div class="menu-images-container">
							{foreach from=$node.image_urls item=image_url}
								<img src="{$image_url}" alt="">
							{/foreach}
						</div>
					{/if}
              </div>
              {/if}
            </li>
        {/foreach}
		
			{if $depth == 0}
			{if Module::isInstalled("smartblog") && Module::isInstalled("smartbloghomelatestnews") && (Module::isEnabled("smartblog")) && (Module::isEnabled("smartbloghomelatestnews"))}
			<li class="ttblog">
			<a class="dropdown-item" href="{$link->getPageLink('smartblog')|escape:'html':'UTF-8'}" data-depth="{$depth}">{l s='blog' d='Shop.Theme.Global'}</a>
			</li>
			{/if}
			{/if}

      </ul>
    {/if}
{/function}

<div class="topmenu">
	<div class="menu container js-top-menu position-static hidden-sm-down" id="_desktop_top_menu">
		{menu nodes=$menu.children}
		<div class="clearfix"></div>
	</div>
</div>

{literal}
    <script type="text/javascript">
        function selectCategoryFromName(el, evn){
            if ($(window).width() < 992) {
                $('.top-menu').addClass('selected-category');

                $(el).parent().addClass('selected');
                $('.mobile-menu-head').addClass('selected-category');
                var categoryName = $(el).text();
                $(el).siblings('.popover').addClass('in');
                $('.mobile-menu-title').text(categoryName);
                evn.preventDefault();
            }

        }
    </script>
{/literal}
