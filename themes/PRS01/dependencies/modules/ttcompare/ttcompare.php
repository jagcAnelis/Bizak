<?php
/**
*  @author    TemplateTrip
*  @copyright 2015-2017 TemplateTrip. All Rights Reserved.
*  @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

require_once(_PS_MODULE_DIR_.'ttcompare/classes/CompareProduct.php');

class Ttcompare extends Module
{
    private $templateFile;
    private $link;

    public function __construct()
    {
        $this->name = 'ttcompare';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateTrip';
        $this->controllers = array('compare');
        $this->bootstrap = true;
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('TT - Compare Products');
        $this->description = $this->l('Adds Product Compare function to store.');

        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);

        $this->link = $this->context->link;

        $this->templateFile = 'module:ttcompare/views/templates/hook/ttcompare.tpl';
    }

    public function install()
    {
        $this->createTables();

        Configuration::updateValue('TTCOMPARATOR_ENABLE', 1);
        Configuration::updateValue('TTCOMPARATOR_PRODUCTLIST', 1);
        Configuration::updateValue('TTCOMPARATOR_PRODUCTPAGE', 1);
        Configuration::updateValue('TTCOMPARATOR_HEADER', 1);
        Configuration::updateValue('TTCOMPARATOR_MAXITEM', '3');

        return parent::install()
            && $this->registerHook('displayttcompareButton')
            && $this->registerHook('displayttcompareHeader')
            && $this->registerHook('displayttcompare')
            && $this->registerHook('displayHeader');
    }

    public function uninstall()
    {
        $this->deleteTables();

        Configuration::deleteByName('TTCOMPARATOR_ENABLE');
        Configuration::deleteByName('TTCOMPARATOR_PRODUCTLIST');
        Configuration::deleteByName('TTCOMPARATOR_PRODUCTPAGE');
        Configuration::deleteByName('TTCOMPARATOR_HEADER');
        Configuration::deleteByName('TTCOMPARATOR_MAXITEM');

        return parent::uninstall();
    }

    protected function createTables()
    {
        $res = (bool)Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ttcompare` (
            `id_compare` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_customer` int(10) unsigned NOT NULL,
            PRIMARY KEY (`id_compare`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        $res &= Db::getInstance()->execute('
            CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ttcompare_product` (
            `id_compare` int(10) unsigned NOT NULL,
            `id_product` int(10) unsigned NOT NULL,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_compare`, `id_product`)
            ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;
        ');

        return $res;
    }

    protected function deleteTables()
    {
        return Db::getInstance()->execute('
            DROP TABLE IF EXISTS `'._DB_PREFIX_.'ttcompare`, `'._DB_PREFIX_.'ttcompare_product`;
        ');
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitttcompare';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable Product Comparison'),
                        'name' => 'TTCOMPARATOR_ENABLE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Compare Button in Product List'),
                        'name' => 'TTCOMPARATOR_PRODUCTLIST',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Compare Button in Product Page'),
                        'name' => 'TTCOMPARATOR_PRODUCTPAGE',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Display Compare Link in Header'),
                        'name' => 'TTCOMPARATOR_HEADER',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Product comparison'),
                        'validation' => 'isUnsignedId',
                        'name' => 'TTCOMPARATOR_MAXITEM',
                        'required' => true,
                        'cast' => 'intval',
                        'hint' => $this->l('Set the maximum number of products that can be selected for comparison. Set to "0" to disable this feature.'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    protected function getConfigFormValues()
    {
        $data = array(
            'TTCOMPARATOR_ENABLE' => Tools::getValue('TTCOMPARATOR_ENABLE', Configuration::get('TTCOMPARATOR_ENABLE')),
            'TTCOMPARATOR_PRODUCTLIST' => Tools::getValue('TTCOMPARATOR_PRODUCTLIST', Configuration::get('TTCOMPARATOR_PRODUCTLIST')),
            'TTCOMPARATOR_PRODUCTPAGE' => Tools::getValue('TTCOMPARATOR_PRODUCTPAGE', Configuration::get('TTCOMPARATOR_PRODUCTPAGE')),
            'TTCOMPARATOR_HEADER' => Tools::getValue('TTCOMPARATOR_HEADER', Configuration::get('TTCOMPARATOR_HEADER')),
            'TTCOMPARATOR_MAXITEM' => Tools::getValue('TTCOMPARATOR_MAXITEM', Configuration::get('TTCOMPARATOR_MAXITEM')),
        );
        return $data;
    }

    protected function postProcess()
    {
        if (((bool)Tools::isSubmit('submitttcompare')) == true) {
            Configuration::updateValue('TTCOMPARATOR_ENABLE', (int)(Tools::getValue('TTCOMPARATOR_ENABLE')));
            Configuration::updateValue('TTCOMPARATOR_PRODUCTLIST', (int)(Tools::getValue('TTCOMPARATOR_PRODUCTLIST')));
            Configuration::updateValue('TTCOMPARATOR_PRODUCTPAGE', (int)(Tools::getValue('TTCOMPARATOR_PRODUCTPAGE')));
            Configuration::updateValue('TTCOMPARATOR_HEADER', (int)(Tools::getValue('TTCOMPARATOR_HEADER')));
            Configuration::updateValue('TTCOMPARATOR_MAXITEM', Tools::getValue('TTCOMPARATOR_MAXITEM'));
            $this->_clearCache($this->templateFile);
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }
        return '';
    }

    public function hookDisplayHeader()
    {
        if (Configuration::get('TTCOMPARATOR_ENABLE') && Configuration::get('TTCOMPARATOR_MAXITEM') > 0) {
            $this->context->controller->registerJavascript('module-compare', 'modules/'.$this->name.'/views/js/products-comparison.js', array('position' => 'bottom', 'priority' => 150));

            $compared_products = array();
            if (Configuration::get('TTCOMPARATOR_MAXITEM') && isset($this->context->cookie->id_compare)) {
                $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
            }
            
            $comparator_max_item = (int)Configuration::get('TTCOMPARATOR_MAXITEM');
            
            $productcompare_max_item = sprintf($this->l('You cannot add more than %d product(s) to the product comparison'), $comparator_max_item);
            Media::addJsDef(
                array(
                    'compareUrl' => $this->link->getModuleLink('ttcompare', 'compare'),
                    'compareAdd' => $this->l('The product has been added to product comparison'),
                    'compareRemove' => $this->l('The product has been removed from the product comparison.'),
                    'compareView' => $this->l('Compare'),
                    'comparedProductsIds' => (count($compared_products)>0) ? $compared_products : array(),
                    'comparator_max_item' => $comparator_max_item,
                    'compared_products' => (count($compared_products)>0) ? $compared_products : array(),
                    'max_item' => $productcompare_max_item,
                )
            );
        }
    }

    public function hookDisplayttcompareButton($params)
    {
        if (Configuration::get('TTCOMPARATOR_ENABLE') && Configuration::get('TTCOMPARATOR_MAXITEM') > 0) {
            $page_name = Dispatcher::getInstance()->getController();
            if ((Configuration::get('TTCOMPARATOR_PRODUCTLIST') && $page_name != 'product') || (Configuration::get('TTCOMPARATOR_PRODUCTPAGE') && $page_name == 'product')) {
                $id_product = $params['product']['id_product'];
                $compared_products = array();
                if (Configuration::get('TTCOMPARATOR_MAXITEM') && isset($this->context->cookie->id_compare)) {
                    $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
                }
                $added = false;
                if (count($compared_products) > 0 && in_array($id_product, $compared_products)) {
                    $added = true;
                }
                $this->smarty->assign(array(
                    'added' => $added,
                    'id_product' => $id_product,
                ));
                return $this->display(__FILE__, 'ttcompare-button.tpl');
            }
        }
    }

    public function hookDisplayttcompare($params)
    {
        if (Configuration::get('TTCOMPARATOR_ENABLE') && Configuration::get('TTCOMPARATOR_MAXITEM') > 0) {
            $compared_products = array();
            if (Configuration::get('TTCOMPARATOR_MAXITEM') && isset($this->context->cookie->id_compare)) {
                $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
            }

            $this->smarty->assign(array(
                'compareUrl' => $this->link->getModuleLink('ttcompare', 'compare'),
                'compared_products'   => is_array($compared_products) ? $compared_products : array(),
                'comparator_max_item' => Configuration::get('TTCOMPARATOR_MAXITEM'),
            ));

            return ($this->display(__FILE__, 'product-compare.tpl'));
        }
    }

    public function hookDisplayttcompareHeader($params)
    {
        if (Configuration::get('TTCOMPARATOR_ENABLE') && Configuration::get('TTCOMPARATOR_HEADER') && Configuration::get('TTCOMPARATOR_MAXITEM') > 0) {
            $compared_products = array();
            if (Configuration::get('TTCOMPARATOR_MAXITEM') && isset($this->context->cookie->id_compare)) {
                $compared_products = CompareProduct::getCompareProducts($this->context->cookie->id_compare);
            }

            $this->smarty->assign(array(
                'compareUrl' => $this->link->getModuleLink('ttcompare', 'compare'),
                'compared_products'   => is_array($compared_products) ? $compared_products : array(),
                'comparator_max_item' => Configuration::get('TTCOMPARATOR_MAXITEM'),
            ));

            return ($this->display(__FILE__, 'product-compare-header.tpl'));
        }
    }

    public function prepareVariables()
    {
        return array(
            'TTCOMPARATOR_ENABLE' => (int)Configuration::get('TTCOMPARATOR_ENABLE'),
            'TTCOMPARATOR_PRODUCTLIST' => (int)Configuration::get('TTCOMPARATOR_PRODUCTLIST'),
            'TTCOMPARATOR_PRODUCTPAGE' => (int)Configuration::get('TTCOMPARATOR_PRODUCTPAGE'),
            'TTCOMPARATOR_HEADER' => (int)Configuration::get('TTCOMPARATOR_HEADER'),
            'TTCOMPARATOR_MAXITEM' => Configuration::get('TTCOMPARATOR_MAXITEM'),
        );
    }
}
