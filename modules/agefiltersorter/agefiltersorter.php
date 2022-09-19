<?php
/**
* 2007-2020 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_ . 'agefiltersorter' . DIRECTORY_SEPARATOR . 'AgeFilterSorterImpl.php';


class Agefiltersorter extends Module {
    protected $config_form = false;


    public function __construct() {
        $this->name = 'agefiltersorter';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Just Digital';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Age filter sorter');
        $this->description = $this->l('Sorts age filter by age');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install() {
        return parent::install() &&
           $this->registerHook('actionDispatcher');
    }

    public function uninstall() {
        return parent::uninstall();
    }


    public function hookActionDispatcher() {
        $this->context->smarty->registerPlugin('modifier', 'sortAgeFiltersByAge', array('AgeFilterSorterImpl', 'sortAgeFiltersByAge'));
        $this->context->smarty->registerPlugin('modifier', 'sortAgeCategoryByAge', array('AgeFilterSorterImpl', 'sortAgeCategoryByAge'));
    }
}
