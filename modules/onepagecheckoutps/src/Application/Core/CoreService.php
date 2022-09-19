<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * @author    PresTeamShop SAS (Registered Trademark) <info@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *
 * @category  PrestaShop
 * @category  Module
 */

namespace OnePageCheckoutPS\Application\Core;

use Configuration;
use OnePageCheckoutPS;
use Tools;

class CoreService
{
    private $module;
    private $contextProvider;

    public function __construct(OnePageCheckoutPS $module)
    {
        $this->module = $module;
        $this->contextProvider = $this->module->getContextProvider();
    }

    public function getModule()
    {
        return $this->module;
    }

    public function getCarrierIdSelected()
    {
        $carrierIdSelected = 0;

        $deliveryOptionList = $this->contextProvider->getCart()->getDeliveryOptionList();
        foreach ($this->contextProvider->getCart()->getDeliveryOption() as $key => $value) {
            if (isset($deliveryOptionList[$key][$value])) {
                if (count($deliveryOptionList[$key][$value]['carrier_list']) == 1) {
                    $carrierIdSelected = current(array_keys($deliveryOptionList[$key][$value]['carrier_list']));
                }
            }
        }

        return (int) $carrierIdSelected;
    }

    public function getCommonVars()
    {
        return array(
            'styleModule' => $this->module->getStyleModule(),
            'isVirtualCart' => $this->contextProvider->isVirtualCart(),
            'isLogged' => $this->contextProvider->getCustomer()->isLogged()
                || $this->contextProvider->getCustomer()->isGuest(),
            'isCustomer' => $this->contextProvider->getCustomer()->isLogged(),
            'isGuest' => $this->contextProvider->getCustomer()->isGuest(),
            'isGuestAllowed' => (bool) Configuration::get('PS_GUEST_CHECKOUT_ENABLED'),
            'token' => Tools::getToken(true, $this->contextProvider->getContextLegacy()),
            'shopName' => $this->contextProvider->getShopName(),
            'isMobile' => $this->contextProvider->isMobile(),
        );
    }
}
