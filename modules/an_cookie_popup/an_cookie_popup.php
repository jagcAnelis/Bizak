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

class an_cookie_popup extends Module
{
    protected $config_form = false;

    /**
     * an_cookie_popup constructor.
     */
    public function __construct()
    {
        $this->name = 'an_cookie_popup';
        $this->tab = 'others';
        $this->version = '1.1.1';
        $this->author = 'anvanto';
        $this->need_instance = 0;

        $this->bootstrap = true;
        $this->module_key = '';

        parent::__construct();

        $this->displayName = $this->l('AN Cookie Popup');
        $this->description = $this->l('AN Cookie Popup');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->front_css_path = $this->local_path.'views/css/';
    }

    /**
     * @return bool
     */
    public function install()
    {
		
		Configuration::updateValue('an_modal_cookie_opacity', '80');
        Configuration::updateValue('an_modal_cookie_width', '435');
        Configuration::updateValue('an_modal_cookie_height', '');
        Configuration::updateValue('an_modal_cookie_position', 'bl');
        Configuration::updateValue('an_modal_cookie_accept', 'Accept');
        Configuration::updateValue('an_modal_cookie_privacy', 'Privacy policy');
        Configuration::updateValue('an_modal_cookie_text_color', '#FFFFFF');
        Configuration::updateValue('an_modal_cookie_background', '#000000');
        Configuration::updateValue('an_modal_cookie_privacy_link', '#');
        Configuration::updateValue('an_modal_cookie_links_color', '#ffffff');
		
		$modalCookieText = $this->display($this->name, 'views/templates/front/default_content.tpl');		
        Configuration::updateValue('an_modal_cookie_text', htmlentities($modalCookieText));		

        return parent::install()
            && (bool)$this->generateCSS()
            && $this->registerHook('header')
            && $this->registerHook('backOfficeHeader');
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        Configuration::deleteByName('an_modal_cookie_opacity');
        Configuration::deleteByName('an_modal_cookie_width');
        Configuration::deleteByName('an_modal_cookie_height');
        Configuration::deleteByName('an_modal_cookie_position');
        Configuration::deleteByName('an_modal_cookie_text');
        Configuration::deleteByName('an_modal_cookie_accept');
        Configuration::deleteByName('an_modal_cookie_privacy');
        Configuration::deleteByName('an_modal_cookie_text_color');
        Configuration::deleteByName('an_modal_cookie_privacy_link');
        Configuration::deleteByName('an_modal_cookie_links_color');

        return parent::uninstall();
    }

    /**
     * @return string
     */
    public function getContent()
    {
		if (((bool)Tools::isSubmit('submitan_cookie_popupModule')) == true) {
            $this->postProcess();
        }

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
        $helper->submit_action = 'submitan_cookie_popupModule';
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
        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['an_modal_cookie_text'][$lang['id_lang']] = Tools::getValue(
                'an_modal_cookie_text_'.$lang['id_lang'],
                html_entity_decode(Configuration::get('an_modal_cookie_text', $lang['id_lang']))
            );
            $fields['an_modal_cookie_accept'][$lang['id_lang']] = Tools::getValue(
                'an_modal_cookie_accept_'.$lang['id_lang'],
                Configuration::get('an_modal_cookie_accept', $lang['id_lang'])
            );
            $fields['an_modal_cookie_privacy'][$lang['id_lang']] = Tools::getValue(
                'an_modal_cookie_privacy_'.$lang['id_lang'],
                Configuration::get('an_modal_cookie_privacy', $lang['id_lang'])
            );
            $fields['an_modal_cookie_privacy_link'][$lang['id_lang']] = Tools::getValue(
                'an_modal_cookie_privacy_link_'.$lang['id_lang'],
                Configuration::get('an_modal_cookie_privacy_link', $lang['id_lang'])
            );
        }

