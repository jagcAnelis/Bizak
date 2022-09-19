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

class AdminManageDonationController extends ModuleAdminController
{
    protected $position_identifier = 'id_donation_info';

    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'wk_donation_info';
        $this->identifier = 'id_donation_info';
        $this->className = 'WkDonationInfo';
        $this->_defaultOrderBy = 'position';

        parent::__construct();

        $this->_select .= 'dl.name';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.
        'wk_donation_info_lang` dl ON (dl.`id_donation_info` = a.`id_donation_info`)';
        $this->_where = 'AND dl.`id_lang` = '.(int) $this->context->language->id." AND a.`is_global` = '0'";

        $this->toolbar_title = $this->l('Manage Donation');

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected Donations?'),
                'icon' => 'icon-trash',
            ),
        );

        $priceType = array();
        $priceType[WkDonationInfo::WK_DONATION_PRICE_TYPE_FIXED] = $this->l('Fixed');
        $priceType[WkDonationInfo::WK_DONATION_PRICE_TYPE_CUSTOMER] = $this->l('By customer');

        $this->fields_list = array(
            'id_donation_info' => array(
                'title' => $this->l('Id'),
                'class' => 'fixed-width-xs',
            ),
            'name' => array(
                'title' => $this->l('Donation name'),
            ),
            'price_type' => array(
                'title' => $this->l('Price type'),
                'type' => 'select',
                'hint' => $this->l('\'Fixed\' means donation amount is fixed, \'By customer\' means donation amount can be entered by customer'),
                'list' => $priceType,
                'filter_key' => 'a!price_type',
                'callback' => 'getPriceType',
            ),
            'price' => array(
                'title' => $this->l('Price'),
                'type' => 'price',
            ),
            'advertise' => array(
                'type' => 'bool',
                'filter_key' => 'a!advertise',
                'title' => $this->l('Advertise'),
                'callback' => 'showAdvertise',
            ),
            'position' => array(
                'title' => $this->l('Priority'),
                'hint' => $this->l('priority define the ordering in which multiple dontions will be shown'),
                'filter_key' => 'a!position',
                'position' => 'position',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'orderby' => false,
            ),
        );
        $objDonationInfo = new WkDonationInfo();
        $objDonationInfo->updateDonationExpiry();
    }

    public function getPriceType($row)
    {
        if ($row == WkDonationInfo::WK_DONATION_PRICE_TYPE_FIXED) {
            return $this->l('Fixed');
        } elseif ($row == WkDonationInfo::WK_DONATION_PRICE_TYPE_CUSTOMER) {
            return $this->l('By customer');
        }
    }

    public function showAdvertise($row)
    {
        $this->context->smarty->assign('showAdvertise', $row);

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.
            '/views/templates/admin/manage_donation/helpers/_partials/advertise-badge.tpl'
        );
    }

    public function initPageHeaderToolbar()
    {
        if ($this->display != 'add' && $this->display != 'edit') {
            $this->page_header_toolbar_btn['new'] = array(
                'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                'desc' => $this->l('Add New Donation'),
            );
        }
        parent::initPageHeaderToolbar();
    }

    public function renderForm()
    {
        $smartyVars = array();
        $objDonationInfo = $this->loadObject(true);

        if (Validate::isLoadedObject($objDonationInfo)) {
            $smartyVars['donationInfo'] = (array) $objDonationInfo;
            $objImage = new Image();
            $donationImages = $objImage->getImages($this->context->language->id, $objDonationInfo->id_product);
            if ($donationImages) {
                foreach ($donationImages as &$image) {
                    $image['image_link'] = _PS_IMG_
                    .'p/'.$objImage->getImgFolderStatic($image['id_image'])
                    .$image['id_image']
                    .'.jpg';
                }
                $smartyVars['donationImages'] = $donationImages;
            }
            $smartyVars['imagePath_head_foot'] = $this->getAdvertisementBannerImagePath(
                $objDonationInfo->id.'-head-foot'
            );
            $smartyVars['imagePath_left_right'] = $this->getAdvertisementBannerImagePath(
                $objDonationInfo->id.'-left-right'
            );
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

        $this->context->smarty->assign($smartyVars);
        $this->context->smarty->assign(
            array(
                'adminManageDonationUrl' => $this->context->link->getAdminLink('AdminManageDonation'),
                'pages' => WkDonationDisplayPlaces::getDonationPages(),
                'hooks' => WkDonationDisplayPlaces::getDonationHooks(),
                'donate_hooks' => $donationHooks,
                'defaultCurrencySign' => $this->context->currency->sign,
                'ps_img_lang_dir' => _PS_IMG_.'l/',
                'maxSizeAllowed' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
                'active_tab' => Tools::getValue('tab'),
            )
        );
        $this->fields_form = array(
            'submit' => array(
                'title' => $this->l('Save'),
            ),
        );

        return parent::renderForm();
    }

    public function processStatus()
    {
        if (Validate::isLoadedObject($objDonationInfo = $this->loadObject())) {
            $today = date('Y-m-d');
            $expiry_date = date('Y-m-d', strtotime($objDonationInfo->expiry_date));
            if ($expiry_date > 0) {
                if ($today > $expiry_date) {
                    $this->errors[] = $this->l('Please update the donation expiry before enabling the donation');
                }
            }
        }
        if (!count($this->errors)) {
            $this->toggleDonationProduct();
        }
    }

    public function toggleDonationProduct()
    {
        if ($id_donation_info = Tools::getValue('id_donation_info')) {
            if ($objDonationInfo = new WkDonationInfo($id_donation_info)) {
                if ($objDonationInfo->active) {
                    $objDonationInfo->active = 0;
                } else {
                    $objDonationInfo->active = 1;
                }
                if ($objDonationInfo->save()) {
                    $objDonationProduct = new Product($objDonationInfo->id_product);
                    if ($objDonationInfo->active) {
                        $objDonationProduct->active = 1;
                    } else {
                        $objDonationProduct->active = 0;
                    }
                    if ($objDonationProduct->save()) {
                        Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token.'&conf=5');
                    }
                }
            }
            $this->errors[] = $this->l('There was a problem in changing the  status of the donation, Please try again later');
        }
    }

    public function processSave()
    {
        $active = Tools::getValue('activate_donation');
        $priceType = Tools::getValue('price_type');
        $price = Tools::getValue('price');
        $expiryDate = trim(Tools::getValue('expiry_date'));
        $productVisibility = Tools::getValue('product_visibility');
        $showAtCheckout = Tools::getValue('show_at_checkout');
        $advertise = Tools::getValue('advertise');
        $showDonateButton = Tools::getValue('show_donate_button');
        $advTitleColor = Tools::getValue('adv_title_color');
        $advDescColor = Tools::getValue('adv_desc_color');
        $buttonTextColor = Tools::getValue('button_text_color');
        $buttonBorderColor = Tools::getValue('button_border_color');
        $donationPageHook = Tools::getValue('page_hook');
        $defaultLangId = Configuration::get('PS_LANG_DEFAULT');
        $objDefaultLanguage = Language::getLanguage((int) $defaultLangId);
        $languages = Language::getLanguages(false);

        //validation
        if (!trim(Tools::getValue('name_'.$defaultLangId))) {
            $this->errors[] = sprintf(
                $this->l('Donation name is required at least in %s'),
                $objDefaultLanguage['name']
            );
        } else {
            foreach ($languages as $language) {
                if (!Validate::isCatalogName(Tools::getValue('name_'.$language['id_lang']))) {
                    $this->errors[] = sprintf(
                        $this->l('Invalid donation name for the language %s'),
                        $language['name']
                    );
                }
            }
        }

        if (!trim(Tools::getValue('description_'.$defaultLangId))) {
            $this->errors[] = sprintf(
                $this->l('Donation description is required at least in %s'),
                $objDefaultLanguage['name']
            );
        } else {
            foreach ($languages as $language) {
                if (!Validate::isCleanHtml(Tools::getValue('description_'.$language['id_lang']))
                ) {
                    $this->errors[] = sprintf(
                        $this->l('Invalid donation description for the language %s'),
                        $language['name']
                    );
                }
            }
        }
        if ($advertise) {
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
        }

        foreach ($languages as $language) {
            if (!Validate::isGenericName(Tools::getValue('donate_button_text_'.$language['id_lang']))) {
                $this->errors[] = sprintf(
                    $this->l('Invalid donate button text for the language %s'),
                    $language['name']
                );
            }
        }

        if ((Validate::isUnsignedInt($price) || Validate::isUnsignedFloat($price)) && $price <= 0) {
            $this->errors[] = $this->l('Donation price must be greater than zero');
        } elseif (empty($price)) {
            $this->errors[] = $this->l('Donation price must not be empty');
        } elseif (!Validate::isPrice($price)) {
            $this->errors[] = $this->l('Donation price is invalid');
        }

        if (!empty($expiryDate)) {
            if (!Validate::isDateFormat($expiryDate)) {
                $this->errors[] = $this->l('Expiry date in invalid format');
            } else {
                $currentDate = date('Y-m-d');
                $expiryDate = date('Y-m-d', strtotime($expiryDate));
                if ($currentDate > $expiryDate) {
                    $this->errors[] = $this->l('Expiry date must be greater or equal to current date');
                }
            }
        }
        if (!Validate::isColor($advTitleColor)) {
            $this->errors[] = $this->l('Advertisement title text color is invalid');
        }
        if (!Validate::isColor($advDescColor)) {
            $this->errors[] = $this->l('Advertisement description text color is invalid');
        }

        if ($showDonateButton == 1) {
            if (!trim(Tools::getValue('donate_button_text_'.$defaultLangId))) {
                $this->errors[] = sprintf(
                    $this->l('Donate button text is required at least in %s'),
                    $objDefaultLanguage['name']
                );
            }
            if (!Validate::isColor($buttonTextColor)) {
                $this->errors[] = $this->l('Donate button text color is invalid');
            }
            if (!Validate::isColor($buttonBorderColor)) {
                $this->errors[] = $this->l('Donate button border color is invalid');
            }
        }

        if ($advertise) {
            if (empty($donationPageHook)) {
                $this->errors[] = $this->l('Select atleast one place for advertisement');
            } else {
                foreach ($donationPageHook as $donationhook) {
                    if (in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_HOME, $donationhook)
                        || in_array(WkDonationDisplayPlaces::WK_DONATION_HOOK_FOOTER, $donationhook)) {
                        if ($imgHeadFoot = $_FILES['background_image_head_foot']) {
                            if (empty($imgHeadFoot['name'])) {
                                if (!$this->getAdvertisementBannerImagePath(
                                    Tools::getValue('id_donation_info').'-head-foot'
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
                                    Tools::getValue('id_donation_info').'-left-right'
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
        }

        //if validation passes
        if (!count($this->errors)) {
            if ($id = Tools::getValue('id_donation_info')) {
                $objDonationInfo = new WkDonationInfo($id);
            } else {
                $objDonationInfo = new WkDonationInfo();
                $objDonationInfo->id_product = 0;
                $objDonationInfo->position = WkDonationInfo::getHigherPosition();
            }

            $objDonationInfo->active = $active;
            $objDonationInfo->product_visibility = $productVisibility;
            $objDonationInfo->price_type = $priceType;
            $objDonationInfo->price = $price;
            $objDonationInfo->show_at_checkout = $showAtCheckout;
            $objDonationInfo->advertise = $advertise;
            $objDonationInfo->expiry_date = $expiryDate;
            $objDonationInfo->show_donate_button = $showDonateButton;
            $objDonationInfo->adv_title_color = $advTitleColor;
            $objDonationInfo->adv_desc_color = $advDescColor;
            if ($showDonateButton) {
                $objDonationInfo->button_text_color = $buttonTextColor;
                $objDonationInfo->button_border_color = $buttonBorderColor;
            }

            foreach (Language::getLanguages(false) as $language) {
                $objDonationInfo->name[$language['id_lang']] = Tools::getValue(
                    'name_'.$language['id_lang']
                );

                $objDonationInfo->description[$language['id_lang']] = Tools::getValue(
                    'description_'.$language['id_lang']
                );
            }
            if ($advertise) {
                foreach (Language::getLanguages(false) as $language) {
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
                    if (Tools::getValue('donate_button_text_'.$language['id_lang'])) {
                        $objDonationInfo->donate_button_text[$language['id_lang']] =
                        Tools::getValue('donate_button_text_'.$language['id_lang']);
                    } else {
                        $objDonationInfo->donate_button_text[$language['id_lang']] =
                        Tools::getValue('donate_button_text_'.$defaultLangId);
                    }
                }
            }
            if ($objDonationInfo->save()) {
                $idDonationInfo = $objDonationInfo->id;
                if ($advertise) {
                    ImageManager::resize(
                        $_FILES['background_image_head_foot']['tmp_name'],
                        _PS_MODULE_DIR_.$this->module->name.'/views/img/banner/'.$idDonationInfo.'-head-foot.jpg'
                    );
                    ImageManager::resize(
                        $_FILES['background_image_left_right']['tmp_name'],
                        _PS_MODULE_DIR_.$this->module->name.'/views/img/banner/'.$idDonationInfo.'-left-right.jpg'
                    );
                }
                //add or update donation product to ps
                $idProduct = $objDonationInfo->addDonationProductToPs($idDonationInfo);
                if ($idProduct) {
                    $objDonationInfo->id_product = $idProduct;
                    $objDonationInfo->save();
                }

                // delete previous advertisement hooks
                $objDonationDisplayPlace = new WkDonationDisplayPlaces();
                $selectedHooks = $objDonationDisplayPlace->getDonationHooksByIdDonation($idDonationInfo);
                $hookArray = array_column($selectedHooks, 'id_hook');
                foreach ($hookArray as $hook) {
                    $objDonationDisplayPlace->deleteDonationHooks($idDonationInfo, $hook);
                }
                //add new advertisement hooks
                if ($advertise) {
                    foreach ($donationPageHook as $idPage => $pageHooks) {
                        foreach ($pageHooks as $idHook) {
                            $objDonationDisplayPlace->insertDonationHooks($idDonationInfo, $idHook, $idPage);
                        }
                    }
                }
                if (Tools::isSubmit('submitAdd'.$this->table.'AndStay')) {
                    if ($id) {
                        Tools::redirectAdmin(
                            self::$currentIndex.
                            '&id_donation_info='.(int) $idDonationInfo.
                            '&update'.$this->table.
                            '&conf=4&tab='.Tools::getValue('active_tab').
                            '&token='.$this->token
                        );
                    } else {
                        Tools::redirectAdmin(
                            self::$currentIndex.
                            '&id_donation_info='.(int) $idDonationInfo.
                            '&update'.$this->table.
                            '&conf=3&tab='.Tools::getValue('active_tab').
                            '&token='.$this->token
                        );
                    }
                } else {
                    if ($id) {
                        Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
                    } else {
                        Tools::redirectAdmin(self::$currentIndex.'&conf=3&token='.$this->token);
                    }
                }
            } else {
                $this->errors[] = $this->l('Something went wrong. Please try again later !!');
            }
        } else {
            $this->display = 'edit';
        }
    }

    public function processDelete()
    {
        if (Validate::isLoadedObject($objDonationInfo = $this->loadObject())) {
            $imagePath = _PS_MODULE_DIR_.$this->module->name.'/views/img/banner/'.$objDonationInfo->id;
            if (file_exists($imagePath.'-head-foot.jpg')) {
                unlink($imagePath.'-head-foot.jpg');
            }
            if (file_exists($imagePath.'-left-right.jpg')) {
                unlink($imagePath.'-left-right.jpg');
            }
        }
        parent::processDelete();
    }

    public function getAdvertisementBannerImagePath($imageName)
    {
        $path = _MODULE_DIR_.$this->module->name.'/views/img/banner/'.$imageName.'.jpg';
        if (file_exists(_PS_MODULE_DIR_.$this->module->name.'/views/img/banner/'.$imageName.'.jpg')) {
            return $path;
        }

        return false;
    }

    public function ajaxProcessUploadDonationProductImages()
    {
        if ($idDonationInfo = Tools::getValue('id_donation_info')) {
            if (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo($idDonationInfo))) {
                if (!$invalidImg = ImageManager::validateUpload(
                    $_FILES['donation_image'],
                    Tools::getMaxUploadSize()
                )) {
                    $kwargs = array(
                        'id_product' => $objDonationInfo->id_product,
                        'donation_image' => $_FILES['donation_image'],
                    );
                    $imageDetail = $objDonationInfo->uploadDonationProductImages($kwargs);
                    if ($imageDetail) {
                        $this->ajaxDie(json_encode($imageDetail));
                    } else {
                        $this->ajaxDie(json_encode(array('hasError' => true)));
                    }
                } else {
                    $this->ajaxDie(
                        json_encode(
                            array('hasError' => true, 'message' => $_FILES['donation_image']['name'].': '.$invalidImg)
                        )
                    );
                }
            }
        }
    }

    public function ajaxProcessChangeDonationCoverImage()
    {
        if ($idDonationInfo = Tools::getValue('id_donation_info')) {
            if (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo($idDonationInfo))) {
                if ($idImage = Tools::getValue('id_image')) {
                    Image::deleteCover((int) $objDonationInfo->id_product);
                    $image = new Image((int) $idImage);
                    $image->cover = 1;

                    // unlink existing cover image in temp folder
                    @unlink(_PS_TMP_IMG_DIR_.'product_'.(int) $image->id_product);
                    @unlink(_PS_TMP_IMG_DIR_.'product_mini_'.(int) $image->id_product.'_'.$this->context->shop->id);

                    if ($image->update()) {
                        $this->ajaxDie(true);
                    } else {
                        $this->ajaxDie(false);
                    }
                } else {
                    $this->ajaxDie(false);
                }
            } else {
                $this->ajaxDie(false);
            }
        }
    }

    public function ajaxProcessDeleteDonationImage()
    {
        if ($idDonationInfo = Tools::getValue('id_donation_info')) {
            if (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo($idDonationInfo))) {
                if ($idImage = Tools::getValue('id_image')) {
                    $image = new Image((int) $idImage);
                    if ($image->delete()) {
                        Product::cleanPositions($idImage);
                        if (!Image::getCover($image->id_product)) {
                            $images = Image::getImages($this->context->language->id, $objDonationInfo->id_product);
                            if ($images) {
                                $objImage = new Image($images[0]['id_image']);
                                $objImage->cover = 1;
                                $objImage->save();
                            }
                        }

                        if (file_exists(_PS_TMP_IMG_DIR_.'product_'.$image->id_product.'.jpg')) {
                            @unlink(_PS_TMP_IMG_DIR_.'product_'.$image->id_product.'.jpg');
                        }
                        if (file_exists(
                            _PS_TMP_IMG_DIR_.'product_mini_'.$image->id_product.'_'.$this->context->shop->id.'.jpg'
                        )) {
                            @unlink(
                                _PS_TMP_IMG_DIR_.'product_mini_'.$image->id_product.'_'.$this->context->shop->id.'.jpg'
                            );
                        }
                        if (isset($objImage)) {
                            $this->ajaxDie(json_encode(array('idCover' => $objImage->id_image)));
                        }
                        $this->ajaxDie(json_encode(array('hasError' => false)));
                    } else {
                        $this->ajaxDie(json_encode(array('hasError' => true)));
                    }
                } else {
                    $this->ajaxDie(json_encode(array('hasError' => true)));
                }
            } else {
                $this->ajaxDie(json_encode(array('hasError' => true)));
            }
        }
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int) Tools::getValue('way');
        $idDonationInfo = (int) Tools::getValue('id');
        $positions = Tools::getValue('donation_info');

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2]) && (int) $pos[2] === $idDonationInfo) {
                if ($objDonationInfo = new WkDonationInfo((int) $pos[2])) {
                    if (isset($position)
                        && $objDonationInfo->updatePosition($way, $position)
                    ) {
                        $this->ajaxDie(true);
                    } else {
                        $this->ajaxDie(false);
                    }
                } else {
                    $this->ajaxDie(false);
                }
                break;
            }
        }
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setmedia($isNewTheme);
        $this->addJqueryPlugin('colorpicker');
        $jsVars = array(
            'ps_img_lang_dir' => _PS_IMG_.'l/',
            'maxSizeAllowed' => Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),
            'adminManageDonationUrl' => $this->context->link->getAdminLink('AdminManageDonation'),
            'filesizeError' => $this->l('File exceeds maximum size'),
            'imgUploadSuccessMsg' => $this->l('Image Successfully Uploaded'),
            'coverImgSuccessMsg' => $this->l('Cover image changed successfully'),
            'coverImgErrorMsg' => $this->l('Error while changing cover image'),
            'deleteImgSuccessMsg' => $this->l('Image deleted successfully'),
            'deleteImgErrorMsg' => $this->l('Something went wrong while deleteing image. Please try again.'),
            'imgUploadErrorMsg' => $this->l('Something went wrong while uploading images. Please try again.'),
        );
        Media::addJsDef($jsVars);
        $this->addJS(_PS_JS_DIR_.'tiny_mce/tiny_mce.js');
        if (version_compare(_PS_VERSION_, '1.6.0.11', '>')) {
            $this->addJS(_PS_JS_DIR_.'admin/tinymce.inc.js');
        } else {
            $this->addJS(_PS_JS_DIR_.'tinymce.inc.js');
        }

        $this->addCSS(_MODULE_DIR_.$this->module->name.'/views/css/admin/wk_manage_donation.css');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin/wk_manage_donation.js');
        $this->addJS(_MODULE_DIR_.$this->module->name.'/views/js/admin/wk_donation_images.js');
    }
}
