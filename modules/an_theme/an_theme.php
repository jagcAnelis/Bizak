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
 *  @copyright  2020 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once _PS_MODULE_DIR_.'an_theme/classes/InputFactory.php';
require_once _PS_MODULE_DIR_.'an_theme/classes/Validation.php';

class an_theme extends Module
{
    /**
     * Module prefix
     */
    const ERROR_DEFAULT = -1;

    const PREFIX = 'ant_';
    const SALT = 'css_file';

    const CSS_FILE = 'CSS_FILE';
    const CUSTOM_CSS_FILE = 'CUSTOM_CSS_FILE';
    const CUSTOM_JS_FILE = 'CUSTOM_JS_FILE';
    const CONFIGURATION_FILE = 'configuration';
    const CONFIGURATION_FORMAT = 'json';
//    const RESET_SYSTEM = 'RESET_SYSTEM';
    const REGENERATE_CSS = 'REGENERATE_CSS';
    const EXPORT_CONFIGURATION = 'EXPORT_CONFIGURATION';

    const DEFAULT_JS_POSITION = 'bottom';
    const DEFAULT_JS_PRIORITY = 200;
    const DEFAULT_JS_SERVER = 'local';

    const DEFAULT_CSS_MEDIA = 'all';
    const DEFAULT_CSS_PRIORITY = 200;
    const DEFAULT_CSS_SERVER = 'local';

    const DELETE_IMAGE = 'delete_image';

    protected $_hooks = array();
    protected $_theme_vars = array();
    protected $_theme_colors = array();
    protected $_fields = array();
    protected $_theme_fonts = array();
    protected $errors = array();
    protected $inputFactory = null;
    protected $validationMessages = array();
    protected $loadedConfiguration = false;
	
	protected $varsPreset = array('importPresets_presetName', 'importPresets_presetId');

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->name = 'an_theme';
        $this->tab = 'front_office_features';
        $this->version = '2.3.1';
        $this->author = 'anvanto';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('AN Theme Configurator');
        $this->description = $this->l('AN Theme Configurator');
        $this->bootstrap = true;

        $this->setupValidationMessages();
		