        $fields['an_modal_cookie_opacity'] = Configuration::get('an_modal_cookie_opacity');
        $fields['an_modal_cookie_width'] = Configuration::get('an_modal_cookie_width');
        $fields['an_modal_cookie_height'] = Configuration::get('an_modal_cookie_height');
        $fields['an_modal_cookie_position'] = Configuration::get('an_modal_cookie_position');
        $fields['an_modal_cookie_text_color'] = Configuration::get('an_modal_cookie_text_color');
        $fields['an_modal_cookie_background'] = Configuration::get('an_modal_cookie_background');
        $fields['an_modal_cookie_links_color'] = Configuration::get('an_modal_cookie_links_color');

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
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'lang' => true,
                        'required' => false,
                        'cols' => '12',
                        'class' => 'autoload_rte',
                        'label' => $this->l('Text'),
                        'name' => 'an_modal_cookie_text',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'label' => $this->l('"Accept" link text'),
                        'name' => 'an_modal_cookie_accept',
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'required' => true,
                        'prefix' => '<i class="icon icon-code"></i>',
                        'name' => 'an_modal_cookie_text_color',
                        'label' => $this->l('Text color'),
                    ),
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'required' => true,
                        'prefix' => '<i class="icon icon-code"></i>',
                        'name' => 'an_modal_cookie_links_color',
                        'label' => $this->l('Links color'),
                    ),

                    array(
                        'type' => 'text',
                        'lang' => true,
                        'required' => true,
                        'label' => $this->l('Privacy policy'),
                        'name' => 'an_modal_cookie_privacy',
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'required' => false,
                        'label' => $this->l('Privacy policy link'),
                        'name' => 'an_modal_cookie_privacy_link',
						'col' => 4,
                    ),
					
					array(
						'type' => 'html',
						'name' => 'line2',
						'html_content' => 'hr',	
					),					
					
					
                    array(
                        'col' => 9,
                        'type' => 'color',
                        'required' => true,
                        'prefix' => '<i class="icon icon-code"></i>',
                        'name' => 'an_modal_cookie_background',
                        'label' => $this->l('Modal background color'),
                    ),					
                    array(
                        'type' => 'number',
                        'required' => false,
                        'label' => $this->l('Opacity'),
                        'name' => 'an_modal_cookie_opacity',
                        'suffix' => '%',
                        'min' => 0,
                        'max' => 100,
                        'col' => 2,
                    ),
                    array(
                        'type' => 'number',
                        'required' => false,
                        'label' => $this->l('Width'),
                        'name' => 'an_modal_cookie_width',
                        'suffix' => 'px',
                        'col' => 2,
                    ),
                    /*array(
                        'type' => 'number',
                        'required' => false,
                        'label' => $this->l('height'),
                        'name' => 'an_modal_cookie_height',
                        'suffix' => 'px',
                        'col' => 1,
                    ),*/
                    array(
                        'type' => 'select',
                        'label' => $this->l('Position'),
                        'name' => 'an_modal_cookie_position',
                        'required' => false,
                        'default_value' => (int)$this->context->country->id,
                        'options' => array(
                            'query' => array(
                                array('id' => 'bl', 'name' => $this->l('Bottom left')),
                                array('id' => 'br', 'name' => $this->l('Bottom right')),
                                array('id' => 'tl', 'name' => $this->l('Top left')),
                                array('id' => 'tr', 'name' => $this->l('Top right'))
                            ),
                            'id' => 'id',
                            'name' => 'name',
                        )
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
        if (Tools::isSubmit('submitan_cookie_popupModule')) {
            $languages = Language::getLanguages(false);
            $values = array();
        }

        foreach ($languages as $lang) {
            $values['an_modal_cookie_text'][$lang['id_lang']] = htmlentities(Tools::getValue('an_modal_cookie_text_' . $lang['id_lang']));
            $values['an_modal_cookie_accept'][$lang['id_lang']] = Tools::getValue('an_modal_cookie_accept_' . $lang['id_lang']);
            $values['an_modal_cookie_privacy'][$lang['id_lang']] = Tools::getValue('an_modal_cookie_privacy_' . $lang['id_lang']);
			$values['an_modal_cookie_privacy_link'][$lang['id_lang']] = Tools::getValue('an_modal_cookie_privacy_link_' . $lang['id_lang']);
        }

        $form = $this->getConfigForm();
        foreach ($form['form']['input'] as $input) {
            if (isset($input['required']) && $input['required']) {
                if (isset($values[$input['name']])) {
                    foreach ($languages as $lang) {
                        if (empty($values[$input['name']][$lang['id_lang']])) {
                            $message = (sizeof($languages) > 1) ? 'Field ' . html_entity_decode($input['label']) . ' (' . $lang['iso_code'] . ') is empty.' : 'Field ' . html_entity_decode($input['label']) . ' is empty.';
                            $this->context->controller->errors[] = $this->l($message);
                            return false;
                        }
                    }
                } elseif (empty(Tools::getValue($input['name']))) {
                    $this->context->controller->errors[] = $this->l('Field ' . html_entity_decode($input['label']) . ' is empty.');
                    return false;
                }
            }
        }

		$langError = false;
		foreach ($languages as $lang) {

			if (!preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i', Tools::getValue('an_modal_cookie_privacy_link_' . $lang['id_lang'])) && Tools::getValue('an_modal_cookie_privacy_link_' . $lang['id_lang']) != '#') {
				$this->context->controller->errors[] = $this->l($lang['name'] . ': Field "Privacy policy link" is required to be URL.');
				$langError = true;
			}
	
		}
		if ($langError){
			return false;
		}


		Configuration::updateValue('an_modal_cookie_text', $values['an_modal_cookie_text']);
		Configuration::updateValue('an_modal_cookie_accept', $values['an_modal_cookie_accept']);
		Configuration::updateValue('an_modal_cookie_privacy', $values['an_modal_cookie_privacy']);
		Configuration::updateValue('an_modal_cookie_privacy_link', $values['an_modal_cookie_privacy_link']);

		Configuration::updateValue('an_modal_cookie_opacity', Tools::getValue('an_modal_cookie_opacity'));
		Configuration::updateValue('an_modal_cookie_width', Tools::getValue('an_modal_cookie_width'));
		Configuration::updateValue('an_modal_cookie_height', Tools::getValue('an_modal_cookie_height'));
		Configuration::updateValue('an_modal_cookie_position', Tools::getValue('an_modal_cookie_position'));
		Configuration::updateValue('an_modal_cookie_text_color', Tools::getValue('an_modal_cookie_text_color'));
		Configuration::updateValue('an_modal_cookie_background', Tools::getValue('an_modal_cookie_background'));
		Configuration::updateValue('an_modal_cookie_links_color', Tools::getValue('an_modal_cookie_links_color'));

		$this->generateCSS();

		$this->context->controller->confirmations[] = $this->l(
			'The settings have been updated.'
		);

        return '';
	}

    /**
     *
     */
    public function hookHeader()
    {
		$this->context->controller->registerJavascript(
			"modules-cookiebanner-fe",
			"modules/{$this->name}/views/js/front.js",
			array('position' => AbstractAssetManager::DEFAULT_JS_POSITION, 'priority' => 200)
		);
		$this->context->controller->registerStylesheet(
			"modules-cookiebanner",
			'modules/' . $this->name . '/views/css/front.css',
			array('server' => 'local', 'priority' => 150)
		);
		$this->context->controller->registerStylesheet(
			"modules-cookiebanner-generated",
			'modules/' . $this->name . '/views/css/' . Configuration::get('an_cookie_banner_css'),
			array('server' => 'local', 'priority' => 150)
		);
      
        $lang = $this->context->language->id;
        $this->smarty->assign(array(
            'an_modal_cookie_text'=> html_entity_decode(Configuration::get('an_modal_cookie_text', $lang)),
            'an_modal_cookie_accept'=>Configuration::get('an_modal_cookie_accept', $lang),
            'an_modal_cookie_privacy'=>Configuration::get('an_modal_cookie_privacy', $lang),
            'an_modal_cookie_privacy_link'=>Configuration::get('an_modal_cookie_privacy_link', $lang),
        ));

        return $this->display(__FILE__, 'views/templates/front/cookie_banner.tpl');
    }

    public function hookBackOfficeHeader($params = null)
    {
        if (in_array($this->name, array(Tools::getValue('configure', ''), Tools::getValue('module_name', '')))) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
        }
    }

    protected function generateCSS()
    {
        $this->smarty->assign(array(
            'an_modal_cookie_opacity'=>Configuration::get('an_modal_cookie_opacity'),
            'an_modal_cookie_width'=>Configuration::get('an_modal_cookie_width'),
            'an_modal_cookie_height'=>Configuration::get('an_modal_cookie_height'),
            'an_modal_cookie_position'=>Configuration::get('an_modal_cookie_position'),
            'an_modal_cookie_text_color'=>Configuration::get('an_modal_cookie_text_color'),
            'an_modal_cookie_background'=>Configuration::get('an_modal_cookie_background'),
            'an_modal_cookie_links_color'=>Configuration::get('an_modal_cookie_links_color'),
        ));

        $this->killCSS();
        $filename = $this->generateFileName();
        Configuration::updateValue('an_cookie_banner_css', $filename);

        return file_put_contents($this->local_path.'views/css/' . $filename, $this->display($this->name, 'views/templates/front/css.tpl'));
    }

    protected static function generateFileName($format = 'css', $name = false)
    {
        return ($name !== false ? md5($name.Context::getContext()->shop->id_shop_group.Context::getContext()->shop->id ) : md5(rand(0, 100000))) . '.' . $format;
    }

    protected function killCSS()
    {
        @unlink($this->local_path.'views/css/' . Configuration::get('an_cookie_banner_css'));
    }

}
