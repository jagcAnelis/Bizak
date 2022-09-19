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

class AdminGlobalAdvertisementController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'wk_donation_info';
        $this->identifier = 'id_donation_info';
        $this->className = 'WkDonationInfo';
        parent::__construct();
        $this->toolbar_title = $this->l('Global advertisement configuration');
        $this->_where .= ' AND a.`is_global` = true';
        $this->display = 'add';

        $objDonationInfo = new WkDonationInfo();
        $objDonationInfo->updateDonationExpiry();
    }

    public function renderForm()
    {
        $smartyVars = array();
        $objDonationInfo = $this->loadObject(true);

        $idDonationInfo = WkDonationInfo::getIdGlobalDonation();
        if ($idDonationInfo) {
            $objDonationInfo = new WkDonationInfo($idDonationInfo);
            $smartyVars['donationInfo'] = (array) $objDonationInfo;
        }
        $objDisplayPlaces = new WkDonationDisplayPlaces();
        $donationPages = $objDisplayPlaces->getDonationPagesByIdDonation($objDonationInfo->id);
        $donationHooks = array();
        foreach ($donationPages as $page) {
            $donationHooks[$page['id_page']] = $objDisplayPlaces->getDonationHooksByIdPage(
                $objDonationInfo->id,
                $page['id_page']
            );
        }
        $smartyVars['languages'] = Language::getLanguages(false);
        $currentLangId = Configuration::get('PS_LANG_DEFAULT');
        $smartyVars['currentLang'] = Language::getLanguage((int) $currentLangId);
        $smartyVars['imagePath_head_foot'] = $this->getAdvertisementBannerImagePath(
            $objDonationInfo->id.'-head-foot'
        );
        $smartyVars['imagePath_left_right'] = $this->getAdvertisementBannerImagePath(
            $objDonationInfo->id.'-left-right'
        );

        $this->context->smarty->assign($smartyVars);
        $this->context->smarty->assign(array(
            'pages' => WkDonationDisplayPlaces::getDonationPages(),
            'hooks' => WkDonationDisplayPlaces::getDonationHooks(),
            'donate_hooks' => $donationHooks,
            'ps_img_lang_dir' => _PS_IMG_.'l/',
            'maxSizeAllowed' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
        ));

        $this->fields_form = array(
            'submit' => array(
                'title' => $this->l('Save')
            )
        );
        return parent::renderForm();
    }

    public function getAdvertisementBannerImagePath($imageName)
    {
        $path =  _MODULE_DIR_.$this->module->name.'/views/img/banner/'.$imageName.'.jpg';
        if (file_exists(_PS_MODULE_DIR_.$this->module->name.'/views/img/banner/'.$imageName.'.jpg')) {
            return $path;
        }
        return false;
    }

    public function processSave()
    {
        $defaultLangId = Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $objDefaultLanguage = Language::getLanguage((int) $defaultLangId);
        $active = Tools::getValue('activate_global_donation');
        $showDonateButton = Tools::getValue('show_donate_button');
        $advTitleColor = Tools::getValue('adv_title_color');
        $advDescColor = Tools::getValue('adv_desc_color');
        $buttonTextColor = Tools::getValue('button_text_color');
        $buttonBorderColor = Tools::getValue('button_border_color');
        $donationPageHook = Tools::getValue('page_hook');


        if (!trim(Tools::getValue('advertisement_title_'.$defaultLangId))) {
            $this->errors[] = sprintf(
                $this->l('Advertisement title is required at least in %s'),
                $objDefaultLanguage['name']
            );
        } else {
            foreach ($languages as $language) {
                if (!Validate::isCatalogName(Tools::getValue('advertisement_title_'.$language['id_lang']))) {
                    $this->errors[] = sprintf(
                        $this->l('Invalid advertisement title for the language %s'),
                        $language['name']
                    );
                }
            }
        }
        if (!trim(Tools::getValue('advertisement_description_'.$defaultLangId))) {
            $this->errors[] = sprintf(
                $this->l('Advertisement description is required in at least in %s'),
                $objDefaultLanguage['name']
            );
        } else {
            foreach ($languages as $language) {
                if (!Validate::isCleanHtml(Tools::getValue('advertisement_description_'.$language['id_lang']))
                ) {
                    $this->errors[] = sprintf(
                        $this->l('Invalid advertisement description for the language %s'),
                        $language['name']
                    );
                }
            }
        }
        if ($showDonateButton) {
            if (!trim(Tools::getValue('donate_button_text_'.$defaultLangId))) {
                $this->errors[] = sprintf(
                    $this->l('Donate button text is required at least in %s'),
                    $objDefaultLanguage['name']
                );
            } else {
                foreach ($languages as $language) {
                    if (!Validate::isGenericName(Tools::getValue('donate_button_text_'.$language['id_lang']))) {
                        $this->errors[] = sprintf(
                            $this->l('Invalid donate button text for the language %s'),
                            $language['name']
                        );
                    }
                }
            }
            if (!Validate::isColor($buttonTextColor)) {
                $this->errors[] = $this->l('Donate button text color is invalid');
            }
            if (!Validate::isColor($buttonBorderColor)) {
                $this->errors[] = $this->l('Donate button border color is invalid');
            }
        }
        if (!Validate::isColor($advTitleColor)) {
            $this->errors[] = $this->l('Advertisement title text color is invalid');
        }
        if (!Validate::isColor($advDescColor)) {
            $this->errors[] = $this->l('Advertisement description text color is invalid');
        }

        if (empty($donationPageHook)) {
            $this->errors[] = $this->l('Select at least one place for advertisement');
        } else {
            foreach ($donationPageHook as $donationhook) {
                if (in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_HOME, $donationhook)
                    || in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_FOOTER, $donationhook)) {
                    if ($imgHeadFoot = $_FILES['background_image_head_foot']) {
                        if (empty($imgHeadFoot['name'])) {
                            if (!$this->getAdvertisementBannerImagePath(
                                WkDonationInfo::getIdGlobalDonation().'-head-foot'
                            )) {
                                $this->errors[] = $this->l('Background image is required for header/footer advertisement');
                            }
                        } elseif (!ImageManager::isRealImage($imgHeadFoot['tmp_name'], $imgHeadFoot['type'])
                            || !ImageManager::isCorrectImageFileExt($imgHeadFoot['name'])
                        ) {
                            $this->errors[] = $this->l('Image format not recognized for header/footer advertisement, allowed formats are: .gif, .jpg, .png, .jpeg');
                        }
                    }
                    break;
                }
            }
            foreach ($donationPageHook as $donationhook) {
                if (in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_LEFT, $donationhook)
                    || in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_RIGHT, $donationhook)) {
                    if ($imgLeftRight = $_FILES['background_image_left_right']) {
                        if (empty($imgLeftRight['name'])) {
                            if (!$this->getAdvertisementBannerImagePath(
                                WkDonationInfo::getIdGlobalDonation().'-left-right'
                            )) {
                                $this->errors[] = $this->l('Background image is required for left/right advertisement');
                            }
                        } elseif (!ImageManager::isRealImage($imgLeftRight['tmp_name'], $imgLeftRight['type'])
                                || !ImageManager::isCorrectImageFileExt($imgLeftRight['name'])
                            ) {
                            $this->errors[] = $this->l('Image format not recognized for left/right advertisement, allowed formats are: .gif, .jpg, .png, .jpeg');
                        }
                    }
                    break;
                }
            }
        }

        if (!count($this->errors)) {
            if ($idDonationInfo = WkDonationInfo::getIdGlobalDonation()) {
                $objDonationInfo = new WkDonationInfo($idDonationInfo);
            } else {
                $objDonationInfo = new WkDonationInfo();
            }
            $objDonationInfo->id_product = 0;
            $objDonationInfo->active = $active;
            $objDonationInfo->product_visibility = 1;
            $objDonationInfo->price_type = 0;
            $objDonationInfo->price = 0;
            $objDonationInfo->show_at_checkout = 0;
            $objDonationInfo->auto_add_to_cart = 0;
            $objDonationInfo->advertise = $active;
            $objDonationInfo->expiry_date = 0000-00-00;
            $objDonationInfo->show_donate_button = $showDonateButton;
            $objDonationInfo->adv_title_color = $advTitleColor;
            $objDonationInfo->adv_desc_color = $advDescColor;
            if ($showDonateButton) {
                $objDonationInfo->button_text_color = $buttonTextColor;
                $objDonationInfo->button_border_color = $buttonBorderColor;
            }
            $objDonationInfo->is_global = 1;

            foreach ($languages as $language) {
                $objDonationInfo->name[$language['id_lang']] = $this->l('global donation');
                $objDonationInfo->description[$language['id_lang']] = $this->l('global donation');

                if (Tools::getValue('advertisement_title_'.$language['id_lang'])) {
                    $objDonationInfo->advertisement_title[$language['id_lang']] = Tools::getValue(
                        'advertisement_title_'.$language['id_lang']
                    );
                } else {
                    $objDonationInfo->advertisement_title[$language['id_lang']] =
                        Tools::getValue('advertisement_title_'.$defaultLangId);
                }
                if (Tools::getValue('advertisement_description_'.$language['id_lang'])) {
                    $objDonationInfo->advertisement_description[$language['id_lang']] = Tools::getValue(
                        'advertisement_description_'.$language['id_lang']
                    );
                } else {
                    $objDonationInfo->advertisement_description[$language['id_lang']] =
                        Tools::getValue('advertisement_description_'.$defaultLangId);
                }
                if ($showDonateButton) {
                    if (Tools::getValue('donate_button_text_'.$language['id_lang'])) {
                        $objDonationInfo->donate_button_text[$language['id_lang']] =
                            Tools::getValue('donate_button_text_'.$language['id_lang']);
                    } else {
                        $objDonationInfo->donate_button_text[$language['id_lang']] =
                            Tools::getValue('donate_button_text_'.$defaultLangId);
                    }
                }
            }
            $objDonationInfo->save();
            $idDonationInfo = $objDonationInfo->id;
            ImageManager::resize(
                $_FILES['background_image_head_foot']['tmp_name'],
                _PS_MODULE_DIR_.'wkcharitydonation/views/img/banner/'.$idDonationInfo.'-head-foot.jpg'
            );
            ImageManager::resize(
                $_FILES['background_image_left_right']['tmp_name'],
                _PS_MODULE_DIR_.'wkcharitydonation/views/img/banner/'.$idDonationInfo.'-left-right.jpg'
            );
            // delete previous hooks
            $objDonationDisplayPlace = new WkDonationDisplayPlaces();
            $selectedHooks = $objDonationDisplayPlace->getDonationHooksByIdDonation($idDonationInfo);
            $hookArray = array_column($selectedHooks, 'id_hook');
            foreach ($hookArray as $hook) {
                $objDonationDisplayPlace->deleteDonationHooks($idDonationInfo, $hook);
            }
            //add new hooks
            foreach ($donationPageHook as $idPage => $pageHooks) {
                foreach ($pageHooks as $idHook) {
                    $objDonationDisplayPlace->insertDonationHooks($idDonationInfo, $idHook, $idPage);
                }
            }
            Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token.'&conf=4');
        }
        $this->display = 'add';
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setmedia($isNewTheme);
        $this->addJqueryPlugin('colorpicker');
        Media::addJsDef(array(
            'ps_img_lang_dir' => _PS_IMG_.'l/',
            'maxSizeAllowed' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
            'filesizeError' => $this->l('File exceeds maximum size.'),
        ));
        $this->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        if (version_compare(_PS_VERSION_, '1.6.0.11', '>')) {
            $this->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        } else {
            $this->addJS(_PS_JS_DIR_.'tinymce.inc.js');
        }

        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin/wk_global.js');
    }
}
