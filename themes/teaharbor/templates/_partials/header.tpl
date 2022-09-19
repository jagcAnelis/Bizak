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
<div class="header-position">
  {if $an_width_on_mobile =='992'}

  {block name='header_banner'}
    <div class="header-banner">
      {hook h='displayBanner'}
    </div>
  {/block}


  {block name='header_nav'}
    <nav class="header-nav tablet-h" >
      <div class="container">
          <div class="row">
            <div class="hidden-md-down header-nav-inside vertical-center">
              <div class="col-md-6 col-xs-12" >
                {hook h='displayNav1'}
              </div>
              <div class="col-md-6 right-nav">
                  {hook h='displayNav2'}
              </div>

            </div>
            <div class="hidden-lg-up text-sm-center mobile">
              <div class="float-xs-left" id="menu-icon">
                <i class="material-icons d-inline">&#xE5D2;</i>
              </div>
              <div class="float-xs-right" id="_mobile_cart"></div>
              <div class="top-logo" id="_mobile_logo"></div>
              <div class="clearfix"></div>
            </div>
          </div>
      </div>
    </nav>
  {/block}

  {block name='header_top'}
    <div class="header-top tablet-h" data-mobilemenu='{$an_width_on_mobile}'>
      {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') !== 'header4'))}
      <div class="container">
      {/if}       
        <div class="{if (Module::isEnabled('an_theme') and Module::getInstanceByName('an_theme')->getParam('header_typeHeader') !== 'header4')}row 
           {/if}vertical-center header-top-wrapper">
            {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header4'))}
            <div class="div_top_left container">
            {/if}
            <div class="col-md-3 left-col"></div>
            {hook h='displayTopLeft'}
            {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') == 'header4'))}
            </div>
            {/if}
          {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') !== 'header4'))}
          <div class="position-static">
            <div class="vertical-center">
          {/if} 
              {hook h='displayTop'}
              <div class="clearfix"></div>
          {if (Module::isEnabled('an_theme') and (Module::getInstanceByName('an_theme')->getParam('header_typeHeader') !== 'header4'))}
            </div>
          </div>
          {/if}
        </div>
        
       <div class="amegamenu_mobile-cover"></div>
            <div class="amegamenu_mobile-modal">
              <div id="mobile_top_menu_wrapper" class="row hidden-lg-up" data-level="0">
                <div class="mobile-menu-header">
                  <div class="megamenu_mobile-btn-back">
                      <svg
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      width="26px" height="12px">
                     <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                      d="M25.969,6.346 C25.969,5.996 25.678,5.713 25.319,5.713 L3.179,5.713 L7.071,1.921 C7.324,1.673 7.324,1.277 7.071,1.029 C6.817,0.782 6.410,0.782 6.156,1.029 L1.159,5.898 C0.905,6.145 0.905,6.542 1.159,6.789 L6.156,11.658 C6.283,11.782 6.447,11.844 6.616,11.844 C6.785,11.844 6.949,11.782 7.076,11.658 C7.330,11.411 7.330,11.014 7.076,10.767 L3.184,6.975 L25.329,6.975 C25.678,6.975 25.969,6.697 25.969,6.346 Z"/>
                     </svg>
                  </div>
                  <div class="megamenu_mobile-btn-close">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="16px" height="16px">
                    <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                    d="M16.002,0.726 L15.274,-0.002 L8.000,7.273 L0.725,-0.002 L-0.002,0.726 L7.273,8.000 L-0.002,15.274 L0.725,16.002 L8.000,8.727 L15.274,16.002 L16.002,15.274 L8.727,8.000 L16.002,0.726 Z"/>
                    </svg>
                  </div>

                </div>
                  <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
                  <div class="js-top-menu-bottom">
                    <div class="mobile-menu-fixed">
                      {hook h='displayMobileMenu'}

                      <div id="_mobile_an_wishlist-nav"></div>

                      <div class="mobile-lang-and-cur">
                        <div id="_mobile_currency_selector"></div>
                        <div id="_mobile_language_selector"></div>
                        <div  id="_mobile_user_info"></div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
    {hook h='displayNavFullWidth'}
  {/block}



  {else}
    

  {block name='header_banner'}
    <div class="header-banner">
      {hook h='displayBanner'}
    </div>
  {/block}


  {block name='header_nav'}
    <nav class="header-nav tablet-v">
      <div class="container">
          <div class="row">
            <div class="hidden-sm-down header-nav-inside vertical-center">
              <div class="col-md-4 col-xs-12" >
                {hook h='displayNav1'}
              </div>
              <div class="col-md-8 right-nav">
                  {hook h='displayNav2'}
              </div>
            </div>
            <div class="hidden-md-up text-sm-center mobile">
              <div class="float-xs-left" id="menu-icon">
                <i class="material-icons d-inline">&#xE5D2;</i>
              </div>
              <div class="float-xs-right" id="_mobile_cart"></div>
              <div class="top-logo" id="_mobile_logo"></div>
              <div class="clearfix"></div>
            </div>
          </div>
      </div>
    </nav>
  {/block}

  {block name='header_top'}
    <div class="header-top tablet-v" data-mobilemenu='{$an_width_on_mobile}'>
      <div class="container">
        <div class="row vertical-center header-top-wrapper">
         {hook h='displayTopLeft'}
          <div class="col-md-12 col-xs-12 position-static">
            <div class="row vertical-center">
              {hook h='displayTop'}
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
       <div class="amegamenu_mobile-cover"></div>
            <div class="amegamenu_mobile-modal">
              <div id="mobile_top_menu_wrapper" class="row hidden-lg-up" data-level="0">
                <div class="mobile-menu-header">
                  <div class="megamenu_mobile-btn-back">
                      <svg
                      xmlns="http://www.w3.org/2000/svg"
                      xmlns:xlink="http://www.w3.org/1999/xlink"
                      width="26px" height="12px">
                     <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                      d="M25.969,6.346 C25.969,5.996 25.678,5.713 25.319,5.713 L3.179,5.713 L7.071,1.921 C7.324,1.673 7.324,1.277 7.071,1.029 C6.817,0.782 6.410,0.782 6.156,1.029 L1.159,5.898 C0.905,6.145 0.905,6.542 1.159,6.789 L6.156,11.658 C6.283,11.782 6.447,11.844 6.616,11.844 C6.785,11.844 6.949,11.782 7.076,11.658 C7.330,11.411 7.330,11.014 7.076,10.767 L3.184,6.975 L25.329,6.975 C25.678,6.975 25.969,6.697 25.969,6.346 Z"/>
                     </svg>
                  </div>
                  <div class="megamenu_mobile-btn-close">
                    <svg
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="16px" height="16px">
                    <path fill-rule="evenodd"  fill="rgb(0, 0, 0)"
                    d="M16.002,0.726 L15.274,-0.002 L8.000,7.273 L0.725,-0.002 L-0.002,0.726 L7.273,8.000 L-0.002,15.274 L0.725,16.002 L8.000,8.727 L15.274,16.002 L16.002,15.274 L8.727,8.000 L16.002,0.726 Z"/>
                    </svg>
                  </div>

                </div>
                  <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
                  <div class="js-top-menu-bottom">
                    <div class="mobile-menu-fixed">
                      {hook h='displayMobileMenu'}

                      <div id="_mobile_an_wishlist-nav"></div>

                      <div class="mobile-lang-and-cur">
                        <div id="_mobile_currency_selector"></div>
                        <div id="_mobile_language_selector"></div>
                        <div  id="_mobile_user_info"></div>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
    {hook h='displayNavFullWidth'}
  {/block}

  {/if}  
</div>