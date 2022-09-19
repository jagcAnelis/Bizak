<?php
/**
 * 2020 Anvanto
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

class Anscrolltop extends Module
{
    protected $config_form = false;

    protected $accepted_position = array(
        'top-left',
        'top-right',
        'bottom-left',
        'bottom-right'
    );

    const SALT = 'IDDQD';
    const PREFIX = 'SCROLLTOP_';

    const BUTTON_BG_NONE    = 'none';

    const BUTTON_IMG        = 'icon_img';
    const SVG_COLOR  		= 'svg_color';
    const SVG_WIDTH         = 'SVG_WIDTH';
    const BORDER_WIDTH      = 'BORDER_WIDTH';
    const BORDER_COLOR      = 'BORDER_COLOR';
    const BORDER_RADIUS     = 'BORDER_RADIUS';
    const BUTTON_BG         = 'BUTTON_BG';
    const BUTTON_WIDTH      = 'BUTTON_WIDTH';
    const BUTTON_HEIGHT     = 'BUTTON_HEIGHT';
    const BUTTON_MARGIN_X   = 'BUTTON_MARGIN_X';
    const BUTTON_MARGIN_Y   = 'BUTTON_MARGIN_Y';
    const OPACITY           = 'OPACITY';
    const POSITION          = 'POSITION';
    const CSS_FILE          = 'CSS_FILE';

    public function __construct()
    {
        $this->name = 'anscrolltop';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'anvanto';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '3a484f3d1983a2323b714f018f5fb79d';
        
        parent::__construct();

        $this->configuration_file = $this->local_path.'configuration.json';
        $this->front_css_path = $this->local_path.'views/css/';

        $this->displayName = $this->l('AN Scroll Top Button');
        $this->description = $this->l('A scroll top top button helps a user to get back quickly to the top of the page.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        if (file_exists($this->configuration_file)) {
            foreach ((array)Tools::jsonDecode(Tools::file_get_contents($this->configuration_file)) as $cfg => $val) {
                $this->getParam($cfg, $val);
            }
        }

        return parent::install()
            && (bool)$this->generateCSS()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader')
            && $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        foreach (array_keys($this->getConfigFormValues()) as $key) {
            Configuration::deleteByName(self::PREFIX.$key);
        }

        Configuration::deleteByName(self::PREFIX.self::CSS_FILE);

        return parent::uninstall();
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitScrolltopModule')) {
            $this->postProcess();
        }

        $this->context->smarty->assign(
			array(
				'an_scrolltop_icon' => $this->getPathUri().'img/'.$this->getParam(self::BUTTON_IMG), 
				'an_scrolltop_svg_color' => $this->getParam(self::SVG_COLOR), 
				'an_scrolltop_svg_width' => $this->getParam(self::SVG_WIDTH), 
				'errors' => $this->getErrors())
				);
				
		$this->context->smarty->assign('theme', $this->getThemeInfo());

		$output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
		$output .= $this->renderForm();
		$output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/config_footer.tpl');
				
        return $output;
    }

    protected function renderForm()
    {
		
		$helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = $this->getParam('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', null, 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitScrolltopModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
			
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'uri' => $this->getPathUri(),
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    protected function generateCSS()
    {
        $cfg = $this->getConfigFormValues();
        $cfg[self::OPACITY] = number_format((int)$cfg[self::OPACITY]/100, 2);
        $method = "position".str_replace("-", "", $cfg[self::POSITION]);
        list($cfg['TOP'], $cfg['RIGHT'], $cfg['BOTTOM'], $cfg['LEFT']) = method_exists($this, $method) ? $this->{$method}($cfg) : $this->positionbottomright($cfg);

        $languages = $this->context->controller->getLanguages();

        $export = $this->generateCSSName($this->killCSS());

        if ($this->getParam(self::CSS_FILE, $export)) {
            $this->context->smarty->assign($cfg);
            return @file_put_contents($this->front_css_path.$export, $this->display($this->name, 'front.css.tpl'));
        }

        return true;
    }

    protected function killCSS()
    {
        $file = (string)$this->getParam(self::CSS_FILE);
        @unlink($this->local_path.'views/css/'.$file);
        return $file;
    }

    protected function generateCSSName($name)
    {
        return ($name !== false ? md5($name.self::SALT) : md5("front")).'.css';
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
                        'type' => 'file_image',
                        'label' => $this->l('Image'),
                        'required' => false,
                        'name' => 'icon_img',
                    ),				
					array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => self::SVG_COLOR,
                        'label' => $this->l('Icon SVG Color'),
                    ),
					
                    array(
                        'col' => 2,
                        'min' => 1,
                        'max' => 150,
						'values' => 9,
                        'type' => 'number',
                        'name' => self::SVG_WIDTH,
                        'label' => $this->l('SVG width'),
                    ),					
					
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BORDER_WIDTH,
                        'label' => $this->l('Border width'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => self::BORDER_COLOR,
                        'label' => $this->l('Border color'),
                    ),					
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BORDER_RADIUS,
                        'label' => $this->l('Border radius'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'name' => self::BUTTON_BG,
                        'label' => $this->l('Button background'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BUTTON_WIDTH,
                        'label' => $this->l('Button width'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BUTTON_HEIGHT,
                        'label' => $this->l('Button height'),
                    ),
					
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'prefix' => '<i class="icon"><b>%</b></i>',
                        'name' => self::OPACITY,
                        'label' => $this->l('Button opacity'),
                    ),
					
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BUTTON_MARGIN_X,
                        'label' => $this->l('Button x-indent for x-coordinate'),
                    ),
                    array(
                        'col' => 2,
                        'type' => 'text',
                        'name' => self::BUTTON_MARGIN_Y,
                        'label' => $this->l('Button y-indent for y-coordinate'),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'select',
                        'name' => self::POSITION,
                        'label' => $this->l('Button position'),
                        'options' => array(
                            'query' => array_map(function ($i) {
                                return array(self::POSITION => $i, 'name' => $i);
                            }, $this->accepted_position),
                            'id' => self::POSITION,
                            'name' => 'name'
                        ),
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
        $params = array();
        
        foreach ($this->getConfigKeys() as $entity) {
            $params[$entity] = $this->getParam($entity);
        }

        return $params;
    }

    protected function getConfigKeys()
    {
        return array(
			self::BUTTON_IMG,
			self::SVG_COLOR,
            self::SVG_WIDTH,
            self::BORDER_WIDTH,
            self::BORDER_COLOR,
            self::BORDER_RADIUS,
            self::BUTTON_BG,
            self::BUTTON_WIDTH,
            self::BUTTON_HEIGHT,
            self::BUTTON_MARGIN_X,
            self::BUTTON_MARGIN_Y,
            self::OPACITY,
            self::POSITION,
        );
    }

    protected function getConfigColorKeys()
    {
        return array(
            self::BORDER_COLOR,
            self::SVG_COLOR,
            self::BUTTON_BG,
        );
    }

    protected function getParam($key, $value = null, $default_value = null)
    {
        if (!is_string($key) || empty($key)) {
            return false;
        }

        return is_null($value) ? Configuration::get(self::PREFIX.$key, $default_value) : Configuration::updateValue(self::PREFIX.$key, $value);
    }

    protected function postProcess()
    {
        if (Tools::isSubmit('submitScrolltopModule')) {
            $values = array();
            $update_images_values = false;

            if (isset($_FILES['icon_img'])
                && isset($_FILES['icon_img']['tmp_name'])
                && !empty($_FILES['icon_img']['tmp_name'])) {
                $ext = Tools::substr($_FILES['icon_img']['name'], strrpos($_FILES['icon_img']['name'], '.') + 1);
                $file_name = md5($_FILES['icon_img']['name']) . '.' . $ext;

                if (!move_uploaded_file(
                    $_FILES['icon_img']['tmp_name'],
                    dirname(__FILE__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $file_name
                )
                ) {
                    return $this->displayError($this->l(
                        'An error occurred while attempting to upload the file.'
                    ));
                } else {
                    if (Configuration::get(self::PREFIX.'icon_img') != $file_name) {
                        @unlink(dirname(__FILE__)
                            . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR
                            . Configuration::get(self::PREFIX.'icon_img'));
                    }

                    $values['icon_img'] = $file_name;
                }

                $update_images_values = true;
            }

            if ($update_images_values) {
                Configuration::updateValue(self::PREFIX.'icon_img', $values['icon_img']);
            }

            $this->context->controller->confirmations[] = $this->l(
                'The settings have been updated.'
            );
        }        

		foreach ($this->getConfigKeys() as $key) {
            $value = Tools::getValue($key);
			
			if ($key != 'icon_img'){
				if (in_array($key, $this->getConfigColorKeys())) {
					if (!empty($value)) {
						if (!preg_match('/^(#[0-9a-fA-F]{6})$/', $value)) {
							$this->_errors[] = Tools::displayError($this->l("That is not a color"));
							continue;
						}
					} else {
						$value = self::BUTTON_BG_NONE;
					}
				}

				$this->getParam($key, $value);
			}
        }


        @file_put_contents($this->configuration_file, Tools::jsonEncode($this->getConfigFormValues()));
        
        return $this->generateCSS() //needs a refresh but hookBackOfficeHeader we can't load the new css file
            && Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab_module.'&module_name='.$this->name);
		
	}

    public function hookBackOfficeHeader()
    {
        if (in_array($this->name, array(Tools::getValue('module_name'), Tools::getValue('configure')))) {
            $this->context->controller->addCSS($this->_path.'/views/css/'.$this->getParam(self::CSS_FILE));
            $this->context->controller->addCSS($this->_path.'/views/css/back.css');
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/back.js');
        }
    }

    public function hookHeader()
    {
        if (version_compare(_PS_VERSION_, "1.7.0.0", "<")) {
            $this->context->controller->addJS($this->_path.'/views/js/front.js');
            $this->context->controller->addCSS($this->_path.'/views/css/'.$this->getParam(self::CSS_FILE));
        } else {
            $this->context->controller->registerStylesheet("modules-scrolltop-fe2", "modules/{$this->name}/views/css/".$this->getParam(self::CSS_FILE), array('priority' => 150));
            $this->context->controller->registerJavascript("modules-scrolltop-fe3", "modules/{$this->name}/views/js/front.js", array('position' => AbstractAssetManager::DEFAULT_JS_POSITION, 'priority' => 150));
        }
    }

    public function hookDisplayFooter()
    {
        $this->context->smarty->assign(array(
				'an_scrolltop_icon' => $this->getPathUri().'img/'.$this->getParam(self::BUTTON_IMG), 
				'an_scrolltop_svg_color' => $this->getParam(self::SVG_COLOR), 
				'an_scrolltop_svg_width' => $this->getParam(self::SVG_WIDTH),
			));

        return $this->display($this->name, "display_footer.tpl");
    }

    protected function positiontopleft($cfg)
    {
        return array($cfg[self::BUTTON_MARGIN_Y]."px", "auto", "auto", $cfg[self::BUTTON_MARGIN_X]."px");
    }

    protected function positiontopright($cfg)
    {
        return array($cfg[self::BUTTON_MARGIN_Y]."px", $cfg[self::BUTTON_MARGIN_X]."px", "auto", "auto");
    }

    protected function positionbottomleft($cfg)
    {
        return array("auto", "auto", $cfg[self::BUTTON_MARGIN_Y]."px", $cfg[self::BUTTON_MARGIN_X]."px");
    }

    protected function positionbottomright($cfg)
    {
        return array("auto", $cfg[self::BUTTON_MARGIN_X]."px", $cfg[self::BUTTON_MARGIN_Y]."px", "auto");
    }
	
	public function getThemeInfo(){
		$theme = array();
		$themeFileJson = _PS_THEME_DIR_.'/config/theme.json';
		if (Tools::file_exists_no_cache($themeFileJson)) {
			$theme = (array)Tools::jsonDecode(Tools::file_get_contents($themeFileJson), 1);			
		}

		if (!isset($theme['url_contact_us']) || $theme['url_contact_us'] == ''){
			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$theme['url_contact_us'] = 'https://addons.prestashop.com/contact-form.php';
				$theme['url_contact_us'] = $theme['url_contact_us']. '?id_product=' .$theme['addons_id'];
			}
		}
		if (!isset($theme['url_rate']) || $theme['url_rate'] == ''){
			if (isset($theme['addons_id']) && $theme['addons_id'] != ''){
				$theme['url_rate'] = 'https://addons.prestashop.com/en/ratings.php';
				$theme['url_rate'] = $theme['url_rate'].'?id_product='.$theme['addons_id'];
			}
		}
		
		return $theme;
	}	
}
