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
<div id="js-product-list-top" class="row products-selection">
  <div class="col-md-6 hidden-sm-down total-products">
    {if $listing.pagination.total_items > 1}
      <p>{l s='There are %product_count% products.' d='Shop.Theme.Catalog' sprintf=['%product_count%' => $listing.pagination.total_items]}</p>
    {else if $listing.pagination.total_items > 0}
      <p>{l s='There is 1 product.' d='Shop.Theme.Catalog'}</p>
    {/if}
  </div>
  <div class="col-md-6 filters-toggler-block">
    <div class="sort-by-row">

      {block name='sort_by'}
        {include file='catalog/_partials/sort-orders.tpl' sort_orders=$listing.sort_orders}
      {/block}

      {if !empty($listing.rendered_facets)}
        <div class="hidden-md-up filter-button">
          <button id="search_filter_toggler" class="btn btn-secondary">
              <svg 
              xmlns="http://www.w3.org/2000/svg"
              xmlns:xlink="http://www.w3.org/1999/xlink"
              width="16px" height="4px">
             <image  x="0px" y="0px" width="16px" height="4px"  xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAECAMAAACwak/eAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAASFBMVEUAAAAmIyQmIyQmIyQmJCQmJCQmIyQmIyQmJCQmJCUmJCQmIyQmJCUmIyQmIyQlIyQlIyQmJCQmIyQmJCUmIyQmJCQmJCX///8Qy8P+AAAAFHRSTlMAX/PXIT7oIV/vksHBku+S7/M+6A9vNoEAAAABYktHRBcL1piPAAAAB3RJTUUH4wofECIkmVmdZQAAAD1JREFUCNcly1sOgCAMRcFTKqCg+Lp2/0s1xt9JBks+QS4lQ/U0s0gN+hMrbBGDIe0fqENTHNjpFa5y/8VeQ58CGrPKWTsAAAAASUVORK5CYII=" />
             </svg>
            {l s='Show filter' d='Shop.Theme.Actions'}
          </button>
        </div>
      {/if}
    </div>
  </div>

<div class="col-md-6 collection-view">
    <div class="collection-view-btn
    {if isset($smarty.get.view)}
      {if $smarty.get.view == 'col2'}
        active
      {/if}
    {else}
    {if isset($smarty.cookies.an_collection_view)}
      {if $smarty.cookies.an_collection_view == 6}
        active
      {/if}
    {elseif Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 'col-xs-6'}
      active
    {/if}{/if}" data-xl="6">
      <div class="view-type view-type-3"></div>
    </div>
    <div class="collection-view-btn
    {if isset($smarty.get.view)}
      {if $smarty.get.view == 'col3'}
        active
      {/if}
    {else}
    {if isset($smarty.cookies.an_collection_view)}
      {if $smarty.cookies.an_collection_view == 4}
        active
      {/if}
    {elseif Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 'col-xs-4'}
      active
    {/if}{/if}" data-xl="4">
        <div class="view-type view-type-2"></div>
    </div>
    <div class="collection-view-btn
    {if isset($smarty.get.view)}
      {if $smarty.get.view == 'col4'}
        active
      {/if}
    {else}
    {if isset($smarty.cookies.an_collection_view)}
      {if $smarty.cookies.an_collection_view == 3}
        active
      {/if}
    {elseif Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 'col-xs-3'}
      active
    {/if}{/if}" data-xl="3">
        <div class="view-type view-type-1"></div>
    </div>
    <div class="collection-view-btn
    {if isset($smarty.get.view)}
      {if $smarty.get.view == 'row'}
        active
      {/if}
    {else}
    {if isset($smarty.cookies.an_collection_view)}
      {if $smarty.cookies.an_collection_view == 12}
        active
      {/if}
    {elseif Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('categoryPage_productsAmount') == 'col-xs-12'}
      active
    {/if}{/if}" data-xl="12">
        <div class="view-type view-type-4"></div>
    </div>
  </div>
  <div class="col-sm-6 showing">
    <div class="showing-text">
    {l s='Showing %from%-%to% of %total% item(s)' d='Shop.Theme.Catalog' sprintf=[
    '%from%' => $listing.pagination.items_shown_from ,
    '%to%' => $listing.pagination.items_shown_to,
    '%total%' => $listing.pagination.total_items
    ]}
    </div>
  </div>
</div>
