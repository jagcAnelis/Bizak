<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2018 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

class TtFeaturedProducts extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'ttfeaturedproducts';
        $this->tab = 'front_office_features';
        $this->version = '1.0.5';
        $this->author = 'TemplateTrip';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array(
            'min' => '1.7.0.0',
            'max' => _PS_VERSION_,
        );

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('TT - Featured products', array(), 'Modules.TTfeaturedproducts.Admin');
        $this->description = $this->getTranslator()->trans('Displays featured products in the central column of your homepage.', array(), 'Modules.TTfeaturedproducts.Admin');
    }

    public function install()
    {
        $this->_clearCache('*');
        Configuration::updateValue('TTHOMEFEATURED_NBR', 10);
        Configuration::updateValue('TTHOMEFEATURED_CAT', (int) Context::getContext()->shop->getCategory());
        Configuration::updateValue('HOME_FEATURED_RANDOMIZE', true);

        return parent::install()
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && $this->registerHook('categoryUpdate')
            && $this->registerHook('displayHomeTab')
            || $this->registerHook('displayHome')
            && $this->registerHook('displayOrderConfirmation2')
            && $this->registerHook('displayCrossSellingShoppingCart')
            && $this->registerHook('actionAdminGroupsControllerSaveAfter')
        ;
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        $errors = array();
        if (Tools::isSubmit('submitHomeFeatured')) {
            $nbr = Tools::getValue('TTHOMEFEATURED_NBR');
            if (!Validate::isInt($nbr) || $nbr <= 0) {
                $errors[] = $this->getTranslator()->trans('The number of products is invalid. Please enter a positive number.', array(), 'Modules.TTfeaturedproducts.Admin');
            }

            $cat = Tools::getValue('TTHOMEFEATURED_CAT');
            if (!Validate::isInt($cat) || $cat <= 0) {
                $errors[] = $this->getTranslator()->trans('The category ID is invalid. Please choose an existing category ID.', array(), 'Modules.TTfeaturedproducts.Admin');
            }

            $rand = Tools::getValue('HOME_FEATURED_RANDOMIZE');
            if (!Validate::isBool($rand)) {
                $errors[] = $this->getTranslator()->trans('Invalid value for the "randomize" flag.', array(), 'Modules.TTfeaturedproducts.Admin');
            }
            if (isset($errors) && count($errors)) {
                $output = $this->displayError(implode('<br />', $errors));
            } else {
                Configuration::updateValue('TTHOMEFEATURED_NBR', (int) $nbr);
                Configuration::updateValue('TTHOMEFEATURED_CAT', (int) $cat);
                Configuration::updateValue('HOME_FEATURED_RANDOMIZE', (bool) $rand);
                Tools::clearCache(Context::getContext()->smarty, 'module:ttfeaturedproducts/ttfeaturedproducts.tpl');
                $output = $this->displayConfirmation($this->getTranslator()->trans('Settings updated.', array(), 'Admin.Notifications.Success'));
            }
        }

        return $output.$this->renderForm();
    }

    public function getProducts()
    {
        $category = new Category((int) Configuration::get('TTHOMEFEATURED_CAT'));

        $searchProvider = new CategoryProductSearchProvider(
            $this->context->getTranslator(),
            $category
        );

        $context = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $nProducts = Configuration::get('TTHOMEFEATURED_NBR');
        if ($nProducts < 0) {
            $nProducts = 12;
        }

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        if (Configuration::get('HOME_FEATURED_RANDOMIZE')) {
            $query->setSortOrder(SortOrder::random());
        } else {
            $query->setSortOrder(new SortOrder('product', 'position', 'asc'));
        }

        $result = $searchProvider->runQuery(
            $context,
            $query
        );

        $assembler = new ProductAssembler($this->context);

        $presenterFactory = new ProductPresenterFactory($this->context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $this->context->link
            ),
            $this->context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $this->context->getTranslator()
        );

        $products_for_template = array();

        foreach ($result->getProducts() as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $this->context->language
            );
        }

        return $products_for_template;
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionAdminGroupsControllerSaveAfter($params)
    {
        $this->_clearCache('*');
    }

    public function _clearCache($template, $cache_id = null, $compile_id = null)
    {
        parent::_clearCache('ttfeaturedproducts.tpl', 'ttfeaturedproducts');
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs',
                ),
                'description' => $this->getTranslator()->trans('To add products to your homepage, simply add them to the corresponding product category (default: "Home").', array(), 'Modules.TTfeaturedproducts.Admin'),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Number of products to be displayed', array(), 'Modules.TTfeaturedproducts.Admin'),
                        'name' => 'TTHOMEFEATURED_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->getTranslator()->trans('Set the number of products that you would like to display on homepage (default: 8).', array(), 'Modules.TTfeaturedproducts.Admin'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans('Category from which to pick products to be displayed', array(), 'Modules.TTfeaturedproducts.Admin'),
                        'name' => 'TTHOMEFEATURED_CAT',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->getTranslator()->trans('Choose the category ID of the products that you would like to display on homepage (default: 2 for "Home").', array(), 'Modules.TTfeaturedproducts.Admin'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans('Randomly display featured products', array(), 'Modules.TTfeaturedproducts.Admin'),
                        'name' => 'HOME_FEATURED_RANDOMIZE',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->getTranslator()->trans('Enable if you wish the products to be displayed randomly (default: no).', array(), 'Modules.TTfeaturedproducts.Admin'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->getTranslator()->trans('Yes', array(), 'Admin.Global'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->getTranslator()->trans('No', array(), 'Admin.Global'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans('Save', array(), 'Admin.Actions'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->id = (int) Tools::getValue('id_carrier');
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitHomeFeatured';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'TTHOMEFEATURED_NBR' => Tools::getValue('TTHOMEFEATURED_NBR', (int) Configuration::get('TTHOMEFEATURED_NBR')),
            'TTHOMEFEATURED_CAT' => Tools::getValue('TTHOMEFEATURED_CAT', (int) Configuration::get('TTHOMEFEATURED_CAT')),
            'HOME_FEATURED_RANDOMIZE' => Tools::getValue('HOME_FEATURED_RANDOMIZE', (bool) Configuration::get('HOME_FEATURED_RANDOMIZE')),
        );
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return array(
            'products' => $this->getProducts(),
            'allProductsLink' => Context::getContext()->link->getCategoryLink($this->getConfigFieldsValues()['TTHOMEFEATURED_CAT']),
        );
    }
    public function renderWidget($hookName, array $configuration)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));

        return $this->display(
            __FILE__,
            'views/templates/hook/ttfeaturedproducts.tpl',
            $this->getCacheId('ttfeaturedproducts')
        );
    }
}
