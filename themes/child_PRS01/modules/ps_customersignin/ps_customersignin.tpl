<div id="_desktop_user_info">
    <div class="ttuserheading"></div>
    <ul class="user-info">
        {if $logged}
            <li><a
                        class="logout hidden-sm-down"
                        href="{$logout_url}"
                        rel="nofollow"
                >
                    <i class="material-icons user">&#xE7FF;</i>
                    <span class="hidden-sm-down">{l s='Sign out' d='Shop.Theme.Actions'}</span>
                </a></li>
            <li><a
                        class="account"
                        href="{$my_account_url}"
                        title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
                        rel="nofollow"
                >
                    <i class="material-icons hidden-md-up logged">&#xE7FF;</i>
                    <span class="hidden-sm-down">{$customerName}</span>
                </a></li>
        {else}
            <li><a
                        href="{$my_account_url}"
                        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
                        rel="nofollow"
                >
                    <i class="material-icons user">&#xE7FF;</i>
                    <span class="hidden-sm-down">{l s='Sign in' d='Shop.Theme.Actions'}</span>
                </a></li>
        {/if}

        {hook h='displayTtCompareHeader'}
        {hook h='displayTtWishlistHeader'}
    </ul>
</div>
