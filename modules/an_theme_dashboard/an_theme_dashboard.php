<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
//
class an_theme_dashboard extends Module
{
    const PREFIX = "an_td_";
	
    public function __construct()
    {
        $this->name = 'an_theme_dashboard';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'anvanto';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = '';

        parent::__construct();

        $this->displayName = $this->l('Anvanto Theme Dashboard');
        $this->description = $this->l('Anvanto Theme Dashboard');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		
		$this->dataDir = _PS_MODULE_DIR_.'an_theme_dashboard/data/';
		$this->modules = $this->loadConfigFileIfExists('modules');
		$this->recommended = $this->loadConfigFileIfExists('recommended');
    }
	
    protected function loadConfigFileIfExists($sourceName)
    {
        if (Tools::file_exists_no_cache(_PS_MODULE_DIR_.'an_theme_dashboard/'.$sourceName.'.php')) {
            return include(_PS_MODULE_DIR_.'an_theme_dashboard/'.$sourceName.'.php');
        }

        return array();
    }	

    public function install()
    {
		//////
		$languages = Language::getLanguages();
		
        $new_tab = new Tab();
        $new_tab->class_name = 'AdminAn_theme_dashboard';
        $new_tab->id_parent = Tab::getIdFromClassName('IMPROVE');
        $new_tab->module = $this->name;
        $new_tab->active = 1;
		$new_tab->icon = 'dashboard';
        foreach ($languages as $language) {
            $new_tab->name[$language['id_lang']] = 'AN Theme Dashboard';
        }
        $new_tab->add();
		///	
		
		parent::install();
		
		$this->registerHook('backOfficeHeader');

        return true;
    }

    public function uninstall()
    {
        $idTab = Tab::getIdFromClassName('AdminAn_theme_dashboard');
        $deletion_tab = true;

        if ($idTab) {
            $tab = new Tab($idTab);
            $deletion_tab = $tab->delete();
        }

        return parent::uninstall();
    }
	
    public function hookBackOfficeHeader($params)
    {
        if (Tools::getValue('controller') == 'AdminAn_theme_dashboard') {
			$this->context->controller->addJquery();
			$this->context->controller->addJS($this->_path.'views/js/jquery.magnific-popup.min.js');
			$this->context->controller->addJS($this->_path.'views/js/back.js');
			$this->context->controller->addCSS($this->_path.'views/css/back.css');
		}
    }
}
