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

class WkCharityDonationValidateDonationModuleFrontController extends ModuleFrontController
{
    public function displayAjaxCheckMinimumPrice()
    {
        $result = array();
        $result['status'] = 0;
        $result['errors'] = array();

        if (!$this->isTokenValid()) {
            $result['errors'][] = $this->module->l('Unauthorised access', 'validatedonation');
        } elseif (!$idDonationInfo = Tools::getValue('id_donation')) {
            $result['errors'][] = $this->module->l('Donation information not found', 'validatedonation');
        } elseif (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo($idDonationInfo))) {
            if ($objDonationInfo->price_type == WkDonationInfo::WK_DONATION_PRICE_TYPE_CUSTOMER) {
                $price = Tools::getValue('donation_price');
                if ((Validate::isUnsignedInt($price) || Validate::isUnsignedFloat($price)) && $price <= 0) {
                    $result['errors'] = $this->module->l('Donation amount must be greater than zero', 'validatedonation');
                } elseif (empty($price)) {
                    $result['errors'] = $this->module->l('Donation amount must not be empty', 'validatedonation');
                } elseif (Validate::isPrice($price)) {
                    $donationPrice = Tools::convertPrice($objDonationInfo->price);
                    $precision = Configuration::get('PS_PRICE_DISPLAY_PRECISION');
                    if (Tools::ps_round($donationPrice, $precision) > $price) {
                        if ($idSpecificPrice = $objDonationInfo->checkExistingSpecificPrice(
                            $objDonationInfo->id_product,
                            $this->context->customer->id,
                            $this->context->cart->id
                        )) {
                            $objSpecificPrice = new SpecificPrice($idSpecificPrice);
                            $specificPrice = Tools::convertPrice($objSpecificPrice->price);
                            $newPrice = $price + $specificPrice;
                            if (Tools::ps_round($donationPrice, $precision) > $newPrice) {
                                $result['errors'][] = sprintf(
                                    $this->module->l('Donation amount should not be less than %s', 'validatedonation'),
                                    Tools::displayprice($donationPrice)
                                );
                            }
                        } else {
                            $result['errors'][] = sprintf(
                                $this->module->l('Donation amount should not be less than %s', 'validatedonation'),
                                Tools::displayprice($donationPrice)
                            );
                        }
                    }
                } else {
                    $result['errors'][] = $this->module->l('Invalid donation amount', 'validatedonation');
                }
            } elseif ($objDonationInfo->price_type == WkDonationInfo::WK_DONATION_PRICE_TYPE_FIXED) {
                $price = Tools::convertPriceFull($objDonationInfo->price, null, $this->context->currency);
            }
            if (empty($result['errors'])) {
                if (isset($this->context->cart->id) && $this->context->cart->id) {
                    $objCart = new Cart($this->context->cart->id);
                } else {
                    $objCart = new Cart();
                    $objCart->id_customer = (int)($this->context->cookie->id_customer);
                    $objCart->id_lang = (int)($this->context->cookie->id_lang);
                    $objCart->id_currency = (int)($this->context->cookie->id_currency);
                    $objCart->id_carrier = 1;
                    $objCart->recyclable = 0;
                    $objCart->gift = 0;
                    $objCart->add();
                    $this->context->cart->id = (int)$objCart->id;
                }

                if ($objCart->getProductQuantity($objDonationInfo->id_product)) {
                    $objCart->deleteProduct($objDonationInfo->id_product, 0);
                }
                $objDonationInfo->setSpecificPrice(
                    $objDonationInfo->id_product,
                    $price / $this->context->currency->conversion_rate
                );
                if (Tools::getValue('addProduct') == 1) {
                    if (!$this->context->cart->updateQty(
                        1,
                        $objDonationInfo->id_product,
                        null,
                        null,
                        'up',
                        0,
                        new Shop($this->context->cart->id_shop)
                    )) {
                        $result['errors'][] = $this->module->l('Some error occurred in donation process. Please try again.', 'validatedonation');
                    }
                }
            }
        } else {
            $result['errors'][] = $this->module->l('Donation information not found', 'validatedonation');
        }
        if (!count($result['errors'])) {
            $result['status'] = 1;
        }
        $this->ajaxDie(json_encode($result));
    }
}
