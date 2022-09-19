<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 */

//use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGUtils.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGCommentsWidget.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGStoreComment.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGProductComment.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGCommentsCSV.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGMailer.php'
);
require_once(
    _PS_MODULE_DIR_ . DIRECTORY_SEPARATOR . 'lgcomments' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR .
    'LGCommentsAjax.php'
);

class LGComments extends Module
{
    public $bootstrap;
    public $html;
    public $stars = array();
    public $store_widget = array();

    public $prestashopGDPRModuleInstalled = false;

    public function __construct()
    {
        $this->name          = 'lgcomments';
        $this->tab           = 'advertising_marketing';
        $this->version       = '1.6.7';
        $this->author        = 'Línea Gráfica';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6');
        $this->module_key    = '7a311a563a0daa4a8636f6a5ec27c0e6';
        $this->author_address = '0x30052019eD7528f284fd035BdA14B6eC3A4a1ffB';
        $this->bootstrap     = true;
        $this->is_configurable = true;
        $this->initContext();
        parent::__construct();
        $this->displayName = $this->l('Store Reviews, Product Reviews and Google Rich Snippets');
        $this->description = $this->l('Get your own system of reviews about your store and products.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->id_lang      = (int)Context::getContext()->language->id;
        $this->id_lang      = (int)Context::getContext()->language->id;
        $this->iso_lang     = pSQL(Language::getIsoById($this->id_lang));
        $this->stars        = LGUtils::getStarsConfig($this);
        $this->store_widget = LGUtils::getStoreWidgetConfig($this);

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $this->context->smarty->assign(array(
                'lgcomments_content_dir' => _MODULE_DIR_ . $this->name,
            ));
        }

        // Flag to known if GDPR Prestashop module is installed
        $this->prestashopGDPRModuleInstalled = $this->checkPrestashopGDPRModuleInstalled();
        $this->l('Please insert a nickname');
        $this->l('Please insert a score for your review');
        $this->l('Please insert a title for your review');
        $this->l('Please insert a comment for your review');
    }

    public function install()
    {
        include(dirname(__FILE__) . '/sql/install.php');

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            $tab_type = 3;
            $prod_anchor_position = 1;
        } elseif (substr_count(_PS_VERSION_, '1.6') > 0) {
            $tab_type = 1;
            $prod_anchor_position = 3;
        } else {
            $tab_type = 2;
            $prod_anchor_position = 3;
        }

        // Default value
        $default_values = array(
            'tab_type' => $tab_type,
            'extraright_css_config' => LGUtils::getExtraRightCSSConfig('vertical'),
            'subject_cron' => $this->l('The opinion requests have been sent correctly'),
            'subject_newreviews' => $this->l('You have received new reviews'),
            'prod_anchor_position' => $prod_anchor_position,
        );

        // Todo: For final 1.7 version delete not needed hooks and delete the check for versions
        $hooks = array(
            'header',
            'backOfficeHeader',
            'footer',
            'displayRightColumn',
            'displayLeftColumn',
            'productTab',
            'productTabContent',
            'displayFooterProduct',
            'extraRight',
            'displayHome',
            'displayCustomerAccount',
            'displayLgStoreCommentSlider',
            'registerGDPRConsent',         // GDPR hook
            'actionDeleteGDPRCustomer',    // GDPR hook
            'actionExportGDPRData',        // GDPR hook
            'displayProductListReviews',
            'customStarsPosition',            // Custom hook to display stars where customer wants on product page
            'CustomLGCommentsWidgetPosition', // Custom hook to display widget where customer wants
            'displayProductPriceBlock',
            'actionFrontControllerSetMedia',
            'displayProductExtraContent',
            'displayReassurance',
            'displayRightColumnProduct',
        );

        if (!parent::install() ||
            !LGStoreComment::install() ||
            !LGProductComment::install() ||
            !$this->registerHook($hooks) ||
            !$this->installModuleTab('AdminLGCommentsStore', $this->l('Store reviews')) ||
            !$this->installModuleTab('AdminLGCommentsProducts', $this->l('Products reviews')) ||
            !LGUtils::createDefaultConfig($default_values) ||
            !LGUtils::createDefaultData() ||
            !LGUtils::createDefaultMetas()
        ) {
            $this->_errors[] = $this->l(
                'There was an error during the installation. Please contact us through Addons website.'
            );
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

        $sql = 'SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `module` = "' . pSQL($this->name) . '"';
        $result = Db::getInstance()->ExecuteS($sql);

        if ($result && count($result)) {
            foreach ($result as $tabData) {
                $tab = new Tab($tabData['id_tab']);

                if (Validate::isLoadedObject($tab)) {
                    $tab->delete();
                }
            }
        }

        if (substr_count(_PS_VERSION_, '1.6') > 0) {
            $id_meta = Db::getInstance()->getValue(
                'SELECT id_meta ' .
                'FROM ' . _DB_PREFIX_ . 'meta ' .
                'WHERE page = "module-lgcomments-reviews"'
            );
            Db::getInstance()->Execute(
                'DELETE FROM ' . _DB_PREFIX_ . 'meta WHERE id_meta = ' . (int)$id_meta
            );
            Db::getInstance()->Execute(
                'DELETE FROM ' . _DB_PREFIX_ . 'meta_lang WHERE id_meta = ' . (int)$id_meta
            );
            Db::getInstance()->Execute(
                'DELETE FROM ' . _DB_PREFIX_ . 'theme_meta WHERE id_meta = ' . (int)$id_meta
            );
        }

        return parent::uninstall()
            && LGStoreComment::uninstall()
            && LGProductComment::uninstall();
    }

