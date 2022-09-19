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

class an_logo extends Module implements WidgetInterface
{
    const PREFIX = 'an_logo_';
	const CODECSS = _PS_THEME_DIR_.'/assets/css/an_logo.css';

    public function __construct()
    {
        $this->name = 'an_logo';
        $this->tab = 'others';
        $this->version = '1.1.0';
        $this->author = 'Anvanto';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->module_key = '';

        parent::__construct();

        $this->displayName = $this->l('AN Logo');
        $this->description = $this->l('The module enables you to use a SVG file, SVG code, and PNG file as the logo, allows you to change the CSS code and the size of the logo.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');
    }

    /**
     * @return bool
     */
    public function install()
    {
        if (!parent::install() || !$this->registerHook('displayLogo') || !$this->registerHook('header')) {
            return false;
        }
        Configuration::updateValue('an_logo_view_type', 'logo');
        Configuration::updateValue('svg_textarea', '');
        return true;
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        Configuration::deleteByName('an_logo_view_type');
        Configuration::deleteByName('svg_textarea');
        Configuration::deleteByName('an_logo_img');
        return parent::uninstall();
    }
	
    /**
     *
     */
    public function hookHeader()
    {
		$this->context->controller->registerStylesheet(
			"modules-an_logo",
			'themes/'._THEME_NAME_.'/assets/css/an_logo.css',
			['media' => 'all', 'priority' => 150]
		);
    }	

    /**
     * @return string
     */
    public function getContent()
    {
        if (((bool)Tools::isSubmit('submit_an_logo')) == true) {
            $this->postProcess();
        }
	//	$this->context->smarty->assign('theme', $this->getThemeInfo());
    //    return $this->display(__FILE__, 'views/templates/admin/top.tpl').$this->renderForm();
        return $this->renderForm();
    }

    /**
     * @return string
     */
    protected function renderForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submit_an_logo';
        $helper->currentIndex = $this->context->link->getAdminLink(
                'AdminModules',
                false
            ) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * @return array
     */
    public function getConfigFieldsValues()
    {
        $fields = array();
        $fields['an_logo_img'] = Configuration::get('an_logo_img');
        $fields['an_logo_view_type'] = Configuration::get('an_logo_view_type');
        $fields['svg_textarea'] = Configuration::get('svg_textarea');
        $fields['an_logo_codeCss'] = '';
		
		$codeCss = Tools::file_get_contents(self::CODECSS);
		if ($codeCss){
			$fields['an_logo_codeCss'] = $codeCss;
		}

        return $fields;
    }


    /**
     * @return array
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings Anvanto Logo'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->l('View type'),
                        'name' => 'an_logo_view_type',
                        'default_value' => 0,
                        'values' => array(
                            array(
                                'id' => 'logo',
                                'value' => 'logo',
                                'label' => $this->l('Satandart logo (You can change it here: Design / Theme & Logo)')
                            ),
                            array(
                                'id' => 'svg',
                                'value' => 'svg',
                                'label' => $this->l('File svg, .gif, .jpg, .png')
                            ),
                            array(
                                'id' => 'svg_text',
                                'value' => 'svg_text',
                                'label' => $this->l('Code svg'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'file_image',
                        'label' => $this->l('Image'),
                        'required' => false,
                        'name' => 'an_logo_img',
                    ),
                    array(
                        'type' => 'codecssjs',
                        'label' => $this->l('Code svg'),
                        'name' => 'svg_textarea',
                        'required' => false,
						'height' => '150px',
						'classCol' => 'col-lg-12',
                    ),
					
                    array(
                        'type' => 'codecssjs',
                        'lang' => false,
                        'required' => false,
						'height' => '150px',
						'classCol' => 'col-lg-12',
                        'label' => $this->l('CSS'),
                        'name' => 'an_logo_codeCss',
                    ),					
					
                ),
				
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }


    /**
     * @return string
     */
    protected function postProcess()
    {
        if (Tools::getValue('filename') == 'delete') {
            @unlink(dirname(__FILE__)
                . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR
                . Configuration::get('an_logo_img'));
            Configuration::updateValue('an_logo_img', '');
        } elseif (Tools::isSubmit('submit_an_logo')) {
            $values = array();
            $update_images_values = false;

            if (isset($_FILES['an_logo_img'])
                && isset($_FILES['an_logo_img']['tmp_name'])
                && !empty($_FILES['an_logo_img']['tmp_name'])) {
                $ext = Tools::substr($_FILES['an_logo_img']['name'], strrpos($_FILES['an_logo_img']['name'], '.') + 1);
                $file_name = md5($_FILES['an_logo_img']['name']) . '.' . $ext;

                if (!move_uploaded_file(
                    $_FILES['an_logo_img']['tmp_name'],
                    dirname(__FILE__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $file_name
                )
                ) {
                    return $this->displayError($this->l(
                        'An error occurred while attempting to upload the file.'
                    ));
                } else {
                    if (Configuration::get('an_logo_img') != $file_name) {
                        @unlink(dirname(__FILE__)
                            . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR
                            . Configuration::get('an_logo_img'));
                    }

                    $values['an_logo_img'] = $file_name;
                }

                $update_images_values = true;
            }

            if ($update_images_values) {
                Configuration::updateValue('an_logo_img', $values['an_logo_img']);
            }

            Configuration::updateValue('an_logo_view_type', Tools::getValue('an_logo_view_type'));
            //Configuration::updateValue('svg_textarea', Tools::getValue('svg_textarea'), true); //doesent work, so
            Db::getInstance()->update('configuration', array(
                'value' => Tools::getValue('svg_textarea'),
                'date_upd' => date('Y-m-d H:i:s'),
            ), '`name` = \'svg_textarea\'', 1, true);
            Configuration::set('svg_textarea', Tools::getValue('svg_textarea'));
			
			//	an_logo_codeCss
			
			$codeCss = Tools::file_get_contents(self::CODECSS);
			if ($codeCss != Tools::getValue('an_logo_codeCss')){
				@file_put_contents(self::CODECSS, Tools::getValue('an_logo_codeCss'));
				Media::clearCache();
			}

            $this->context->controller->confirmations[] = $this->l(
                'The settings have been updated.'
            );			
        }
		
		
		
		

        return '';
    }

    /**
     * @param $hookName
     * @param array $params
     * @return mixed|void
     */
    public function renderWidget($hookName, array $params)
    {
        $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        return $this->fetch('module:an_logo/views/templates/front/logo.tpl');
    }

    /**
     * @param $hookName
     * @param array $params
     * @return array
     */
    public function getWidgetVariables($hookName, array $params)
    {
        $ret = array(
            'an_logo_view_type' => Configuration::get('an_logo_view_type'),
            'svg_textarea' => Configuration::get('svg_textarea')
        );
        if (Configuration::get('an_logo_img')) {
            $ret['an_logo_img'] = $this->getPathUri().'img/' .
                Configuration::get('an_logo_img');
        } else {
            $ret['an_logo_img'] = false;
        }
        return $ret;
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
