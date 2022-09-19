<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/vendor/autoload.php';

use DHLParcel\Shipping\Model\Core\Kernel;
use DHLParcel\Shipping\Model\Core\Settings;
use DHLParcel\Shipping\Model\Data\Api\Response\Capability\Option;
use DHLParcel\Shipping\Model\Data\Db\CartData\ServicePoint as ServicePointCartData;
use DHLParcel\Shipping\Model\Data\Db\OrderData\ServicePoint as ServicePointOrderData;
use DHLParcel\Shipping\Model\Data\Panel;
use DHLParcel\Shipping\Model\Service\CapabilityService;
use DHLParcel\Shipping\Model\Service\CartService;
use DHLParcel\Shipping\Model\Service\LabelService;
use DHLParcel\Shipping\Model\Service\OrderService;
use DHLParcel\Shipping\Model\Service\PanelNotificationService;
use DHLParcel\Shipping\Model\Service\PanelService;
use DHLParcel\Shipping\Model\Service\PresetService;
use DHLParcel\Shipping\Model\Service\SettingsService;

class DHLParcel_Shipping extends CarrierModule
{
    public $id_carrier;
    public $tabs = [];

    public function __construct()
    {
        $this->name = 'dhlparcel_shipping';
        $this->displayName = $this->l('DHL Parcel for PrestaShop');
        $this->description = $this->l('DHL Parcel for PrestaShop');
        $this->tab = 'shipping_logistics';
        $this->version = '0.1.4';
        $this->author = 'DHL Parcel';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0',
            'max' => _PS_VERSION_
        ];
        $this->bootstrap = true;

        if (version_compare(_PS_VERSION_, '1.7.5', '<')) {
            $this->tabs = [[
                'name'              => 'DHL Parcel for PrestaShop',
                'class_name'        => 'DHLParcelMenu',
                'visible'           => true,
                'parent_class_name' => 'CONFIGURE',
            ]];
        }

