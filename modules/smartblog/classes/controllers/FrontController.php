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

class SmartblogModuleFrontController extends ModuleFrontController
{
    public $ssl = false;
    public function initContent()
    {
        $meta = array();
        parent::initContent();
        if ($id_category = Tools::getvalue('id_category') && Tools::getvalue('id_category') != null) {
            $this->context->smarty->assign(BlogCategory::getMetaByCategory(Tools::getvalue('id_category')));
        }
        if ($id_post = Tools::getvalue('id_post')  && Tools::getvalue('id_post') != null) {
            $this->context->smarty->assign(SmartBlogPost::getPostMetaByPost(Tools::getvalue('id_post')));
        }
        if (Tools::getvalue('id_category') == null  && Tools::getvalue('id_post') == null) {
            $meta['meta_title'] = Configuration::get('smartblogmetatitle');
            $meta['meta_description'] = Configuration::get('smartblogmetadescrip');
            $meta['meta_keywords'] = Configuration::get('smartblogmetakeyword');
            $this->context->smarty->assign($meta);
        }
        if (Configuration::get('smartshowcolumn') == 0) {
            $this->context->smarty->assign(array(
                'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft'),
                'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
            ));
        } elseif (Configuration::get('smartshowcolumn') == 1) {
            $this->context->smarty->assign(array(
                'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft')
            ));
        } elseif (Configuration::get('smartshowcolumn') == 2) {
            $this->context->smarty->assign(array(
                'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
            ));
        } elseif (Configuration::get('smartshowcolumn') == 3) {
            $this->context->smarty->assign(array());
        } else {
            $this->context->smarty->assign(array(
                'HOOK_LEFT_COLUMN' => Hook::exec('displaySmartBlogLeft'),
                'HOOK_RIGHT_COLUMN' => Hook::exec('displaySmartBlogRight')
            ));
        }
    }
}
