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

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class an_hurry_up extends Module implements WidgetInterface
{
    const PREFIX = 'an_hurry_up_';

    public function __construct()
    {
        $this->name = 'an_hurry_up';
        $this->tab = 'others';
        $this->version = '1.0.5';
        $this->author = 'Anvanto';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = '53c8fa5127d0818abf58e6a11d28ce94';
		$this->addons_product_id = '47293';
        $this->module_root_path = _PS_MODULE_DIR_.$this->name;
        $this->configuration_source = $this->module_root_path.'/configuration.json';

        parent::__construct();

        $this->displayName = $this->l('AN Hurry up');
        $this->description = $this->l('Encourage customers to make a purchase faster — notify them ofthe product quantity left in stock with a hurry-up bar on the product page.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');
    }

    /**
     * @return bool
     */
    public function install()
    {
		$defaulTitlesLeft['en'] = 'Hurry up! Only';
		$defaulTitlesLeft['fr'] = 'Hurry up! Only';
		$defaulTitlesLeft['es'] = 'Hurry up! Only';
		$defaulTitlesLeft['pl'] = 'Pośpiesz się! Tylko';
		$defaulTitlesLeft['it'] = 'Hurry up! Only';
		$defaulTitlesLeft['nl'] = 'Hurry up! Only';
		$defaulTitlesLeft['de'] = 'Hurry up! Only';
		
		$defaulTitlesRight['en'] = 'item(s) left in Stock!';
		$defaulTitlesRight['fr'] = 'item(s) left in Stock!';
		$defaulTitlesRight['es'] = 'item(s) left in Stock!';
		$defaulTitlesRight['pl'] = 'szt. w magazynie!';
		$defaulTitlesRight['it'] = 'item(s) left in Stock!';
		$defaulTitlesRight['nl'] = 'item(s) left in Stock!';
		$defaulTitlesRight['de'] = 'item(s) left in Stock!';
		
		$defaulTitlesNoitems['en'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['fr'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['es'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['pl'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['it'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['nl'] = 'Sorry, no items left.';
		$defaulTitlesNoitems['de'] = 'Sorry, no items left.';		
				
		$languages = Language::getLanguages(false);
		$title_left = [];
		$title_right = [];
		$title_noitems = [];
        foreach ($languages as $lang) {
			if (isset($defaulTitlesLeft[$lang['iso_code']])){
				$title_left[$lang['id_lang']] = $defaulTitlesLeft[$lang['iso_code']];
				$title_right[$lang['id_lang']] = $defaulTitlesRight[$lang['iso_code']];
				$title_noitems[$lang['id_lang']] = $defaulTitlesNoitems[$lang['iso_code']];
			} else {
				$title_left[$lang['id_lang']] = $defaulTitlesLeft['en'];
				$title_right[$lang['id_lang']] = $defaulTitlesRight['en'];
				$title_noitems[$lang['id_lang']] = $defaulTitlesNoitems['en'];
			}

		}
		Configuration::updateValue(self::PREFIX.'title_left', $title_left);	
		Configuration::updateValue(self::PREFIX.'title_right', $title_right);	
		Configuration::updateValue(self::PREFIX.'title_noitems', $title_noitems);	

        Configuration::updateValue(self::PREFIX.'stockProgressBarMaxValue', 1200);
        Configuration::updateValue(self::PREFIX.'stockProgressBarColor', '');
        Configuration::updateValue(self::PREFIX.'show_line', 1);

        $this->importConfiguration();
    
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()
            || !$this->registerHook('displayProductAdditionalInfo')
            || !$this->registerHook('header')
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall();
    }
    
    
    protected function importConfiguration($configuration_source = null)
    {
        $configuration_source = $configuration_source === null ? $this->configuration_source : $configuration_source;
        $config = Tools::jsonDecode(Tools::file_get_contents($configuration_source), 1);
        
        $paramList = $this->getParamList();
        foreach ($paramList as $key => $name) {
            if (isset($config[$name])) {
                $this->updateParam($name, $config[$name]);
            }
        }
        return true;
    }
    
    protected function exportConfiguration($configuration_source = null)
    {
        $configuration_source = $configuration_source === null ? $this->configuration_source : $configuration_source;

        $data = array();
        $paramList = $this->getParamList();
        foreach ($paramList as $param => $name) {
            $data[$name] = $this->getParam($name);
        }

        return @file_put_contents($configuration_source, Tools::jsonEncode($data));
    }
    
    /**
     * @param $key
     * @param null $value
     * @param null $id_lang
     * @return bool|string
     */
    public static function getParam($key, $value = null, $id_lang = null)
    {
        return $value === null ? Configuration::get(
            self::PREFIX . $key,
            $id_lang
        ) : Configuration::updateValue(self::PREFIX . $key, $value);
    }

    public function getParamList()
    {
        return array(
            'stockProgressBarColor',
        );
    }

    protected function deleteParams($keys)
    {
        foreach ($keys as $key) {
            $this->deleteParam($key);
        }
    }

    protected function deleteParam($key)
    {
        return Configuration::deleteByName(self::PREFIX.$key);
    }

    protected function updateParam($key, $value)
    {
        return Configuration::updateValue(self::PREFIX.$key, $value);
    }

    public function getConfig()
    {
		$config = [];
		$form = $this->getConfigForm();
		foreach ($form['input'] as $input){
			$key = Tools::str_replace_once(self::PREFIX, '', $input['name']);
			if (isset($input['lang']) && $input['lang']){
				$config[$key] = Configuration::get($input['name'], $this->context->language->id);
			} else {
				$config[$key] = Configuration::get($input['name']);
			}
		}       
		
        return $config;
    }

    /**
     *
     */
    public function hookHeader()
    {
        $this->context->controller->addJquery();

        $this->context->controller->registerStylesheet(
            "anhurryupss",
            'modules/' . $this->name . '/views/css/front.css',
            array('server' => 'local', 'priority' => 150)
        );

        $this->context->controller->registerJavascript(
            "anhurryupjs",
            'modules/' . $this->name . '/views/js/front.js',
            array('server' => 'local', 'priority' => 150)
        );
    }

    /**
     * @param $hookName
     * @param array $params
     * @return mixed|void
     */
    public function renderWidget($hookName, array $params)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        return $this->fetch('module:an_hurry_up/views/templates/front/hurryup.tpl');
    }
	 
    /**
     * @param $hookName
     * @param array $params
     * @return array
     */
    public function getWidgetVariables($hookName, array $params)
    {
        $return = array();
        $return['config'] = $this->getConfig();
        return $return;
    }

   public function getContent()
   {
		if (Tools::isSubmit('submit'.$this->name)) {
			$this->postProcess();
		}
		
		$this->context->smarty->assign('theme', $this->getThemeInfo());
		
        return $this->display(__FILE__, 'views/templates/admin/suggestions.tpl') . $this->displayForm();
    }
    
    protected function postProcess()
    {
		$languages = Language::getLanguages(false);
		
		$form = $this->getConfigForm();
		
		foreach ($form['input'] as $input){
			
			$html = false;
			if (isset($input['html']) && $input['html']){
				$html = true;
			}
			
			if (isset($input['lang']) && $input['lang']){
				$value = [];
				foreach ($languages as $lang) {
					$value[$lang['id_lang']] = Tools::getValue($input['name'].'_' . $lang['id_lang']);
					
					if ($html){
						$value[$lang['id_lang']] = htmlentities($value[$lang['id_lang']]);
					}
				}

				Configuration::updateValue($input['name'], $value, $html);
			} else {
				Configuration::updateValue($input['name'], Tools::getValue($input['name']), $html);
			}
		} 
		
		$this->exportConfiguration();

		Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules') . '&conf=4&configure='.$this->name);
		
        return $this->context->controller->confirmations[] = $this->l('The settings have been updated.');
    }


    public function displayForm()
    {

        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fields_form = [];

        $fields_form[0]['form'] = $this->getConfigForm();
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ],
        );
		
		$helper->languages = $this->context->controller->getLanguages();
		$helper->id_language = $this->context->language->id;
			
		
		$languages = Language::getLanguages(false);
		foreach ($fields_form[0]['form']['input'] as $input){
			
			$html = false;
			if (isset($input['html']) && $input['html']){
				$html = true;
			}
			
			if (isset($input['lang']) && $input['lang']){
				foreach ($languages as $lang) {
					
					$value = Configuration::get($input['name'], $lang['id_lang']);
					
					if ($html){
						$value = html_entity_decode($value);
					}
					
					$helper->fields_value[$input['name']][$lang['id_lang']] = $value;
				}
			} else {
				$helper->fields_value[$input['name']] = Configuration::get($input['name']);
			}
		}


        return $helper->generateForm($fields_form);
    }
	
    protected function getConfigForm()
    {
		$form = [
            'legend' => [
                'title' => $this->l('Settings'),
            ],
            'input' => [
           
				array(
                    'type' => 'number',
                    'label' => 'Stock progress bar max value',
                    'name' => self::PREFIX.'stockProgressBarMaxValue',
                    'values' => '20',
                    'col' => '2',
                    'min' => 1,
                ),
               array(
                    'type' => 'color',
                    'label' => 'Background',
                    'name' => self::PREFIX.'stockProgressBarColor',
                    'values' => '#ffc427',
                ),
                array(
                    'type' => 'switch',
                    'required' => false,
                    'label' => $this->l('Show line'),
                    'name' => self::PREFIX.'show_line',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'is_enabled_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'is_enabled_off',
                            'value' => 0
                        )
                    ),
                ),	

				[
					'type' => 'text',
					'lang' => true,
					'label' => $this->l('Title left'),
					'name' => self::PREFIX.'title_left',
				],

				[
					'type' => 'text',
					'lang' => true,
					'label' => $this->l('Title right'),
					'name' => self::PREFIX.'title_right',
				],				
 
				[
					'type' => 'text',
					'lang' => true,
					'label' => $this->l('Title, when no items left'),
					'name' => self::PREFIX.'title_noitems',
				],
				
            ],
            'submit' => [
                'title' => $this->l('Save'),
            ]
        ];
		
		return $form;
		
	}






 
 
 
 
    protected function isReceivedParamsValid($params)
    {
        return $this->isColor($params['stockProgressBarColor']);
    }

    protected function isColor($string, $empty = true)
    {
        if (!$empty or $string != '') {
            $string = htmlspecialchars($string);
            $customvalarray = str_split('#0123456789aAbBcCdDeEfF');
            foreach (str_split($string) as $val) {
                if (!in_array($val, $customvalarray)) {
                    return false;
                }
            }
        }
        return true;
    }
	
	
	public function getThemeInfo()
	{
		$theme = [];
		$themeFileJson = _PS_THEME_DIR_.'/config/theme.json';
		if (Tools::file_exists_no_cache($themeFileJson)) {
			$theme = (array)Tools::jsonDecode(Tools::file_get_contents($themeFileJson), 1);			
		}

		if (!isset($theme['url_contact_us']) || $theme['url_contact_us'] == ''){
			
			$urlContactUs = 'https://addons.prestashop.com/contact-form.php';

			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$urlContactUs .= '?id_product=' .$theme['addons_id'];
			} elseif (isset($this->url_contact_us) && $this->url_contact_us != ''){
				$urlContactUs = $this->url_contact_us;
			} elseif (isset($this->addons_product_id) && $this->addons_product_id != ''){
				$urlContactUs .= '?id_product=' .$this->addons_product_id;
			}
			
			$theme['url_contact_us'] = $urlContactUs;
		}
		
		if (!isset($theme['url_rate']) || $theme['url_rate'] == ''){
			
			$urlRate = 'https://addons.prestashop.com/ratings.php';

			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$urlRate .= '?id_product=' .$theme['addons_id'];
			} elseif (isset($this->url_rate) && $this->url_rate != ''){
				$urlRate = $this->url_rate;
			} elseif (isset($this->addons_product_id) && $this->addons_product_id != ''){
				$urlRate .= '?id_product=' .$this->addons_product_id;
			}
			
			$theme['url_rate'] = $urlRate;
		}		
		
		return $theme;
	}


