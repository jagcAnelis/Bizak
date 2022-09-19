<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * @author    knowband.com <support@knowband.com>
 * @copyright 2017 knowband
 * @license   see file: LICENSE.txt
 * @category  PrestaShop Module
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once dirname(__FILE__) . '/classes/admin/CommonCoupon.php';

/**
 * The parent class is extending the "Module" core class.
 * So no need to extend "Module" core class here in this class.
 */
class BirthdayCoupon extends CommonCoupon
{
    const BIRTHDAY_COUPON_MODULE_CONFIGURATION = 'BIRTHDAYCOUPON_MODULE';
    const BIRTHDAY_COUPON_EMAIL_CONFIGURATION = 'BI_CO_EMAIL_CONFIGURATION';
    const BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION = 'BI_CO_EMAIL_ANNIVERSARY_CONFIGURATION';
    const BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION = 'BI_CO_CATEGORY_RESTRICTION';
    const BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION = 'BI_CO_COUNTRY_RESTRICTION';
    const ADMIN_BIRTHDAY_COUPON_CONTROLLER = 'AdminBirthdayCoupon';
    const BIRTHDAY_COUPON_CRON_SECURE_KEY = 'BI_CO_SECURE_KEY';
    const BIRTHDAY_COUPON_STORE_VISIT_EXECUTED = 'BI_CO_STORE_VISIT_EXECUTED';