    public function getContent()
    {
        $link       = new Link();
        $tokenC     = Tools::getAdminTokenLite('AdminCustomers');
        $tokenO     = Tools::getAdminTokenLite('AdminOrders');
        $tokenL     = Tools::getAdminTokenLite('AdminLanguages');
        $tokenPe    = Tools::getAdminTokenLite('AdminPerformance');
        $tokenPr    = Tools::getAdminTokenLite('AdminProducts');
        $tokenM     = Tools::getAdminTokenLite('AdminModulesPositions');
        $tokenE     = Tools::getAdminTokenLite('AdminEmails');
        $secureKey  = md5(_COOKIE_KEY_ . Configuration::get('PS_SHOP_NAME'));
        $this->html = $this->getP();

        // Saving rating config
        if (Tools::isSubmit('submitLGCommentsGeneral')) {
            if (LGUtils::saveRatingsConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Rating configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Rating configuration cannot be updated'));
            }
        }

        // Saving widget config
        if (Tools::isSubmit('submitLGCommentsWidget')) {
            if (LGUtils::saveStoreWidgetConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Widget configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Widget configuration cannot be updated'));
            }
        }

        // Saving home page slider config
        if (Tools::isSubmit('submitLGCommentsHomepage')) {
            if (LGUtils::saveHomeSliderConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Homepage configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Homepage configuration cannot be updated'));
            }
        }

        // Saving store page review config
        if (Tools::isSubmit('submitLGCommentsStore')) {
            if (LGUtils::saveStorePageReviewConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Store review configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Store review configuration cannot be updated'));
            }
        }

        if (Tools::isSubmit('submitLGCommentsProducts')) {
            if (LGUtils::saveProductReviewConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Product review configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Product review configuration cannot be updated'));
            }
        }

        if (Tools::isSubmit('submitLGCommentsSnippets')) {
            if (LGUtils::saveRichSnippetsConfig()) {
                $this->html .= $this->displayConfirmation($this->l('Rich Snippets configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Rich Snippets configuration cannot be updated'));
            }
        }

        if (Tools::isSubmit('submitLGCommentsSend')) {
            if (LGUtils::saveSendEmails()) {
                $this->html .= $this->displayConfirmation($this->l('Send emails configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Send emails configuration cannot be updated'));
            }
        }

        if (Tools::isSubmit('submitLGCommentsConfigure')) {
            if (LGUtils::saveConfigureEmails()) {
                $this->html .= $this->displayConfirmation($this->l('Emails configuration updated'));
            } else {
                $this->html .= $this->displayError($this->l('Emails configuration cannot be updated'));
            }
        }

        if (Tools::isSubmit('submitLGCommentsManage')) {
            // Opinion form
            Configuration::updateValue(
                'PS_LGCOMMENTS_OPINION_FORM',
                (int)Tools::getValue('PS_LGCOMMENTS_OPINION_FORM', 1)
            );
            // Validation
            Configuration::updateValue(
                'PS_LGCOMMENTS_VALIDATION',
                (int)Tools::getValue('lgcomments_validation', 1)
            );
            $this->html .= $this->displayConfirmation($this->l('General review configuration updated'));
        }

        // Import product comments
        if (Tools::isSubmit('productCSV')) {
            $this->html .= LGCommentsCSV::importProductComments($this);
        }

        // Import store comments
        if (Tools::isSubmit('storeCSV')) {
            $this->html .= LGCommentsCSV::importStorecomments($this);
        }

        // Export product comments
        if (Tools::isSubmit('exportProductCSV')) {
            $this->html .= LGCommentsCSV::exportProductComments($this);
        }

        // Export store comments
        if (Tools::isSubmit('exportStoreCSV')) {
            $this->html .= LGCommentsCSV::exportStoreComments($this);
        }

        // Display error messages
        if ((int)Configuration::get('PS_DISABLE_NON_NATIVE_MODULE') > 0) {
            $this->html .= $this->displayError(
                $this->l('Non PrestaShop modules are currently disabled on your store.') . '&nbsp;' .
                $this->l('Please change the configuration') . '&nbsp;'.
                $this->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => 'index.php?tab=AdminPerformance&token='.$tokenPe,
                        'lgcomments_warning_link_target'  => '_blank',
                        'lgcomments_warning_link_message' => $this->l('here'),
                    )
                )
            );
        }
        $checkIfEmpty = $this->checkIfEmptyStore();
        if ($checkIfEmpty == false) {
            $this->html .= $this->displayError(
                $this->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => $link->getAdminlink('AdminLGCommentsStore'),
                        'lgcomments_warning_link_target'  => '_blank',
                        'lgcomments_warning_link_class'   => 'redtext',
                        'lgcomments_warning_link_message' => $this->l(
                            'You haven\'t received any store reviews at the moment.'
                        )
                    )
                )
                . '&nbsp;' . $this->l('Please take a look at our') . '&nbsp;'.
                $this->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => '../modules/'
                            .$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=11',
                        'lgcomments_warning_link_target'  => '_blank',
                        'lgcomments_warning_link_message' => $this->l('PDF documentation')
                    )
                )
                . '&nbsp;' . $this->l('to see how to send opinion requests to your customers.')
            );
        }
        $checkIfEmpty2 = $this->checkIfEmptyProduct();
        if ($checkIfEmpty2 == false) {
            $this->html .= $this->displayError(
                $this->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => $link->getAdminlink('AdminLGCommentsProducts'),
                        'lgcomments_warning_link_target'  => '_blank',
                        'lgcomments_warning_link_message' => $this->l(
                            'You haven\'t received any product reviews at the moment.'
                        )
                    )
                )
                . '&nbsp;' . $this->l('Please take a look at our') . '&nbsp;'.
                $this->getAnchor(
                    array(
                        'lgcomments_warning_link_href'    => '../modules/'
                            .$this->name.'/readme/readme_'.$this->l('en').'.pdf#page=11',
                        'lgcomments_warning_link_target'  => '_blank',
                        'lgcomments_warning_link_message' =>  $this->l('PDF documentation')
                    )
                )
                . '&nbsp;' . $this->l('to see how to send opinion requests to your customers.')
            );
        }
        $checkIfEmpty3 = $this->checkIfEmptyStatus();
        if ($checkIfEmpty3 == false) {
            $this->html .= $this->displayError(
                $this->l('You must select at least one order status') . '&nbsp;' .
                $this->l('to be able to send opinion requests to your customers.')
            );
        }
        $checkIfEmpty4 = $this->checkIfEmptyGroup();
        if ($checkIfEmpty4 == false) {
            $this->html .= $this->displayError(
                $this->l('You must select at least one group of customers') . '&nbsp;' .
                $this->l('to be able to send opinion requests to your customers.')
            );
        }
        $checkIfEmpty5 = $this->checkIfEmptyMultistore();
        if ($checkIfEmpty5 == false) {
            $this->html .= $this->displayError(
                $this->l('You must select at least one shop') . '&nbsp;' .
                $this->l('to be able to send opinion requests to your customers.')
            );
        }

        /* common vars */
        $this->context->smarty->assign(
            array(
                'module_path'                  => $this->getPathUri(),
                'iso_lang'                     => $this->iso_lang,
                'lgcommentsStoreReviewsUrl'    => $link->getAdminlink('AdminLGCommentsStore'),
                'lgcommentsProductsReviewsUrl' => $link->getAdminlink('AdminLGCommentsProducts'),
            )
        );

        /* menubar.tpl vars */
        $this->context->smarty->assign(
            array(
                'LGCOMMENTS_SELECTED_MENU' => pSQL(
                    Tools::getValue(
                        'LGCOMMENTS_SELECTED_MENU',
                        'general-config'
                    )
                ),
            )
        );

        /* ratings.tpl vars */
        $this->context->smarty->assign(
            array(
                'stars'                => $this->stars,
                'selected_star_design' => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
                'selected_star_colour' => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
                'selected_star_size'   => Configuration::get('PS_LGCOMMENTS_STARSIZE'),
                'selected_star_scale'  => Configuration::get('PS_LGCOMMENTS_SCALE'),
                'displayzerostar'      => Configuration::get('PS_LGCOMMENTS_DISPLAY_ZEROSTAR'),
                'cat_top_margin'       => Configuration::get('PS_LGCOMMENTS_CATTOPMARGIN'),
                'cat_bottom_margin'    => Configuration::get('PS_LGCOMMENTS_CATBOTMARGIN'),
                'pro_top_margin'       => Configuration::get('PS_LGCOMMENTS_PRODTOPMARGIN'),
                'pro_bottom_margin'    => Configuration::get('PS_LGCOMMENTS_PRODBOTMARGIN'),
            )
        );

        /* store_widget.tpl vars */
        $this->context->smarty->assign(
            array(
                'store_widget'          => $this->store_widget,
                'PS_LGCOMMENTS_DISPLAY' => Configuration::get('PS_LGCOMMENTS_DISPLAY'),
                'display_type'          => Configuration::get('PS_LGCOMMENTS_DISPLAY_TYPE'),
                'display_side'          => Configuration::get('PS_LGCOMMENTS_DISPLAY_SIDE'),
                'moduleHook'            => $this->getHookList(),
                'bg_design'             => Configuration::get('PS_LGCOMMENTS_BGDESIGN1'),
                'bg_color'              => Configuration::get('PS_LGCOMMENTS_BGDESIGN2'),
                'display'               => Configuration::get('PS_LGCOMMENTS_DISPLAYpSQL'),
                'cross'                 => Configuration::get('PS_LGCOMMENTS_CROSS'),
                'text_color'            => Configuration::get('PS_LGCOMMENTS_TEXTCOLOR'),
                'PS_LGCOMMENTS_WIDGET_HOOK' => Configuration::get('PS_LGCOMMENTS_WIDGET_HOOK'),
                // REFACTORIZAR: Poner esto como constante o algo
                'tokenM'                => $tokenM, // addToHook
            )
        );

        /* homepage.tpl vars*/
        $this->context->smarty->assign(
            array(
                'display_slider' => Configuration::get('PS_LGCOMMENTS_DISPLAY_SLIDER'),
                'slider_blocks'  => (Configuration::get('PS_LGCOMMENTS_SLIDER_BLOCKS')
                    ?(int)Configuration::get('PS_LGCOMMENTS_SLIDER_BLOCKS')
                    :4),
                'slider_total'   => Configuration::get('PS_LGCOMMENTS_SLIDER_TOTAL'),
                'owlcarousel_disabled' => Configuration::get('PS_LGCOMMENTS_OWLCAROUSEL_DISABLED'),
            )
        );

        /* store_reviews.tpl vars*/
        $this->context->smarty->assign(
            array(
                'store_form'                     => Configuration::get('PS_LGCOMMENTS_STORE_FORM'),
                'PS_LGCOMMENTS_STORE_FILTER'     => Configuration::get('PS_LGCOMMENTS_STORE_FILTER'),
                'PS_LGCOMMENTS_DISPLAY_LANGUAGE' => Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE'),
                'PS_LGCOMMENTS_TEXTCOLOR'        => Configuration::get('PS_LGCOMMENTS_TEXTCOLOR'),
                'PS_LGCOMMENTS_TEXTCOLOR2'       => Configuration::get('PS_LGCOMMENTS_TEXTCOLOR2'),
                'PS_LGCOMMENTS_BACKCOLOR2'       => Configuration::get('PS_LGCOMMENTS_BACKCOLOR2'),
                'PS_LGCOMMENTS_PER_PAGE'         => Configuration::get('PS_LGCOMMENTS_PER_PAGE'),
                'PS_LGCOMMENTS_DISPLAY_ORDER'    => Configuration::get('PS_LGCOMMENTS_DISPLAY_ORDER'),
            )
        );

        /* store_reviews.tpl vars */
        $this->context->smarty->assign(
            array(
                'PS_LGCOMMENTS_DISPLAY_COMMENTS'  => Configuration::get('PS_LGCOMMENTS_DISPLAY_COMMENTS'),
                'PS_LGCOMMENTS_TAB_CONTENT'       => Configuration::get('PS_LGCOMMENTS_TAB_CONTENT'),
                'PS_LGCOMMENTS_PRODUCT_FORM'      => Configuration::get('PS_LGCOMMENTS_PRODUCT_FORM'),
                'PS_LGCOMMENTS_PRODUCT_FILTER'    => Configuration::get('PS_LGCOMMENTS_PRODUCT_FILTER'),
                'PS_LGCOMMENTS_PRODUCT_FILTER_NB' => Configuration::get('PS_LGCOMMENTS_PRODUCT_FILTER_NB'),
                'PS_LGCOMMENTS_DISPLAY_DEFAULT'   => Configuration::get('PS_LGCOMMENTS_DISPLAY_DEFAULT'),
                'PS_LGCOMMENTS_DISPLAY_MORE'      => Configuration::get('PS_LGCOMMENTS_DISPLAY_MORE'),
                'PS_LGCOMMENTS_DISPLAY_ORDER2'    => Configuration::get('PS_LGCOMMENTS_DISPLAY_ORDER2'),
                'PS_LGCOMMENTS_DISPLAY_LANGUAGE2' => Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE2'),
                'PS_LGCOMMENTS_STARST_POSITION'   => Configuration::get('PS_LGCOMMENTS_STARST_POSITION'),
            )
        );

        /* google_ritch_snippets.tpl vars */
        $this->context->smarty->assign(
            array(
                'PS_LGCOMMENTS_DISPLAY_SNIPPETS2' => Configuration::get('PS_LGCOMMENTS_DISPLAY_SNIPPETS2'),
                'PS_LGCOMMENTS_DISPLAY_SNIPPETS'  => Configuration::get('PS_LGCOMMENTS_DISPLAY_SNIPPETS'),
                'PS_LGCOMMENTS_PRICE_RANGE'       => Configuration::get('PS_LGCOMMENTS_PRICE_RANGE'),
                'currency'                        => $this->context->currency->getSign(),
            )
        );

        /* send_emails.tpl vars */
        $this->context->smarty->assign(
            array(
                'tokenE'                           => $tokenE,
                'cron_url'                         => $this->getCronUrl($secureKey),
                'PS_LGCOMMENTS_SUBJECT_NEWREVIEWS' => Configuration::get('PS_LGCOMMENTS_SUBJECT_NEWREVIEWS'),
                'PS_LGCOMMENTS_SUBJECT_CRON'       => Configuration::get('PS_LGCOMMENTS_SUBJECT_CRON'),
                'PS_LGCOMMENTS_EMAIL_ALERTS'       => Configuration::get('PS_LGCOMMENTS_EMAIL_ALERTS'),
                'PS_LGCOMMENTS_EMAIL_CRON'         => Configuration::get('PS_LGCOMMENTS_EMAIL_CRON'),
                'lgcomments_email_config_link'     => $link->getAdminlink('AdminEmails').'#mail_fieldset_test',
            )
        );

        /* configure_emails.tpl vars */
        $customerGroupsChecked = LGUtils::getCustomerGroups();
        foreach ($customerGroupsChecked as $index => $cGroup) {
            $customerGroupsChecked[$index]['checked'] = (bool)$this->getCustomerGroupsByGroup($cGroup['id_group']);
        }

        $subjects = array();
        $langs = LanguageCore::getLanguages();
        foreach ($langs as $lang) {
            $subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT'] = Configuration::get(
                'PS_LGCOMMENTS_SUBJECT' .
                $lang['iso_code']
            );
            $subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT2'] = Configuration::get(
                'PS_LGCOMMENTS_SUBJECT2' .
                $lang['iso_code']
            );
            $subjects[$lang['iso_code']]['PS_LGCOMMENTS_SUBJECT3'] = Configuration::get(
                'PS_LGCOMMENTS_SUBJECT3' .
                $lang['iso_code']
            );
        }

        $orderStatus = LGUtils::getOrdersStatus();
        foreach ($orderStatus as $index => $estado) {
            $orderStatus[$index]['checked'] = (bool)$this->getLGCommentsOrderStatus($estado['id_order_state']);
        }

        $shopList = LGUtils::getShops();
        foreach ($shopList as $index => $shop) {
            $shopList[$index]['checked'] = (bool)$this->getSelectedShops($shop['id_shop']);
        }
        $this->context->smarty->assign(
            array(
                'langs'          => $langs,
                'customerGroups' => $customerGroupsChecked,
                'subjects'       => $subjects,
                'orderStatus'    => $orderStatus,
                'shopList'       => $shopList,
            )
        );

        /* corresponding_orders.tpl vars */
        // Check orders
        $days1     = (int)Configuration::get('PS_LGCOMMENTS_DIAS');
        $days2     = (int)Configuration::get('PS_LGCOMMENTS_DIAS2');

        $lgMailer  = new LGMailer();
        // $orderList = $this->getCorrespondingOrders($days1, $days2);
        $orderList = $lgMailer->getOrders(true);

        $days3     = (int)Configuration::get('PS_LGCOMMENTS_DAYS_AFTER');
        $days4     = $days2 + $days3;
        $fecha_desde = new DateTime('now');
        $fecha_hasta = new DateTime('now');
        $fecha_segunda = new DateTime('now');
        $date1     = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_desde->format('Y-m-d 23:59:59'));
        $date2     = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_hasta->format('Y-m-d 00:00:00'));
        $date3     = DateTime::createFromFormat('Y-m-d H:i:s', $fecha_segunda->format('Y-m-d 00:00:00'));
        $date1->sub(new DateInterval('P' . $days1 . 'D'));
        $date2->sub(new DateInterval('P' . $days2 . 'D'));
        $date3->sub(new DateInterval('P' . $days4 . 'D'));

        foreach ($orderList as $io => $order) {
            $orderList[$io]['shops']                = $this->getShopsByCustomer($order['id_customer']);
            $orderList[$io]['groups']               = $this->getCustomerGroupsByCustomer($order['id_customer']);
            $orderList[$io]['date_email_formated']  = date("d/m/Y H:i", strtotime($order['date_email']));
            $orderList[$io]['date_email2_formated'] = date("d/m/Y H:i", strtotime($order['date_email2']));
        }

        $this->context->smarty->assign(
            array(
                'allGroups'        => $this->getCorrespondingGroups(),
                'allShops'         => $this->getCorrespondingShops(),
                'allStatus'        => $this->getCorrespondingStatus(),
                'orderList'        => $orderList,
                'date1'            => $date1->format($this->getDateFormatFull()),
                'date2'            => $date2->format($this->getDateFormatFull()),
                'date3'            => $date3->format($this->getDateFormatFull()),
                'date_format_full' => $this->getDateFormatFull(),
            )
        );

        /* import_store_comments.tpl vars */
        $this->context->smarty->assign(
            array(
                'tokenC' => $tokenC,
                'tokenO' => $tokenO,
                'tokenL' => $tokenL,
            )
        );

        /* import_product_comments.tpl vars */
        $this->context->smarty->assign(
            array(
                'tokenC'  => $tokenC,
                'tokenPr' => $tokenPr,
                'tokenL'  => $tokenL,
            )
        );

        /* manage_reviews.tpl vars */
        $this->context->smarty->assign(
            array(
                'PS_LGCOMMENTS_VALIDATION'   => Configuration::get('PS_LGCOMMENTS_VALIDATION'),
                'PS_LGCOMMENTS_OPINION_FORM' => Configuration::get('PS_LGCOMMENTS_OPINION_FORM'),
            )
        );

        $this->html .= $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . $this->name
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'templates'
            . DIRECTORY_SEPARATOR . 'admin'
            . DIRECTORY_SEPARATOR . 'configuration.tpl'
        );

        if ($this->bootstrap == true) {
            $this->html = $this->formatBootstrap($this->html);
        }
        return $this->html;
    }

    public function getMedia()
    {
        $path = LGUtils::getMediaBasePath($this);
        LGUtils::addJS($path . 'views/js/store_widget.js', 'lgcomments_store_widget_js');
        LGUtils::addCSS($path . 'views/css/store_widget.css', 'lgcomments_store_widget_Css', $this->context);

        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_SLIDER')) {
            if (!Configuration::get('PS_LGCOMMENTS_OWLCAROUSEL_DISABLED')) {
                LGUtils::addCSS($path . 'views/css/owl.carousel.min.css', 'owl.carousel');
                LGUtils::addCSS($path . 'views/css/owl.theme.default.css', 'owl.theme.default');
                LGUtils::addJS($path . 'views/js/owl.carousel.min.js', 'owl.carousel');
            }
            LGUtils::addCSS($path . 'views/css/jquery.lgslider.css', 'jquery.lgslider');
            LGUtils::addJS($path . 'views/js/home_reviews.js', 'home_reviews');
        }

        if ($this->context->controller instanceof ProductController) {
            $this->context->controller->addJqueryPlugin('fancybox');
            LGUtils::addJS($path . 'views/js/form_review.js', 'form_review');
            LGUtils::addJS($path . 'views/js/product_reviews.js', 'product_reviews');
            LGUtils::addCSS($path . 'views/css/form_review.css', 'form_review');

            if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                LGUtils::addCSS($path . 'views/css/product_reviews_17.css', 'product_reviews_17');
            } else {
                LGUtils::addCSS($path . 'views/css/product_reviews.css', 'product_reviews');
            }
        } elseif ($this->context->controller instanceof LGCommentsReviewsModuleFrontController) {
            LGUtils::addCSS($path . 'views/css/form_review.css', 'form_review');
            LGUtils::addJS($path . 'views/js/form_review.js', 'form_review');
            LGUtils::addCSS($path . 'views/css/form_review.css', 'form_review');
            LGUtils::addCSS($path . 'views/css/store_reviews.css', 'store_reviews');

            if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                LGUtils::addJS($path . 'views/js/store_reviews.js', 'store_reviews_17');
                LGUtils::addCSS($path . 'views/css/store_reviews_17.css', 'store_reviews_17');
            } else {
                LGUtils::addJS($path . 'views/js/store_reviews.js', 'store_reviews');
                LGUtils::addCSS($path . 'views/css/store_reviews.css', 'store_reviews');
            }
        } elseif ($this->context->controller instanceof LGCommentsAccountModuleFrontController) {
            LGUtils::addCSS($path . 'views/css/account.css', 'account_css');
            LGUtils::addJS($path . 'views/js/account.js', 'account');
            $this->context->controller->addjQueryPlugin('validate');
            LGUtils::addJS(
                _PS_JS_DIR_.'jquery/plugins/validate/localization/messages_'
                .$this->context->language->iso_code.'.js',
                'jquery.validate'
            );
        }

        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            LGUtils::addCSS($path . 'views/css/product_list_17.css', 'product_list_17');
        } else {
            LGUtils::addCSS($path . 'views/css/product_list.css', 'product_list');
        }
    }

    /*************************************************************************************************************/
    /*                                                                                                           */
    /*                                          Methods for process Hooks                                        */
    /*                                                                                                           */
    /*************************************************************************************************************/

    // Todo: For final 1.7 version move method called content to hook directly
    public function hookActionFrontControllerSetMedia($params)
    {
        $this->getMedia();
    }

    public function hookDisplayBackOfficeHeader()
    {
        if ($this->context->controller instanceof AdminModulesController &&
            pSQL(Tools::getValue('configure')) == $this->name
        ) {
            $this->context->controller->addJquery();
            $this->context->controller->addCSS($this->_path . 'views/css/lgcomments.css');
            $this->context->controller->addJS($this->_path . 'views/js/jscolor-205.js');
            $this->context->controller->addJS($this->_path . 'views/js/menu.js');

            // Carlos Utrera: Agragamos validaciones por JQuery
            // Todo: Comprobar compatibilidad en 1.5 y 1.7
            $this->context->controller->addjQueryPlugin('validate');
            $this->context->controller->addJS(
                _PS_JS_DIR_.'jquery/plugins/validate/localization/messages_'
                .$this->context->language->iso_code.'.js'
            );
            $this->context->controller->addJS($this->_path . 'views/js/jquery.validate-integer.js');
            $this->context->controller->addJS($this->_path . 'views/js/jquery.validate-hexadecimal.js');
            $this->context->controller->addJS($this->_path . 'views/js/jquery.validate-nonempty.js');
            $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
            $this->context->controller->addJS($this->_path . 'views/js/admin.js');

            if (version_compare(_PS_VERSION_, '1.6.0', '<')) {
                $this->context->controller->addJS(_MODULE_DIR_ . $this->name . 'views/js/bootstrap.js');
                $this->context->controller->addJS(_MODULE_DIR_ . $this->name . 'views/js/admin15.js');
                $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . 'views/css/admin15.css');
            }

            $this->context->smarty->assign(array(
                'lgcomments_auth_token' => LGCommentsAjax::getAccessToken(),
                'lgcomments_token'      => Tools::getAdminTokenLite('AdminModules'),
            ));

            return $this->display($this->_path, 'views/templates/hooks/backofficeHeader.tpl');
        }
    }

    public function hookHeader()
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '<')) {
            $this->context->controller->addJQuery();
        }

        $uri_path = Dispatcher::getInstance()->createUrl('module-lgcomments-reviews');
        $ssl      = Configuration::get('PS_SSL_ENABLED_EVERYWHERE') && Configuration::get('PS_SSL_ENABLED');
        $link     = Context::getContext()->link;
        $values   = array(
            'lgcomments_products_default_display' => Configuration::get('PS_LGCOMMENTS_DISPLAY_DEFAULT'),
            'lgcomments_products_extra_display'   => Configuration::get('PS_LGCOMMENTS_DISPLAY_MORE'),
            'module_dir'                          => $this->_path,
            'star_style'                          => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
            'star_color'                          => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
            'comment_tab'                         => Configuration::get('PS_LGCOMMENTS_TAB_CONTENT'),
            'sliderblocks'                        => (Configuration::get('PS_LGCOMMENTS_SLIDER_BLOCKS')
                ?(int)Configuration::get('PS_LGCOMMENTS_SLIDER_BLOCKS')
                :4),
            'send_successfull_msg'                => $this->l('The review has been correctly sent.'),
            'review_controller_name'              => $uri_path,
            'review_controller_link'              => $link->getModuleLink(
                'lgcomments',
                'reviews',
                array(
                    'action' => 'sendReview',
                    'ajax'   => '1'
                ),
                $ssl
            ),
        );

        $this->getMedia();

        $this->context->smarty->assign($values);
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            Media::addJsDef($values);
        } else {
            return ($this->display(__FILE__, '/views/templates/front/header.tpl'));
        }
    }

    public function hookCustomStarsPosition()
    {
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_COMMENTS')) {
            return LGProductComment::render(LGProductComment::PRODUCT_EXTRA_RIGHT, $this, $this->smarty);
        }
    }

    /**
     * display Rating on Product page right column
     *
     * @return mixed
     */
    public function hookExtraRight()
    {
        if ($this->context->controller instanceof ProductController &&
            (int)Tools::getValue('id_product', 0) > 0 &&
            Configuration::get('PS_LGCOMMENTS_STARST_POSITION') == 3
        ) {
            return $this->hookCustomStarsPosition();
        }
    }

    public function hookProductTab()
    {
        if ($this->context->controller instanceof ProductController && (int)Tools::getValue('id_product', 0) > 0) {
            if (Configuration::get('PS_LGCOMMENTS_DISPLAY_COMMENTS') &&
                Configuration::get('PS_LGCOMMENTS_TAB_CONTENT') == 2
            ) {
                return LGProductComment::render(LGProductComment::PRODUCT_REVIEW_TAB, $this, $this->smarty);
            }
        }
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if ($this->context->controller instanceof ProductController &&
            Configuration::get('PS_LGCOMMENTS_STARST_POSITION') == 1 &&
            (int)Tools::getValue('id_product', 0) > 0 &&
            $params['type'] == 'after_price'
        ) {
            return $this->hookCustomStarsPosition();
        }
    }

    public function hookDisplayReassurance($params)
    {
        if ($this->context->controller instanceof ProductController &&
            Configuration::get('PS_LGCOMMENTS_STARST_POSITION') == 2 &&
            (int)Tools::getValue('id_product', 0) > 0
        ) {
            return $this->hookCustomStarsPosition();
        }
    }

    public function hookDisplayRightColumnProduct($params)
    {
        if ($this->context->controller instanceof ProductController &&
            (int)Tools::getValue('id_product', 0) > 0 &&
            Configuration::get('PS_LGCOMMENTS_STARST_POSITION') == 3
        ) {
            return $this->hookCustomStarsPosition();
        }
    }

    public function hookDisplayProductExtraContent($params)
    {
        if ($this->context->controller instanceof ProductController) {
            $this->context->smarty->assign(
                array(
                    'lgcomments_id_module' => $this->id,
                )
            );
            $array = array();
            if (Configuration::get('PS_LGCOMMENTS_TAB_CONTENT') == 3) {
                $array[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                    ->setTitle($this->l('Comments'))
                    ->setContent(
                        LGProductComment::render(LGProductComment::PRODUCT_REVIEW_CONTENT, $this, $this->smarty)
                    );
            }
            return $array;
        }
    }

    public function hookProductTabContent()
    {
        if ($this->context->controller instanceof ProductController) {
            if (Configuration::get('PS_LGCOMMENTS_DISPLAY_COMMENTS') &&
                Configuration::get('PS_LGCOMMENTS_TAB_CONTENT') >= 1) {
                return LGProductComment::render(LGProductComment::PRODUCT_REVIEW_CONTENT, $this, $this->smarty);
            }
        }
    }

    public function hookDisplayFooterProduct()
    {
        if ($this->context->controller instanceof ProductController) {
            if (Configuration::get('PS_LGCOMMENTS_TAB_CONTENT') == 1) {
                return $this->hookProductTabContent();
            }
        }
    }

    public function hookDisplayLgStoreCommentSlider()
    {
        return $this->hookDisplayHome();
    }

    /**
     * Display product rating on products lists
     *
     * @param $params
     * @return mixed
     */
    public function hookDisplayProductListReviews($params)
    {
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_COMMENTS')) {
            $id_product        = (int)$params['product']['id_product'];
            if (1 > $id_product) {
                return;
            }
            $rating_scale = Configuration::get('PS_LGCOMMENTS_SCALE');
            $number_of_reviews = LGProductComment::getNummberOfReviews($id_product);
            $sum_of_reviews = 0;
            $averagecomments = 0;
            $averagestars = 0;

            if ($number_of_reviews > 0) {
                $sum_of_reviews  = LGProductComment::getSumOfReviews($id_product);

                $averagecomments = @round($sum_of_reviews / $number_of_reviews);
                $averagestars    = ceil($averagecomments);

                // Tools::dieObject($averagecomments);
                // Tools::dieObject($averagestars);

                if ($rating_scale == 5) {
                    $averagestars = @round($averagecomments / 2 * 2);
                    $averagecomments = $averagecomments / 2;
                }
            }

            if ($number_of_reviews
                || (!$number_of_reviews && Configuration::get('PS_LGCOMMENTS_DISPLAY_ZEROSTAR'))
            ) {
                $template_name = 'product_list.tpl';
                if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
                    $template_name = 'module:'.$template_name;
                }

                // Tools::dieObject($params['product']);

                if (!$this->isCached($template_name, $this->getCacheId($id_product))) {
                    $this->context->smarty->assign(
                        array(
                            'sum_of_reviews'     => $sum_of_reviews,
                            'averagecomments'    => $averagecomments,
                            'averagestars'       => $averagestars,
                            'ratingscale'        => $rating_scale,
                            'path_lgcomments'    => _MODULE_DIR_ . $this->name,
                            'number_of_reviews'  => (int)($number_of_reviews?$number_of_reviews:0),
                            'starstyle'          => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
                            'starcolor'          => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
                            'starsize'           => Configuration::get('PS_LGCOMMENTS_STARSIZE'),
                            'displayzerostar'    => Configuration::get('PS_LGCOMMENTS_DISPLAY_ZEROSTAR'),
                            'cattopmargin'       => Configuration::get('PS_LGCOMMENTS_CATTOPMARGIN'),
                            'catbotmargin'       => Configuration::get('PS_LGCOMMENTS_CATBOTMARGIN'),
                            'productname'        => $params['product']['name'],
                            'productdescription' => $params['product']['description_short'],
                            'productsku'         => $params['product']['reference'],
                            'productbrand'       => $params['product']['manufacturer_name'],
                            'productlink'        => $this->context->link->getProductLink(
                                $id_product,
                                $this->getProductRewrite($id_product),
                                null,
                                null,
                                null,
                                null,
                                (int)$params['product']['id_product_attribute']
                            ),
                        )
                    );

                    return $this->display(__FILE__, '/views/templates/front/product_list.tpl');
                }
            }
        }
    }

    public function hookCustomLGCommentsWidgetPosition($params)
    {
        // Línea Gráfica - Carlos Utrera:
        // Extraemos la lógica de la carga del widget a una clase
        //if (version_compare(_PS_VERSION_, '1.7.0', '<')) {
        $this->context->controller->addJQuery();
        //}
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY')
            && LGCommentsWidget::isActive()
        ) {
            $this->context->smarty->assign(LGCommentsWidget::getTemplateVars($this));
            return ($this->display(__FILE__, LGCommentsWidget::getTemplate()));
        }
    }

    /**
     * displays Reviews Widget
     *
     * @return mixed
     */
    public function hookFooter($params)
    {
        if (Configuration::get('PS_LGCOMMENTS_WIDGET_HOOK') == 'displayFooter') {
            return $this->hookCustomLGCommentsWidgetPosition($params);
        }
    }

    public function hookLeftColumn($params)
    {
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_TYPE') == 2 || Configuration::get('PS_LGCOMMENTS_WIDGET_HOOK') == 'displayLeftColumn') {
            return $this->hookCustomLGCommentsWidgetPosition($params);
        }
    }

    public function hookRightColumn($params)
    {
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_TYPE') == 2 || Configuration::get('PS_LGCOMMENTS_WIDGET_HOOK') == 'displayRightColumn') {
            return $this->hookCustomLGCommentsWidgetPosition($params);
        }
    }

    /**
     * displays reviews carusel on home page
     *
     * @return mixed
     */
    public function hookDisplayHome()
    {
        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_SLIDER') != 1) {
            return;
        }
        $slidertotal = Configuration::get('PS_LGCOMMENTS_SLIDER_TOTAL');

        if (version_compare(_PS_VERSION_, '1.6.0', '>')) {
            $ps16 = true;
        } else {
            $ps16 = false;
        }

        $base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ?
            'https://' . $this->context->shop->domain_ssl :
            'http://' . $this->context->shop->domain);

        if (Configuration::get('PS_LGCOMMENTS_DISPLAY_LANGUAGE') == 1) {
            $totalcomentarios  = LGStoreComment::getSumShopCommentsByLang();
            $numerocomentarios = LGStoreComment::getCountShopCommentsByLang();
            $mediacomentarios  = @round($totalcomentarios / $numerocomentarios);
            $mediacomentarios2 = @round($totalcomentarios / $numerocomentarios, 1);
            $allreviews        = $this->getSliderShopCommentsByLang($slidertotal);
        } else {
            $totalcomentarios  = LGStoreComment::getSumShopComments();
            $numerocomentarios = LGStoreComment::getCountShopComments();
            $mediacomentarios  = @round($totalcomentarios / $numerocomentarios);
            $mediacomentarios2 = @round($totalcomentarios / $numerocomentarios, 1);
            $allreviews        = $this->getSliderShopComments($slidertotal);
        }

        $rating_scale = Configuration::get('PS_LGCOMMENTS_SCALE');
        foreach ($allreviews as $index => $c) {
            if ($rating_scale == 5) {
                $allreviews[$index]['rating'] = ceil($c['stars'] / 2);
                $allreviews[$index]['stars'] = ceil($c['stars'] / 2)*2; // Con esto siempre sale la estrella entera o no
            } elseif ($rating_scale == 5) {
                $allreviews[$index]['rating'] = $c['stars'] * 2;
            }
        }

        $lgcomments_shop_address = AddressFormat::generateAddress($this->context->shop->getAddress());
        $shop_url = $this->context->shop->getBaseURL(true, true);

        $this->context->smarty->assign(array(
            'lgcomments_shop_url' => $shop_url,
            'lgcomments_shop_address' => $lgcomments_shop_address,
            'lgcomments_content_dir'       => _MODULE_DIR_ . $this->name,
            'logo_url'    => $this->context->link->getMediaLink(_PS_IMG_.Configuration::get('PS_LOGO')),
            'numerocomentarios' => $numerocomentarios,
            'mediacomentarios'  => $mediacomentarios,
            'mediacomentarios2' => $mediacomentarios2,
            'allreviews'        => $allreviews,
            'ps16'              => $ps16,
            'displaysnippets'   => Configuration::get('PS_LGCOMMENTS_DISPLAY_SNIPPETS'),
            'storename'         => Configuration::get('PS_SHOP_NAME'),
            'address_street1'   => Configuration::get('PS_SHOP_ADDR1'),
            'address_street2'   => Configuration::get('PS_SHOP_ADDR2'),
            'address_zip'       => Configuration::get('PS_SHOP_CODE'),
            'address_city'      => Configuration::get('PS_SHOP_CITY'),
            'address_state'     => Configuration::get('PS_SHOP_STATE'),
            'address_country'   => Configuration::get('PS_SHOP_COUNTRY'),
            'address_phone'     => Configuration::get('PS_SHOP_PHONE'),
            'price_range'       => Configuration::get('PS_LGCOMMENTS_PRICE_RANGE'),
            'starstyle'         => Configuration::get('PS_LGCOMMENTS_STARDESIGN1'),
            'starcolor'         => Configuration::get('PS_LGCOMMENTS_STARDESIGN2'),
            'starsize'          => Configuration::get('PS_LGCOMMENTS_STARSIZE'),
            'ratingscale'       => Configuration::get('PS_LGCOMMENTS_SCALE'),
            'displayslider'     => Configuration::get('PS_LGCOMMENTS_DISPLAY_SLIDER'),
            'base_url'          => $base,
            'dateformat'        => LGUtils::getDateFormat(),
            'module_name'       => $this->name,
            'worstrating'       => Configuration::get('PS_LGCOMMENTS_SCALE') == 20
                ? 2
                : ( Configuration::get('PS_LGCOMMENTS_SCALE') == 5
                    ? 0.5
                    : 1
                ),
        ));
        return $this->display(__FILE__, '/views/templates/front/home_reviews.tpl');
    }

    public function hookCustomerAccount()
    {
        if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
            return $this->display(__FILE__, 'views/templates/front/account_button_17.tpl');
        } else {
            return $this->display(__FILE__, 'views/templates/front/account_button.tpl');
        }
    }

    /*************************************************************************************************************/
    /*                                                                                                           */
    /*                                                 GDPR Hooks                                                */
    /*                                                                                                           */
    /*************************************************************************************************************/

    /**
     * Hook for let user delete all info about their comments EU GDPR
     *
     * Todo: Quizás deba ser opcional a elección del admin, el convertir el comentario en anónimo o borrarlo
     *
     * @param $customer
     * @return string
     * @throws Exception
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        $customer_obj = new Customer($customer['id']);
        if (Validate::isLoadedObject($customer_obj)) {
            if (LGProductComment::anonymize($customer_obj->id)
                && LGStoreComment::anonymize($customer_obj->id)
            ) {
                return json_encode(true);
            }
            return json_encode($this->l('Comments Module: Unable to delete customer.'));
        }
    }

    /**
     * Hook for let user collect all personal data stored by our module for accomplish EU GDPR
     *
     * @param $customer
     * @return string
     * @throws Exception
     */
    public function hookActionExportGDPRData($customer)
    {
        $customer_obj = new Customer($customer['id']);
        if (Validate::isLoadedObject($customer_obj)) {
            $res = array_merge(
                array(
                    0 => array('Store' => '')
                ),
                LGStoreComment::exportData($customer_obj->id),
                array(
                    0 => array('Products' => '')
                ),
                LGProductComment::exportData($customer_obj->id)
            );
            if ($res) {
                return json_encode($res, true);
            }
            return json_encode($this->l('Comments Module: Unable to collect data for customer.'));
        } else {
            return json_encode($this->l('Comments Module: User does not exists.'));
        }
    }

    /*************************************************************************************************************/
    /*                                                                                                           */
    /*                                              Useful methods                                               */
    /*                                                                                                           */
    /*************************************************************************************************************/

    /* Retrocompatibility 1.4/1.5 */
    // Todo: Remove for final 1.7 version when 1.7 and 1.6 version will have different branches
    private function initContext()
    {
        $this->context = Context::getContext();
    }

    private function getP()
    {
        $default_lang = $this->context->language->id;
        $lang         = Language::getIsoById($default_lang);
        $pl           = array('es', 'fr');
        if (!in_array($lang, $pl)) {
            $lang = 'en';
        }
        $this->context->controller->addCSS(_MODULE_DIR_ . $this->name . '/views/css/publi/style.css');
        $base = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ?
            'https://' . $this->context->shop->domain_ssl :
            'http://' . $this->context->shop->domain);
        if (version_compare(_PS_VERSION_, '1.5.0', '>')) {
            $uri = $base . $this->context->shop->getBaseURI();
        } else {
            $uri = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ?
                    'https://' . _PS_SHOP_DOMAIN_SSL_DOMAIN_ :
                    'http://' . _PS_SHOP_DOMAIN_) . __PS_BASE_URI__;
        }
        $path = _PS_MODULE_DIR_ . $this->name
            . DIRECTORY_SEPARATOR . 'views'
            . DIRECTORY_SEPARATOR . 'publi'
            . DIRECTORY_SEPARATOR . $lang
            . DIRECTORY_SEPARATOR . 'index.php';
        $object = Tools::file_get_contents($path);
        $object = str_replace('src="/modules/', 'src="' . $uri . 'modules/', $object);

        return $object;
    }

    /**
     * In order to could add html anchors to error, warning and success messages, we need to use a template to fit to
     * Prestashop standars
     *
     * @param $array
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function getAnchor($array)
    {
        $this->context->smarty->assign($array);
        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->name
            .DIRECTORY_SEPARATOR.'views'
            .DIRECTORY_SEPARATOR.'templates'
            .DIRECTORY_SEPARATOR.'admin'
            .DIRECTORY_SEPARATOR.'_partials'
            .DIRECTORY_SEPARATOR.'warningLink.tpl'
        );
    }

    /**
     * Check if Prestashop GDPR module is installed and enabled
     *
     * @return bool
     */
    public static function checkPrestashopGDPRModuleInstalled()
    {
        if (class_exists('ModuleManagerBuilder')) {
            $moduleManager = ModuleManagerBuilder::getInstance()->build();
            return $moduleManager->isInstalled('bestkit_opc') && $moduleManager->isEnabled('bestkit_opc');
        }
    }

    /**
     * Add a new backoffice menu section
     *
     * @param $class
     * @param $name
     * @return bool
     */
    private function installModuleTab($class, $name)
    {
        $sql = 'SELECT `id_tab` FROM `' . _DB_PREFIX_ . 'tab` WHERE `class_name` = "AdminCatalog"';

        $tabParent = (int)(Db::getInstance()->getValue($sql));

        if (!is_array($name)) {
            $name = self::getMultilangField($name);
        }

        $tab = new Tab();
        $tab->name = $name;
        $tab->class_name = $class;
        $tab->module = $this->name;
        $tab->id_parent = $tabParent;
        return $tab->save();
    }

    private static function getMultilangField($field)
    {
        $languages = Language::getLanguages();
        $res = array();

        foreach ($languages as $lang) {
            $res[$lang['id_lang']] = $field;
        }
        return $res;
    }

    // Todo: Eliminar este método ya que es para compatibilidad con versiones 1.5
    private function formatBootstrap($text)
    {
        $text = str_replace('<fieldset>', '<div class="panel">', $text);
        $text = str_replace(
            '<fieldset style="background:#DFF2BF;color:#4F8A10;border:1px solid #4F8A10;">',
            '<div class="panel"  style="background:#DFF2BF;color:#4F8A10;border:1px solid #4F8A10;">',
            $text
        );
        $text = str_replace('</fieldset>', '</div>', $text);
        $text = str_replace('<legend>', '<h3>', $text);
        $text = str_replace('</legend>', '</h3>', $text);
        return $text;
    }

    /* CONFIGURATION MODULE */

    private function getLGCommentsOrderStatus($id_state)
    {
        $estado = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_status ' .
            'WHERE id_order_status = ' . (int)$id_state
        );
        return $estado;
    }

    private function checkIfEmptyStore()
    {
        $checkS = Db::getInstance()->getValue(
            'SELECT COUNT(*) ' .
            'FROM ' . _DB_PREFIX_ . LGStoreComment::$definition['table']
        );
        return $checkS;
    }

    private function checkIfEmptyProduct()
    {
        $checkP = Db::getInstance()->getValue(
            'SELECT COUNT(*) ' .
            'FROM ' . _DB_PREFIX_ . LGProductComment::$definition['table']
        );
        return $checkP;
    }

    private function checkIfEmptyStatus()
    {
        $checkE = Db::getInstance()->getValue(
            'SELECT COUNT(*) ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_status'
        );
        return $checkE;
    }

    private function checkIfEmptyGroup()
    {
        $checkG = Db::getInstance()->getValue(
            'SELECT COUNT(*) ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_customergroups'
        );
        return $checkG;
    }

    private function checkIfEmptyMultistore()
    {
        $checkM = Db::getInstance()->getValue(
            'SELECT COUNT(*) ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_multistore'
        );
        return $checkM;
    }

    private function getHookList()
    {
        $hookList = Db::getInstance()->ExecuteS(
            'SELECT h.name ' .
            'FROM ' . _DB_PREFIX_ . 'hook h ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'hook_module hm ' .
            'ON h.id_hook = hm.id_hook ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'module m ' .
            'ON hm.id_module = m.id_module ' .
            'WHERE m.name = "lgcomments" ' .
            'AND hm.id_shop = ' . (int)$this->context->shop->id
        );
        return $hookList;
    }

    private function getCustomerGroupsByGroup($id_group)
    {
        $grupog = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_customergroups ' .
            'WHERE id_customer_group = ' . (int)$id_group
        );
        return $grupog;
    }

    private function getCustomerGroupsByCustomer($customer_id)
    {
        $groups = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT lcg.*, gl.name ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_customergroups lcg ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'group_lang gl ON lcg.id_customer_group = gl.id_group ' .
            'RIGHT JOIN ' . _DB_PREFIX_ . 'customer_group cg ON gl.id_group = cg.id_group ' .
            'WHERE gl.id_lang = ' . (int)$this->context->language->id . ' ' .
            'AND cg.id_customer = ' . (int)$customer_id
        );
        return $groups;
    }

    private function getSelectedShops($id_shop)
    {
        $shop = Db::getInstance()->ExecuteS(
            'SELECT * ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_multistore ' .
            'WHERE id_shop = ' . (int)$id_shop
        );
        return $shop;
    }

    private function getShopsByCustomer($id_customer)
    {
        $cshop = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT lm.*, s.name ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_multistore lm ' .
            'LEFT JOIN ' . _DB_PREFIX_ . 'customer c ON lm.id_shop = c.id_shop ' .
            'LEFT JOIN ' . _DB_PREFIX_ . 'shop s ON lm.id_shop = s.id_shop ' .
            'WHERE c.id_customer = ' . (int)$id_customer
        );
        return $cshop;
    }

    public function getCorrespondingOrders($date1, $date2)
    {
        if (Configuration::get('PS_LGCOMMENTS_BOXES') == 2) {
            $boxes_checked = 'AND c.newsletter = 1 ';
        } elseif (Configuration::get('PS_LGCOMMENTS_BOXES') == 3) {
            $boxes_checked = 'AND c.optin = 1 ';
        } elseif (Configuration::get('PS_LGCOMMENTS_BOXES') == 4) {
            $boxes_checked = 'AND c.newsletter = 1 AND c.optin = 1 ';
        } else {
            $boxes_checked = '';
        }
        $orderList = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT o.id_order, o.id_customer, o.reference, o.date_add, osl.name as statusname, ' .
            'lo.date_email, lo.voted, lo.sent, lo.date_email2, os.color, c.newsletter, c.optin, ' .
            'CONCAT(c.firstname, " ", (SUBSTRING(c.lastname,1,1)), ".") as customer ' .
            'FROM ' . _DB_PREFIX_ . 'orders o ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'lgcomments_status ek ON o.current_state = ek.id_order_status ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'order_state_lang osl ON o.current_state = osl.id_order_state ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'order_state os ON osl.id_order_state = os.id_order_state ' .
            'LEFT JOIN ' . _DB_PREFIX_ . 'lgcomments_orders lo ON o.id_order = lo.id_order ' .
            'RIGHT JOIN ' . _DB_PREFIX_ . 'customer_group cg ON o.id_customer = cg.id_customer ' .
            'RIGHT JOIN ' . _DB_PREFIX_ . 'customer c ON o.id_customer = c.id_customer ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'lgcomments_customergroups lcg ON cg.id_group = lcg.id_customer_group ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'group_lang gl ON cg.id_group = gl.id_group ' .
            'RIGHT JOIN ' . _DB_PREFIX_ . 'lgcomments_multistore lm ON o.id_shop = lm.id_shop ' .
            'WHERE o.date_add >= DATE_SUB(NOW(),INTERVAL ' . (int)$date1 . ' DAY) ' .
            'AND o.date_add <= DATE_SUB(NOW(),INTERVAL ' . (int)$date2 . ' DAY) ' .
            'AND osl.id_lang = ' . (int)$this->context->language->id . ' ' .
            'AND gl.id_lang = ' . (int)$this->context->language->id . ' ' .
            $boxes_checked .
            'ORDER BY o.id_order DESC'
        );
        return $orderList;
    }

    public function getCorrespondingStatus()
    {
        $statusList = Db::getInstance()->ExecuteS(
            'SELECT osl.name, os.color ' .
            'FROM ' . _DB_PREFIX_ . 'order_state_lang osl ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'lgcomments_status lgs ON osl.id_order_state = lgs.id_order_status ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'order_state os ON osl.id_order_state = os.id_order_state ' .
            'WHERE osl.id_lang = ' . (int)$this->context->language->id . ''
        );
        return $statusList;
    }

    public function getCorrespondingGroups()
    {
        $groupList = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT lcg.*, gl.name ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_customergroups lcg ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'group_lang gl ON lcg.id_customer_group = gl.id_group ' .
            'WHERE gl.id_lang = ' . (int)$this->context->language->id . ''
        );
        return $groupList;
    }

    public function getCorrespondingShops()
    {
        $shopList = Db::getInstance()->ExecuteS(
            'SELECT DISTINCT lm.*, s.name ' .
            'FROM ' . _DB_PREFIX_ . 'lgcomments_multistore lm ' .
            'INNER JOIN ' . _DB_PREFIX_ . 'shop s ON lm.id_shop = s.id_shop '
        );
        return $shopList;
    }

    private function getCronUrl($secure_key)
    {
        $link = new Link();
        $ssl = Configuration::get('PS_SSL_ENABLED_EVERYWHERE') && Configuration::get('PS_SSL_ENABLED');

        return $link->getModuleLink(
            $this->name,
            'cron',
            array(
                'securekey' => $secure_key
            ),
            $ssl
        );
    }

    /* PRODUCT COMMENTS */

    public function getProdComments($id_product, $order)
    {
        $comments = Db::getInstance()->executeS(
            'SELECT pc.*, CONCAT(c.firstname, " ", (SUBSTRING(c.lastname,1,1)), ".") as customer ' .
            'FROM ' . _DB_PREFIX_ . LGProductComment::$definition['table'] . ' pc ' .
            'LEFT JOIN ' . _DB_PREFIX_ . 'customer as c ON pc.id_customer = c.id_customer ' .
            'WHERE pc.id_product = ' . (int)$id_product . ' ' .
            'AND pc.active = 1 ' .
            'ORDER BY pc.position ' . pSQL($order)
        );
        return $comments;
    }

    private function getProductRewrite($id_product)
    {
        $rewrite = Db::getInstance()->getValue(
            'SELECT link_rewrite ' .
            'FROM ' . _DB_PREFIX_ . 'product_lang ' .
            'WHERE id_product = ' . (int)$id_product .
            ' AND id_lang = ' . (int)$this->context->language->id
        );
        return $rewrite;
    }

    /* SHOP COMMENTS */

    public function getSliderShopComments($number)
    {
        $slider = Db::getInstance()->executeS(
            'SELECT st.*, CONCAT(c.firstname, " ", (SUBSTRING(c.lastname,1,1)), ".") as customer, ' .
            (
                Configuration::get('PS_LGCOMMENTS_SCALE') == 20
                    ? '( st.stars * 2 ) '
                    : ( Configuration::get('PS_LGCOMMENTS_SCALE') == 5
                    ? ' ROUND( ( st.stars / 2 ), 1) '
                    : ' st.stars '
                )
            ).
            'AS rating '.
            'FROM `' . _DB_PREFIX_ . LGStoreComment::$definition['table'] . '` st ' .
            'LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON st.`id_customer` = c.`id_customer` ' .
            'WHERE st.active = 1 ' .
            'ORDER BY st.date DESC ' .
            'LIMIT ' . (int)$number
        );
        return $slider;
    }

    public function getSliderShopCommentsByLang($number)
    {
        $sliderL = Db::getInstance()->executeS(
            'SELECT st.*, CONCAT(c.firstname, " ", (SUBSTRING(c.lastname,1,1)), ".") as customer, ' .
            (
                Configuration::get('PS_LGCOMMENTS_SCALE') == 20
                    ? '( st.stars * 2 ) '
                    : ( Configuration::get('PS_LGCOMMENTS_SCALE') == 5
                    ? ' ROUND( ( st.stars / 2 ), 1) '
                    : ' st.stars '
                )
            ).
            'AS rating '.
            'FROM `' . _DB_PREFIX_ . LGStoreComment::$definition['table'] . '` st ' .
            'LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON st.`id_customer` = c.`id_customer` ' .
            'WHERE st.active = 1 ' .
            'AND st.id_lang = ' . (int)$this->context->language->id . ' ' .
            'ORDER BY st.date DESC ' .
            'LIMIT ' . (int)$number
        );
        return $sliderL;
    }

    public function getDateFormatFull()
    {
        $format = Db::getInstance()->getValue(
            'SELECT date_format_full ' .
            'FROM ' . _DB_PREFIX_ . 'lang ' .
            'WHERE id_lang = ' . (int)$this->context->language->id
        );
        return $format;
    }

    public function getMailTemplateProducts($products)
    {
        $this->smarty->assign('products', $products);
        return $this->display(__FILE__, 'views/templates/admin/product_details.tpl');
    }

    protected function forceNickToValue($nick, $table)
    {
        $result = DB::getInstance()->update(
            $table,
            array('nick' => $nick),
            '`nick` = ""',
            0,
            false,
            false // Para forzar no cache
        );

        return $result;
    }

    protected function getInitial($string)
    {
        if (!empty($string)) {
            $string = Tools::substr($string, 0, 1);
        }

        return $string;
    }

    protected function forceNickFromUsername($table)
    {
        if ($table == LGStoreComment::$definition['table']) {
            $primary = LGStoreComment::$definition['primary'];
        } else {
            $primary = LGProductComment::$definition['primary'];
        }
        $query = new DbQueryCore();
        $query->select('sc.`'.$primary.'`, c.`firstname`, c.`lastname`');
        $query->from($table, 'sc');
        $query->leftJoin('customer', 'c', 'sc.`id_customer` = c.`id_customer`');
        $query->where('sc.`nick` = "" OR sc.`nick` IS NULL');
        $query->where('sc.`id_customer` > 0 AND sc.`id_customer` IS NOT NULL');
        $customers = Db::getInstance()->executeS($query);
        foreach ($customers as $customer) {
            $idstorecomment = (int)$customer[$primary];
            if ($table == LGStoreComment::$definition['table']) {
                $review = new LGStoreComment($idstorecomment);
            } else {
                $review = new LGProductComment($idstorecomment);
            }
            if (ValidateCore::isLoadedObject($review)) {
                $review->nick = Tools::strtoupper($this->getInitial($customer['firstname']))
                    . '. ' . $customer['lastname'];
                $review->save();
            }
        }
    }

    /*************************************************************************************************************/
    /*                                                                                                           */
    /*                                          Methods for process Ajax                                         */
    /*                                                                                                           */
    /*************************************************************************************************************/

    public function ajaxProcessForceNickRepairStore()
    {
        $response      = array();
        $response_code = 400;

        try {
            if (LGCommentsAjax::checkToken()) {
                $whattodo = (int)Tools::getValue('whattodo', 0);
                if ($whattodo) {
                    switch ($whattodo) {
                        case 3: // Force a Name
                            $nick = pSQL(Tools::getValue('nick', ''));
                            if (empty($nick)) {
                                throw new Exception($this->l('You haven\'t provide a Nick'));
                            } else {
                                if (!$this->forceNickToValue($nick, LGStoreComment::$definition['table'])) {
                                    throw new Exception($this->l('Error updating empty nicks to '). $nick);
                                }
                            }
                            break;
                        case 2:
                            $this->forceNickFromUsername(LGStoreComment::$definition['table']);
                            $response['status']  = 'success';
                            $response['message'] = $this->l('Store Reviews Nicks updated with success');
                            break;
                        case 1:
                            $nick = $this->l('Anonymous'); // Realmente hay que ponerlo en el lenguage que corresponda
                            if (!$this->forceNickToValue($nick, LGStoreComment::$definition['table'])) {
                                throw new Exception($this->l('Error updating empty nicks to '). $nick);
                            }
                            break;
                        case 0:
                        default:
                            throw new Exception($this->l('Nothing selected'));
                    }
                } else {
                    throw new Exception($this->l('No action selected'));
                }
                $response_code       = 200;
                $response['status']  = 'success';
                $response['message'] = $this->l('Store Reviews Nicks updated with success');
                LGCommentsAjax::returnResponse($response, $response_code);
            }
        } catch (Exception $e) {
            $response_code                = 400;
            $response['status']           = 'error';
            $response['error']['code']    = $e->getCode();
            $response['error']['message'] = $e->getMessage();
            //$response['error']['traze']   = $e->getTrace();
            LGCommentsAjax::returnResponse($response, $response_code);
        }
    }

    public function ajaxProcessForceNickRepairProducts()
    {
        $response      = array();
        $response_code = 400;

        try {
            if (LGCommentsAjax::checkToken()) {
                $whattodo = (int)Tools::getValue('whattodo', 0);
                if ($whattodo) {
                    switch ($whattodo) {
                        case 3: // Force a Name
                            $nick = pSQL(Tools::getValue('nick', ''));
                            if (empty($nick)) {
                                throw new Exception($this->l('You haven\'t provide a Nick'));
                            } else {
                                if (!$this->forceNickToValue($nick, LGProductComment::$definition['table'])) {
                                    throw new Exception($this->l('Error updating empty nicks to '). $nick);
                                }
                            }
                            break;
                        case 2:
                            $this->forceNickFromUsername(LGProductComment::$definition['table']);
                            $response['status']  = 'success';
                            $response['message'] = $this->l('Product Reviews Nicks updated with success');
                            break;
                        case 1:
                            $nick = $this->l('Anonymous'); // Realmente hay que ponerlo en el lenguage que corresponda
                            if (!$this->forceNickToValue($nick, LGProductComment::$definition['table'])) {
                                throw new Exception($this->l('Error updating empty nicks to '). $nick);
                            }
                            break;
                        case 0:
                        default:
                            throw new Exception($this->l('Nothing selected'));
                    }
                } else {
                    throw new Exception($this->l('No action selected'));
                }
                $response_code       = 200;
                $response['status']  = 'success';
                $response['message'] = $this->l('Product Reviews Nicks updated with success');
                LGCommentsAjax::returnResponse($response, $response_code);
            }
        } catch (Exception $e) {
            $response_code                = 400;
            $response['status']           = 'error';
            $response['error']['code']    = $e->getCode();
            $response['error']['message'] = $e->getMessage();
            //$response['error']['traze']   = $e->getTrace();
            LGCommentsAjax::returnResponse($response, $response_code);
        }
    }
}
