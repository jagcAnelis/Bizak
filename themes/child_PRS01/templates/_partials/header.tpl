{**
 * 2007-2018 PrestaShop
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
 * @copyright 2007-2018 PrestaShop SA
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{block name='header_banner'}
    <div class="header-banner">
        {hook h='displayBanner'}
    </div>
{/block}

{block name='header_nav'}
    <nav class="hidden-md-up header-nav">
        <div class="hidden-md-up text-sm-center mobile" style="margin-top: 0!important;">
            <div class="float-xs-left" id="menu-icon">
                <img src="/themes/child_PRS01/assets/img/red/menu-open.svg" alt="menu-button"/>
            </div>
            <div class="float-xs-right" id="_mobile_cart"></div>
            <div class="float-xs-right" id="_mobile_user_info"></div>
            <div class="top-logo" id="">
                <a href="{$urls.base_url}">
                    <img class="logo img-responsive" width="79" height="56" src="/themes/child_PRS01/assets/img/red/shop.jpg" alt="{$shop.name}">
                </a>
            </div>
            <div class="clearfix"></div>
            <div id="top_section_mobile">
                {hook h='displayTop'}
            </div>
        </div>
    </nav>
{/block}

{block name='header_top'}
    <div class="header-top hidden-sm-down">
        <div class="container" id="container-header-top">
            <div class="position-static">
                <div class="hidden-sm-down" id="_desktop_logo">
                    <a href="{$urls.base_url}">
                        <img class="logo img-responsive" width="79" height="57" src="{$shop.logo}" alt="{$shop.name}">
                    </a>
                </div>
                <div>
                    <div class="hidden-sm-down" id="top_section_desktop">
                        {hook h='displayTop'}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header-marquee">
        <div class="container marquee" style="overflow: hidden">
            {hook h='displayTopColumn'}
        </div>
    </div>

    <div id="mobile_top_menu_wrapper" class="row hidden-md-up" style="display: none;">
        <div class="mobile-menu-head">
            <img onclick="toggleMobileMenu()" src="/themes/child_PRS01/assets/img/red/close.png" alt="close menu"/>
            <img onclick="goToList()" class="go-back" src="/themes/child_PRS01/assets/img/red/left.png" alt="go-back "/>
            <div class="mobile-menu-title">MEN??</div>
        </div>
        <div class="js-top-menu mobile" id="_mobile_top_menu"></div>
    </div>

    {hook h='displayNavFullWidth'}
{/block}

{literal}
    <script type="text/javascript">
        function toggleMobileMenu(){ $("#mobile_top_menu_wrapper").toggle(); }
        function goToList(){
            $('#top-menu li.selected .sub-menu').removeClass('in');
            $('.mobile-menu-title').text('Men??');
            $('.mobile-menu-head').removeClass('selected-category');

            $('.top-menu').removeClass('selected-category');
            $('.top-menu li').removeClass('selected');
        }

        function startMarquee(){
            $('.marquee').marquee({
                //duration in milliseconds of the marquee
                duration: 15000,
                //gap in pixels between the tickers
                gap: 50,
                //time in milliseconds before the marquee will start animating
                delayBeforeStart: 0,
                //'left' or 'right'
                direction: 'left',
                //true or false - should the marquee be duplicated to show an effect of continues flow
                duplicated: true
            });
        }
        function defer(method) {
            if (window.jQuery && $('.marquee').marquee) {
                method();
            } else {
                setTimeout(function() { defer(method) }, 50);
            }
        }
        defer(startMarquee);
    </script>
{/literal}
