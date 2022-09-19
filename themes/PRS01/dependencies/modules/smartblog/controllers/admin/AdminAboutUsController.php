<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class AdminAboutUsController extends ModuleAdminController
{

    public $asso_type = 'shop';
    public function __construct()
    {
        $this->module = 'smartblog';
        $this->lang = true;
        $this->context = Context::getContext();
        $this->bootstrap = true;

        parent::__construct();
    }

    public function ajaxProcessGetSmartThemes()
    {
        if (@fsockopen('referals.smartdatasoft.com', 80, $errno, $errst, 3)) {
            @readfile('http://referals.smartdatasoft.com/adminthemes.php?lang='.$this->context->language->iso_code);
        }
    }

    public function initContent()
    {
        $htm = '<p id="smartextra"><img src="http://referals.smartdatasoft.com/images/logo.png"><b>Smartdatasoft is a Prestashop certified and authorized 
            Prestashop partner developing brand. With some of very outstanding Prestashop project like-
            opensource blog module, Revolution Slider and others; it is already a name of trust and quality among web clients. With continuous Total Quality Management (TQM) philosophy, inventive creativity 
            and reliable after sales service concept, Smartdatasoft is trying to bring a difference among marketplace.</b></p>
            <p  id="smartextra"><a href="http://www.prestashop.com/en/web-agency-partners/bronze/smartdatasoft"><img src="http://smartdatasoft.com/envato-add/prestashop_partner.png"></a>
                <a href="http://themeforest.net/user/smartdatasoft/?ref=smartdatasoft"><img src="http://smartdatasoft.com/envato-add/mecror-prestashop/envato-branding-envato1.png"></a>
                <a href="http://addons.prestashop.com/en/69_smartdatasoft"><img height="56" width="305" src="http://medias1.prestastore.com/themes/prestastore/img/logo_addons.png"></a>
                <a href="http://facebook.com/pages/SmartDataSoft/332747343429694"><img src="http://smartdatasoft.com/envato-add/mecror-prestashop/envato-branding-facebook1.png"></a> <a href="http://twitter.com/smartdatasoft">
                <img src="http://smartdatasoft.com/envato-add/mecror-prestashop/envato-branding-twitter1.png"></a>
            </p>';

        $this->content = $htm.'<fieldset class="width3" id="smartdatasoft-content">
            <style>               
                #smartdatasoft-content { 
                    padding: 0;
                    width: 99%;
                    padding-left: 1.3em;
                    background: white;
                }
                #smartdatasoft-content #smartextra{
                    display:none;
                }
                #smartextra img{
                    vertical-align: bottom;
                }
                p{
                    verticle-align:bottom;
                }
                .bootstrap h3, .bootstrap .h3 {
                    font-size: 13px;
                }
            </style></fieldset>
            <script type="text/javascript">
                $.post(
                "ajax-tab.php",
                {
                    tab: \'AdminAboutUs\',
                    token: \''.$this->token.'\',
                    ajax: \'1\',
                    action:\'GetSmartThemes\',
                    page:\'themes\'
                }, function(a){
                    $("#smartdatasoft-content").html("<legend><img src=\'../img/admin/prestastore.gif\' class=\'middle\' />Live from SmartDataSoft Addons!</legend>"+a);
                });
            </script>';
        return parent::initContent();
    }
}