        parent::__construct();

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('DHLPARCEL_SHIPPING')) {
            $this->warning = $this->l('No name provided');
        }
    }

    public function install()
    {
        // Run sql for creating DB tables
        Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_labels` (
                `id_label` int(11) unsigned NOT null AUTO_INCREMENT,
                `id_order` INT(11) UNSIGNED NOT null,
                `label_uuid` varchar(255) NOT null,
                `size` varchar(255) NOT null,
                `options` varchar(255) NOT null,
                `file` varchar(255) NOT null,
                `url` varchar(255) NOT null,
                `tracker_code` varchar(255) NOT null,
                `is_return` int(1) NOT NULL,
                PRIMARY KEY(`id_label`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET = utf8;
        ');

        Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_presets` (
                `id_preset` int(11) unsigned NOT null AUTO_INCREMENT,
                `id_carrier` int(11) unsigned NOT null,
                `link` int(1) NOT NULL,
                `options` text NOT null,
                `temp_link` int(1) NOT NULL,
                `temp_options` text NOT null,
                PRIMARY KEY(`id_preset`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET = utf8;
        ');

        Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_cart_data` (
                `id_data` int(11) unsigned NOT null AUTO_INCREMENT,
                `id_cart` int(11) unsigned NOT null,
                `data` text NOT null,
                PRIMARY KEY(`id_data`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET = utf8;
        ');

        Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_order_data` (
                `id_data` int(11) unsigned NOT null AUTO_INCREMENT,
                `id_order` int(11) unsigned NOT null,
                `data` text NOT null,
                PRIMARY KEY(`id_data`)
            ) ENGINE = ' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET = utf8;
        ');

        if (!parent::install() ||
            !$this->registerHook('displayHeader') ||
            !$this->registerHook('backOfficeHeader') ||
            !$this->registerHook('displayAdminOrderRight') ||
            !$this->registerHook('displayCarrierList') ||
            !$this->registerHook('displayCarrierExtraContent') ||
            !$this->registerHook('displayBeforeCarrier') ||
            !$this->registerHook('actionDeliveryPriceByPrice') ||
            !$this->registerHook('actionCarrierUpdate') ||
            !$this->registerHook('displayExpressCheckout') ||
            !$this->registerHook('actionValidateOrder') ||
            !Configuration::updateValue('DHLPARCEL_SHIPPING', 'DHL Parcel for PrestaShop') ||
            !$this->installTabs()
        ) {
            //CONFIGURE
            //ShopParameters
            return false;
        }

        PresetService::instance()->addCarriers();
        return true;
    }

    protected function installTabs()
    {
        if (version_compare(_PS_VERSION_, '1.7.5', '>=')) {
            return $this->installTab('CONFIGURE', 'DHLParcel_Shipping_BackOffice_Menu', 'DHL Parcel for PrestaShop');
        }
        return true;
    }

    public function hookDisplayHeader($params)
    {
        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'order') {
            $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_checkout.css');
            $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_modal.css');

            $servicePointLocator = Kernel::instance()->render('frontoffice/checkout/servicepoint/locator', [
                'logo' => '../modules/dhlparcel_shipping/assets/images/dhlparcel_shipping_logo.png'
            ]);
            $servicePointConfirmTemplate = Kernel::instance()->render('frontoffice/checkout/servicepoint/confirm-button');

            $servicePointLink = Context::getContext()->link->getModuleLink('dhlparcel_shipping', 'legacy', ['action' => 'servicePoint']);
            $servicePointSyncLink = Context::getContext()->link->getModuleLink('dhlparcel_shipping', 'legacy', ['action' => 'servicePointSync']);

            $servicePointMapsKey = SettingsService::instance()->mapsKey();
            Media::addJsDef([
                'dhlparcel_shipping_checkout_servicepoint_stylesheet'     => 'https://servicepoint-locator.dhlparcel.nl/servicepoint-locator.css',
                'dhlparcel_shipping_checkout_servicepoint_locator'        => $servicePointLocator,
                'dhlparcel_shipping_checkout_servicepoint_gradient'       => '../modules/dhlparcel_shipping/assets/images/dhlparcel_shipping_top_gradient.jpg',
                'dhlparcel_shipping_checkout_servicepoint_link'           => $servicePointLink,
                'dhlparcel_shipping_checkout_servicepoint_confirm_button' => $servicePointConfirmTemplate,
                'dhlparcel_shipping_checkout_servicepoint_sync'           => $servicePointSyncLink,
                'dhlparcel_shipping_checkout_servicepoint_maps_key'       => $servicePointMapsKey,
            ]);
            $this->context->controller->addJquery();
            $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_checkout_servicepoint.js');

            if (version_compare(_PS_VERSION_, '1.7.5', '>=')
                && version_compare(_PS_VERSION_, '1.7.6', '<')) {
                $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_checkout_carrier_selection_1_7_5.js');
            }

            $presets = PresetService::instance()->search([Option::KEY_PS]);
            if (!empty($presets)) {
                $servicePointCarriers = [];
                foreach ($presets as $preset) {
                    $servicePointCarriers[] = $preset->id_carrier;
                }

                Media::addJsDef([
                    'dhlparcel_shipping_checkout_servicepoints' => $servicePointCarriers,
                ]);
            }
        }
    }

    public function hookActionValidateOrder($params)
    {
        // TODO save servicepoint
        /** @var Cart $cart */
        $cart = $params['cart'];

        /** @var Order $order */
        $order = $params['order'];

        /** @var Customer $customer */
        $customer = $params['customer'];

        /** @var Currency $currency */
        $customer = $params['currency'];

        /** @var OrderState $orderStatus */
        $orderStatus = $params['orderStatus'];

        // Check if ServicePoint
        $presets = PresetService::instance()->search([Option::KEY_PS]);
        $servicePointCarriers = [];
        if (!empty($presets)) {
            foreach ($presets as $preset) {
                $servicePointCarriers[] = $preset->id_carrier;
            }
        }

        if (in_array($cart->id_carrier, $servicePointCarriers)) {

            $servicePointCartData = CartService::instance()->getDataKey($cart->id, CartService::SERVICEPOINT);
            if ($servicePointCartData) {
                $servicePointData = new ServicePointCartData($servicePointCartData);

                // Check if still in sync
                $address = new Address($this->context->cart->id_address_delivery);
                if ($servicePointData->postcode == $address->postcode
                    && strtoupper($servicePointData->countryCode) == strtoupper(Country::getIsoById($address->id_country))) {

                    // Save ServicePoint selection to OrderId
                    $servicePointOrderData = new ServicePointOrderData($servicePointData->toArray());
                    OrderService::instance()->saveDataKey($order->id, OrderService::SERVICEPOINT, $servicePointOrderData->toArray());
                }
            }
        }
    }

    public function hookDisplayBeforeCarrier($params)
    {
        // TODO data before showing carrier in checkout
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_carrier.js');
    }

    public function hookBackOfficeHeader($params)
    {
        if (Dispatcher::getInstance()->getController() !== 'AdminCarrierWizard') {
            return;
        }

        $carrierId = Tools::getValue('id_carrier');
        if (!$carrierId) {
            return;
        }

        $carrier = new Carrier($carrierId);
        if ($carrier->external_module_name !== $this->name) {
            return;
        }

        $preset = PresetService::instance()->load($carrierId);
        if (!$preset) {
            return;
        }

        $carrierLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'carrier']);
        $carrierTemporarySaveLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'carrierTemporarySave']);

        Media::addJsDef([
            'dhlparcel_shipping_backoffice_carrier_id'                  => $carrierId,
            'dhlparcel_shipping_backoffice_carrier_link'                => $carrierLink,
            'dhlparcel_shipping_backoffice_carrier_temporary_save_link' => $carrierTemporarySaveLink
        ]);

        $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_panel.css');
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_carrier.js');
    }

    public function hookActionDeliveryPriceByPrice($params)
    {
        // TODO temp
        //array('id_carrier' => $id_carrier, 'order_total' => $order_total, 'id_zone' => $id_zone)
    }

    public function hookActionCarrierUpdate($params)
    {
        $carrierId = $params['id_carrier'];
        /** @var Carrier $carrier */
        $carrier = $params['carrier'];

        if ($preset = PresetService::instance()->load($carrierId)) {
            if (!$preset->temp_link) {
                $preset->temp_options = [];
            }
            PresetService::instance()->update($carrier->id, $preset->temp_link, $preset->temp_options);
        }
    }

    /**
     * @param Cart $cart
     * @param $shipping_cost
     * @return bool|float|int
     */
    public function getOrderShippingCost($cart, $shipping_cost)
    {
        // Capability check on checkout
        $receiverAddress = new Address($cart->id_address_delivery);
        $business = boolval(Configuration::get(Settings::BUSINESS));

        $capabilities = CapabilityService::instance()->check(
            Country::getIsoById($receiverAddress->id_country),
            $receiverAddress->postcode,
            $business
        );

        $options = CapabilityService::instance()->getOptions($capabilities);

        $preset = PresetService::instance()->load($this->id_carrier);
        if (!$preset) {
            return false;
        }

        foreach($preset->options as $option) {
            if (!array_key_exists($option, $options)) {
                return false;
            }
        }

        return $this->getCostForCart($cart, $this->id_carrier);
    }

    /**
     * Return a BASE price to display for the user ( base price + shipping handling if enabled )
     * Tax calculations are done AFTER this method is called
     *
     * We are using the same code that presta uses in getPackageShippingCost to determine the cost, we'd very much like to use the default value..
     * but unfortunetelly we are forced to implement our own getCost method to declare a carrier.
     * This method assumes the carrier is valid, that is that it is in range !!
     *
     * invokes getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone =
     * incokes $cart->getProducts($refresh = false, $id_product = false, $id_country = null, $fullInfos = true)
     *
     * @param Cart $cart
     * @param int $id_carrier - the zid of the carrier we are getting the cost for
     */
    protected function getCostForCart($cart, $id_carrier)
    {
        $carrier = new Carrier($id_carrier);
        $shipping_method = $carrier->getShippingMethod();
        $id_zone = Address::getZoneById((int)$cart->id_address_delivery);
        $product_list = $cart->getProducts(false, false, null, false);

        $shipping_hangling_cost = Configuration::get('PS_SHIPPING_HANDLING');

        // Order total in default currency without fees
        $order_total = $cart->getOrderTotal(true, Cart::BOTH_WITHOUT_SHIPPING, $product_list);
        $shipping_base_cost = 0;

        if ($shipping_method == Carrier::SHIPPING_METHOD_WEIGHT) {
            $shipping_base_cost = $carrier->getDeliveryPriceByWeight($cart->getTotalWeight($product_list), $id_zone);
        } else { // is price based
            $shipping_base_cost = $carrier->getDeliveryPriceByPrice($order_total, $id_zone, (int)$cart->id_currency);
        }

        if ($shipping_hangling_cost && $carrier->shipping_handling) {
            $shipping_base_cost += (float)$shipping_hangling_cost;
        }

        return $shipping_base_cost;
    }

    public function getOrderShippingCostExternal($params)
    {
        // Required to be 'implemented'
        return true;
    }

    public function uninstall()
    {
        // Run sql for deleting DB tables
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_labels`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_presets`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_cart_data`;');
        Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'dhlparcel_shipping_order_data`;');

        //$this->removeCarriers();
        PresetService::instance()->deleteCarriers();

        if (!parent::uninstall() ||
            !Configuration::deleteByName('DHLPARCEL_SHIPPING')
        ) {
            return false;
        }

        return true;
    }

    // Settings page
    public function getContent()
    {
        $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_settings.css');

        $authenticateLoadingMessage = $this->l('Please wait...');
        $authenticateAccountsMessage = $this->l('Accounts found. Click to use.');
        $shippingMethodResetMessage  = $this->l('Are you sure you want to reset all DHLParcel method to default');

        $authenticateTemplate = Kernel::instance()->render('backoffice/settings/account/authenticate');
        $shippingMethodJumpTemplate = Kernel::instance()->render('backoffice/settings/shipping-method/jump');
        $shippingMethodPaymentTemplate = Kernel::instance()->render('backoffice/settings/shipping-method/payment');
        $shippingMethodResetTemplate = Kernel::instance()->render('backoffice/settings/shipping-method/reset');

        $shippingMethodJumpLink = $this->context->link->getAdminLink('AdminCarriers');
        $shippingMethodPaymentLink = $this->context->link->getAdminLink('AdminPaymentPreferences');
        $authenticateLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'authenticate']);
        $shippingMethodResetLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'carrierReset']);

        Media::addJsDef([
            'dhlparcel_shipping_backoffice_ajax_authenticate'     => $authenticateLink,
            'dhlparcel_shipping_authenticate_template'            => $authenticateTemplate,
            'dhlparcel_shipping_authenticate_loading_message'     => $authenticateLoadingMessage,
            'dhlparcel_shipping_authenticate_accounts_message'    => $authenticateAccountsMessage,
            'dhlparcel_shipping_shipping_method_jump_link'        => $shippingMethodJumpLink,
            'dhlparcel_shipping_shipping_method_jump_template'    => $shippingMethodJumpTemplate,
            'dhlparcel_shipping_shipping_method_payment_link'     => $shippingMethodPaymentLink,
            'dhlparcel_shipping_shipping_method_payment_template' => $shippingMethodPaymentTemplate,
            'dhlparcel_shipping_shipping_method_reset_link'       => $shippingMethodResetLink,
            'dhlparcel_shipping_shipping_method_reset_template'   => $shippingMethodResetTemplate,
            'dhlparcel_shipping_shipping_method_reset_message'    => $shippingMethodResetMessage,
        ]);
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_settings.js');

        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            // TODO add validation
            $settings = Settings::instance()->allSettings();
            foreach ($settings as $key) {
                $formValue = trim(Tools::getValue($key));
                Configuration::updateValue($key, $formValue);
            }
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->displayForm();
    }

    public function displayForm()
    {
        // Get default language
        $defaultLang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fieldsForm[0]['form'] = Settings::instance()->getFormData();

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // Language
        $helper->default_form_language = $defaultLang;
        $helper->allow_employee_form_lang = $defaultLang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                    '&token=' . Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        // Load current values
        $settings = Settings::instance()->allSettings();
        foreach ($settings as $key) {
            $helper->fields_value[$key] = Configuration::get($key);
        }

        return $helper->generateForm($fieldsForm);
    }

    /**
     * displayAdminOrderRight
     */
    public function hookDisplayAdminOrderRight($params)
    {
        if (!isset($params['id_order'])) {
            return null;
        }

        $orderId = $params['id_order'];

        $panelLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'panel']);
        $createLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'panelCreate']);
        $deleteLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'panelDelete']);
        $servicePointLink = $this->context->link->getAdminLink('DHLParcelAjax', true, [], ['action' => 'panelServicePoint']);

        Media::addJsDef([
            'dhlparcel_shipping_backoffice_ajax_panel'              => $panelLink,
            'dhlparcel_shipping_backoffice_ajax_panel_create'       => $createLink,
            'dhlparcel_shipping_backoffice_ajax_panel_delete'       => $deleteLink,
            'dhlparcel_shipping_backoffice_ajax_panel_servicepoint' => $servicePointLink,
        ]);

        $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_panel_servicepoint.css');
        $this->context->controller->addCSS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/css/dhlparcel_shipping_panel.css');
        $this->context->controller->addJquery();
        $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_panel.js');
        $this->context->controller->addJS(_PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/dhlparcel_shipping/assets/js/dhlparcel_shipping_panel_servicepoint.js');

        $panelData = $this->getPanelData($orderId);

        return Kernel::instance()->render('backoffice/order/panel', $panelData);
    }