/*     
	public function getContent()
    {
        $output = null;

        if (Tools::isSubmit('submit'.$this->name)) {
            $output = $this->getSubmitOutput();
        }

        return $output.$this->displayForm();
    }

    protected function getSubmitOutput()
    {
        $params = array();
        $paramList = $this->getParamList();

        foreach ($paramList as $key) {
            $params[$key] = Tools::getValue(self::PREFIX.$key);
        }

        if (!$this->isReceivedParamsValid($params)) {
            return $this->displayError($this->l('Invalid Configuration value'));
        }
 
        foreach ($paramList as $key) {
            $this->updateParam($key, $params[$key]);
        }
        
        $this->exportConfiguration();

        return $this->displayConfirmation($this->l('Settings updated'));
    } */

	
	
	
/* 
    public function hookDisplayProductAdditionalInfo($params)
    {
        $isset = $this->new ? isset($params['product']['id_product']) : isset($params['product']->id);
        if ($isset) {
            if (is_array($params['product']) && isset($params['product']['id_product_attribute'])) {
                $an_hu_ipa = $params['product']['id_product_attribute'];
            } elseif (isset($params['product']->id_product_attribute)) {
                $an_hu_ipa = $params['product']->id_product_attribute;
            } else {
                $an_hu_ipa = null;
            }
        }
        $this->context->smarty->assign(array(
            'an_hu_ipa' => $an_hu_ipa
        ));
        $display = '';
        $display .= $this->fetch('module:an_hurry_up/views/templates/front/hurryup_ipa.tpl');
        if ($this->getParam('productAdditionalInfo')) {
            $display .= $this->renderWidget('displayProductAdditionalInfo', $params);
        }
        return $display;
    }
 */


/* 
    public function displayForm()
    {

        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $fields_form = array();

        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
               array(
                    'type' => 'number',
                    'label' => 'Stock progress bar max value',
                    'name' => self::PREFIX.'stockProgressBarMaxValue',
                    'values' => '20',
                    'col' => '2',
                    'min' => 1,
                ),
               array(
                    'type' => 'color',
                    'label' => 'Background',
                    'name' => self::PREFIX.'stockProgressBarColor',
                    'values' => '#ffc427',
                ),
                array(
                    'type' => 'switch',
                    'required' => false,
                    'label' => $this->l('Enable display in hook ProductAdditionalInfo'),
                    'name' => self::PREFIX.'productAdditionalInfo',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'is_enabled_on',
                            'value' => 1
                        ),
                        array(
                            'id' => 'is_enabled_off',
                            'value' => 0
                        )
                    ),
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                        '&token='.Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $paramList = $this->getParamList();

        foreach ($paramList as $key) {
            $helper->fields_value[self::PREFIX.$key] = $this->getParam($key);
        }

        return $helper->generateForm($fields_form);
    }
 */
	
}
