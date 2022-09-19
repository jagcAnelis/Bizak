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

class AdminDonationStatsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->list_no_link = true;
        $this->table = 'wk_donation_stats';
        parent::__construct();
        $this->toolbar_title = $this->l('Donations Statistics');
        $this->_defaultOrderBy = 'id_donation_info';
        $this->identifier = 'id_donation_info';
        $this->_group = 'GROUP BY a.id_donation_info';

        $this->_select .= ' COUNT(DISTINCT a.`id_customer`) as `total_customer` ,';
        $this->_select .= ' COUNT(DISTINCT a.`id_order`) as `total_order`,';
        $this->_select .= ' IF(di.`active`, "'.$this->l('Active').'", IF(IFNULL(di.`active`, \'1\'), "'.
        $this->l('Deleted').'", "'.$this->l('Inactive').'")) as status, ';
        $this->_select .= ' IF(di.`active`, 1, 0) badge_success, IF(di.`active`, 0, 1) badge_danger, ' ;

        $this->_select .= '  ROUND(SUM(CASE ';
        foreach (Currency::getCurrencies(false) as $currency) {
            $this->_select .= '  WHEN od.`id_currency` = '.$currency['id_currency'].'
            THEN ord.`total_price_tax_incl` / '.$currency['conversion_rate'];
        }
        $this->_select .= ' END), 2) as `total_amount` ,';
        $this->_select .= ' self.`name` as latest_name ';

        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'orders` od ON (od.`id_order` = a.`id_order`)';
        $this->_join .= ' LEFT JOIN (SELECT * FROM `'._DB_PREFIX_.'wk_donation_stats` WHERE id_donation_stats
        IN (SELECT MAX(id_donation_stats) FROM  `'._DB_PREFIX_.'wk_donation_stats` GROUP BY id_donation_info) ) self
        ON (self.`id_donation_info` = a.`id_donation_info`)';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'order_detail` ord
        ON CONCAT(ord.`id_order`, ord.`product_id`)= CONCAT(a.`id_order`, a.`id_product`)';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'wk_donation_info` di
        ON (di.`id_donation_info` = a.`id_donation_info`)';

        if (Tools::getIsset('viewwk_donation_stats')) {
            $this->list_id = 'donation_stats_customer';
        } else {
            $this->list_id = 'wk_donation_stats';
        }

        $this->allow_export = true;
        $this->addRowAction('view');
    }

    public function initDonationStats()
    {
        $statusList = array(
            $this->l('Active') => $this->l('Active'),
            $this->l('Inactive') => $this->l('Inactive'),
            $this->l('deleted') => $this->l('deleted')
        );
        $this->toolbar_title = $this->l('Donation statistics');

        $this->fields_list = array(
            'id_donation_info' => array(
                'title' => $this->l('ID'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
            ),
            'latest_name' => array(
                'title' => $this->l('Donation name'),
                'align' => 'center',
                'filter_key' => 'self!name',
                'havingFilter' => true,
                'callback' => 'displayDonationlink'
            ),
            'total_amount' => array(
                'title' => $this->l('Total donation amount'),
                'type' => 'price',
                'align' => 'center',
                'currency' => true,
                'havingFilter' => true,
            ),
            'total_customer' => array(
                'title' => $this->l('Total no. of customer'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'havingFilter' => true,
                'hint' => $this->l('Total number of customer that have donated in a campaign'),
            ),
            'total_order' => array(
                'title' => $this->l('Total no. of order'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'type' => 'datetime',
                'hint' => $this->l('Total number of donation recieved in a campaign'),
                'filter_key' => 'a!date_add',
            ),
            'status' => array(
                'title' => $this->l('Donation status'),
                'align' => 'center',
                'type' => 'select',
                'list' => $statusList,
                'hint' => $this->l('Donation current status'),
                'badge_success' => true,
                'badge_danger' => true,
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
                'filter_key' => 'status',
            ),
        );

        if (Tools::isSubmit('submitReset'.$this->list_id)) {
            $this->processResetFilters($this->list_id);
        } elseif (Tools::getValue('submitFilter'.$this->list_id)) {
            $this->toolbar_title = '';
        }
        $this->processFilter();

        return parent::renderList();
    }

    public function displayDonationLink($name, $row)
    {
        if (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo($row['id_donation_info']))) {
            $this->context->smarty->assign(array(
                'displayText' => $name,
                'displayLink' => $this->context->link->getAdminLink(
                    'AdminManageDonation'
                ).'&id_donation_info='.$objDonationInfo->id.'&updatewk_donation_info'
            ));

            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.$this->module->name.
                '/views/templates/admin/donation_stats/helpers/_partials/display-link.tpl'
            );
        } else {
            return $name;
        }
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function renderList()
    {
        $list = $this->initDonationStats();
        $list .= $this->initNewDonationsList();

        return $list;
    }

    public function initNewDonationsList()
    {
        unset($this->fields_list, $this->_select, $this->_join, $this->_filterHaving, $this->_having);
        $this->filter = false;
        $this->toolbar_title = $this->l('Nonfunded donation campaigns');
        $this->table = 'wk_donation_info';
        $this->className   = 'WkDonationInfo';
        $this->identifier = 'id_donation_info';

        $this->_select = ' dl.`name`, IF(a.`expiry_date`, a.`expiry_date`, "'.$this->l('No expiry').'") as expiry, ';
        $this->_select .= ' IF(IFNULL(a.`active`, \'0\'), "'.$this->l('Active').'", "'.$this->l('Inactive').'")
        as status, ';
        $this->_select .= ' IF(a.`active`, 1, 0) badge_success, IF(a.`active`, 0, 1) badge_danger, ' ;

        $this->_join = ' LEFT JOIN `'._DB_PREFIX_.'wk_donation_info_lang` dl
        ON (dl.`id_donation_info` = a.`id_donation_info`)';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'wk_donation_stats` ds
        ON (ds.`id_donation_info` = a.`id_donation_info`)';
        $this->_where = ' AND ds.`id_donation_info` IS NULL AND a.`is_global` = 0
        AND dl.`id_lang` = '.(int)$this->context->language->id;
        $this->_orderBy = null;
        $this->list_id = 'newDonationList';


        $this->fields_list = array(
            'id_donation_info' => array(
                'title' => $this->l('ID'),
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Donation name'),
                'align' => 'center',
                'callback' => 'displayDonationlink',
                'search' => false,
            ),
            'price_type' => array(
                'title' => $this->l('Price type'),
                'align' => 'center',
                'hint' => $this->l('\'Fixed\' means donation amount is fixed, \'By customer\' means donation amount can be entered by customer'),
                'search' => false,
                'callback' => 'getPriceType',
            ),
            'price' => array(
                'title' => $this->l('Price'),
                'align' => 'center',
                'type' => 'price',
                'search' => false,
            ),
            'date_add' => array(
                'title' => $this->l('Date created'),
                'class' => 'fixed-width-xs',
                'align' => 'text-right',
                'type' => 'datetime',
                'search' => false,
            ),
            'expiry' => array(
                'title' => $this->l('Expiry date'),
                'class' => 'fixed-width-xs',
                'align' => 'text-right',
                'type' => 'datetime',
                'search' => false,
            ),
            'status' => array(
                'title' => $this->l('Donation status'),
                'hint' => $this->l('Donation current status'),
                'align' => 'center',
                'badge_success' => true,
                'badge_danger' => true,
                'class' => 'fixed-width-xs',
                'search' => false,
            ),
        );
        $this->actions = array();
        $this->processResetFilters($this->list_id);

        return parent::renderList();
    }

    public function getPriceType($row)
    {
        if ($row == WkDonationInfo::WK_DONATION_PRICE_TYPE_FIXED) {
            return $this->l('Fixed');
        } elseif ($row == WkDonationInfo::WK_DONATION_PRICE_TYPE_CUSTOMER) {
            return $this->l('By customer');
        }
    }

    public function displayViewLink($token, $id)
    {
        $tpl = $this->createTemplate('helpers/list/list_action_view.tpl');
        if (Tools::getisset('viewwk_donation_stats')) {
            foreach ($this->_list as $row) {
                if ($id == $row['id_donation_stats']) {
                    $idOrder = $row['id_order'];
                    break;
                }
            }
            $tpl->assign(array(
                'href' => $this->context->link->getAdminLink('AdminOrders').'&id_order='.$idOrder.'&vieworder',
                'action' => $this->l('View Order'),
            ));
        } else {
            $tpl->assign(array(
                'href' => self::$currentIndex.'&'.$this->identifier.'='.$id.'&view'.$this->table.
                '&token='.($token != null ? $token : $this->token),
                'action' => $this->l('View'),
            ));
        }

        return $tpl->fetch();
    }

    public function renderKpis()
    {
        $objDonationInfo = new WkDonationInfo();
        $kpis = array();

        $helper = new HelperKpi();
        $helper->id = 'box-total-donation-orders';
        $helper->icon = 'icon-shopping-cart';
        $helper->color = 'color1';
        $helper->title = $this->l('Total Donations');
        $helper->value = $objDonationInfo->getTotalDonationCount();
        $kpis[] = $helper->generate();

        $helper = new HelperKpi();
        $helper->id = 'box-total-donation-amount';
        $helper->icon = 'icon-money';
        $helper->color = 'color3';
        $helper->title = $this->l('Total Donation Amount');
        $helper->value = Tools::displayPrice($objDonationInfo->getTotalDonationAmount());
        $kpis[] = $helper->generate();

        $helper = new HelperKpi();
        $helper->id = 'box-total-donation-customer';
        $helper->icon = 'icon-user';
        $helper->color = 'color4';
        $helper->title = $this->l('Total Customers');
        $helper->value = $objDonationInfo->getTotalCustomerCount();
        $kpis[] = $helper->generate();


        $helper = new HelperKpiRow();
        $helper->refresh = false;
        $helper->kpis = $kpis;

        return $helper->generate();
    }

    public function renderView()
    {
        if ($idDonation = Tools::getValue('id_donation_info')) {
            $this->table = 'wk_donation_stats';
            $this->identifier = 'id_donation_stats';

            $this->_orderBy = 'id_order';
            $this->_orderWay = 'DESC';
            $this->_group = '';

            $this->_select = ' CONCAT(cu.`firstname`,\' \', cu.`lastname`) as `customer_name` ,
            ord.`total_price_tax_incl` as `amount` ,od.`id_currency` as `id_currency` , ';
            $this->_select .= 'IF(os.`paid`, 1, 0) badge_success , IF(os.`paid`, 0, 1) badge_danger, ';
            $this->_select .= 'IF(IFNULL(os.`paid`, \'0\'), "'.$this->l('Payment received').'",
            IF(os.`id_order_state` =  \'6\', "'.$this->l('Cancled').'",
            IF(os.`id_order_state` =  \'7\', "'.$this->l('Refunded').'",
            IF(os.`id_order_state` =  \'8\', "'.$this->l('Payment Error').'",
            "'.$this->l('Payment awaiting').'")))) as order_status, ';

            $this->_join = ' LEFT JOIN `'._DB_PREFIX_.'orders` od ON (od.`id_order` = a.`id_order`)';
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'customer` cu ON (od.`id_customer` = cu.`id_customer`)';
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = od.`current_state`)';
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'order_detail` ord
            ON CONCAT(ord.`id_order`, ord.`product_id`)= CONCAT(a.`id_order`, a.`id_product`)';

            $this->_where = ' AND a.`id_donation_info` = '. (int)$idDonation;

            $this->initDonationOrderList();

            return parent::renderList();
        }
    }

    public function initDonationOrderList()
    {
        $this->status_array = array(
            $this->l('Payment received') => $this->l('Payment received'),
            $this->l('Payment awaiting') => $this->l('Payment awaiting'),
            $this->l('Cancled') => $this->l('Cancled'),
            $this->l('Refunded') => $this->l('Refunded'),
            $this->l('Payment Error') => $this->l('Payment Error')
        );

        if ($idDonation = Tools::getValue('id_donation_info')) {
            $this->fields_list = array(
                'id_order' => array(
                    'title' => $this->l('Order ID'),
                    'align' => 'center',
                    'havingFilter' => true,
                    'filter_key' => 'a!id_order',
                    'class' => 'fixed-width-xs',
                ),
                'customer_name' => array(
                    'title' => $this->l('Customer name'),
                    'align' => 'center',
                    'havingFilter' => true,
                    'callback' => 'displayCustomerName'
                ),
                'amount' => array(
                    'title' => $this->l('Amount donated'),
                    'type' => 'price',
                    'havingFilter' => true,
                    'currency' => true,
                    'callback' => 'displayDonationAmount',
                    'align' => 'center',
                ),
                'order_status' => array(
                    'title' => $this->l('Payment status'),
                    'type' => 'select',
                    'align' => 'center',
                    'list' => $this->status_array,
                    'havingFilter' => true,
                    'filter_key' => 'order_status',
                    'badge_success' => true,
                    'badge_danger' => true,
                ),
                'date_add' => array(
                    'title' => $this->l('Donation date'),
                    'class' => 'fixed-width-xs',
                    'align' => 'text-right',
                    'type' => 'datetime',
                    'filter_key' => 'a!date_add',
                ),
            );

            if (Validate::isLoadedObject($objDonationInfo = new WkDonationInfo((int)$idDonation))) {
                $this->toolbar_title = $objDonationInfo->name[$this->context->language->id].' > '.$this->l('View');
            } else {
                $donationName = $objDonationInfo->getDonationNameFromStats((int)$idDonation);
                $this->toolbar_title = $donationName.' > '.$this->l('View');
            }

            self::$currentIndex = self::$currentIndex.'&viewwk_donation_stats&id_donation_info='.(int) $idDonation;
            $objDonationInfo = new WkDonationInfo();
            $this->context->smarty->assign(array(
                'stats_page' => 'viewwk_donation_stats',
                'total_amount' => Tools::displayPrice($objDonationInfo->getTotalDonationAmount($idDonation)),
                'total_donations' => $objDonationInfo->getTotalDonationCount($idDonation),
                'total_customer' => $objDonationInfo->getTotalCustomerCount($idDonation)
            ));
        }
    }

    public function displayCustomerName($name, $row)
    {
        $this->context->smarty->assign(array(
            'displayText' => $name,
            'displayLink' => $this->context->link->getAdminLink('AdminCustomers').'&id_customer='.
            $row['id_customer'].'&viewcustomer'
        ));

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.$this->module->name.
            '/views/templates/admin/donation_stats/helpers/_partials/display-link.tpl'
        );
    }

    public function displayDonationAmount($amount, $data)
    {
        return Tools::displayPrice($amount, (int)$data['id_currency']);
    }

    protected function filterToField($key, $filter)
    {
        if (Tools::getIsset('viewwk_donation_stats')
        ) {
            $this->initDonationOrderList();
        }

        return parent::filterToField($key, $filter);
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitResetdonation_stats_customer')) {
            $this->processResetFilters();
        }
        parent::postProcess();
    }
}
