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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once(_PS_MODULE_DIR_.'smartblog/classes/SmartBlogPost.php');
require_once(_PS_MODULE_DIR_.'smartblog/smartblog.php');
class SmartblogHomeLatestnews extends Module
{
	protected $templateFile;
    public function __construct()
    {
        $this->name = 'smartbloghomelatestnews';
        $this->tab = 'front_office_features';
        $this->version = '2.0.1';
        $this->bootstrap = true;
        $this->author = 'SmartDataSoft';
        $this->secure_key = Tools::encrypt($this->name);
        parent::__construct();
        $this->displayName = $this->l('SmartBlog Home Latest');
        $this->description = $this->l('The Most Powerfull Presta shop Blog  Modules tag - by smartdatasoft');
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details ?');
		$this->templateFile = 'module:smartbloghomelatestnews/views/templates/hook/smartbloghomelatestnews.tpl';
    }

    public function install()
    {
        $languages = $this->context->language->getLanguages();
        $blockTitle = array();

        foreach ($languages as $lang) {
            $blockTitle[$lang['id_lang']] = 'Latest News';
        }
        if (!parent::install()
            || !$this->registerHook('displayHome')
            || !$this->registerHook('actionsbdeletepost')
            || !$this->registerHook('actionsbnewpost')
            || !$this->registerHook('actionsbupdatepost')
            || !$this->registerHook('actionsbtogglepost')
        ) {
            return false;
        }
        Configuration::updateGlobalValue('smartshowhometitle', $blockTitle);
        Configuration::updateGlobalValue('smartshowhomepost', 6);
        return true;
    }

    public function uninstall()
    {
        $this->DeleteCache();
        if (!parent::uninstall() || !Configuration::deleteByName('smartshowhometitle') || !Configuration::deleteByName('smartshowhomepost')) {
            return false;
        }
        return true;
    }

    public function hookDisplayHome($params)
    {
        $id_lang = (int)Context::getContext()->language->id;
        $view_data = array();
        if (Module::isInstalled('smartblog') != 1) {
            $this->smarty->assign(array(
                'smartmodname' => $this->name
                ));
            return $this->display(__FILE__, 'views/templates/front/install_required.tpl');
        } else {
                $view_data['posts'] = SmartBlogPost::GetPostLatestHome(Configuration::get('smartshowhomepost'));
                $this->smarty->assign(array(
                    'view_data' => $view_data['posts'],
                    'smartshowhometitle' => Configuration::get('smartshowhometitle', $id_lang),
                    ));
            return $this->display(__FILE__, 'views/templates/front/smartblog_latest_news.tpl', $this->getCacheId());
        }
    }

    public function hookDisplayHomeBottom($params)
    {
        if ($this->context->controller->php_self == 'index') {
            return $this->hookDisplayHome($params);
        }
    }

    public function getContent()
    {
        $html = '';
        $blockTitle = array();
        $languages = $this->context->language->getLanguages();
        if (Tools::isSubmit('save'.$this->name)) {
            foreach ($languages as $lang) {
                $blockTitle[$lang['id_lang']] = Tools::getValue('smartshowhometitle_'.$lang['id_lang']);
            }
            Configuration::updateValue('smartshowhometitle', $blockTitle);
            Configuration::updateValue('smartshowhomepost', Tools::getvalue('smartshowhomepost'));
            $html = $this->displayConfirmation($this->l('The settings have been updated successfully.'));
            $helper = $this->SettingForm();
            $html .= $helper->generateForm($this->fields_form);
            return $html;
        } else {
            $helper = $this->SettingForm();
            $html .= $helper->generateForm($this->fields_form);
            return $html;
        }
    }

    public function SettingForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('General Setting'),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Title'),
                    'name' => 'smartshowhometitle',
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Number of posts to dispay in Lastest News'),
                    'name' => 'smartshowhomepost',
                    'size' => 15,
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }
        $helper->toolbar_btn = array(
            'save' => array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save'.$this->name.'token=' . Tools::getAdminTokenLite('AdminModules'),
            )
        );
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'save'.$this->name;

        $languages = $this->context->language->getLanguages();
        foreach ($languages as $lang) {
            $helper->fields_value['smartshowhometitle'][$lang['id_lang']] = Configuration::get('smartshowhometitle', $lang['id_lang']);
        }

        $helper->fields_value['smartshowhomepost'] = Configuration::get('smartshowhomepost');
        return $helper;
    }

    public function DeleteCache()
    {
        return $this->_clearCache('smartblog_latest_news.tpl', $this->getCacheId());
    }

    public function hookactionsbdeletepost($params)
    {
        return $this->DeleteCache();
    }

    public function hookactionsbnewpost($params)
    {
        return $this->DeleteCache();
    }

    public function hookactionsbupdatepost($params)
    {
        return $this->DeleteCache();
    }

    public function hookactionsbtogglepost($params)
    {
        return $this->DeleteCache();
    }
	 
	public function renderWidget(
        $hookName = null,
        array $configuration = array()
    ) {
        $cacheId = $this->getCacheId('smartbloghomelatestnews');
        $isCached = $this->isCached($this->templateFile, $cacheId);

        if (!$isCached) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }
        return $this->fetch($this->templateFile, $cacheId);
    } 
}
