<?php
/*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Og_Banner_rightSmall extends Module implements WidgetInterface
{
    private $templateFile;

	public function __construct()
	{
		$this->name = 'og_banner_rightsmall';
		$this->version = '2.1.0';
		$this->author = 'Ogilvy';
		$this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->trans('Banner Small Right', array(), 'Modules.Banner.Admin');
        $this->description = $this->trans('Displays a banner on your shop.', array(), 'Modules.Banner.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:og_banner_rightsmall/og_banner_rightsmall.tpl';
    }

    public function install()
    {
        return (parent::install() &&
            $this->registerHook('displayHome') &&
            $this->registerHook('actionObjectLanguageAddAfter') &&
            $this->installFixtures() &&
            $this->disableDevice(Context::DEVICE_MOBILE));
    }

    public function hookActionObjectLanguageAddAfter($params)
    {
        return $this->installFixture((int)$params['object']->id, Configuration::get('BANNER_IMG', (int)Configuration::get('PS_LANG_DEFAULT')));
    }

    protected function installFixtures()
    {
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $this->installFixture((int)$lang['id_lang'], 'sale70.png');
        }

        return true;
    }

    protected function installFixture($id_lang, $image = null)
    {
        $values['BANNER_RIGHTSMALL_IMG'][(int)$id_lang] = $image;
        $values['BANNER_RIGHTSMALL_LINK'][(int)$id_lang] = '';
        $values['BANNER_RIGHTSMALL_TEXT'][(int)$id_lang] = '';
        $values['BANNER_RIGHTSMALL_SHOW_BTN'][(int)$id_lang] = '';
        $values['BANNER_RIGHTSMALL_DESC'][(int)$id_lang] = '';

        Configuration::updateValue('BANNER_RIGHTSMALL_IMG', $values['BANNER_RIGHTSMALL_IMG']);
        Configuration::updateValue('BANNER_RIGHTSMALL_LINK', $values['BANNER_RIGHTSMALL_LINK']);
        Configuration::updateValue('BANNER_RIGHTSMALL_TEXT', $values['BANNER_RIGHTSMALL_TEXT']);
        Configuration::updateValue('BANNER_RIGHTSMALL_SHOW_BTN', $values['BANNER_RIGHTSMALL_SHOW_BTN']);
        Configuration::updateValue('BANNER_RIGHTSMALL_DESC', $values['BANNER_RIGHTSMALL_DESC']);
    }

    public function uninstall()
    {
        Configuration::deleteByName('BANNER_RIGHTSMALL_IMG');
        Configuration::deleteByName('BANNER_RIGHTSMALL_LINK');
        Configuration::deleteByName('BANNER_RIGHTSMALL_TEXT');
        Configuration::deleteByName('BANNER_RIGHTSMALL_SHOW_BTN');
        Configuration::deleteByName('BANNER_RIGHTSMALL_DESC');

        return parent::uninstall();
    }

    private function uploadDesktopImage($lang, $values) {
        if (isset($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']])
            && isset($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['tmp_name'])
            && !empty($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']], 4000000)) {
                return $error;
            } else {
                $ext = substr($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['name'], strrpos($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['name'], '.') + 1);
                $file_name = md5($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['name']).'.'.$ext;

                if (!move_uploaded_file($_FILES['BANNER_RIGHTSMALL_IMG_'.$lang['id_lang']]['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file_name)) {
                    return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                } else {
                    if (Configuration::hasContext('BANNER_RIGHTSMALL_IMG', $lang['id_lang'], Shop::getContext())
                        && Configuration::get('BANNER_RIGHTSMALL_IMG', $lang['id_lang']) != $file_name) {
                        @unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . Configuration::get('BANNER_RIGHTSMALL_IMG', $lang['id_lang']));
                    }

                    $values['BANNER_RIGHTSMALL_IMG'][$lang['id_lang']] = $file_name;
                }
            }

            $update_images_values = true;

            if ($update_images_values) {
                Configuration::updateValue('BANNER_RIGHTSMALL_IMG', $values['BANNER_RIGHTSMALL_IMG']);
            }
        }
    }

    private function uploadMobileImage($lang, $values) {
        if (isset($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']])
            && isset($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['tmp_name'])
            && !empty($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']], 4000000)) {
                return $error;
            } else {
                $ext = substr($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['name'], strrpos($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['name'], '.') + 1);
                $file_name = md5($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['name']).'.'.$ext;

                if (!move_uploaded_file($_FILES['BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang']]['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$file_name)) {
                    return $this->displayError($this->trans('An error occurred while attempting to upload the file.', array(), 'Admin.Notifications.Error'));
                } else {
                    if (Configuration::hasContext('BANNER_RIGHTSMALL_IMG_MOBILE', $lang['id_lang'], Shop::getContext())
                        && Configuration::get('BANNER_RIGHTSMALL_IMG_MOBILE', $lang['id_lang']) != $file_name) {
                        @unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . Configuration::get('BANNER_RIGHTSMALL_IMG_MOBILE', $lang['id_lang']));
                    }

                    $values['BANNER_RIGHTSMALL_IMG_MOBILE'][$lang['id_lang']] = $file_name;
                }
            }

            $update_images_values = true;

            if ($update_images_values) {
                Configuration::updateValue('BANNER_RIGHTSMALL_IMG_MOBILE', $values['BANNER_RIGHTSMALL_IMG_MOBILE']);
            }
        }
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitStoreConf')) {
            $languages = Language::getLanguages(false);
            $values = array();


            foreach ($languages as $lang) {
                $this->uploadDesktopImage($lang, $values);
                $this->uploadMobileImage($lang, $values);

                $values['BANNER_RIGHTSMALL_LINK'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_LINK_'.$lang['id_lang']);
                $values['BANNER_RIGHTSMALL_DESC'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_DESC_'.$lang['id_lang']);
                $values['BANNER_RIGHTSMALL_TEXT'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_TEXT_'.$lang['id_lang']);
                $values['BANNER_RIGHTSMALL_SHOW_BTN'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_SHOW_BTN_'.$lang['id_lang']);
            }


            Configuration::updateValue('BANNER_RIGHTSMALL_LINK', $values['BANNER_RIGHTSMALL_LINK']);
            Configuration::updateValue('BANNER_RIGHTSMALL_DESC', $values['BANNER_RIGHTSMALL_DESC']);
            Configuration::updateValue('BANNER_RIGHTSMALL_TEXT', $values['BANNER_RIGHTSMALL_TEXT']);
            Configuration::updateValue('BANNER_RIGHTSMALL_SHOW_BTN', $values['BANNER_RIGHTSMALL_SHOW_BTN']);

            $this->_clearCache($this->templateFile);

            return $this->displayConfirmation($this->trans('The settings have been updated.', array(), 'Admin.Notifications.Success'));
        }

        return '';
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->trans('Settings', array(), 'Admin.Global'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'file_lang',
                        'label' => 'Banner para desktop (1115 x 407)',
                        'name' => 'BANNER_RIGHTSMALL_IMG',
                        'desc' => $this->trans('Upload an image for your top banner. The recommended dimensions are 1110 x 214px if you are using the default theme.', array(), 'Modules.Banner.Admin'),
                        'lang' => true,
                    ),
                    array(
                        'type' => 'file_lang',
                        'label' => 'Banner para mobile (414 x 543)',
                        'name' => 'BANNER_RIGHTSMALL_IMG_MOBILE',
                        'desc' => $this->trans('Upload an image for your top banner. The recommended dimensions are 1110 x 214px if you are using the default theme.', array(), 'Modules.Banner.Admin'),
                        'lang' => true,
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->trans('Banner Link', array(), 'Modules.Banner.Admin'),
                        'name' => 'BANNER_RIGHTSMALL_LINK',
                        'desc' => $this->trans('Enter the link associated to your banner. When clicking on the banner, the link opens in the same window. If no link is entered, it redirects to the homepage.', array(), 'Modules.Banner.Admin')
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Texto del banner',
                        'name' => 'BANNER_RIGHTSMALL_TEXT',
                        'desc' => $this->trans('Enter the link associated to your banner. When clicking on the banner, the link opens in the same window. If no link is entered, it redirects to the homepage.', array(), 'Modules.Banner.Admin')
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => 'Mostrar botón ver más (poner 0 o 1)',
                        'name' => 'BANNER_RIGHTSMALL_SHOW_BTN',
                        'desc' => $this->trans('Enter the link associated to your banner. When clicking on the banner, the link opens in the same window. If no link is entered, it redirects to the homepage.', array(), 'Modules.Banner.Admin')
                    ),
                    array(
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->trans('Banner description', array(), 'Modules.Banner.Admin'),
                        'name' => 'BANNER_RIGHTSMALL_DESC',
                        'desc' => $this->trans('Please enter a short but meaningful description for the banner.', array(), 'Modules.Banner.Admin')
                    )
                ),
                'submit' => array(
                    'title' => $this->trans('Save', array(), 'Admin.Actions')
                )
            ),
        );

        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitStoreConf';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = array();

        foreach ($languages as $lang) {
            $fields['BANNER_RIGHTSMALL_IMG'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_IMG_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_IMG', $lang['id_lang']));
            $fields['BANNER_RIGHTSMALL_LINK'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_LINK_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_LINK', $lang['id_lang']));
            $fields['BANNER_RIGHTSMALL_DESC'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_DESC_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_DESC', $lang['id_lang']));
            $fields['BANNER_RIGHTSMALL_SHOW_BTN'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_SHOW_BTN_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_SHOW_BTN', $lang['id_lang']));
            $fields['BANNER_RIGHTSMALL_IMG_MOBILE'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_IMG_MOBILE_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_IMG_MOBILE', $lang['id_lang']));
            $fields['BANNER_RIGHTSMALL_TEXT'][$lang['id_lang']] = Tools::getValue('BANNER_RIGHTSMALL_TEXT_'.$lang['id_lang'], Configuration::get('BANNER_RIGHTSMALL_TEXT', $lang['id_lang']));
        }

        return $fields;
    }

    public function renderWidget($hookName, array $params)
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('ps_banner'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $params));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('ps_banner'));
    }

    public function getWidgetVariables($hookName, array $params)
    {
        $imgnamedesktop = Configuration::get('BANNER_RIGHTSMALL_IMG', $this->context->language->id);

        if ($imgnamedesktop && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$imgnamedesktop)) {
            $this->smarty->assign('banner_img', $this->context->link->protocol_content . Tools::getMediaServer($imgnamedesktop) . $this->_path . 'img/' . $imgnamedesktop);
        }

        $imgnamemobile = Configuration::get('BANNER_RIGHTSMALL_IMG_MOBILE', $this->context->language->id);

        if ($imgnamemobile && file_exists(_PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.$imgnamemobile)) {
            $this->smarty->assign('banner_img_mobile', $this->context->link->protocol_content . Tools::getMediaServer($imgnamemobile) . $this->_path . 'img/' . $imgnamemobile);
        }

        $banner_link = Configuration::get('BANNER_RIGHTSMALL_LINK', $this->context->language->id);
        if (!$banner_link) {
            $banner_link = $this->context->link->getPageLink('index');
        }

        return array(
            'banner_link' => Configuration::get('BANNER_RIGHTSMALL_LINK', $this->context->language->id),
            'banner_desc' => Configuration::get('BANNER_RIGHTSMALL_DESC', $this->context->language->id),
            'banner_text' => Configuration::get('BANNER_RIGHTSMALL_TEXT', $this->context->language->id),
            'banner_button' => Configuration::get('BANNER_RIGHTSMALL_SHOW_BTN', $this->context->language->id)
        );
    }

    private function updateUrl($link)
    {
        if (substr($link, 0, 7) !== "http://" && substr($link, 0, 8) !== "https://") {
            $link = "http://" . $link;
        }

        return $link;
    }
}