        if (Shop::isFeatureActive()) {
            $this->id_shop = Context::getContext()->shop->id;
        } else {
            $this->id_shop = 1;
        }		
    }

    protected function setupValidationMessages()
    {
        $this->validationMessages = array(
            self::ERROR_DEFAULT => $this->l('An error occurred'),
            AnThemeValidation::ERROR_EMPTY_VALUE => $this->l('Invalid `%s`. Can not be empty!'),
            AnThemeValidation::ERROR_INVALID_NUMBER => $this->l('Invalid `%s`. Should be numeric!'),
            AnThemeValidation::ERROR_INVALID_FLOAT => $this->l('Invalid `%s`. Should be numeric (float)!'),
            AnThemeValidation::ERROR_INVALID_COLOR => $this->l('Invalid `%s`. Should be valid HEX color!')
        );

        return $this;
    }

    public function getValidationErrorByCode($code)
    {
        if (array_key_exists($code, $this->validationMessages)) {
            return $this->validationMessages[$code];
        }

        return $this->validationMessages[self::ERROR_DEFAULT];
    }

    protected function setupConfiguration()
    {
        if ($this->loadedConfiguration) {
            return $this;
        }
        
        $this->loadedConfiguration = true;

        return $this->loadThemeFonts()
            ->loadThemeJS()
            ->loadThemeCSS()
            ->loadHookList()
            ->loadThemeVars()
            ->loadThemeColors()
            ->loadTabbedFields()
            ->loadFields();
    }

    protected function loadConfigFileIfExists($sourceName)
    {
        if (Tools::file_exists_no_cache(_PS_MODULE_DIR_.'an_theme/cfg/'.$sourceName.'.php')) {
            return include(_PS_MODULE_DIR_.'an_theme/cfg/'.$sourceName.'.php');
        }

        return array();
    }

    protected function loadThemeFonts()
    {
        $this->_theme_fonts = $this->loadConfigFileIfExists('theme_fonts');
        return $this;
    }

    protected function loadThemeJS()
    {
        $this->_theme_js = $this->loadConfigFileIfExists('theme_js');
        return $this;
    }

    protected function loadThemeCSS()
    {
        $this->_theme_css = $this->loadConfigFileIfExists('theme_css');
        return $this;
    }

    protected function loadHookList()
    {
        $this->_hooks = $this->loadConfigFileIfExists('hooks');
        return $this;
    }
    
    protected function loadThemeVars()
    {
        $this->_theme_vars = $this->loadConfigFileIfExists('vars');
        return $this;
    }    

    protected function loadThemeColors()
    {
        $this->_theme_colors = $this->loadConfigFileIfExists('theme_colors');
        return $this;
    }

    protected function loadTabbedFields()
    {
        $this->_tabbed_fields = $this->loadConfigFileIfExists('fields');
        return $this;
    }

    protected function loadFields()
    {
        $fields = array_map(function ($tab) {
            return $tab['fields'];
        }, $this->_tabbed_fields);
        $this->_fields = call_user_func_array('array_merge', $fields);

        return $this;
    }

    protected function importConfiguration($configurationSource = '', $defaultPreset = '')
    {
        if ($configurationSource == ''){
			$configurationSource = $this->local_path.self::CONFIGURATION_FILE.'.'.self::CONFIGURATION_FORMAT;
		}

        if (Tools::file_exists_no_cache($configurationSource)) {
            $accepted = $this->getExportConfigurationKeys();
            $configurationJson = Tools::file_get_contents($configurationSource);
            $configuration = (array)Tools::jsonDecode($configurationJson, 1);
            $shops = Shop::getShops();
			foreach ($shops as $shop){
                foreach ($configuration as $key => $item) {
						
                    if (in_array($key, $accepted)) {
                        if($item){
                            $this->getParam($key, $item, null, $shop['id_shop']);
                        }
                    }
                }
				if ($defaultPreset != ''){
					$this->getParam('importPresets_preset', $defaultPreset, null, $shop['id_shop']);
				}
            }	
			return true;
        } else {
			return false;
		}
    }

    protected function exportConfiguration($configurationSource = '')
    {
        if ($configurationSource == ''){
			$configurationSource = $this->local_path.self::CONFIGURATION_FILE.'.'.self::CONFIGURATION_FORMAT;
		}
        $configuration = $this->getConfigFormValuesForExport();
        $configurationJson = Tools::jsonEncode($configuration);
        if (@file_put_contents($configurationSource, $configurationJson)){
            $this->context->controller->confirmations[] = 'Successful export';
            return true;
        }
        return false;
    }

    protected function getConfigFormValuesForExport()
    {
        $unfilteredConfig = $this->getConfigFormValues();
        $filteredConfig = array();

        $acceptedConfigKeys = $this->getExportConfigurationKeys();

        foreach (array_keys($unfilteredConfig) as $key) {
            if (in_array($key, $acceptedConfigKeys)) {
                $filteredConfig[$key] = $unfilteredConfig[$key];
            }
        }

        return $filteredConfig;
    }

    protected function getConfigFormValues()
    {
        $config = array();
        $keys = $this->getConfigKeys();
        $lang = $this->getLangKeys();

        foreach ($keys as $key) {
            if (in_array($key, $lang)) {
                foreach (Language::getLanguages(1, 0, 1) as $id_lang) {
                    $config[$key][$id_lang] = $this->getParamValue($key, $id_lang, $this->context->shop->id);
                }
            } else {
                $config[$key] = $this->getParamValue($key, $this->context->language->id, $this->context->shop->id);
            }
        }

        return $config;
    }

    protected function killFile($file)
    {
        @unlink(_PS_MODULE_DIR_.$this->name.'/views/' .  Tools::substr($file, strpos($file, '.') + 1) .'/'.$file);
        return $file;
    }

    protected static function getCSSFile()
    {
        return Configuration::get(self::CSS_FILE,null, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id);
    }

    protected static function updateFile($key,$file)
    {
        return Configuration::updateValue($key, $file, false, Context::getContext()->shop->id_shop_group, Context::getContext()->shop->id );
    }

    protected static function generateFileName($name,$format)
    {
        return ($name !== false ? md5($name.self::SALT.Context::getContext()->shop->id_shop_group.Context::getContext()->shop->id ) : md5("theme")) . '.' . $format;
    }

    public function hookActionObjectLanguageAddAfter($params = null)
    {
        $this->setupConfiguration();
        $lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = array_map('intval', Language::getLanguages(0, 0, 1));

        if ($params !== null && $params['object'] instanceof Language) {
            $languages[] = (int)$params['object']->id;
        }

        foreach ($this->getLangKeys() as $key) {
            $values[$lang_default] = $this->getParamValue($key, $lang_default);
            
            foreach ($languages as $id_lang) {
                $value = $this->getParamValue($key, $id_lang);

                if ($value === false || empty($value)) {
                    $values[$id_lang] = $values[$lang_default];
                }
            }

            $this->updateParamValue($key, $values);
        }

        return true;
    }

    public function getImage($image)
    {
        $image = $this->getParamValue($image);
        return empty($image) ? false : _MODULE_DIR_.'an_theme/views/img/'.$image;
    }

    /**
     * Module installation
     *
     * @return bool
     */
    public function install()
    {
        $this->setupConfiguration();
        $this->setDefaultValues();
		
		$languages = Language::getLanguages();
/* 		
        $new_tab = new Tab();
        $new_tab->class_name = 'AdminAn_theme';
        $new_tab->id_parent = Tab::getIdFromClassName('IMPROVE');
        $new_tab->module = $this->name;
        $new_tab->active = 1;
		$new_tab->icon = 'style';
        foreach ($languages as $language) {
            $new_tab->name[$language['id_lang']] = 'AN Theme Configurator';
        }
        $new_tab->add();		
 */
        $install = parent::install()
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('actionObjectLanguageAddAfter');

        if ($install) {
            foreach ($this->_hooks as $hook) {
                $this->registerHook($hook);
            }
        }
		
        return $install
            && $this->hookActionObjectLanguageAddAfter()
            && $this->importConfigurationInstall()
			&& $this->updateAnThemeBlocks()
            && $this->updateModulesHooks()
            && $this->generateFiles();
    }
	
	protected function arrayKeyFirst(array $arr) {
		foreach($arr as $key => $unused) {
			return $key;
		}
		return NULL;
	}
    
	protected function importConfigurationInstall(){
		
		$presets = $this->loadPresets();
		$value = $this->arrayKeyFirst($presets);

		if (isset($presets[$value]['folder'])){
			return $this->importConfiguration($presets[$value]['folder'].'/configuration.json', $value);
		} else {
			return $this->importConfiguration();
		}
		
	}

    protected function getConfigKeys()
    {
        $keys = array();
	
        foreach ($this->_fields as $key => $value) {
            foreach (array_keys($value['options']) as $_key) {
                $keys[] = $key.'_'.$_key;
            }
        }
		
		$keys = array_merge($keys, $this->varsPreset);
        return $keys;
    }

    protected function getLangKeys()
    {
        return array_filter($this->getConfigKeys(), function ($key) {
            $buffer = explode('_', $key);
            $key = array_pop($buffer);

            foreach ($this->_fields as $value) {
                if (isset($value['options'][$key])) {
                    return isset($value['options'][$key]['lang']);
                }
            }

            return false;
        });
    }

    protected function getExportConfigurationKeys()
    {
        return $this->getConfigKeys();
    }

    /**
     * Module uninstallation
     *
     * @return bool
     */
    public function uninstall()
    {
/* 		
        $idTab = Tab::getIdFromClassName('AdminAn_ThemeController');
        $deletion_tab = true;

        if ($idTab) {
            $tab = new Tab($idTab);
            $deletion_tab = $tab->delete();
        }	
		 */
		return parent::uninstall();
    }

    public function hookBackOfficeHeader($params = null)
    {
        if (in_array($this->name, array(Tools::getValue('module_name'), Tools::getValue('configure')))) {
            $this->context->controller->addJquery();
            $this->context->controller->addCSS($this->_path.'/views/css/back.css');
            $this->context->controller->addJS($this->_path.'/views/js/back.js');
        }
    }

    /**
     * Add custom CSS to the header
     *
     * @return bool
     */
    public function hookDisplayHeader()
    {
        $this->setupConfiguration();
        $this->hookDisplayHeaderNew();
    }

    protected function hookDisplayHeaderNew()
    {
        foreach ($this->_fields as $field_key => $field) {
            foreach ($field['options'] as $option_key => $option) {
                $field_id = $field_key . '_' . $option_key;
                $value = $this->getParam($field_id, null, null, $this->context->shop->id);

                if (isset($option['type']) && $option['type'] == 'font') {
                    if (isset($this->_theme_fonts[$value]['link'])) {
                        $server = 'remote';
                        $link = $this->_theme_fonts[$value]['link'];
                    } else {
                        $server = 'local';
                        $link = 'modules/'.$this->name.'/views/fonts/'.$this->_theme_fonts[$value]['path'].'.css';
                    }

                    $this->context->controller->registerStylesheet(sha1('font-'.$value), $link, array('server' => $server));
                }
                
                if (isset($option['type']) && $option['type'] == 'fileAdd') {
                    if ($value){
                        foreach ($option['files'] as $jsCss){
                            if (isset($jsCss['type']) && $jsCss['type'] == 'css'){
                                $this->context->controller->registerStylesheet(sha1($jsCss['path']), $jsCss['server'] == 'remote' ? $jsCss['path'] : 'modules/'.$this->name.'/'.$jsCss['path'], $jsCss);
                            } else {
                                $this->context->controller->registerJavascript(sha1($jsCss['path']), $jsCss['server'] == 'remote' ? $jsCss['path'] : 'modules/'.$this->name.'/'.$jsCss['path'], $jsCss);
                            }
                        }
                    }
                }
                
                if (isset($option['type']) && $option['type'] == 'selectFileAdd') {
                    
                    if (isset($option['files'][$value])){
                        foreach ($option['files'][$value] as $jsCss){
                            if (isset($jsCss['type']) && $jsCss['type'] == 'css'){
                                $this->context->controller->registerStylesheet(sha1($jsCss['path']), $jsCss['server'] == 'remote' ? $jsCss['path'] : 'modules/'.$this->name.'/'.$jsCss['path'], $jsCss);
                            } else {
                                $this->context->controller->registerJavascript(sha1($jsCss['path']), $jsCss['server'] == 'remote' ? $jsCss['path'] : 'modules/'.$this->name.'/'.$jsCss['path'], $jsCss);
                            }
                        }
                    }
                }
                
                if ($option['source'] == 'exSelect'){
                    
                    if (isset($option['options'][$value]['css'])){
                        foreach ($option['options'][$value]['css'] as $fileCss){
                            $this->context->controller->registerStylesheet(sha1($fileCss['path']), $fileCss['server'] == 'remote' ? $fileCss['path'] : 'modules/'.$this->name.'/'.$fileCss['path'], $fileCss); 
                        }
                    }

                    if (isset($option['options'][$value]['js'])){
                        foreach ($option['options'][$value]['js'] as $fileJs){
                            $this->context->controller->registerJavascript(sha1($fileJs['path']), $fileJs['server'] == 'remote' ? $fileJs['path'] : 'modules/'.$this->name.'/'.$fileJs['path'], $fileJs);
                        }
                    }                    
                }
                
            }
            
        }
        
        $this->context->controller->registerStylesheet("modules-".$this->name."-fe2", "modules/{$this->name}/views/css/".$this->getCSSFile(), array('media' => 'all', 'priority' => 150));
        if(Configuration::get(self::CUSTOM_CSS_FILE)){
            $this->context->controller->registerStylesheet("modules-".$this->name."-custcss", "modules/{$this->name}/views/css/".Configuration::get(self::CUSTOM_CSS_FILE), array('media' => 'all', 'priority' => 9999));
        }
        if(Configuration::get(self::CUSTOM_JS_FILE)){
            $this->context->controller->registerJavascript("modules-".$this->name."-custjs", "modules/{$this->name}/views/js/".Configuration::get(self::CUSTOM_JS_FILE), array('media' => 'all', 'priority' => 200));
        }

        foreach ($this->_theme_js as $js) {
            $this->context->controller->registerJavascript(sha1($js['path']), $js['server'] == 'remote' ? $js['path'] : 'modules/'.$this->name.'/'.$js['path'], $js);
        }

        foreach ($this->_theme_css as $css) {
            $this->context->controller->registerStylesheet(sha1($css['path']), $css['server'] == 'remote' ? $css['path'] : 'modules/'.$this->name.'/'.$css['path'], $css);
        }
        
        foreach ($this->_theme_vars as $key => $val) {
            $this->context->smarty->assign($key, $val);
        }

    }

    protected function getParamValue($key, $id_lang = null, $id_shop = null)
    {
        $input = $this->getInputFactory()->create($key);
        $defaultValue = $input->getData('default', '');

        return Configuration::get(self::PREFIX.$key, $id_lang, null, $id_shop ? $id_shop : $this->context->shop->id, $defaultValue);
    }

    /**
     * Get/Set a parameter
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function getParam($key, $value = null, $id_lang = null, $id_shop = null)
    { 
        if ($value === null) {
            return $this->getParamValue($key, $id_lang, $id_shop);
        }

        try {
            $this->updateParamValue($key, $value, $id_shop);
        } catch (ReflectionException $e) {
            // $this->errors[] = $this->displayError($this->l('Invalid validator. Please contact with developer'));
        }
    }

    protected function updateParamValue($key, $value, $id_shop = null)
    {
        $input = $this->getInputFactory()->create($key);
        $ex_key = explode('_', $key);
        if (isset($this->_fields[$ex_key['0']]['options'][$ex_key['1']]['source'])){
            $source = $this->_fields[$ex_key['0']]['options'][$ex_key['1']]['source'];
        } else {
            $source = '';
        }
        
        if ($input->validateValue($value)) {
            if ($source == 'switch'){
                if ($value == '1'){
                    $value = true;
                } else {
                    $value = false;
                }
            }
            return Configuration::updateValue(self::PREFIX.$key, $value,null, null, $id_shop ? $id_shop : $this->context->shop->id);
        } else {
            $errors = $input->errors;
            foreach ($errors as &$error) {
                $error = $this->displayError($error);
            }
            $this->errors = array_merge($this->errors, $errors);
        }

        return false;
    }

    /**
     * Upload an image
     *
     * @param string $name
     * @return bool
     */
    protected function uploadImage($name)
    {
        $image = uniqid('image_').'.jpg';

        if (isset($_FILES[$name]['tmp_name']) && !empty($_FILES[$name]['tmp_name'])) {
            $tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
            if (!$tmp_name) {
                return false;
            }

            if (!move_uploaded_file($_FILES[$name]['tmp_name'], $tmp_name)) {
                return false;
            }

            if (!ImageManager::checkImageMemoryLimit($tmp_name)) {
                return false;
            }

            if (!ImageManager::resize($tmp_name, _PS_MODULE_DIR_ . $this->name . '/views/img/'.$image)) {
                return false;
            }

            if (count($this->errors)) {
                return false;
            }

            @unlink($tmp_name);
        } else {
            return false;
        }

        return $image;
    }
	
	protected function loadPresets()
	{	
		$presets = array();
		$presetsFolders = glob(_PS_MODULE_DIR_ . $this->name . '/import/*');
		foreach ($presetsFolders as $folderPath){
		
			if (Tools::file_exists_no_cache($folderPath . '/configuration.json')){
				
				$configurationJson = Tools::file_get_contents($folderPath . '/configuration.json');
				$configuration = (array)Tools::jsonDecode($configurationJson, 1);
				
				if (isset($configuration['importPresets_presetName'], $configuration['importPresets_presetId'])){
					$presets[$configuration['importPresets_presetId']]['name'] = $configuration['importPresets_presetName'];
					$presets[$configuration['importPresets_presetId']]['folder'] = $folderPath;
					
					if (Tools::file_exists_no_cache($folderPath . '/preview.jpg')){
						
						$exFolder = explode('/', $folderPath);
						$folder = array_pop($exFolder);

						$presets[$configuration['importPresets_presetId']]['preview'] = '../modules/'.$this->name.'/import/'.$folder.'/preview.jpg';
					} else {
						$presets[$configuration['importPresets_presetId']]['preview'] = '';
					}
				}
			}
		}	
		
		return $presets;
	}
	

    protected function importPreset(){
		
        $id_tab = (int)Tools::getValue('id_tab', 0);
		if (isset($this->_tabbed_fields[$id_tab]['legend']['id']) && $this->_tabbed_fields[$id_tab]['legend']['id'] == 'anthemeimport'){

			foreach ($this->_fields as $field_key => $field) {
				foreach ($field['options'] as $option_key => $option) {
					
					$field_id = $field_key . '_' . $option_key;
					$value = $this->getParam($field_id, null, null, $this->context->shop->id);
					
					if ($option['source'] == 'presets'){

						$presets = $this->loadPresets();
						if (isset($presets[$value]['folder'])){
							$this->importConfiguration($presets[$value]['folder'].'/configuration.json');
						}		
					}
					
				}
			}
		}	
        return true;
    }
	
    
    protected function updateModulesHooks($checkPermission = false){
							
        foreach ($this->_fields as $field_key => $field) {
            foreach ($field['options'] as $option_key => $option) {
                
                $field_id = $field_key . '_' . $option_key;
                $value = $this->getParam($field_id, null, null, $this->context->shop->id);

                if ($option['source'] == 'exSelect' && (!$checkPermission || ($checkPermission && Tools::getIsset($field_id.'_apply')))  ){
                    
                    if (isset($option['options'][$value]['unregisterHook'])){
                        foreach ($option['options'][$value]['unregisterHook'] as $moduleName => $hooks){
                            foreach ($hooks as $hook){
                                Module::getInstanceByName($moduleName)->unregisterHook($hook, array($this->id_shop));
                            }
                        }
                    }
					
					if (!isset($option['options'][$value]['ignoreHooks'])){
						$option['options'][$value]['ignoreHooks'] = array();
					}
					
                    if (isset($option['options'][$value]['unregisterAllHooks'])){
                        foreach ($option['options'][$value]['unregisterAllHooks'] as $moduleName){
							$this->unregisterAllHooksModule($moduleName, $option['options'][$value]['ignoreHooks']);
                        }
                    }						

                    if (isset($option['options'][$value]['registerHook'])){
                        foreach ($option['options'][$value]['registerHook'] as $moduleName => $hooks){
                            foreach ($hooks as $hook){
                                Module::getInstanceByName($moduleName)->registerHook($hook, array($this->id_shop));                                        
                            }
                        }
                    }
                }
                
            }
        }
        return true;
    }
	
    protected function unregisterAllHooksModule($moduleName, $ignoreHooks = array()){
		
		if ($moduleName == ''){
			return false;
		}
			
		$moduleId = Module::getModuleIdByName($moduleName);
		
        $sql = 'SELECT DISTINCT(`id_hook`) FROM `' . _DB_PREFIX_ . 'hook_module` WHERE `id_module` = ' . (int) $moduleId;
        $result = Db::getInstance()->executeS($sql);
        foreach ($result as $row) {
			
			$hookName =  hook::getNameById($row['id_hook']);
			if (hook::isDisplayHookName($hookName) && !in_array($hookName, $ignoreHooks)){
				 Module::getInstanceByName($moduleName)->unregisterHook($hookName, array($this->id_shop));
			}
        } 
	}	
	
	public function changeStatusANThemeBlock($blockID, $status = 0){
		if ($blockID == ''){
			return false;
		}
		
		Db::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'anthemeblock SET status = '.(int) $status.' WHERE block_identifier = "'.pSQL($blockID).'"');
		
		return true;
	}
	
    protected function updateAnThemeBlocks($checkPermission = false){
							
        foreach ($this->_fields as $field_key => $field) {
            foreach ($field['options'] as $option_key => $option) {
                
                $field_id = $field_key . '_' . $option_key;
                $value = $this->getParam($field_id, null, null, $this->context->shop->id);

                if ($option['source'] == 'exSelect' && (!$checkPermission || ($checkPermission && Tools::getIsset($field_id.'_apply')))  ){
				

                    if (isset($option['options'][$value]['toEnableTb'])){
                        foreach ($option['options'][$value]['toEnableTb'] as $blockId){
                            $this->changeStatusANThemeBlock($blockId, 1);
                        }
                    }
					
                    if (isset($option['options'][$value]['toDisableTb'])){
                        foreach ($option['options'][$value]['toDisableTb'] as $blockId){
                            $this->changeStatusANThemeBlock($blockId, 0);
                        }
                    }
                }
                
            }
        }
        return true;
    }		
	


    /**
     * Select renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function selectRenderer($field_id, $option, $input)
    {
        $options = array(
            'optiongroup' => array(
                'label' => 'label',
                'query' => array()
            ),
            'options' => array(
                'query' => 'options',
                'id' => 'id',
                'name' => 'name'
            )
        );

        $query = array();

        if (isset($option['type']) && $option['type'] == 'font') {
            foreach ($this->_theme_fonts as $key => $font) {
                $query[] = array(
                    'id' => $key,
                    'name' => $font['name'],
                );
            }
        } else if (isset($option['type']) && $option['type'] == 'tpl') {
            $files = glob(_PS_MODULE_DIR_ . $this->name . $option['path'] . '*');

            foreach ($files as $file) {
                $filename = basename($file);

                if (is_file($file) && $filename != 'index.php') {
                    $query[] = array(
                        'id' => $option['path'] . $filename,
                        'name' => $filename,
                    );
                }
            }
        } else {
            foreach ($option['options'] as $option_value => $value) {
                if (is_integer($option_value) && is_scalar($value)) {
                    $query[] = array(
                        'id' => $value,
                        'name' => $value
                    );
                } else if (is_array($value)) {
                    if (isset($value['label'])) {
                        $_query = array();

                        foreach ($value['query'] as $_option_value => $option_name) {
                            if (is_integer($_option_value)) {
                                $_query[] = array(
                                    'id' => $option_name,
                                    'name' => $option_name
                                );
                            } else {
                                $_query[] = array(
                                    'id' => $_option_value,
                                    'name' => $option_name
                                );
                            }
                        }

                        $options['optiongroup']['query'][] = array(
                            'label' => $value['label'],
                            'options' => $_query
                        );
                    }
                } else {
                    $query[] = array(
                        'id' => $option_value,
                        'name' => $value
                    );
                }
            }
        }

        $input['type'] = 'select';

        if (!empty($options['optiongroup']['query'])) {
            $input['options'] = $options;
        } else {
            $input['options'] = array(
                'query' => $query,
                'id' => 'id',
                'name' => 'name',
            );
        }

        return $input;
    }

    /**
     * Switch renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function switchRenderer($field_id, $option, $input)
    {
        $input['type'] = 'switch';
        $input['is_bool'] = true;
        $input['values'] = array(
            array(
                'id' => $field_id . '_on',
                'value' => 1,
                'label' => $this->l('Yes')
            ),
            array(
                'id' => $field_id . '_off',
                'value' => 0,
                'label' => $this->l('No')
            )
        );

        return $input;
    }

    /**
     * Color renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function colorRenderer($field_id, $option, $input, $helper)
    {
        $input['type'] = 'free';
        $value = $this->getParam($field_id, null , $this->context->language->id, $this->context->shop->id);
        $html = '';
        foreach ($this->_theme_colors as $color) {
            $checked = ($value == $color ? ' checked="checked"' : '');
            $html .= '
                <span class="custom-antheme-select" style="
                    background-color:' . $color . ';
                ">
                    <input type="radio" name="' . $field_id . '" value="' . $color . '"' . $checked . ' />
                </span>';
        }

        $bg_images = glob(_PS_MODULE_DIR_ . $this->name . '/views/img/background/*');
        foreach ($bg_images as $image) {
            $filename = basename($image);
            if (is_file($image) && $filename != 'index.php') {
                $color = '/views/img/background/' . $filename;
                $checked = ($value == $color ? ' checked="checked"' : '');
                $html .= '
                    <span class="custom-antheme-select" style="
                        background-image: url(../modules/' . $this->name . '/views/img/background/' . $filename . ');
                        background-size: 100%;
                    ">
                        <input type="radio" name="' . $field_id . '" value="' . $color . '"' . $checked . ' />
                    </span>';
            }
        }

        $helper->fields_value[$field_id] = $html;
        return $input;
    }
    
    /**
     * exSelect renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function exSelectRenderer($field_id, $option, $input)
    {
        $options = array(
            'optiongroup' => array(
                'label' => 'label',
                'query' => array()
            ),
            'options' => array(
                'query' => 'options',
                'id' => 'id',
                'name' => 'name'
            )
        );

        $query = array();
        
        foreach ($option['options'] as $key => $value) {
            $query[] = array(
                'id' => $key,
                'name' => $value['name']
            );
        }

        $input['type'] = 'select';
		$input['typeSub'] = 'exSelect';
		$input['class'] = 'exSelect';

        if (!empty($options['optiongroup']['query'])) {
            $input['options'] = $options;
        } else {
            $input['options'] = array(
                'query' => $query,
                'id' => 'id',
                'name' => 'name',
            );
        }

        return $input;
    }
    
	
    /**
     * selectPresets renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function presetsRenderer($field_id, $option, $input, $helper)
    {
		/*
		$input['type'] = 'free';
		$currentValue = $this->getParam($field_id, null , $this->context->language->id, $this->context->shop->id);
		$presets = $this->loadPresets();
		$html = '';
		$checked = '';
        foreach ($presets as $id => $value) {
			$checked = ($currentValue == $id ? ' checked="checked"' : '');
            $html .= '
                <div class="an_theme-preset-item" style="float: left;">
					<label>
						<img src="'.$value['preview'].'" width="100" /><br/>
						<input type="radio" name="' . $field_id . '" value="' . $id . '"' . $checked . ' /> ' . $value['name'] . '
					</label>
                </div>';
			
        }
		
		
		$helper->fields_value[$field_id] = $html;
		*/
		
 		$input['type'] = 'radio';
		
		$presets = $this->loadPresets();
		
        foreach ($presets as $id => $value) {
            $input['values'][] = array(
                'id' => $id,
                'value' => $id,
				'label' => $value['name'],
            );
			
        }

		return $input; 
    }

    /**
     * Image renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function imageRenderer($field_id, $option, $input)
    {
        $defaultKey = explode('_', $field_id);
        $defaultInput = $this->_fields[$defaultKey[0]]['options'][$defaultKey[1]];
        
        $image = _PS_MODULE_DIR_ . $this->name . '/views/img/' . $this->getParam($field_id, null , $this->context->language->id, $this->context->shop->id);
        $image_url = false;
        $thumb_size = false;

        if (is_file($image) && file_exists($image)) {
            $image_url = '<img src="../modules/' . $this->name . '/views/img/' . basename($image) . '?rand=' . rand(1, 1000) . '" class="imgm img-thumbnail" />';
            $thumb_size = filesize($image) / 1000;
        }

        $input['type'] = 'file';
        $input['display_image'] = true;
        $input['image'] = $image_url;
        $input['size'] = $thumb_size;
        $input['format'] = 'medium';

        if (isset($defaultInput['allow_delete']) && $defaultInput['allow_delete'] === true) {
            $input['delete_url'] = $this->context->link->getAdminLink('AdminModules').'&id_tab='.Tools::getValue('id_tab').'&configure='.$this->name.'&'.self::DELETE_IMAGE.'='.$input['name'];
        }

        return $input;
    }

    /**
     * Number renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function numberRenderer($field_id, $option, $input, $helper)
    {
        $input['type'] = 'number';
        return $input;
    }

    protected function floatRenderer($field_id, $option, $input, $helper)
    {
        $input['type'] = 'number';
        $input['step'] = 0.01;

        if (isset($option['step'])) {
            $input['step'] = $option['step'];
        }

        if (isset($option['min'])) {
            $input['min'] = $option['min'];
        }

        if (isset($option['max'])) {
            $input['max'] = $option['max'];
        }

        return $input;
    }
    
    /**
     * Picker renderer
     *
     * @param string $field_id
     * @param array $option
     * @param array $input
     * @return array
     */
    protected function pickerRenderer($field_id, $option, $input, $helper)
    {
        $input['type'] = 'color';
        return $input;
    }

    /**
     * CSS Generator
     *
     * @return void
     */
    protected function generateFiles()
    {
        foreach ($this->_fields as $field_key => $field) {
            foreach ($field['options'] as $option_key => $option) {
                $field_id = $field_key . '_' . $option_key;
                $value = $this->getParam($field_id,null, null, $this->context->shop->id);

                if (isset($option['type']) && $option['type'] == 'font') {
                    $value = isset($this->_theme_fonts[$value]['css']) ? $this->_theme_fonts[$value]['css'] : $this->_theme_fonts[$value]['link'];
                } else {
                    if ($option['source'] == 'switch') {
                        if ($value && isset($option['css'])) {
                            $value = $option['css'];
                        }
                    } else if ($option['source'] == 'color') {
                        if (!Validate::isColor($value)) {
                            $value = 'url(' . $this->_path . $value . ')';
                        }
                    }
                }

                $this->context->smarty->assign($field_id, $value);
            }
        }
        $export = $this->generateFileName($this->killFile(Configuration::get(self::CSS_FILE, null, $this->context->shop->id_shop_group, $this->context->shop->id)),'css');
        $this->context->smarty->assign('module_path', $this->_path);
        @file_put_contents(_PS_MODULE_DIR_.$this->name.'/views/css/'.$export, $this->display($this->name, 'css_generator.tpl')) ? $this->updateFile(self::CSS_FILE, $export) : false;
        if (Tools::isSubmit('custom_code_code_css')){
            $customCSS = $this->generateFileName($this->killFile(Configuration::get(self::CUSTOM_CSS_FILE, null, $this->context->shop->id_shop_group, $this->context->shop->id)),'css');
            @file_put_contents(_PS_MODULE_DIR_.$this->name.'/views/css/'.$customCSS, Tools::getValue('custom_code_code_css')) ? $this->updateFile(self::CUSTOM_CSS_FILE, $customCSS) : false;
        }
        if (Tools::isSubmit('custom_code_code_js')){
            $customJS = $this->generateFileName($this->killFile(Configuration::get(self::CUSTOM_JS_FILE, null, $this->context->shop->id_shop_group, $this->context->shop->id)),'js');
            @file_put_contents(_PS_MODULE_DIR_.$this->name.'/views/js/'.$customJS, Tools::getValue('custom_code_code_js')) ? $this->updateFile(self::CUSTOM_JS_FILE, $customJS) : false;
        }
        return true;
    }

    /**
     * Generate configuration form
     *
     * @return string
     */
    public function displayForm($fields)
    {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper = new HelperForm();

        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name . '&id_tab=' . $this->getIdTab();

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->submit_action = $this->name;

        $fields_form = array();
        $field_counter = 0;

        foreach ($fields as $field_key => $field) {
            $fields_form[$field_counter]['form'] = array(
                'legend' => array(
                    'title' => $this->l($field['title']),
                ),
                'input' => array(),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            );

            foreach ($field['options'] as $option_key => $option) {
                
                $field_id = $field_key . '_' . $option_key;
                if (isset($option['type']) && $option['type'] == 'textarea') {
                    $input = array(
                        'type' => 'textarea',
                        'rows' => $option['rows'],
                        'label' => $this->l($option['title']),
                        'name' => $field_id,
                        'desc' => $this->l($option['description']),
                        'required' => isset($option['allow_empty']) ? !$option['allow_empty'] : false
                    );
                    $helper->fields_value[$field_id] = Tools::file_get_contents(_PS_MODULE_DIR_.$this->name.'/views/' . $option['file_type'] . '/' . Configuration::get('CUSTOM_' . Tools::strtoupper($option['file_type']) . '_FILE', null, $this->context->shop->id_shop_group, $this->context->shop->id ));
                } else {
                    $input = array(
                        'type' => 'text',
                        'label' => $this->l($option['title']),
                        'name' => $field_id,
                        'desc' => $this->l($option['description']),
                        'required' => isset($option['allow_empty']) ? !$option['allow_empty'] : false
                    );
                    if (isset($option['lang']) && $option['lang'] === true) {
                        $input['lang'] = true;
                        $input['col'] = 6;

                        foreach (Language::getLanguages(1, 0, 1) as $id_lang) {
                            $helper->fields_value[$field_id][$id_lang] = $this->getParam($field_id, null, null, (int)$id_lang, $this->context->shop->id);
                        }
                    } else {
                        $helper->fields_value[$field_id] = $this->getParam($field_id, null, $this->context->language->id, $this->context->shop->id);
                    }
                }

                $renderMethod = $option['source'].'Renderer';

                if (method_exists($this, $renderMethod)) {
                    $input = $this->$renderMethod($field_id, $option, $input, $helper);
                }

                $fields_form[$field_counter]['form']['input'][] = $input;
            }

            $field_counter++;
        }

/*         $fields_form[] = array(
            'form' => array(
                // 'legend' => array(
                //     'title' => $this->l('System')
                // ),
                'buttons' => array_merge(array(
                    array(
                        'type' => 'submit',
                        'name' => self::RESET_SYSTEM,
                        'title' => $this->l('Reset system configuration')
                    ))
                )
            )
        ); */

        $helper->languages = $this->context->controller->getLanguages();
        $helper->tpl_vars = array(
            'base_url' => $this->context->shop->getBaseURL(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );
        
        return $helper->generateForm($fields_form);
    }

    protected function setDefaultValues()
    {
        foreach ($this->_fields as $field_key => $field) {
            foreach ($field['options'] as $option_key => $option) {
                if (isset($option['default'])) {
                    $this->getParam($field_key.'_'.$option_key, $option['default'], $this->context->language->id, $this->context->shop->id);
                }
            }
        }

        return true;
    }

    protected function deleteImage()
    {
        $image = Tools::getValue(self::DELETE_IMAGE);
        $path = realpath(_PS_MODULE_DIR_.$this->name.'/views/img/'.$this->getParam($image, null, $this->context->language->id, $this->context->shop->id));

        if ($image !== false) {
            if (Tools::file_exists_no_cache($path)) {
                @unlink($path);
                $this->getParam($image, '', $this->context->language->id, $this->context->shop->id);
            }
        }
    }

    /**
     * Get configuration page
     *
     * @return string
     */
    public function getContent()
    {
        if(Shop::getContextShopID() || Shop::getContextShopGroupID()){

        $this->setupConfiguration();		
            
            if (Tools::isSubmit('dev')) {
				
				$currentPreset = $this->getParam('importPresets_preset', null, null, $this->context->shop->id);
				$presets = $this->loadPresets();
				if (isset($presets[$currentPreset]['folder'])){
					$this->exportConfiguration($presets[$currentPreset]['folder'].'/configuration.json');
				} else {
					$this->exportConfiguration();
				}

            } else if (Tools::getIsset(self::DELETE_IMAGE)) {
                $this->deleteImage();
                $this->generateFiles();
            } else if (Tools::getIsset($this->name)) {
                
/*                 if (Tools::getIsset(self::RESET_SYSTEM)) {
                    $this->importConfiguration();
                } else {
                    $this->postProcess();
                } */
				
				$checkPermission = false;
				if (!Tools::getIsset('importPresets_preset')){
					$checkPermission = true;
				}
				
				$this->postProcess();
				
				$this->importPreset();
				$this->updateAnThemeBlocks($checkPermission);
                $this->updateModulesHooks($checkPermission);

                $this->generateFiles();
                $this->redirectAfterUpdate();
            }

            return $this->showBackendContentPage();
        } else {
            $this->context->controller->warnings[] = $this->l('Please, select a shop of your multistore first you want to apply changes for');
        }

    }

    protected function redirectAfterUpdate()
    {
        if (empty($this->errors)) {
            $module = 'AdminModules'.(int)Tab::getIdFromClassName('AdminModules').(int)$this->context->employee->id;
            $token = Tools::getAdminToken($module);
            
            $redirectAfter = 'index.php?tab=AdminModules&conf=4&configure='.$this->name.'&id_tab='.$this->getIdTab();
            $redirectAfter .= '&token='.$token;
            
            Tools::redirectAdmin($redirectAfter);
        }
    }

    public function postProcess()
    {
        $id_tab = (int)Tools::getValue('id_tab', 0);

        if ($id_tab >= 0 && isset($this->_tabbed_fields[$id_tab])) {
            foreach ($this->_tabbed_fields[$id_tab]['fields'] as $field_key => $field) {
                if ($field_key != 'custom_code'){
                    foreach ($field['options'] as $option_key => $option) {
                        $field_id = $field_key . '_' . $option_key;

                        if (isset($option['lang']) && $option['lang'] == true) {
                            $value = array();

                            foreach (Language::getLanguages(1, 1, 1) as $id_lang) {
                                $value[$id_lang] = Tools::getValue($field_id.'_'.$id_lang, '');
                            }
                        } elseif (Tools::getIsset($field_id)) {
                            $value = Tools::getValue($field_id);
                        }

                        if (isset($_FILES[$field_id])) {
                            if (!$_FILES[$field_id]['error']) {
                                if ($result = $this->uploadImage($field_id)) {
                                    @unlink(_PS_MODULE_DIR_ .$this->name.'/views/img/'.$this->getParam($field_id, null, $this->context->language->id, $this->context->shop->id));
                                    $this->getParam($field_id, $result, $this->context->language->id, $this->context->shop->id);
                                } else {
                                    $this->errors[] = $this->l('Error during file uploading');
                                }
                            }
                        } else {
                            $this->getParam($field_id, $value, $this->context->language->id, $this->context->shop->id);
                        }
                    }
                }
            }
        }		
    }

    protected function showBackendContentPage()
    {
        $idTab = $this->getIdTab();
        $currentTabRaw = $this->_tabbed_fields[$idTab];
        $tabs = array();
        $currentTab = $this->displayError($this->l('The tab does not exist content'));
        $i = 0;

        if (isset($currentTabRaw, $currentTabRaw['fields'])) {
            $currentTab = array(
                'legend' => $currentTabRaw['legend'],
                'form' => $this->displayForm($currentTabRaw['fields'])
            );
        }

        foreach ($this->_tabbed_fields as $tabContainsFields) {
            $tabs[] = array(
                'legend' => $tabContainsFields['legend'],
                'link' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&id_tab='.$i++
            );
        }

        $this->context->smarty->assign(array(
            'tabs' => $tabs,
            'id_tab' => $idTab,
            'tab' => $currentTab
        ));
		
		$this->context->smarty->assign('theme', $this->getThemeInfo());
		
        return implode('', (array)$this->errors).$this->display(__FILE__, 'views/templates/admin/form.tpl');
    }

    protected function getIdTab()
    {
        return (int)Tools::getValue('id_tab', 0);
    }

    protected function getInputFactory()
    {
        if (!($this->inputFactory instanceof AnThemeInputFactory)) {
            $this->inputFactory = new AnThemeInputFactory($this->_fields);
        }

        return $this->inputFactory;
    }
	
    /**
     * Get an id_product_attribute by an id_product and one or more
     * id_attribute.
     *
     * e.g: id_product 8 with id_attribute 4 (size medium) and
     * id_attribute 5 (color blue) returns id_product_attribute 9 which
     * is the dress size medium and color blue.
     *
     * @param int $idProduct
     * @param int|int[] $idAttributes
     * @param bool $findBest
     *
     * @return int
     *
     * @throws PrestaShopException
     */
    public static function getIdProductAttributeByIdAttributes($idProduct, $idAttributes, $findBest = false)
    {
        $idProduct = (int) $idProduct;

        if (!is_array($idAttributes) && is_numeric($idAttributes)) {
            $idAttributes = [(int) $idAttributes];
        }

        if (!is_array($idAttributes) || empty($idAttributes)) {
            //throw new PrestaShopException(sprintf('Invalid parameter $idAttributes with value: "%s"', print_r($idAttributes, true)));
			return false;
        }

        $idAttributesImploded = implode(',', array_map('intval', $idAttributes));
        $idProductAttribute = Db::getInstance()->getValue(
            '
            SELECT
                pac.`id_product_attribute`
            FROM
                `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
            WHERE
                pa.id_product = ' . $idProduct . '
                AND pac.id_attribute IN (' . $idAttributesImploded . ')
            GROUP BY
                pac.`id_product_attribute`
            HAVING
                COUNT(pa.id_product) = ' . count($idAttributes)
        );

        if ($idProductAttribute === false && $findBest) {
            //find the best possible combination
            //first we order $idAttributes by the group position
            $orderred = [];
            $result = Db::getInstance()->executeS(
                '
                SELECT
                    a.`id_attribute`
                FROM
                    `' . _DB_PREFIX_ . 'attribute` a
                    INNER JOIN `' . _DB_PREFIX_ . 'attribute_group` g ON a.`id_attribute_group` = g.`id_attribute_group`
                WHERE
                    a.`id_attribute` IN (' . $idAttributesImploded . ')
                ORDER BY
                    g.`position` ASC'
            );

            foreach ($result as $row) {
                $orderred[] = $row['id_attribute'];
            }

            while ($idProductAttribute === false && count($orderred) > 1) {
                array_pop($orderred);
                $idProductAttribute = Db::getInstance()->getValue(
                    '
                    SELECT
                        pac.`id_product_attribute`
                    FROM
                        `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                        INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pa.id_product_attribute = pac.id_product_attribute
                    WHERE
                        pa.id_product = ' . (int) $idProduct . '
                        AND pac.id_attribute IN (' . implode(',', array_map('intval', $orderred)) . ')
                    GROUP BY
                        pac.id_product_attribute
                    HAVING
                        COUNT(pa.id_product) = ' . count($orderred)
                );
            }
        }
		
		

        if (empty($idProductAttribute)) {
			return false;
            //throw new PrestaShopObjectNotFoundException('Can not retrieve the id_product_attribute');
        }

        return $idProductAttribute;
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
}