    public function __construct()
    {
        $this->name = 'birthdaycoupon';
        $this->tab = 'front_office_features';
        $this->version = '1.0.5';
        $this->author = 'knowband';
        $this->need_instance = 0;
        $this->module_key = '50af5a6d2808d4adcd9e3385745fd984';
        $this->author_address = '0x2C366b113bd378672D4Ee91B75dC727E857A54A6';
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Birthday Coupon');
        $this->description = $this->l('Automatic sending of coupons according to your choice customer birthday or anniversary of the inscription on your site.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('BIRTHDAYCOUPON_NAME')) {
            $this->warning = $this->l('No name provided');
        }
    }
    private function getDefaultSettings()
    {
        $settings = array(
            'birthday_coupon' => array(
                'enable' => 0,
                'GDPR_compatibility_status' => 0,
                'prefix' => 'BIRTHDAY_',
                'highlight' => 1,
                'partial_use' => 0,
                'type_birthday' => 1,
                'validity' => 7,
                'minimum_amount' => 0,
                'minimum_amount_currency' => '',
                'tax_included' => 0,
                'shipping_included' => 0,
                'valid_order' => 1,
                'free_shipping' => 1,
                'apply_discount_type' => 1,
                'discount_percent_value' => '',
                'discount_amount_value' => '',
                'discount_amount_currency' => '',
                'discount_tax_included' => '',
                'send_free_gift' => '',
                'send_free_gift_hidden' =>'',
                'number_of_days' => 1,
                'cron_type' => 1,
                'cron_execution' => '',
                
            )
        );
        //default value multi language text box
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $settings['birthday_coupon']['main_title'][$lang['id_lang']] = '';
        }
        return $settings;
    }
    private function getDefaultEmailSettings()
    {
        $settings = array(
            'birthday_coupon' => array(
                'email_templates' => 'Common',
            )
        );
        //default value multi language text box
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $settings['birthday_coupon']['email_subject'][$lang['id_lang']] = 'Congratulations! Here\'s a coupon for you.';
        }
        return $settings;
    }
    private function getDefaultSettingsForCountryRestriction()
    {
        $settings = array(
            'birthday_coupon' => array(
                'enable_country_restriction' => 0,
                'allowed_country'=> array()
            )
        );
        return $settings;
    }
    private function getDefaultSettingsForCategoryRestriction()
    {
        $settings = array(
            'birthday_coupon' => array(
                'enable_category_restriction' => 0,
                'prestashop_category'=> array()
            )
        );
        return $settings;
    }
    
    /*
    * Function to insert email templates in DB and assigning variables to email templates
    *
    *  @param    Array template_data    Contains template data which is to be inserted
    *  @param    boolean return    True if email is inserted
    */
    protected function insertEmailDefaultData($template_data)
    {
        foreach (Language::getLanguages(false) as $lang) {
            //$template_data['body'] = str_replace('{minimal_img_path}', $this->getModuleDirUrl() . 'spinwheel/views/img/admin/email/minimal6.png', $template_data['body']);
            //$template_data['body'] = str_replace('{icon_img_path}', $this->getModuleDirUrl() . 'spinwheel/views/img/admin/email/ICON.png', $template_data['body']);
            $this->saveEmailTemplate($lang['id_lang'], $template_data['body'], $template_data['name']);
        }
        return true;
    }
    /*
     * Method to install the module
     */
    public function install()
    {
        //Sql query for creating a mapping table from cart_rule
        $sql = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "birthday_coupon_cart_rule_mapping ( "
                . "`id_birthday_coupon` BIGINT NOT NULL AUTO_INCREMENT , "
                . "`id_cart_rule` BIGINT NOT NULL , "
                . "`id_customer` BIGINT NOT NULL , "
                . "`date_added` DATETIME NOT NULL ,"
                . "`date_updated` DATETIME NOT NULL ,"
                . " PRIMARY KEY (`id_birthday_coupon`)) ENGINE = InnoDB";
         //Create email templates table
        $query = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'birthday_coupon_email` (
			`id_template` int(10) NOT NULL auto_increment,
			`id_lang` int(10) NOT NULL,
			`id_shop` INT(11) NOT NULL DEFAULT  "0",			
			`template_name` varchar(255) NOT NULL,
			`text_content` text NOT NULL,
			`body` text NOT NULL,
			`date_added` DATETIME NULL,
			`date_updated` DATETIME NULL,
			PRIMARY KEY (`id_template`),
			INDEX (  `id_lang` )
			) ENGINE = InnoDB';
       
        //for creating language directry for email
        $mail_dir = dirname(__FILE__) . '/mails/en';
        foreach (Language::getLanguages(false) as $lang) {
            if ($lang['iso_code'] != 'en') {
                $new_dir = dirname(__FILE__) . '/mails/' . $lang['iso_code'];
                if (!file_exists($new_dir)) {
                    $this->copyfolder($mail_dir, $new_dir);
                }
            }
        }
       
        //cretaing tab for registering the controller
        $tab = new Tab();
        $tab->active = 0;
        $tab->class_name = self::ADMIN_BIRTHDAY_COUPON_CONTROLLER;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $this->l('Birthday Coupon');
        }
        $tab->id_parent = 0;
        $tab->module = $this->name;
        $tab->add();
        
        $results1 = Db::getInstance()->execute($sql);
        $results2 = Db::getInstance()->execute($query);
        
        //Inserting default email template
        $template_aqua = $this->getDefaultAquaEmail();
        $this->insertEmailDefaultData($template_aqua);
        $template_wind = $this->getDefaultWindEmail();
        $this->insertEmailDefaultData($template_wind);
        $template_common = $this->getDefaultCommonEmail();
        $this->insertEmailDefaultData($template_common);
        $template_earth = $this->getDefaultEarthEmail();
        $this->insertEmailDefaultData($template_earth);
        $template_fire = $this->getDefaultFireEmail();
        $this->insertEmailDefaultData($template_fire);

        $defaultsettings = $this->getDefaultSettings();
        if (!parent::install() ||
                !$results1 || !$results2 ||
                !$this->registerHook('displayFooter') ||
                !$this->registerHook('actionExportGDPRData') ||
                !$this->registerHook('actionDeleteGDPRCustomer') ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_CRON_SECURE_KEY, $this->kbgcSecureKeyGenerator()) ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION, serialize($defaultsettings)) ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_EMAIL_CONFIGURATION, serialize($this->getDefaultEmailSettings())) ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION, serialize($this->getDefaultEmailSettings())) ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION, serialize($this->getDefaultSettingsForCategoryRestriction())) ||
                !Configuration::updateValue(self::BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION, serialize($this->getDefaultSettingsForCountryRestriction()))) {
            return false;
        }
        return true;
    }
    
    /*
     * Method to uninstall the module
     */
    public function uninstall()
    {
        //deleting table
//        $sql = "DROP TABLE IF EXISTS " . _DB_PREFIX_ . "birthday_coupon_cart_rule_mapping";
        $query = "DROP TABLE IF EXISTS " . _DB_PREFIX_ . "birthday_coupon_email";
//        $results1 = Db::getInstance()->execute($sql);
        $results2 = Db::getInstance()->execute($query);
        // Deleting Tabs
        $id_tab = Tab::getIdFromClassName(self::ADMIN_BIRTHDAY_COUPON_CONTROLLER);
        if ($id_tab) {
            $tab = new Tab($id_tab);
            $tab->delete();
        }
        if (!parent::uninstall() ||
                !$results2 ||
                !$this->unregisterHook('displayFooter') ||
                !$this->unregisterHook('actionDeleteGDPRCustomer') ||
                !$this->unregisterHook('actionExportGDPRData') ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_CRON_SECURE_KEY) ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION) ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_EMAIL_CONFIGURATION) ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION) ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION) ||
                !Configuration::deleteByName(self::BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION)) {
            return false;
        }
        return true;
    }
    public function getContent()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        $output = null;
        
        //setting default tab open
        $this->context->smarty->assign('selected_tab', 'bthcpn_general_setting');
        //$this->context->smarty->assign('selected_tab', 'bthcpn_statistics');

        $languages = Language::getLanguages(false);
        foreach ($languages as $k => $language) {
            $languages[$k]['is_default'] = ((int) ($language['id_lang'] == $this->context->language->id));
        }
        if (Tools::isSubmit('submitFilterbirthday_coupon_cart_rule_mapping')) {
            if ((int) Tools::getValue('submitFilterbirthday_coupon_cart_rule_mapping')==0) {
                Tools::redirectAdmin(AdminController::$currentIndex
                            . '&token='. Tools::getAdminTokenLite('AdminModules')
                            . '&configure=' . $this->name
                            . '&tab_module='. $this->tab
                            . '&module_name='. $this->name
                            . '&reset_load=true');
            }
        }
        if (Tools::isSubmit('birthday_coupon') && Tools::getIsset('birthday_coupon_submit')) {
            $submitted_data=array('birthday_coupon'=>Tools::getValue('birthday_coupon'));
            //Code for getting the language from form and converting it to required format
            foreach ($languages as $lang) {
                $submitted_data['birthday_coupon']['main_title'][$lang['id_lang']] = Tools::getValue('main_title_' . $lang['id_lang']);
            }
            //Updating the value in the configuration table
            Configuration::updateValue(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION, serialize($submitted_data));
            $this->context->smarty->assign('selected_tab', 'bthcpn_general_setting');
            $output .= $this->displayConfirmation($this->l('Configuration has been updated successfully.'));
        }

        if (Tools::isSubmit('birthday_coupon') && Tools::getIsset('country_restriction_form_submit')) {
            $submitted_data=array('birthday_coupon'=>Tools::getValue('birthday_coupon'));
            if (!array_key_exists('allowed_country', $submitted_data['birthday_coupon'])) {
                 $submitted_data['birthday_coupon']['allowed_country']=array();
            }
            Configuration::updateValue(self::BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION, serialize($submitted_data));
            $this->context->smarty->assign('selected_tab', 'bthcpn_country_restriction');
            $output .= $this->displayConfirmation($this->l('Country restriction has been updated successfully.'));
        }

        if (Tools::isSubmit('birthday_coupon') && Tools::getIsset('category_restriction_form_submit')) {
            $submitted_data=array('birthday_coupon'=>Tools::getValue('birthday_coupon'));
            if (Tools::getIsset('birthday_couponprestashop_category')) {
                 $submitted_data['birthday_coupon']['prestashop_category']=Tools::getValue('birthday_couponprestashop_category');
            }
            if (!array_key_exists('prestashop_category', $submitted_data['birthday_coupon'])) {
                 $submitted_data['birthday_coupon']['prestashop_category']=array();
            }
            Configuration::updateValue(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION, serialize($submitted_data));
            $this->context->smarty->assign('selected_tab', 'bthcpn_category_restriction');
            $output .= $this->displayConfirmation($this->l('Category restriction has been updated successfully.'));
        }
        if (Tools::isSubmit('birthday_coupon') && Tools::getIsset('email_setting_form')) {
            if ($this->saveEmailSetting(Tools::getAllValues(), $languages)) {
                $output .= $this->displayConfirmation($this->l('Email setting has been updated successfully.'));
            }
            $this->context->smarty->assign('selected_tab', 'bthcpn_email_setting');
        }
        //getting fields value of the form
        $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
        $form_value_country_restriction=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION));
        $form_value_category_restriction=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION));

        //Setting fields value of the forms
        $field_value=array(
            'birthday_coupon[enable]'=>$form_value['birthday_coupon']['enable'],
            'birthday_coupon[GDPR_compatibility_status]'=>$form_value['birthday_coupon']['GDPR_compatibility_status'],
            'birthday_coupon[prefix]'=>$form_value['birthday_coupon']['prefix'],
            'birthday_coupon[highlight]'=>$form_value['birthday_coupon']['highlight'],
            'birthday_coupon[partial_use]'=>$form_value['birthday_coupon']['partial_use'],
            'birthday_coupon[type_birthday]'=>$form_value['birthday_coupon']['type_birthday'],
            'birthday_coupon[validity]'=>$form_value['birthday_coupon']['validity'],
            'birthday_coupon[minimum_amount]'=>$form_value['birthday_coupon']['minimum_amount'],
            'birthday_coupon[minimum_amount_currency]'=>$form_value['birthday_coupon']['minimum_amount_currency'],
            'birthday_coupon[tax_included]'=>$form_value['birthday_coupon']['tax_included'],
            'birthday_coupon[shipping_included]'=>$form_value['birthday_coupon']['shipping_included'],
            'birthday_coupon[valid_order]'=>$form_value['birthday_coupon']['valid_order'],
            'birthday_coupon[free_shipping]'=>$form_value['birthday_coupon']['free_shipping'],
            'birthday_coupon[apply_discount_type]'=>$form_value['birthday_coupon']['apply_discount_type'],
            'birthday_coupon[discount_percent_value]'=>$form_value['birthday_coupon']['discount_percent_value'],
            'birthday_coupon[discount_amount_value]'=>$form_value['birthday_coupon']['discount_amount_value'],
            'birthday_coupon[discount_amount_currency]'=>$form_value['birthday_coupon']['discount_amount_currency'],
            'birthday_coupon[discount_tax_included]'=>$form_value['birthday_coupon']['discount_tax_included'],
            'birthday_coupon[send_free_gift]'=>$form_value['birthday_coupon']['send_free_gift'],
            'birthday_coupon[send_free_gift_hidden]'=>$form_value['birthday_coupon']['send_free_gift_hidden'],
            'birthday_coupon[number_of_days]'=>$form_value['birthday_coupon']['number_of_days'],
            'birthday_coupon[cron_type]'=>$form_value['birthday_coupon']['cron_type'],
            'birthday_coupon[cron_execution]'=>$form_value['birthday_coupon']['cron_execution'],
            'birthday_coupon_submit'=>'birthday_coupon_submit',
        );
        $field_value_country_restriction=array(
            'birthday_coupon[enable_country_restriction]'=>$form_value_country_restriction['birthday_coupon']['enable_country_restriction'],
            'birthday_coupon[allowed_country][]'=>$form_value_country_restriction['birthday_coupon']['allowed_country'],
            'country_restriction_form_submit'=>'country_restriction_form_submit',
        );
        $field_value_category_restriction=array(
            'birthday_coupon[enable_category_restriction]'=>$form_value_category_restriction['birthday_coupon']['enable_category_restriction'],
            'category_restriction_form_submit'=>'category_restriction_form_submit',
        );

        $action = AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules');

        //setting the css and js
        $this->addBackOfficeMedia();
        
        //getting form
        $this->fields_form = $this->getBirthdayCouponFields();
        $this->fields_form1 = $this->getEmailSettingForm((int)$form_value['birthday_coupon']['type_birthday']);
        $this->fields_form2 = $this->getCountryRestrictionForm();
        $this->fields_form3 = $this->getCategoryTabFields();
        $statistics_data=$this->getDataforGraph(date('d/m/Y', strtotime('-7 days')), date('d/m/Y'));

        //converting form in html
        $form = $this->getFormHtml($this->fields_form, $languages, $field_value, $action, $form_value, 'config');
        $email_setting_form = $this->getFormHtmlForEmail($this->fields_form1, $languages, $action, (int)$form_value['birthday_coupon']['type_birthday']);
        $country_restriction_form = $this->getFormHtml($this->fields_form2, $languages, $field_value_country_restriction, $action);
        $category_restriction_form = $this->getFormHtml($this->fields_form3, $languages, $field_value_category_restriction, $action);
        
        //Persisting free gift product
        if ($form_value['birthday_coupon']['send_free_gift_hidden']!=null || $form_value['birthday_coupon']['send_free_gift_hidden']!='') {
            $gift_product_data = $this->getProductDataLight(
                $this->context->language->id,
                (int)$form_value['birthday_coupon']['send_free_gift_hidden']
            );
            $this->context->smarty->assign('gift_product', $gift_product_data);
        }

        $current_time = date('Y-m-d h:i:s');
        $pasttime = date('Y-m-d h:i:s', strtotime('-7 days'));

        $this->context->smarty->assign(array(
            'admin_birthday_coupon_controller'=>$this->context->link->getAdminLink(self::ADMIN_BIRTHDAY_COUPON_CONTROLLER, true),
            'kb_current_token' => Tools::getAdminTokenLite(self::ADMIN_BIRTHDAY_COUPON_CONTROLLER),
            'form'=>$form,
            'email_setting_form' => $email_setting_form,
            'country_restriction_form' => $country_restriction_form,
            'category_restriction_form' => $category_restriction_form,
            'coupon_listing' => $this->getReportList(date('Y-m-d h:i:s', strtotime('-1 days')), date('Y-m-d h:i:s')),
            'cron_url'=>$this->getKbGcCronUrl(),
            'total_generated_coupon_data' =>Tools::jsonEncode($statistics_data['total_generated']),
            'total_unused_data'=> Tools::jsonEncode($statistics_data['total_unused']),
            'total_used_data'=> Tools::jsonEncode($statistics_data['total_used']),
            'ticks'=> Tools::jsonEncode($statistics_data['ticks']),
            'loader_image_url'=>$this->getModuleDirUrl() . $this->name.'/views/img/loading_spinner.gif',
            'start_date' => $pasttime,
            'end_date' => $current_time
        ));
        $this->context->smarty->assign('firstCall', false);

        $tpl = 'Form_custom.tpl';
        $helper = new Helper();
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->override_folder = 'helpers/';
        $helper->base_folder = 'form/';
        $helper->setTpl($tpl);
        $tpl = $helper->generate();
        $output = $output . $tpl;
        return $output;
    }
    
    /*
     * Function for generating unique secure key that will be used to run CRON job
     */
    private function kbgcSecureKeyGenerator($length = 32)
    {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(mt_rand(33, 126));
        }
        return md5($random);
    }
    
    /*
     * Function to get the CRON URL for the module
     */
    private function getKbGcCronUrl()
    {
        $cron_url = $this->context->link->getModuleLink(
            $this->name,
            'cron',
            array('secure_key' => Configuration::get(self::BIRTHDAY_COUPON_CRON_SECURE_KEY)),
            (bool)Configuration::get('PS_SSL_ENABLED')
        );
        return $cron_url;
    }
    
    /*
     * Method for saving and updating the email setting
     */
    public function saveEmailSetting($form_data, $languages)
    {
        try {
            //BIRTHDAY_COUPON_EMAIL_CONFIGURATION
            $email_subject_conf=array('birthday_coupon'=>
                array(
                    'email_templates'=>$form_data['birthday_coupon']['email_templates']
                ));
            foreach ($languages as $lang) {
                 //d($form_data['birthday_coupon']['email_templates']);
                $email_subject_conf['birthday_coupon']['email_subject'][$lang['id_lang']]=$form_data['birthday_coupon_email_subject_' . (int)$lang['id_lang']];
                if ($this->isTemplateExist($lang['id_lang'], $form_data['birthday_coupon']['email_templates'])) {
                    $this->updateEmailTemplate($lang['id_lang'], $form_data['birthday_coupon_email_content_' . $lang['id_lang']], $form_data['birthday_coupon']['email_templates']);
                } else {
                    $this->saveEmailTemplate($lang['id_lang'], $form_data['birthday_coupon_email_content_' . $lang['id_lang']], $form_data['birthday_coupon']['email_templates']);
                }
            }
            //data of configuration form
            $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
            
            //1 for birthday and 2 for year of account created
            if ((int)$form_value['birthday_coupon']['type_birthday']==1) {
                Configuration::updateValue(self::BIRTHDAY_COUPON_EMAIL_CONFIGURATION, serialize($email_subject_conf));
            } else {
                Configuration::updateValue(self::BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION, serialize($email_subject_conf));
            }
            
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }
    /*
     * function for checking if template exists
     */
    public function isTemplateExist($lang_id, $email_templates_name)
    {
        $sql = 'SELECT Count(*) FROM ' . _DB_PREFIX_ . 'birthday_coupon_email'
                . ' WHERE id_lang='.(int)$lang_id.
                ' AND template_name="' . pSQL($email_templates_name).'"';
        $content = Db::getInstance()->executeS($sql);
        if (((int) $content[0]['Count(*)']) > 0) {
            return true;
        }
        return false;
    }
    
    /*
     * function for updating the email templates
     */
    public function updateEmailTemplate($lang_id, $email_body, $email_templates_name)
    {
        $text_data=strip_tags($email_body);
        $sql = 'UPDATE ' . _DB_PREFIX_ . 'birthday_coupon_email set '
                . 'body="' . pSQL(Tools::htmlentitiesUTF8($email_body)) . '",
                text_content="'. pSQL($text_data).'",
                date_updated="' . pSQL(date("Y-m-d H:i:s")) . '" WHERE
                template_name = "' . pSQL($email_templates_name) . '" and '
                . 'id_lang=' . (int)$lang_id;
        try {
            Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /*
     * Function for saving new email templates
     */
    public function saveEmailTemplate($lang_id, $email_body, $email_templates_name)
    {
        try {
            $text_data=strip_tags($email_body);
            Db::getInstance()->insert('birthday_coupon_email', array(
                        'id_template' => '',
                        'id_lang' => (int)$lang_id,
                        'id_shop' => 0,
                        'template_name' =>  pSQL($email_templates_name),
                        'text_content' => pSQL($text_data),
                        'body' => pSQL(Tools::htmlentitiesUTF8($email_body)),
                        'date_added' => pSQL(date("Y-m-d H:i:s")),
                        'date_updated' => pSQL(("Y-m-d H:i:s")),
                    ));
            return true;
        } catch (Exception $ex) {
            return false;
        }
    }

    /*
     * Function for creating email setting form
     */
    public function getEmailSettingForm($occassion_type)
    {
        $temp_options=array();
        if ((int)$occassion_type==1) {
            $temp_options = array(
                array(
                    'id_temp' => 'Common',
                    'name' => $this->l('Common'),
                ),
                array(
                    'id_temp' => 'Fire',
                    'name' => $this->l('Fire'),
                ),
                array(
                    'id_temp' => 'Aqua',
                    'name' => $this->l('Aqua'),
                )
            );
        } else {
            $temp_options = array(
                array(
                    'id_temp' => 'Common',
                    'name' => $this->l('Common'),
                ),
                array(
                    'id_temp' => 'Wind',
                    'name' => $this->l('Wind'),
                ),
                array(
                    'id_temp' => 'Earth',
                    'name' => $this->l('Earth'),
                )
            );
        }
        
        $form_fields = array(
            'form' => array(
                'id_form' => 'birthday_coupon_email_setting_form',
                'legend' => array(
                    'title' => $this->l('Birthday Coupon Email Setting'),
                    'icon' => 'icon-check'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Subject'),
                        'hint' => $this->l('Subject of email which will be sent to customers.'),
                        'name' => 'birthday_coupon_email_subject',
                        'class' => 'optn_general',
                        'lang' => true,
                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Email Templates'),
                        'type' => 'select',
                        'class' => 'optn_email_templates',
                        'name' => 'birthday_coupon[email_templates]',
                        'id' => 'ddl_email_templates',
                        'options' => array(
                            'query' => $temp_options,
                            'id' => 'id_temp',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->l('Email Content'),
                        'hint' => $this->l('Content of selected email templates'),
                        'name' => 'birthday_coupon_email_content',
                        'id' => 'optn_email_content',
                        'required' => true,
                        'cols' => '9',
                        'rows' => '20',
                        'class' => 'col-lg-9',
                        'lang' => true,
                        'autoload_rte' => true
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'email_setting_form',
                    ),
                ),
                'submit' => array(
                    'id' => 'birthday_coupon_email_setting_submit_btn',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right enable_set_save'
                ),
            ),
        );
        return $form_fields;
    }
    
    /*
     * Function for creating country restriction form
     */
    public function getCountryRestrictionForm()
    {
        $form_fields = array(
            'form' => array(
                'id_form' => 'birthday_coupon_country_restriction_form',
                'legend' => array(
                    'title' => $this->l('Birthday Coupon Country Restriction'),
                    'icon' => 'icon-check'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable the Restriction'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[enable_country_restriction]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('If you enable the restriction, then only selected countries can able to use this coupon'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select Country'),
                        'id' => 'birthday_coupon_allowed_country',
                        'name' => 'birthday_coupon[allowed_country]',
                        'class' => 'optn_general',
                        'multiple' => true,
                        'options' => array(
                            'query' => Country::getCountries($this->context->language->id, false, false, false),
                            'id' => 'id_country',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'country_restriction_form_submit',
                    ),
                ),
                'submit' => array(
                    'id' => 'birthday_coupon_country_restriction_submit_btn',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right enable_set_save'
                ),
            ),
        );
        return $form_fields;
    }
    
     /*Fetching fields in categories tab*/
    public function getCategoryTabFields()
    {
        $category_id=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION));
        //d($category_id);
        $selected_store_cat=array();
        if (count($category_id['birthday_coupon']['prestashop_category']) > 0) {
            $selected_store_cat = $category_id['birthday_coupon']['prestashop_category'];
        }
        //Get Store root category
        $root = Category::getRootCategory();
        //Generating the tree for the first column
        $tree = new HelperTreeCategories('prestashop_category');
        $tree->setUseCheckBox(true)
                ->setAttribute('is_category_filter', $root->id)
                ->setRootCategory($root->id)
                ->setSelectedCategories($selected_store_cat)
                ->setInputName('birthday_coupon[prestashop_category]');

        $categoryTreePresta = $tree->render();
        $form_fields = array(
            'form' => array(
                'id_form' => 'category_set',
                'legend' => array(
                    'title' => $this->l('Birthday Coupon Category Restriction'),
                    'icon' => 'icon-check'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable the Restriction'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[enable_category_restriction]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('If you enable the restriction, then coupon will only be apply on selected category'),
                    ),
                    array(
                        'type' => 'categories_select',
                        'id' => 'birthday_coupon_allowed_category',
                        'label' => $this->l('Select prestashop category'),
                        'name' => 'prestashop_category',
                        'required' => false,
                        'category_tree' => $categoryTreePresta,
                        'class' => 'optn_cat erreo'
                    ),
                     array(
                        'type' => 'hidden',
                        'name' => 'category_restriction_form_submit',
                    ),
                ),
                'submit' => array(
                    'id' => 'birthday_coupon_category_restriction_submit_btn',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right category_set_save'
                ),
            ),
        );

        return $form_fields;
    }
    
    public function getBirthdayCouponFields()
    {
        $this->context->smarty->assign('icon_variable', 'money');
        $money_icon = $this->context->smarty->fetch(_PS_MODULE_DIR_ .$this->name. '/views/templates/admin/birthday_coupon_icon.tpl');
        $this->context->smarty->assign('icon_variable', 'search');
        $search_icon = $this->context->smarty->fetch(_PS_MODULE_DIR_ .$this->name. '/views/templates/admin/birthday_coupon_icon.tpl');
        $form_fields = array(
            'form' => array(
                'id_form' => 'birthday_coupon_form',
                'legend' => array(
                    'title' => $this->l('Birthday Coupon Settings'),
                    'icon' => 'icon-gear'
                ),
                'input' => array(
                    array(
                        'label' => $this->l('Enable/Disable the module'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[enable]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'hint' => $this->l('Enable or Disable the plugin functionality'),
                    ),
                    array(
                        'label' => $this->l('Delete Customer Data On Delete Request for GDPR'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[GDPR_compatibility_status]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('Enable/Disable to delete customer data on GDPR module delete request.'),
                        'hint' => $this->l('Enable this to delete customer data on GDPR module delete request.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Name'),
                        'hint' => $this->l('This will be displayed in the cart summary, as well as on the invoice.'),
                        'name' => 'main_title',
                        'class' => 'optn_general',
                        'lang' => true,
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Prefix Cart rule'),
                        'desc' => $this->l('This prefix will be added before all coupon codes generated through this module.'),
                        'name' => 'birthday_coupon[prefix]',
                        'class' => 'optn_general',
                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Highlight'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[highlight]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('If the voucher is not in the cart, it will be displayed in the cart summary.'),
                    ),
                    array(
                        'label' => $this->l('Partial use'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[partial_use]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => array(
                            $this->l('Only applicable if the voucher value is greater than the cart total.'),
                            $this->l('If you do not allow the partial use, the voucher value will be lowered to the total order amount. If you allow the partial use, however, a new voucher will be created with the remainder')
                                )
                    ),
                    array(
                        'label' => $this->l('Choose Occasion'),
                        'name' => 'birthday_coupon[type_birthday]',
                        'type' => 'radio',
                        'values' => array(
                            array(
                                'id' => 'type_birthday',
                                'value' => 1,
                                'label' => $this->l('Customer birthday'),
                            ),
                            array(
                                'id' => 'type_birthday',
                                'value' => 2,
                                'label' => $this->l('Created account'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Coupon Validity in days'),
                        'desc' => $this->l('Coupon will expire after number of given day(s)'),
                        'name' => 'birthday_coupon[validity]',
                        'suffix'=>$this->l('Days'),
                        'class' => 'optn_general col-sm-3',
                        'required' => true,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum Amount'),
                        'desc' => $this->l('Coupon valid after minimum amount of the order.'),
                        'name' => 'birthday_coupon[minimum_amount]',
                        'class' => 'optn_general col-sm-3',
                        'prefix' => $money_icon,
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select Minimum Amount Currency'),
                        'name' => 'birthday_coupon[minimum_amount_currency]',
                        'class' => 'optn_general',
                        'options' => array(
                            'query' => Currency::getCurrencies(false, true, true),
                            'id' => 'id_currency',
                            'name' => 'iso_code',
                        ),
                    ),
                     array(
                        'label' => $this->l('Minimum Amount Tax Included'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[tax_included]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                    ),
                     array(
                        'label' => $this->l('Minimum Amount Shipping Included'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[shipping_included]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                    ),
                    array(
                        'label' => $this->l('Valid Order'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[valid_order]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                        'desc' => $this->l('The customer must complete at least one order to receive a coupon from this module.')
                    ),
                    array(
                        'label' => $this->l('Free Shipping'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[free_shipping]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                    ),
                    array(
                        'label' => $this->l('Apply a Discount'),
                        'name' => 'birthday_coupon[apply_discount_type]',
                        'type' => 'radio',
                        'values' => array(
                            array(
                                'id' => 'apply_discount_type',
                                'value' => 1,
                                'label' => $this->l('Percent (%)'),
                            ),
                            array(
                                'id' => 'apply_discount_type',
                                'value' => 2,
                                'label' => $this->l('Amount'),
                            ),
                             array(
                                'id' => 'apply_discount_type',
                                'value' => 3,
                                'label' => $this->l('Send a Free Gift'),
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Value'),
                        'desc' => $this->l('Coupon valid after given minimum amount.'),
                        'name' => 'birthday_coupon[discount_percent_value]',
                        'suffix' => $this->l('%'),
                        'class' => 'optn_general col-sm-3',
                        'required' => true,
                    ),
                     array(
                        'type' => 'text',
                        'label' => $this->l('Amount'),
                        'name' => 'birthday_coupon[discount_amount_value]',
                        'prefix' => $money_icon,
                        'class' => 'optn_general col-sm-3',
                        'required' => true,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Amount Currency'),
                        'name' => 'birthday_coupon[discount_amount_currency]',
                        'class' => 'optn_general',
                        'options' => array(
                            'query' => Currency::getCurrencies(false, true, true),
                            'id' => 'id_currency',
                            'name' => 'iso_code',
                        ),
                    ),
                     array(
                        'label' => $this->l('Tax Included'),
                        'type' => 'switch',
                        'class' => 'enable_set_tab',
                        'name' => 'birthday_coupon[discount_tax_included]',
                        'values' => array(
                            array(
                                'value' => 1,
                            ),
                            array(
                                'value' => 0,
                            ),
                        ),
                    ),
                     array(
                        'type' => 'text',
                        'label' => $this->l('Search a product'),
                        'name' => 'birthday_coupon[send_free_gift]',
                        'prefix' => $search_icon,
                        'class' => 'optn_general',
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'birthday_coupon[send_free_gift_hidden]',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of Days'),
                        'desc' => $this->l('Send coupon email before given number of day(s) from the birth date.'),
                        'name' => 'birthday_coupon[number_of_days]',
                        'suffix'=>$this->l('Days'),
                        'class' => 'optn_general col-sm-3',
                        'required' => true,
                    ),
                    array(
                        'label' => $this->l('Cron tab'),
                        'name' => 'birthday_coupon[cron_type]',
                        'type' => 'radio',
                        'values' => array(
                            array(
                                'id' => 'cron',
                                'value' => 1,
                                'label' => $this->l('Store Visit'),
                            ),
                            array(
                                'id' => 'cron',
                                'value' => 2,
                                'label' => $this->l('Automatically'),
                            ),
                        ),
                        'desc'=> array(
                            $this->l('Store Visit (Cron functionality will be executed when the some one visits your site once a day)'),
                            $this->l('Automatically (Cron functionality will be executed through Crontab)')
                            )
                    ),
                    array(
                        'type'=>'hidden',
                        'name'=>'birthday_coupon[cron_execution]'
                    ),
                    array(
                        'type'=>'hidden',
                        'name'=>'birthday_coupon_submit'
                    ),
                ),
                'submit' => array(
                    'id'=>'birthday_coupon_configuration_form_submit_btn',
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right enable_set_save'
                ),
            ),
        );
        return $form_fields;
    }
    /*
     * Returns the HTML of the helper form
     */
    public function getFormHtml($field_form, $languages, $field_value, $action, $form_value = null, $form_type = null)
    {
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->fields_value = $field_value;
        if ($form_value!=null && $form_type=='config') {
        //Persistence for multi-langual field
            foreach (Language::getLanguages(false) as $lang) {
                if ($form_value['birthday_coupon']['main_title'][$lang['id_lang']] != null) {
                    $helper->fields_value['main_title'][$lang['id_lang']] = $form_value['birthday_coupon']['main_title'][$lang['id_lang']];
                } else {
                    $helper->fields_value['main_title'][$lang['id_lang']]='';
                }
            }
        }
        $helper->name_controller = $this->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->table = 'configuration';
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = true;
        $helper->submit_action = $action;
        return $helper->generateForm(array('form' => $field_form));
    }
    
    
    public function getFormHtmlForEmail($field_form, $languages, $action, $occassion_type)
    {
        $form_value_email_setting=null;
        if ((int)$occassion_type==1) {
            $form_value_email_setting=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_EMAIL_CONFIGURATION));
        } else {
            $form_value_email_setting=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION));
        }
        
        $sql='Select * from '. _DB_PREFIX_ . 'birthday_coupon_email WHERE template_name="'.pSQL($form_value_email_setting['birthday_coupon']['email_templates']).'"';
        
        $data_table = Db::getInstance()->ExecuteS($sql);
        $helper = new HelperForm();
        $helper->module = $this;
        //Persistence for multi-langual field
        foreach ($data_table as $row) {
            $body_email=Tools::htmlentitiesDecodeUTF8($row['body']);
            $body_email = str_replace('{minimal_img_path}', $this->getModuleDirUrl() . 'birthdaycoupon/views/img/admin/email/minimal6.png', $body_email);
            $body_email = str_replace('{icon_img_path}', $this->getModuleDirUrl() . 'birthdaycoupon/views/img/admin/email/ICON.png', $body_email);
            $helper->fields_value['birthday_coupon_email_content'][$row['id_lang']] = $body_email;
        }
        foreach (Language::getLanguages(false) as $lang) {
            if ($form_value_email_setting['birthday_coupon']['email_subject'][$lang['id_lang']] != null) {
                $helper->fields_value['birthday_coupon_email_subject'][$lang['id_lang']] = $form_value_email_setting['birthday_coupon']['email_subject'][$lang['id_lang']];
            } else {
                $helper->fields_value['birthday_coupon_email_subject'][$lang['id_lang']] ='';
            }
            if (Count($data_table)<1) {
                $helper->fields_value['birthday_coupon_email_content'][$lang['id_lang']]='';
            }
        }
        $helper->fields_value['birthday_coupon[email_templates]']=$form_value_email_setting['birthday_coupon']['email_templates'];
        $helper->fields_value['email_setting_form']='email_setting_form';
        $helper->name_controller = $this->name;
        $helper->languages = $languages;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->default_form_language = $this->context->language->id;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->table = 'configuration';
        $helper->toolbar_scroll = true;
        $helper->show_cancel_button = true;
        $helper->submit_action = $action;
        return $helper->generateForm(array('form' => $field_form));
    }
    
    /**
     * Get product (only names)
     *
     * @param int $id_lang Language id
     * @param int $product_ids Product ids
     * @return array Product
     */
    public static function getProductDataLight($id_lang, $product_ids)
    {
        return Db::getInstance()->executeS('
            SELECT p.`id_product`, p.`reference`, pl.`name`
            FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
                    p.`id_product` = pl.`id_product`
                    AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
            ) WHERE p.`id_product` IN (' . pSQL($product_ids) .')');
    }

    /*
    * Function to update email templates in DB and to update email html and text files in mails folder
    *
    *  @param    Array template_data    Contains template data which is to be updated
    *  @param    boolean return    True if email is updated otherwise returns False
    */
    public function updateEmailTemplateFile($template_data)
    {
        $iso = Language::getIsoById((int) $template_data['template_lang']);
        $directory = _PS_MODULE_DIR_ .$this->name. '/mails/' . $iso . '/';
        if (is_writable($directory)) {
            $html_template = $template_data['name'] . '.html';
            $txt_template = $template_data['name'] . '.txt';

            $base_html = $this->getTemplateBaseHtml();
            $template_html = str_replace('{template_content}', Tools::htmlentitiesDecodeUTF8($template_data['body']), $base_html);

            $file = fopen($directory . $html_template, 'w+');
            fwrite($file, $template_html);
            fclose($file);

            $file = fopen($directory . $txt_template, 'w+');
            fwrite($file, strip_tags($template_html));
            fclose($file);
        }
    }

    /*
    * Copies files of one folder into another folder
    *
    * @param    string source   Path of source folder
    * @param    string destination   Path of destination folder
    */
    protected function copyfolder($source, $destination)
    {
        $directory = opendir($source);
        mkdir($destination);
        while (($file = readdir($directory)) != false) {
            Tools::copy($source . '/' . $file, $destination . '/' . $file);
        }
        closedir($directory);
    }

    /*
    * Function to fetch base html for updating email template in mails folder
    */
    private function getTemplateBaseHtml()
    {
        $template_html = $this->context->smarty->fetch(_PS_MODULE_DIR_ . 'birthdaycoupon/views/templates/admin/email_base_temp.tpl');
        return $template_html;
    }

    /*
     * Function to export the customer data from this module for the GDPR compliant plugin.
     */
    public function hookActionExportGDPRData($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            if (Module::isInstalled($this->name)) {
                $config = Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
                $config = $config['birthday_coupon'];
                if ($config['enable']) {
                    $existing_customer = Db::getInstance()->executeS('SELECT id_customer from ' . _DB_PREFIX_ . 'customer where email="' . pSQL($customer['email']) . '"');
                    $gdpr_data = array();
                    $id_lang = Context::getContext()->language->id;
                    if (!empty($existing_customer)) {
                        foreach ($existing_customer as $key => $existing_cust) {
                            $id_customer = $existing_cust['id_customer'];
                            if (!empty($id_customer)) {
                                $bithday_coupons = Db::getInstance()->executeS('SELECT id_cart_rule FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping where id_customer=' . (int) $id_customer);
                                if (!empty($bithday_coupons)) {
                                    foreach ($bithday_coupons as $birthday_coupon) {
                                        $id_cart_rule = $birthday_coupon['id_cart_rule'];
                                        $cartrule = new CartRule($id_cart_rule, $id_lang);
                                        $gdpr_data[] = array(
                                            $this->l('Email') => $customer['email'],
                                            $this->l('Coupon') => $cartrule->code,
                                            $this->l('Description') => $cartrule->description,
                                            $this->l('Quantity') => $cartrule->quantity,
                                            $this->l('Valid From') => $cartrule->date_from,
                                            $this->l('Valid To') => $cartrule->date_to,
                                            $this->l('Active') => ($cartrule->active) ? $this->l('Yes') : $this->l('No'),
                                        );
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($gdpr_data) && count($gdpr_data) > 0) {
                        return json_encode($gdpr_data);
                    }

                    return json_encode($this->l('Birthday Coupon: No user found with this email.'));
                }
            }
        }
    }
    
    /*
     * Function to delete the customer data from this module for the GDPR compliant plugin.
     */
    public function hookActionDeleteGDPRCustomer($customer)
    {
        if (!empty($customer['email']) && Validate::isEmail($customer['email'])) {
            if (Module::isInstalled($this->name)) {
                $config = Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
                $config = $config['birthday_coupon'];
                if ($config['enable'] && $config['GDPR_compatibility_status']) {
                    $existing_customer = Db::getInstance()->executeS('SELECT id_customer from ' . _DB_PREFIX_ . 'customer where email="' . pSQL($customer['email']) . '"');
                    if (!empty($existing_customer)) {
                        foreach ($existing_customer as $existing_cust) {
                            $id_customer = $existing_cust['id_customer'];
                            if (!empty($id_customer)) {
                                Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'birthday_coupon_cart_rule_mapping where id_customer='.($id_customer));
                            }
                        }
                        return json_encode(true);
                    }
                }
                return json_encode($this->l('Birthday Coupon: No user found with this email.'));
            }
        }
    }

    /*
     * Function for showing the module front link in the left side of the window
     */
    public function hookdisplayFooter()
    {
        $this->handleStoreVisitCronFunctionality();
    }

    /*
     * This function handles the store visit CRON functionality
     */
    public function handleStoreVisitCronFunctionality()
    {
        $m_config = Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
        
        $module_config=$m_config['birthday_coupon'];
        
        if (isset($module_config['enable']) && $module_config['enable'] == 1) {
            if (isset($module_config['cron_type']) && $module_config['cron_type'] == 1) {
                $last_execution = (int)Configuration::get(self::BIRTHDAY_COUPON_STORE_VISIT_EXECUTED);
                $last_24 = ($last_execution + (24 * 60 * 60)); //plus 24 hours
                $current_timestamp = time();
                if ($current_timestamp > $last_24) {
                    $this->executeBirtdayCouponCron(true);
                }
            }
        }
        unset($module_config);
    }

    public function getReportList($start_date, $end_date)
    {
        $this->fields_list = array(
            'id_birthday_coupon' => array(
                'title' => $this->l('Id'),
                'type' => 'text',
                'search' => false,
                'orderby' => false
            ),
            'firstname' => array(
                'title' => $this->l('Customer Name'),
                'type' => 'text',
                'callback_object' => $this,
                'callback' => 'getCustomerFullName',
                'search' => true,
                'orderby' => false,
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'type' => 'text',
                'search' => true,
                'orderby' => false,
            ),
            'date_added' => array(
                'title' => $this->l('Coupon Generated Date'),
                'type' => 'date',
                'search' => true,
                'orderby' => false,
            ),
            'quantity' => array(
                'title' => $this->l('Coupon Satus'),
                'type' => 'text',
                'callback_object' => $this,
                'callback' => 'getCouponStatus',
                'search' => false,
                'orderby' => false,
            ),
        );
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = false;
        $helper->no_link = true;
        $helper->table = 'birthday_coupon_cart_rule_mapping';
        $helper->identifier = 'id_birthday_coupon';
        $helper->show_toolbar = true;
        $helper->title = $this->l('Recent Generated Birthday Coupon');
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        
        $start_date = date('Y-m-d 00:00:00', strtotime($start_date));
        $end_date = date('Y-m-d 23:59:59', strtotime($end_date));
       
        $query_condition='';
        if (Tools::getValue('reset_load')==true) {
                $this->context->smarty->assign('selected_tab', 'bthcpn_statistics');
        }
        if (Tools::getIsset('submitFilterbirthday_coupon_cart_rule_mapping')) {
            $this->context->smarty->assign('selected_tab', 'bthcpn_statistics');
            if ((int) Tools::getValue('submitFilterbirthday_coupon_cart_rule_mapping')==1) {
                if (Tools::getIsset('birthday_coupon_cart_rule_mappingFilter_firstname')) {
                    $name_data=trim(Tools::getValue('birthday_coupon_cart_rule_mappingFilter_firstname'));
                    if (!empty($name_data)) {
                        $query_condition .=' AND (c.firstname LIKE "%'.pSQL($name_data).'%" OR c.lastname LIKE "%'.pSQL($name_data).'%" OR  CONCAT_WS(" ",c.firstname,c.lastname) LIKE "%'.pSQL($name_data).'%")';
                    }
                }
                
                if (Tools::getIsset('birthday_coupon_cart_rule_mappingFilter_email')) {
                    $email_data=trim(Tools::getValue('birthday_coupon_cart_rule_mappingFilter_email'));
                    if (!empty($email_data)) {
                        $query_condition .=' AND c.email LIKE "%'.pSQL($email_data).'%"';
                    }
                }

                if (Tools::getIsset('local_birthday_coupon_cart_rule_mappingFilter_date_added')) {
                    $data_of_date=Tools::getValue('local_birthday_coupon_cart_rule_mappingFilter_date_added');
                    if (!empty($data_of_date[0])) {
                        $start_date = date('Y-m-d 00:00:00', strtotime($data_of_date[0]));
                    }
                    if (!empty($data_of_date[1])) {
                        $end_date = date('Y-m-d 23:59:59', strtotime($data_of_date[1]));
                    }
                }
            }
        }
        $order_by=' ORDER BY id_birthday_coupon DESC';
        if (Tools::getIsset('birthday_coupon_cart_rule_mappingOrderby')) {
            $this->context->smarty->assign('selected_tab', 'bthcpn_statistics');
            $order_by_field=Tools::getValue('birthday_coupon_cart_rule_mappingOrderby');
            $order_by_direction=Tools::getValue('birthday_coupon_cart_rule_mappingOrderway');
            $order_by=' ORDER BY '. pSQL($order_by_field).' '. pSQL($order_by_direction);
        }
        $sql='SELECT bm.id_birthday_coupon, c.firstname, c.lastname,c.email,bm.date_added,cr.quantity'
                . ' FROM '._DB_PREFIX_.'birthday_coupon_cart_rule_mapping AS bm'
                . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                . ' JOIN ' . _DB_PREFIX_ . 'customer AS c ON c.id_customer=bm.id_customer'
                . ' WHERE bm.date_added >= "'.pSQL($start_date).'"'
                . ' AND bm.date_added <="'.pSQL($end_date).'"';
        $sql.=$query_condition.$order_by;
        $data = Db::getInstance()->executeS($sql);
        return $helper->generateList($data, $this->fields_list);
    }

    //returns the text for used and unused coupon
    public function getCouponStatus($row_data, $tr)
    {
        if ((int)$row_data==1) {
            return $this->l('Unused');
        }
        if ((int)$row_data==0) {
            return $this->l('Used');
        }
        return '';
    }
    //returns Customer Full Name
    public function getCustomerFullName($row_data, $tr)
    {
        return $tr['firstname'].' '.$tr['lastname'];
    }
   
    /*
     * Statistics Graph Filter
    *
    * @param    date from_date   Start date of filter data
    * @param    date to_date    End date of filter data
    * @return   Array   Return Filtered data according to from and to date
    */
    public function getDataforGraph($from_date, $to_date)
    {
        if (isset($from_date) && isset($to_date)) {
            $start_date = explode('/', $from_date);
            $start_date = $start_date[2] . '-' . $start_date[1] . '-' . $start_date[0];
            $end_date = explode('/', $to_date);
            $end_date = $end_date[2] . '-' . $end_date[1] . '-' . $end_date[0];
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
            $datediff = $end_date - $start_date;
            $days = floor($datediff / (60 * 60 * 24));
            if ($days == 0) {
                $diff = 'hours';
            } elseif ($days < 31) {
                $diff = 'days';
            } elseif ($days < 366) {
                $diff = 'months';
            } else {
                $diff = 'year';
            }
            switch ($diff) {
                case 'hours':
                    $start_date = date('Y-m-d 00:00:00', $start_date);
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($start_date));
                    $end_date = date('Y-m-d 23:59:59', $end_date);
                    
                    $sql2='SELECT COUNT(*) AS generated_coupon_quantity, HOUR(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' GROUP BY HOUR(bm.date_added)'
                            . ' ORDER BY HOUR(bm.date_added) DESC';
                    
                    $total_generated_coupon_data = Db::getInstance()->executeS($sql2);
                    $i = 0;
                    //$dates = array();
                    $coupons_data = array();
                    foreach ($total_generated_coupon_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['generated_coupon_quantity'];
                    }
                    $total_generated_coupon_graph_data = array();
                    $date = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_generated_coupon_graph_data[] = $coupons_data[$date];
                        } else {
                            $total_generated_coupon_graph_data[] = 0;
                        }
                        $date += 1;
                        $new_start_date = date("Y-m-d H:i:s", strtotime("+1 hour", strtotime($new_start_date)));
                        if ($date > date('H', strtotime($end_date)) || $i == 25) {
                            break;
                        }
                    }
                    $graph_data = array();
                    $graph_data['total_generated'] = $total_generated_coupon_graph_data;
                     //finding unused coupon
                    $sql='SELECT COUNT(*) AS unused_coupon_quantity, HOUR(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=1'
                            . ' GROUP BY HOUR(bm.date_added)'
                            . ' ORDER BY HOUR(bm.date_added) DESC';
                    $total_unused_data = Db::getInstance()->executeS($sql);
                    $i = 0;
                    //$dates = array();
                    $coupons_data = array();
                    foreach ($total_unused_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['unused_coupon_quantity'];
                    }
                    $total_unused_garph_data = array();
                    $date = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_unused_garph_data[] = $coupons_data[$date];
                        } else {
                            $total_unused_garph_data[] = 0;
                        }
                        $date += 1;
                        $new_start_date = date("Y-m-d H:i:s", strtotime("+1 hour", strtotime($new_start_date)));
                        if ($date > date('H', strtotime($end_date)) || $i == 25) {
                            break;
                        }
                    }
                    $graph_data['total_unused'] = $total_unused_garph_data;
                    ///$new_start_date = date('Y-m-d 00:00:00', strtotime($this->request->data['start_date']));
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($start_date));
                    
                    //finding used coupon
                    $sql3='SELECT COUNT(*) AS used_coupon_quantity, HOUR(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=0'
                            . ' GROUP BY HOUR(bm.date_added)'
                            . ' ORDER BY HOUR(bm.date_added) DESC';
                    $total_used_data = Db::getInstance()->executeS($sql3);
                    $i = 0;
                    //$dates = array();
                    $coupons_data = array();
                    foreach ($total_used_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['used_coupon_quantity'];
                    }
                    $total_used_graph_data = array();
                    $date = 0;
                    $ticks = array();
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_used_graph_data[] = $coupons_data[$date];
                        } else {
                            $total_used_graph_data[] = 0;
                        }
                        $ticks[] = date("h a", strtotime($new_start_date));
                        $date += 1;
                        $new_start_date = date("Y-m-d H:i:s", strtotime("+1 hour", strtotime($new_start_date)));
                        if ($date > date('H', strtotime($end_date)) || $i == 25) {
                            break;
                        }
                    }
                    $graph_data['total_used'] = $total_used_graph_data;
                    $graph_data['ticks'] = $ticks;
                    return $graph_data;
                case 'days':
                    $start_date = date('Y-m-d 00:00:00', $start_date);
                    $end_date = date('Y-m-d 23:59:59', $end_date);
                    //finding generated coupon coupon
                     $sql2='SELECT COUNT(*) AS generated_coupon_quantity, DATE(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' GROUP BY DATE(bm.date_added)'
                            . ' ORDER BY bm.date_added DESC';


                    $total_generated_coupon_data = Db::getInstance()->executeS($sql2);
                    $i = 0;
                    //$dates = array();
                    $coupons_data = array();
                    foreach ($total_generated_coupon_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['generated_coupon_quantity'];
                    }
                    $i = 0;
                    $date = date('Y-m-d', strtotime($start_date));
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_generated_coupon_graph_data[] = $coupons_data[$date];
                        } else {
                            $total_generated_coupon_graph_data[] = 0;
                        }
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        if (strtotime($date) > strtotime($end_date) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_generated'] = $total_generated_coupon_graph_data;

                    //finding unused coupon
                    $sql='SELECT COUNT(*) AS unused_coupon_quantity, DATE(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=1'
                            . ' GROUP BY DATE(bm.date_added)'
                            . ' ORDER BY bm.date_added DESC';
                    $total_unused_data = Db::getInstance()->executeS($sql);
                    $i = 0;
                    //$dates = array();
                    $coupons_data = array();
                    $total_unused_garph_data = array();
                    foreach ($total_unused_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['unused_coupon_quantity'];
                    }
                    $i = 0;
                    $date = date('Y-m-d', strtotime($start_date));
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_unused_garph_data[] = $coupons_data[$date];
                        } else {
                            $total_unused_garph_data[] = 0;
                        }
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        if (strtotime($date) > strtotime($end_date) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_unused'] = $total_unused_garph_data;

                    //finding used coupon
                    $sql3='SELECT COUNT(*) AS used_coupon_quantity, DATE(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=0'
                            . ' GROUP BY DATE(bm.date_added)'
                            . ' ORDER BY bm.date_added DESC';
                    $total_used_data = Db::getInstance()->executeS($sql3);
                    $i = 0;
                    $ticks = array();
                    //$dates = array();
                    $coupons_data = array();
                    foreach ($total_used_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['used_coupon_quantity'];
                    }

                    $i = 0;
                    $total_used_garph_data = array();
                    $date = date('Y-m-d', strtotime($start_date));
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_used_garph_data[] = $coupons_data[$date];
                        } else {
                            $total_used_garph_data[] = 0;
                        }
                        $ticks[] = date("d M", strtotime($date));
                        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        if (strtotime($date) > strtotime($end_date) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_used'] = $total_used_garph_data;

                    $graph_data['total_used'] = $total_used_garph_data;
                    $graph_data['ticks'] = $ticks;
                    return $graph_data;
                case 'months':
                    $start_date = date('Y-m-d 00:00:00', $start_date);
                    $end_date = date('Y-m-d 23:59:59', $end_date);
                    //finding generated coupon coupon
                    $sql2='SELECT COUNT(*) AS generated_coupon_quantity, MONTH(bm.date_added) AS date_added'
                           . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                           . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                           . ' AND bm.date_added <="'.pSQL($end_date).'"'
                           . ' GROUP BY YEAR(bm.date_added), MONTH(bm.date_added)'
                           . ' ORDER BY YEAR(bm.date_added) DESC, MONTH(bm.date_added) DESC';

                    $total_generated_coupon_data = Db::getInstance()->executeS($sql2);
                    $i = 0;
                    $date = date('m', strtotime($start_date));
                    $coupons_data = array();
                    foreach ($total_generated_coupon_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['generated_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_generated_coupon_graph_data[] = $coupons_data[$date];
                        } else {
                            $total_generated_coupon_graph_data[] = 0;
                        }
                        $date += 1;
                        if ($date > date('m', strtotime($end_date)) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_generated'] = $total_generated_coupon_graph_data;
                    //finding unused coupon
                    $sql='SELECT COUNT(*) AS unused_coupon_quantity, MONTH(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=1'
                            . ' GROUP BY YEAR(bm.date_added), MONTH(bm.date_added)'
                            . ' ORDER BY YEAR(bm.date_added) DESC, MONTH(bm.date_added) DESC';
                    $total_unused_data = Db::getInstance()->executeS($sql);

                    $i = 0;
                    $date = date('m', strtotime($start_date));
                    $coupons_data = array();
                    foreach ($total_unused_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['unused_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_unused_garph_data[] = $coupons_data[$date];
                        } else {
                            $total_unused_garph_data[] = 0;
                        }
                        $date += 1;
                        if ($date > date('m', strtotime($end_date)) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_unused'] = $total_unused_garph_data;
                    
                    //finding used coupon
                    $sql3='SELECT COUNT(*) AS used_coupon_quantity, MONTH(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=0'
                            . ' GROUP BY YEAR(bm.date_added), MONTH(bm.date_added)'
                            . ' ORDER BY YEAR(bm.date_added) DESC, MONTH(bm.date_added) DESC';
                    $total_used_data = Db::getInstance()->executeS($sql3);

                    $date = date('m', strtotime($start_date));
                    $coupons_data = array();
                    $ticks = array();
                    foreach ($total_used_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['used_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_used_garph_data[] = $coupons_data[$date];
                        } else {
                            $total_used_garph_data[] = 0;
                        }
                        $ticks[] = $this->getmonths($date);
                        $date += 1;
                        if ($date > date('m', strtotime($end_date)) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_used'] = $total_used_garph_data;
                    $graph_data['ticks'] = $ticks;
                    return $graph_data;
                case 'year':
                    $start_date = date('Y-m-d 00:00:00', $start_date);
                    $end_date = date('Y-m-d 23:59:59', $end_date);
                    
                    //finding generated coupon coupon
                    $sql2='SELECT COUNT(*) AS generated_coupon_quantity, YEAR(bm.date_added) AS date_added'
                           . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                           . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                           . ' AND bm.date_added <="'.pSQL($end_date).'"'
                           . ' GROUP BY YEAR(bm.date_added)'
                           . ' ORDER BY YEAR(bm.date_added) DESC';
                    $total_generated_coupon_data = Db::getInstance()->executeS($sql2);

                    $date = date('Y', strtotime($start_date));
                    $coupons_data = array();

                    foreach ($total_generated_coupon_data as $k => $coupon) {
                        $coupons_data[$coupon['date_added']] = $coupon['generated_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_data[$date])) {
                            $total_generated_coupon_graph_data[] = $coupons_data[$date];
                        } else {
                            $total_generated_coupon_graph_data[] = 0;
                        }
                        $date += 1;
                        if ($date > date('Y', strtotime($end_date)) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_generated'] = $total_generated_coupon_graph_data;
                    
                    //finding unused coupon
                    $sql='SELECT COUNT(*) AS unused_coupon_quantity, YEAR(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=1'
                            . ' GROUP BY YEAR(bm.date_added)'
                            . ' ORDER BY YEAR(bm.date_added) DESC';
                    $total_unused_data = Db::getInstance()->executeS($sql);

                    $date_strt = date('Y', strtotime($start_date));
                    $coupons_used_data = array();
                    foreach ($total_unused_data as $k => $coupon) {
                        $coupons_used_data[$coupon['date_added']] = $coupon['unused_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($coupons_used_data[$date_strt])) {
                            $total_unused_garph_data[] = $coupons_used_data[$date_strt];
                        } else {
                            $total_unused_garph_data[] = 0;
                        }
                        $date_strt += 1;
                        if ($date_strt > date('Y', strtotime($end_date)) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_unused'] = $total_unused_garph_data;
                    
                    //$new_start_date = date('Y-m-d 00:00:00', strtotime($this->request->data['start_date']));
                    $new_start_date = date('Y-m-d 00:00:00', strtotime($start_date));
                    
                    //finding used coupon
                    $sql3='SELECT COUNT(*) AS used_coupon_quantity, YEAR(bm.date_added) AS date_added'
                            . ' FROM ' . _DB_PREFIX_ . 'birthday_coupon_cart_rule_mapping AS bm'
                            . ' JOIN ' . _DB_PREFIX_ . 'cart_rule AS cr ON bm.id_cart_rule=cr.id_cart_rule'
                            . ' WHERE bm.date_added >="'.pSQL($start_date).'"'
                            . ' AND bm.date_added <="'.pSQL($end_date).'"'
                            . ' AND cr.quantity=0'
                            . ' GROUP BY YEAR(bm.date_added)'
                            . ' ORDER BY YEAR(bm.date_added) DESC';
                    $total_used_data = Db::getInstance()->executeS($sql3);
                   
                    $date = date('Y', strtotime($start_date));
                    $discount_data = array();
                    $ticks = array();

                    foreach ($total_used_data as $k => $discount) {
                        $discount_data[$discount['date_added']] = $discount['used_coupon_quantity'];
                    }
                    $i = 0;
                    while (true) {
                        $i++;
                        if (isset($discount_data[$date])) {
                            $total_used_garph_data[] = $discount_data[$date];
                        } else {
                            $total_used_garph_data[] = 0;
                        }
                        $ticks[] = $date;
                        $date += 1;
                        if (($date > date('Y', strtotime($end_date))) || $i == 60) {
                            break;
                        }
                    }
                    $graph_data['total_used'] = $total_used_garph_data;
                    $graph_data['ticks'] = $ticks;
                    return $graph_data;
                default:
                    $graph_data = array();
                    return $graph_data;
            }
        }
    }

    /*
    * Get months name to show in graph when filtering case is of month
    *
    * @param    int month_num   Month number
    * @return   Array   Return 3 letters month name
    */
    public function getMonths($month_num)
    {
        $month_name = '';
        switch ($month_num) {
            case '1':
                $month_name = $this->l('Jan');
                break;
            case '2':
                $month_name = $this->l('Feb');
                break;
            case '3':
                $month_name = $this->l('Mar');
                break;
            case '4':
                $month_name = $this->l('Apr');
                break;
            case '5':
                $month_name = $this->l('May');
                break;
            case '6':
                $month_name = $this->l('Jun');
                break;
            case '7':
                $month_name = $this->l('Jul');
                break;
            case '8':
                $month_name = $this->l('Aug');
                break;
            case '9':
                $month_name = $this->l('Sep');
                break;
            case '10':
                $month_name = $this->l('Oct');
                break;
            case '11':
                $month_name = $this->l('Nov');
                break;
            case '12':
                $month_name = $this->l('Dec');
                break;
        }
        return $month_name;
    }

    public function addBackOfficeMedia()
    {
        /* CSS files */
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/tab/birthday_coupon_normalize.css');
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/tab/birthday_coupon_demo.css');
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/tab/birthday_coupon_tabs.css');
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/tab/birthday_coupon_tabstyles.css');
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/birthday_coupon_custom.css');
        $this->context->controller->addCSS($this->getModuleDirUrl() . $this->name . '/views/css/admin/select2.css');
        
        /* JS files */
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/admin/birthday_coupon_top_tabs.js');
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/admin/birthday_coupon_auto_complete.js');
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/admin/birthday_coupon_form_handling.js');
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/admin/select2.min.js');
        $this->context->controller->addJS($this->getModuleDirUrl() . $this->name . '/views/js/velovalidation.js');
        $this->context->controller->addJqueryPlugin('autocomplete');
        $this->context->controller->addJqueryPlugin('flot');
    }
    
    // <editor-fold desc="Path related methods">
    public function getModuleDirUrl()
    {
        $module_dir = '';
        if ($this->checkSecureUrl()) {
            $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        } else {
            $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__ . str_replace(_PS_ROOT_DIR_ . '/', '', _PS_MODULE_DIR_);
        }
        return $module_dir;
    }

    private function checkSecureUrl()
    {
        $custom_ssl_var = 0;

        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] == 'on') {
                $custom_ssl_var = 1;
            }
        } else if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $custom_ssl_var = 1;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getPath()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        } else {
            $custom_ssl_var = 0;
        }
        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $module_dir = _PS_BASE_URL_SSL_ . __PS_BASE_URI__;
        } else {
            $module_dir = _PS_BASE_URL_ . __PS_BASE_URI__;
        }

        return $module_dir;
    }
    // </editor-fold>

    /*
     * This id the first method for running the cron
     */
    public function executeBirtdayCouponCron($store_visit = false)
    {
        $module_conf= $this->getConfiguarationSetting('module_conf');
        $country_restriction_conf= $this->getConfiguarationSetting('country_restriction_conf');
        $category_restriction_conf= $this->getConfiguarationSetting('category_restriction_conf');
        $retricted_product_id=array();
        if ((int)$category_restriction_conf['birthday_coupon']['enable_category_restriction']==1) {
            $retricted_product_id= $this->getRestrictedProductId($category_restriction_conf['birthday_coupon']);
        }
        //1 for birthday and 2 for year of account created
        if ((int)$module_conf['birthday_coupon']['type_birthday']==1) {
            $email_conf= $this->getConfiguarationSetting('email_birthday_conf');
        } else {
            $email_conf= $this->getConfiguarationSetting('email_anniversary_conf');
        }
        //list of the customer for whom offer will be generated and mail will be sent
        $customer_list=$this->getCustomerList($module_conf['birthday_coupon']);
        foreach ($customer_list as $row) {
            $coupon_code= $module_conf['birthday_coupon']['prefix'].$this->getNewCouponCode();
            $discount_data= $this->getDiscountData($module_conf['birthday_coupon']);
            $this->disableCustomerPreviousCoupon((int)$row['id_customer']);
            $expiry_date=date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + '.$module_conf['birthday_coupon']['validity'].' days'));
            Db::getInstance()->insert('cart_rule', array(
                'id_customer'=>(int)$row['id_customer'],
                'date_from'=>pSQL(date('Y-m-d H:i:s')),
                'date_to'=> pSQL($expiry_date),
                'description'=>'',
                'quantity'=> 1,
                'quantity_per_user'=> 1,
                'priority'=> 1,
                'partial_use'=>(int)$module_conf['birthday_coupon']['partial_use'],
                'code'=> pSQL($coupon_code),
                'minimum_amount'=>(float)$module_conf['birthday_coupon']['minimum_amount'],
                'minimum_amount_tax'=>(int)$module_conf['birthday_coupon']['tax_included'],
                'minimum_amount_currency'=>(int)$module_conf['birthday_coupon']['minimum_amount_currency'],
                'minimum_amount_shipping'=>(int)$module_conf['birthday_coupon']['shipping_included'],
                'country_restriction'=>(int)$country_restriction_conf['birthday_coupon']['enable_country_restriction'],
                'carrier_restriction'=>0,
                'group_restriction'=>0,
                'cart_rule_restriction'=>0,
                'product_restriction'=>(int)$category_restriction_conf['birthday_coupon']['enable_category_restriction'],
                'shop_restriction'=>0,
                'free_shipping'=>(int)$module_conf['birthday_coupon']['free_shipping'],
                'reduction_percent'=>(float)$discount_data['reduction_percent'],
                'reduction_amount'=>(float)$discount_data['reduction_amount'],
                'reduction_tax'=>(int)$discount_data['reduction_tax'],
                'reduction_currency'=>(int)$discount_data['reduction_currency'],
                'reduction_product'=>0,
                'gift_product'=>(int)$discount_data['gift_product'],
                'gift_product_attribute'=>0,
                'highlight'=>(int)$module_conf['birthday_coupon']['highlight'],
                'active'=>1,
                'date_add'=>pSQL(date('Y-m-d H:i:s')),
                'date_upd'=>pSQL(date('Y-m-d H:i:s')),
            ));
            
            $last_inserted_cart_rule_id = Db::getInstance()->Insert_ID();
            
            foreach (Language::getLanguages(false) as $lang) {
                 Db::getInstance()->insert('cart_rule_lang', array(
                        'id_cart_rule'=>(int)$last_inserted_cart_rule_id,
                        'id_lang'=>(int)$lang['id_lang'],
                        'name'=>pSQL($module_conf['birthday_coupon']['main_title'][$lang['id_lang']])
                    ));
            }
            Db::getInstance()->insert('birthday_coupon_cart_rule_mapping', array(
                       'id_cart_rule'=>(int)$last_inserted_cart_rule_id,
                       'id_customer'=>(int)$row['id_customer'],
                       'date_added'=>pSQL(date('Y-m-d H:i:s')),
                       'date_updated'=>pSQL(date('Y-m-d H:i:s')),
            ));
             
            if ((int)$country_restriction_conf['birthday_coupon']['enable_country_restriction']==1) {
                foreach ($country_restriction_conf['birthday_coupon']['allowed_country'] as $country_id) {
                    Db::getInstance()->insert('cart_rule_country', array(
                        'id_cart_rule'=>(int)$last_inserted_cart_rule_id,
                        'id_country'=>(int)$country_id,
                    ));
                }
            }
            if ((int)$category_restriction_conf['birthday_coupon']['enable_category_restriction']==1) {
                Db::getInstance()->insert('cart_rule_product_rule_group', array(
                     'id_cart_rule'=>(int)$last_inserted_cart_rule_id,
                     'quantity'=>1,
                 ));
                $last_inserted_product_rule_group_id = Db::getInstance()->Insert_ID();

                Db::getInstance()->insert('cart_rule_product_rule', array(
                    'id_product_rule_group'=>(int)$last_inserted_product_rule_group_id,
                    'type'=>'products'
                ));
                $last_inserted_cart_rule_product_rule_id = Db::getInstance()->Insert_ID();

                foreach ($retricted_product_id as $id_product) {
                    Db::getInstance()->insert('cart_rule_product_rule_value', array(
                        'id_product_rule'=>(int)$last_inserted_cart_rule_product_rule_id,
                        'id_item'=>(int)$id_product['id_product'],
                    ));
                }
            }
            $template_data= $this->getEmailTemplate($row['id_lang'], $email_conf['birthday_coupon']['email_templates']);

            $this->updateEmailTemplateFile($template_data);
            $this->sendEmail($row, $coupon_code, $expiry_date, $module_conf['birthday_coupon']);
        }
        
        if ($store_visit) {
            Configuration::updateGlobalValue(self::BIRTHDAY_COUPON_STORE_VISIT_EXECUTED, time());
        }
    }
    
    /*
     * function for disable the cart rule of the customer
     */
    public function disableCustomerPreviousCoupon($id_customer)
    {
        $sql='SELECT id_cart_rule FROM '._DB_PREFIX_.'birthday_coupon_cart_rule_mapping WHERE id_customer='.(int)$id_customer;
        $result = Db::getInstance()->executeS($sql);
        if (count($result)>0) {
            foreach ($result as $row) {
                $sql = 'UPDATE ' . _DB_PREFIX_ . 'cart_rule set active =0 where id_cart_rule='.(int)$row['id_cart_rule'];
                try {
                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($sql);
                } catch (Exception $ex) {
                }
            }
        }
    }

    public function getRestrictedProductId($category)
    {
        (int)$count=1;
        $sql='SELECT DISTINCT id_product FROM '._DB_PREFIX_.'category_product';
        foreach ($category['prestashop_category'] as $row) {
            if ($count==1) {
                $sql.= ' WHERE id_category='. (int)$row;
                $count++;
            } else {
                $sql.= ' OR id_category='. (int)$row;
            }
        }
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /*
     * Returns the details of discount
     */
    public function getDiscountData($module_conf)
    {
        $data_array = array();
        if ((int)$module_conf['apply_discount_type']==1) {
            $data_array=array(
                'reduction_percent'=>$module_conf['discount_percent_value'],
                'reduction_amount'=>0,
                'reduction_tax'=>0,
                'reduction_currency'=>0,
                'gift_product'=>0
            );
        }
        
        if ((int)$module_conf['apply_discount_type']==2) {
            $data_array=array(
                'reduction_percent'=>0,
                'reduction_amount'=>$module_conf['discount_amount_value'],
                'reduction_tax'=>$module_conf['discount_tax_included'],
                'reduction_currency'=>$module_conf['discount_amount_currency'],
                'gift_product'=>0
            );
        }
        
        if ((int)$module_conf['apply_discount_type']==3) {
            $data_array=array(
                'reduction_percent'=>0,
                'reduction_amount'=>0,
                'reduction_tax'=>0,
                'reduction_currency'=>0,
                'gift_product'=>$module_conf['send_free_gift_hidden']
            );
        }
        return $data_array;
    }


    /*
     * Return the template for the user
     */
    public function getEmailTemplate($id_lang, $template_name)
    {
        $sql='SELECT * FROM '._DB_PREFIX_.'birthday_coupon_email'
                . ' WHERE id_lang='.(int)$id_lang
                . ' AND template_name="'. pSQL($template_name).'"';
        $result = Db::getInstance()->getRow($sql);
        if (count($result)==0) {
            $id_lang=(int)Language::getIdByIso('en');
             $sql='SELECT * FROM '._DB_PREFIX_.'birthday_coupon_email'
                . ' WHERE id_lang='.(int)$id_lang
                . ' AND template_name="'. pSQL($template_name).'"';
            $result = Db::getInstance()->getRow($sql);
        }
        return array(
                'name'=> $result['template_name'],
                'template_lang'=>$id_lang,
                'body'=>$result['body'],
                'text_content'=>$result['text_content']
        );
    }

    /*
     * Return the discount text for email
     */
    public function getDiscountText($module_conf)
    {
        $email_conf= array();
        //1 for birthday and 2 for year of account created
        if ((int)$module_conf['type_birthday']==1) {
            $email_conf= $this->getConfiguarationSetting('email_birthday_conf');
        } else {
            $email_conf= $this->getConfiguarationSetting('email_anniversary_conf');
        }
        $discount_text='';
        
        if (Tools::strtolower($email_conf['birthday_coupon']['email_templates'])!='common') {
            if ($module_conf['apply_discount_type']==1) {
                $discount_text= $module_conf['discount_percent_value'].'%'.$this->l(' off');
            }
            if ($module_conf['apply_discount_type']==2) {
                $discount_text= $this->l('amount of ').Tools::displayPrice((float)$module_conf['discount_amount_value'], (int)$module_conf['discount_amount_currency']).$this->l(' off');
            }
            if ($module_conf['apply_discount_type']==3) {
                $discount_text= $this->l('a free gift ');
            }
        } else {
            if ($module_conf['apply_discount_type']==1) {
                $discount_text= $this->l('Discount of ').$module_conf['discount_percent_value'].'%';
            }
            if ($module_conf['apply_discount_type']==2) {
                $discount_text= $this->l('Discount of amount ').Tools::displayPrice((float)$module_conf['discount_amount_value'], (int)$module_conf['discount_amount_currency']);
            }
            if ($module_conf['apply_discount_type']==3) {
                $discount_text= $this->l('Get a free gift on next purchase ');
            }
        }
        return $discount_text;
    }
     /*
     * Return the occasion text for common email
     */
    public function getOccasionText($module_conf)
    {
        $occasion_text='';
        //1 for birthday and 2 for year of account created
        if ((int)$module_conf['type_birthday']==1) {
            $occasion_text=$this->l('Birthday Greeting ');
        } else {
            $occasion_text=$this->l('Thank you for being a loyal customer for a complete year');
        }
        return $occasion_text;
    }
    
     /*
     * Return the template name email
     */
    public function getTemplateName($module_conf)
    {
        $email_conf= array();
        //1 for birthday and 2 for year of account created
        if ((int)$module_conf['type_birthday']==1) {
            $email_conf= $this->getConfiguarationSetting('email_birthday_conf');
        } else {
            $email_conf= $this->getConfiguarationSetting('email_anniversary_conf');
        }
        return $email_conf['birthday_coupon']['email_templates'];
    }
    
     /*
     * Return the subject for common email
     */
    public function getEmailSubject($module_conf, $id_lang)
    {
        $email_conf= array();
        $subject='';
        //1 for birthday and 2 for year of account created
        if ((int)$module_conf['type_birthday']==1) {
            $email_conf= $this->getConfiguarationSetting('email_birthday_conf');
        } else {
            $email_conf= $this->getConfiguarationSetting('email_anniversary_conf');
        }
        if (array_key_exists($id_lang, $email_conf['birthday_coupon']['email_subject'])) {
                $subject = $email_conf['birthday_coupon']['email_subject'][$id_lang];
        }
        if ($subject=='') {
            $id_lang=(int)Language::getIdByIso('en');
            $subject = $email_conf['birthday_coupon']['email_subject'][$id_lang];
        }
        return $subject;
    }
    
    /*
     * Function to send email to customers
     */
    public function sendEmail($user_data, $coupon_code, $expiry_date, $module_conf)
    {
        $customer_email = $user_data['email'];
        $code = $coupon_code;
        $discount_text= $this->getDiscountText($module_conf);

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $custom_ssl_var = 1;
        }

        if ((bool) Configuration::get('PS_SSL_ENABLED') && $custom_ssl_var == 1) {
            $ps_base_url = _PS_BASE_URL_SSL_;
        } else {
            $ps_base_url = _PS_BASE_URL_;
        }
        $customer_name=$user_data['firstname'].' '.$user_data['lastname'];
        $shop_url_obj = new ShopUrl($this->context->shop->id);
        $shop_url = $shop_url_obj->getUrl((bool) Configuration::get('PS_SSL_ENABLED'));
        $template_vars = array(
            '{Customer_Name}'=>$customer_name,
            '{Discount_Amount_Or_Free_Gift}' => $discount_text,
            '{Coupon_Code}' => $code,
            '{Occasion_Wishes}'=> $this->getOccasionText($module_conf),
            '{minimal_img_path}'=> $this->getModuleDirUrl() . 'birthdaycoupon/views/img/admin/email/minimal6.png',
            '{icon_img_path}'=> $this->getModuleDirUrl() . 'birthdaycoupon/views/img/admin/email/ICON.png',
            '{shop_name}'=>Configuration::get('PS_SHOP_NAME'),
            '{shop_url}'=>$shop_url,
            '{shop_email}'=>Configuration::get('PS_SHOP_EMAIL'),
            '{expiry_date}'=> Tools::displayDate($expiry_date),
        );
        $email_subject = $this->getEmailSubject($module_conf, $user_data['id_lang']);
        $email_template= $this->getTemplateName($module_conf);
        $is_mail_send = Mail::Send(
            $this->context->language->id,
            $email_template,
            Tools::htmlentitiesDecodeUTF8($email_subject),
            $template_vars,
            $customer_email,
            $customer_name,
            Configuration::get('PS_SHOP_EMAIL'),
            Configuration::get('PS_SHOP_NAME'),
            null,
            null,
            _PS_MODULE_DIR_ . 'birthdaycoupon/mails/',
            false,
            $this->context->shop->id
        );
        return $is_mail_send;
    }
    /*
     * Function to get random coupons
     */
    private function getNewCouponCode()
    {
        $length = 8;
        $code = '';
        $chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ0123456789';
        $maxlength = Tools::strlen($chars);
        if ($length > $maxlength) {
            $length = $maxlength;
        }
        $i = 0;
        while ($i < $length) {
            $char = Tools::substr($chars, mt_rand(0, $maxlength - 1), 1);
            if (!strstr($code, $char)) {
                $code .= $char;
                $i++;
            }
        }
        // Check if coupon code alredy exist or not
        $sql = 'SELECT * FROM ' . _DB_PREFIX_ . 'cart_rule where code = "' . pSQL($code) . '"';
        $result = Db::getInstance()->executeS($sql);
        if (count($result) == 0) {
            return $code;
        }
        return $this->generateCouponCode();
    }
    
    /*
     * Method for getting the user list for whom the coupon will be generated
     * Parameter will accept the module configuation
     */
    public function getCustomerList($conf)
    {
        $sql='SELECT DISTINCT c.id_customer, c.id_lang, c.firstname, c.lastname, c.email '
                . 'FROM '._DB_PREFIX_.'customer AS c '
                . 'LEFT JOIN '._DB_PREFIX_.'orders AS o ON c.id_customer = o.id_customer '
                . 'WHERE c.is_guest=0 '
                . 'AND c.active=1';
        
        if ((int)$conf['valid_order']==1) {
            $sql.=' AND o.valid = 1';
        }
        // 1 indicate the cron for Customer birthday
        if ((int)$conf['type_birthday']==1) {
            $sql.=' AND MONTH(c.birthday)= EXTRACT(MONTH FROM DATE_ADD(CURDATE(), INTERVAL '. (int)$conf['number_of_days'].' DAY))';
            $sql.=' AND DAY(c.birthday)= EXTRACT(DAY FROM DATE_ADD(CURDATE(), INTERVAL '. (int)$conf['number_of_days'].' DAY))';
        }
        //2 indicate the cron for one year of created account
        if ((int)$conf['type_birthday']==2) {
            $sql.=' AND MONTH(c.date_add)= EXTRACT(MONTH FROM DATE_ADD(CURDATE(), INTERVAL '. (int)$conf['number_of_days'].' DAY))';
            $sql.=' AND DAY(c.date_add)= EXTRACT(DAY FROM DATE_ADD(CURDATE(), INTERVAL '. (int)$conf['number_of_days'].' DAY))';
            $sql.=' AND YEAR(c.date_add)< EXTRACT(YEAR FROM DATE_ADD(CURDATE(), INTERVAL '. (int)$conf['number_of_days'].' DAY))';
        }
        $sql.=' AND c.id_customer NOT IN ( SELECT bm.id_customer '
                . ' FROM '._DB_PREFIX_.'birthday_coupon_cart_rule_mapping AS bm'
                . ' WHERE YEAR(bm.date_added)= YEAR(CURDATE()))';
        $result = Db::getInstance()->executeS($sql);
        return $result;
    }

    /*
     * Function for getting different configuration setting of the module.
     */
    public function getConfiguarationSetting($setting_name)
    {
        $form_value=array();
        switch ($setting_name) {
            case 'module_conf':
                $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_MODULE_CONFIGURATION));
                break;
            case 'email_birthday_conf':
                $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_EMAIL_CONFIGURATION));
                break;
            case 'email_anniversary_conf':
                $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_EMAIL_ANNIVERSARY_CONFIGURATION));
                break;
            case 'category_restriction_conf':
                $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_CATEGORY_RESTRICTION_CONFIGURATION));
                break;
            case 'country_restriction_conf':
                $form_value=Tools::unSerialize(Configuration::get(self::BIRTHDAY_COUPON_COUNTRY_RESTRICTION_CONFIGURATION));
                break;
        }
        return $form_value;
    }
}
