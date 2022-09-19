<?php
/**
* 2010-2021 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through LICENSE.txt file inside our module
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to CustomizationPolicy.txt file inside our module for more information.
*
* @author Webkul IN
* @copyright 2010-2021 Webkul IN
* @license LICENSE.txt
*/

/*require_once dirname(__FILE__).'/classes/WkCharityDonationDb.php';
require_once dirname(__FILE__).'/classes/WkDonationInfo.php';
require_once dirname(__FILE__).'/classes/WkDonationDisplayPlaces.php';

if (!defined('_PS_VERSION_')) {
    exit;
}*/

class WkCharityDonationOverride extends WkCharityDonation
{

    public function hookDisplayOverrideTemplate($params)
    {
        if (('checkout/cart' === $params['template_file']) || ('checkout/cart-empty' === $params['template_file'])) {
            $objDonationInfo = new WkDonationInfo();
            if ($idDonationInfo = $objDonationInfo->getCheckoutDonations($this->context->shop->id)) {
                $checkoutDonations = array();
                foreach ($idDonationInfo as $idCheckoutDonation) {
                    $objCheckoutdonation = new WkDonationInfo($idCheckoutDonation['id_donation_info']);
                    $objCheckoutdonation->price = Tools::ps_round(
                        Tools::convertPrice($objCheckoutdonation->price),
                        Configuration::get('PS_PRICE_DISPLAY_PRECISION')
                    );
                    $objCheckoutdonation->link = $this->context->link->getProductLink($objCheckoutdonation->id_product);
                    $objCheckoutdonation->displayPrice = Tools::displayprice($objCheckoutdonation->price);
                    $checkoutDonations[] = (array) $objCheckoutdonation;
                }
                $columns = '0';
                if ('layout-full-width' == $this->context->shop->theme->getLayoutNameForPage('cart')) {
                    $columns = '1';
                }
                $this->context->smarty->assign(array(
                    'checkoutDonations' => $checkoutDonations,
                    'id_current_lang' => $this->context->language->id,
                    'currency_sign' => $this->context->currency->sign,
                    'cart_url' => $this->context->link->getPageLink('cart').'?action=show',
                    'columnLayout' => $columns,
                ));

                if ('checkout/cart-emptyl' === $params['template_file']) {
                    return 'module:wkcharitydonation/views/templates/front/checkout-donation-cart-empty.tpl';
                    /*return dirname(__FILE__).'/views/templates/front/checkout-donation.tpl';*/
                    /*return _PS_MODULE_DIR_.'WkCharityDonation/views/templates/front/checkout-donation.tpl';*/
                    
                } else{
                    return 'module:wkcharitydonation/views/templates/front/checkout-donation.tpl';
                    /*return dirname(__FILE__).'checkout-donation-cart-empty.tpl';*/
                    
                }
            }
        }
        if ('catalog/_partials/quickview' == $params['template_file']) {
            if ($idProduct = Tools::getValue('id_product')) {
                if (WkDonationInfo::isDonationProduct($idProduct)) {
                    $this->context->smarty->assign(array(
                        'isDonationProduct' => 1,
                    ));

                    /*return dirname(__FILE__).'/views/templates/front/product-donation-add-to-cart-override.tpl';*/
                    return 'module:wkcharitydonation/views/templates/front/product-donation-add-to-cart-override.tpl';
                }
            }
        }
    }

}
