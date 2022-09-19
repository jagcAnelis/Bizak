{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<!-- Block search module TOP -->
<!-- && $page.page_name !== 'pagenotfound' -->

<div id="_desktop_search">
{if ($page.page_name == 'search' && $listing.products|count !== 0) || ($page.page_name !== 'pagenotfound' && $page.page_name !== 'search')}
{if (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') !== 'header3')}

<svg class="search-icon open-icon"
    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path class="open-icon-path" d="M90.4,84L75.8,69.5C87.2,54.9,86,34.1,72.9,21c-6.9-6.9-16.1-10.7-25.9-10.7c-9.8,0-19,3.8-25.9,10.7  c-6.9,6.9-10.7,16.1-10.7,25.9c0,9.8,3.8,19,10.7,25.9c6.9,6.9,16.1,10.7,25.9,10.7c8.1,0,16.1-2.7,22.5-7.8L84,90.4  c0.9,0.9,2,1.3,3.2,1.3c1.2,0,2.3-0.5,3.2-1.3C92.2,88.7,92.2,85.8,90.4,84z M74.6,46.9c0,7.4-2.9,14.3-8.1,19.5  c-5.2,5.2-12.2,8.1-19.5,8.1s-14.3-2.9-19.5-8.1c-5.2-5.2-8.1-12.2-8.1-19.5c0-7.4,2.9-14.3,8.1-19.5s12.2-8.1,19.5-8.1  c7.4,0,14.3,2.9,19.5,8.1C71.7,32.6,74.6,39.5,74.6,46.9z"/>
</svg>
{/if}
{/if}

{if (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header3')}
<div id="search_header_3"></div>
{/if}
<div id="search_widget" class="search-widget" data-search-controller-url="{$search_controller_url}">

	<form method="get" action="{$search_controller_url}">
		<input type="hidden" name="controller" value="search">
		<input type="text" name="s" value="{$search_string}" placeholder="{l s='Search our catalog' d='Shop.Theme.Catalog'}" aria-label="{l s='Search' d='Shop.Theme.Catalog'}">
		<button type="submit">
			{if (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header1')
		    or (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header2')
		    or (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header3')
            or (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header4')
            or (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header5')}
	       <svg class="search-icon-in"
	       xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve">
	       <path d="M90.4,84L75.8,69.5C87.2,54.9,86,34.1,72.9,21c-6.9-6.9-16.1-10.7-25.9-10.7c-9.8,0-19,3.8-25.9,10.7  c-6.9,6.9-10.7,16.1-10.7,25.9c0,9.8,3.8,19,10.7,25.9c6.9,6.9,16.1,10.7,25.9,10.7c8.1,0,16.1-2.7,22.5-7.8L84,90.4  c0.9,0.9,2,1.3,3.2,1.3c1.2,0,2.3-0.5,3.2-1.3C92.2,88.7,92.2,85.8,90.4,84z M74.6,46.9c0,7.4-2.9,14.3-8.1,19.5  c-5.2,5.2-12.2,8.1-19.5,8.1s-14.3-2.9-19.5-8.1c-5.2-5.2-8.1-12.2-8.1-19.5c0-7.4,2.9-14.3,8.1-19.5s12.2-8.1,19.5-8.1  c7.4,0,14.3,2.9,19.5,8.1C71.7,32.6,74.6,39.5,74.6,46.9z"/>
	       </svg>
	   {/if}
      <span class="hidden-xl-down">{l s='Search' d='Shop.Theme.Catalog'}</span>
		</button>
	</form>
</div>
</div>
<!-- /Block search module TOP -->