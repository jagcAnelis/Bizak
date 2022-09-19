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

class AdminAn_theme_dashboardController extends ModuleAdminController {

    public function __construct()
    {
		$this->bootstrap = true;
		
		parent::__construct();
		
		$theme = array();
		$themeFileJson = _PS_THEME_DIR_.'/config/theme.json';
		if (Tools::file_exists_no_cache($themeFileJson)) {
			$theme = (array)Tools::jsonDecode(Tools::file_get_contents($themeFileJson), 1);			
		}

		if (!isset($theme['url_contact_us']) || $theme['url_contact_us'] == ''){
			$theme['url_contact_us'] = 'https://addons.prestashop.com/contact-form.php';
			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$theme['url_contact_us'] = $theme['url_contact_us']. '?id_product=' .$theme['addons_id'];
			}
		}
		if (!isset($theme['url_rate']) || $theme['url_rate'] == ''){
			$theme['url_rate'] = 'https://addons.prestashop.com/en/ratings.php';
			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$theme['url_rate'] = $theme['url_rate'].'?id_product='.$theme['addons_id'];
			}
		}
		
		//	DOC in the module
		$an_dashboard = [];
		$an_dashboard['translations_faq'] = $this->searchDoc('an_theme_dashboard', 'translations_faq.pdf');
				
		$an_dashboard['theme_doc'] = $this->searchDocTheme('theme_configuration.pdf');
		
		
		$this->context->smarty->assign('an_dashboard', $an_dashboard);
		
		
		
		foreach ($this->module->modules as $name => $item){
			if (Module::isInstalled($name)){
				//	Logo
				$this->module->modules[$name]['logo'] = $this->searchLogo($name);
				
				//	URL
				if (!isset($this->module->modules[$name]['url']) || $this->module->modules[$name]['url'] == ''){
					if (isset($this->module->modules[$name]['id']) && $this->module->modules[$name]['id'] != ''){
						$this->module->modules[$name]['url'] = 'https://addons.prestashop.com/en/product.php?id_product='.$this->module->modules[$name]['id'];
					}
				}

				//	Configure
				if (!isset($this->module->modules[$name]['configure']) || $this->module->modules[$name]['configure']){
					$this->module->modules[$name]['configure'] = $this->context->link->getAdminLink('AdminModules').'&configure='.$name;
				}
				
				//	Doc
				$this->module->modules[$name]['doc'] = $this->searchDoc($name);
				
				$this->module->modules[$name]['enabled'] = Module::isEnabled($name);
			} else {
				unset($this->module->modules[$name]);
			}
		}

		$this->context->smarty->assign('theme', $theme);
		$this->context->smarty->assign('modules', $this->module->modules);
		$this->context->smarty->assign('recommended', $this->module->recommended);
		
		$this->context->smarty->assign('imgPath', _MODULE_DIR_.'an_theme_dashboard/views/img/');
	//	$this->template = 'info.tpl';

    }	
	
	public function renderList(){	
		return $this->context->smarty->fetch(_PS_MODULE_DIR_ . '/' . $this->module->name . '/views/templates/admin/info.tpl');
	}
	
	protected function searchLogo($moduleName){
        if (Tools::file_exists_no_cache(_PS_MODULE_DIR_.$moduleName.'/logo.png')) {
            return _MODULE_DIR_.$moduleName.'/logo.png';
        }
		
		return '';
	}
	
	protected function searchDoc($moduleName, $fileName = 'readme_en.pdf'){
        if (Tools::file_exists_no_cache(_PS_MODULE_DIR_.$moduleName.'/doc/'.$fileName)) {
            return _MODULE_DIR_.$moduleName.'/doc/'.$fileName;
        }
		
		return '';
	}	

	protected function searchDocTheme($fileName = 'readme_en.pdf'){
        if (Tools::file_exists_no_cache(_PS_THEME_DIR_.'/doc/'.$fileName)) {
            return _THEME_DIR_.'doc/'.$fileName;
        }
		
		return '';
	}
}