// // Deprecated in 1.7.0.0
//    public function hookDisplayCarrierList($params)
//    {
//        // TODO to be implemented hook
//        return '';
//    }

    // Copied logic from classes/checkout/DeliveryOptionsFinder.php, due to no way to inject that class here.
    protected function getSelectedDeliveryOption()
    {
        return intval(current($this->context->cart->getDeliveryOption(null, false, false)));
    }

    public function installTab($parent_class, $class_name, $name)
    {
        $tab = new Tab();
        // Define the title of your tab that will be displayed in BO

        #$tab->name[$this->context->language->id] = $name;
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[(int)$lang['id_lang']] = $name;
        }

        // Name of your admin controller
        $tab->class_name = $class_name;
        // Id of the controller where the tab will be attached
        // If you want to attach it to the root, it will be id 0 (I'll explain it below)
        $tab->id_parent = (int)Tab::getIdFromClassName($parent_class);
        // Name of your module, if you're not working in a module, just ignore it, it will be set to null in DB
        $tab->module = $this->name;
        // Other field like the position will be set to the last, if you want to put it to the top you'll have to modify the position fields directly in your DB
        return $tab->add();
    }

    protected function getPanelData($orderId)
    {
        $panelData = new Panel();
        $panelData->order = new Order($orderId);
        $panelData->business = boolval(Configuration::get(Settings::BUSINESS));

        $receiverAddress = new Address($panelData->order->id_address_delivery);
        $capabilities = CapabilityService::instance()->check(
            Country::getIsoById($receiverAddress->id_country),
            $receiverAddress->postcode,
            $panelData->business
        );

        $options = CapabilityService::instance()->getOptions($capabilities);

        $autoEnabledOptions = PanelService::instance()->autoEnabledOptions($orderId);
        $selectedOptions = array_keys($autoEnabledOptions);
        $selectedOptionsData = $autoEnabledOptions;

        $preset = PresetService::instance()->load($panelData->order->id_carrier);
        if ($preset) {
            $selectedOptions = array_merge($selectedOptions, $preset->options);
        }

        // Add/update additional data if needed
        if (in_array(Option::KEY_PS, $selectedOptions)) {
            if (!array_key_exists(Option::KEY_PS, $selectedOptionsData)) {
                // Check for saved data from custom table
                $servicePointOrderData = OrderService::instance()->getDataKey($orderId, OrderService::SERVICEPOINT);
                $servicePointData = new ServicePointOrderData($servicePointOrderData);
                if ($servicePointData->servicePointId) {
                    $selectedOptionsData[Option::KEY_PS] = $servicePointData->servicePointId;
                }
            }
            // Update ServicePoint data for template
            $selectedOptionsData[Option::KEY_PS] = [
                'value'   => isset($selectedOptionsData[Option::KEY_PS]) ? $selectedOptionsData[Option::KEY_PS] : null,
                'country' => Country::getIsoById($receiverAddress->id_country)
            ];
        }

        $panelData->deliveryOptions = PanelService::instance()->parseDeliveryOptions($options, $selectedOptions, $selectedOptionsData);
        $panelData->serviceOptions = PanelService::instance()->parseServiceOptions($options, $selectedOptions, $selectedOptionsData);
        $panelData->sizes = CapabilityService::instance()->getSizes($capabilities);
        $panelData->labels = LabelService::instance()->getAll($orderId);

        $panelData->notifications = [
            'notices'  => PanelNotificationService::instance()->getNotices(),
            'warnings' => PanelNotificationService::instance()->getWarnings(),
            'errors'   => PanelNotificationService::instance()->getErrors(),
        ];

        return $panelData->toArray();
    }
}
