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

class WkDonationDisplayPlaces extends ObjectModel
{
    const WK_DONATION_HOOK_HOME = 1;
    const WK_DONATION_HOOK_FOOTER = 3;
    const WK_DONATION_HOOK_LEFT= 4;
    const WK_DONATION_HOOK_RIGHT = 5;

    const WK_DONATION_PRODUCT_PAGE = 1;
    const WK_DONATION_HOME_PAGE = 2;
    const WK_DONATION_CATEGORY_PAGE = 3;
    const WK_DONATION_CART_PAGE = 4;

    public $donationDisplayPages = array();

    public function __construct()
    {
        $this->donationDisplayPages = array(
            'product' => self::WK_DONATION_PRODUCT_PAGE,
            'index' => self::WK_DONATION_HOME_PAGE,
            'category' => self::WK_DONATION_CATEGORY_PAGE,
            'cart' => self::WK_DONATION_CART_PAGE,
        );
    }

    public static function getDonationPages()
    {
        $moduleInstance = new WkCharityDonation();

        $pages = array(
            'homepage' => array(
                'id_page' => WkDonationDisplayPlaces::WK_DONATION_HOME_PAGE,
                'name' => $moduleInstance->l('Home page', 'WkDonationDisplayPlaces')
            ),
            'product' => array(
                'id_page' => WkDonationDisplayPlaces::WK_DONATION_PRODUCT_PAGE,
                'name' => $moduleInstance->l('Product page', 'WkDonationDisplayPlaces')
            ),
            'category_page' => array(
                'id_page' => WkDonationDisplayPlaces::WK_DONATION_CATEGORY_PAGE,
                'name' => $moduleInstance->l('Category page', 'WkDonationDisplayPlaces')
            ),
            'cart_page' => array(
                'id_page' => WkDonationDisplayPlaces::WK_DONATION_CART_PAGE,
                'name' => $moduleInstance->l('Cart page', 'WkDonationDisplayPlaces')
            )
        );
        return $pages;
    }

    public static function getDonationHooks()
    {
        $moduleInstance = new WkCharityDonation();
        $hooks = array(
            'home_hook' => array(
                'id_hook' => WkDonationDisplayPlaces::WK_DONATION_HOOK_HOME,
                'name' => $moduleInstance->l('Header', 'WkDonationDisplayPlaces')
            ),
            'footer_hook' => array(
                'id_hook' => WkDonationDisplayPlaces::WK_DONATION_HOOK_FOOTER,
                'name' => $moduleInstance->l('Footer', 'WkDonationDisplayPlaces')
            ),
            'left_hook' => array(
                'id_hook' => WkDonationDisplayPlaces::WK_DONATION_HOOK_LEFT,
                'name' => $moduleInstance->l('Left', 'WkDonationDisplayPlaces')
            ),
            'right_hook' => array(
                'id_hook' => WkDonationDisplayPlaces::WK_DONATION_HOOK_RIGHT,
                'name' => $moduleInstance->l('Right', 'WkDonationDisplayPlaces')
            ),
        );
        return $hooks;
    }

    public function getDonationPagesByIdDonation($donationInfoId)
    {
        return Db::getInstance()->executeS(
            'SELECT DISTINCT `id_page` FROM `'._DB_PREFIX_.'wk_donation_display_places`
            WHERE `id_donation_info` = '.(int) $donationInfoId
        );
    }
    public function getDonationHooksByIdPage($donationInfoId, $idPage)
    {
        $result = Db::getInstance()->executeS(
            'SELECT `id_hook` FROM `'._DB_PREFIX_.'wk_donation_display_places` WHERE `id_page` = '.
            (int) $idPage.' AND `id_donation_info` = '.(int) $donationInfoId
        );
        return array_column($result, 'id_hook');
    }

    public function getDonationHooksByIdDonation($idDonationInfo)
    {
        return Db::getInstance()->executeS(
            'SELECT `id_hook` FROM `'._DB_PREFIX_.'wk_donation_display_places`
            WHERE `id_donation_info` = '.(int) $idDonationInfo
        );
    }
    public function deleteDonationHooks($idDonationInfo, $idHook)
    {
        return Db::getInstance()->delete(
            'wk_donation_display_places',
            'id_donation_info = '.(int)$idDonationInfo.' AND `id_hook` = '.(int) $idHook
        );
    }
    public function insertDonationHooks($idDonationInfo, $idHook, $idPage)
    {
        return Db::getInstance()->insert(
            'wk_donation_display_places',
            array(
                'id_donation_info' => (int) $idDonationInfo,
                'id_page' => (int) $idPage,
                'id_hook'=> (int) $idHook,
                'date_add'=> pSQL(date('Y-m-d'))
            )
        );
    }

    public function displayDonationsAdvertisement($hookId)
    {
        $currentPage = Tools::getValue('controller');
        if (isset($this->donationDisplayPages[$currentPage])) {
            $currentPageId = $this->donationDisplayPages[$currentPage];
            $context = Context::getContext();
            $idLang = $context->language->id;
            $objDonationInfo = new WkDonationInfo();
            $advertisementDonationInfo = $objDonationInfo->getDonationsByHook($hookId, $currentPageId, $idLang);
            if ($advertisementDonationInfo) {
                foreach ($advertisementDonationInfo as &$donationInfo) {
                    if ($donationInfo['product_visibility']) {
                        if (!$donationInfo['is_global']) {
                            $donationInfo['button_link'] = $context->link->getProductLink(
                                $donationInfo['id_product']
                            );
                        } else {
                            $donationInfo['button_link'] = $context->link->getCategoryLink(
                                Configuration::get('WK_DONATION_ID_CATEGORY')
                            );
                        }
                    }
                    if ($hookId == self::WK_DONATION_HOOK_FOOTER
                        || $hookId == self::WK_DONATION_HOOK_HOME
                    ) {
                        $donationInfo['image_path'] = _MODULE_DIR_.'wkcharitydonation/views/img/banner/'.
                        $donationInfo['id_donation_info'].'-head-foot.jpg';
                    } elseif ($hookId == self::WK_DONATION_HOOK_LEFT
                        || $hookId == self::WK_DONATION_HOOK_RIGHT
                    ) {
                        $donationInfo['image_path'] = _MODULE_DIR_.'wkcharitydonation/views/img/banner/'.
                        $donationInfo['id_donation_info'].'-left-right.jpg';
                    }
                }
                return $advertisementDonationInfo;
            }
        }
        return false;
    }
}
