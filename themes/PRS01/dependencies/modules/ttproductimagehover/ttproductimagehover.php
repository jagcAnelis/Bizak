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

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;

class TtProductImageHover extends Module
{
    protected $templateFile;
	
    public function __construct()
    {
        $this->name = 'ttproductimagehover';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TemplateTrip';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->getTranslator()->trans('TT - Product image hover', array(), 'Modules.TTproductimagehover.Admin');
        $this->description = $this->getTranslator()->trans('Display second product image on product hover', array(), 'Modules.TTproductimagehover.Admin');
        $this->ps_versions_compliancy = array('min' => '1.7.0.0', 'max' => _PS_VERSION_);
		$this->templateFile = 'module:ttproductimagehover/views/templates/hook/ttproductimagehover.tpl';
    }
    public function install()
    {
         return parent::install() && $this->registerHook('displayTtproductImageHover');
    }
    public function hookDisplayTtproductImageHover($params)
    {
            $id_lang = $this->context->language->id;
            $id_shop = $this->context->shop->id;
            $obj = new Product((int) ($params['id_product']), false, $id_lang, $id_shop);
            $images = $obj->getImages($this->context->language->id);
            $total_image = count($images);
            $_images = array();
            if (!empty($images)) {
                foreach ($images as $image) {
                    if (!$image['cover']) {
                        $_images[] = $obj->id . '-' . $image['id_image'];
                    }
                }
            }
            if ($total_image > 1) {
                $this->smarty->assign(array(
                'home_image' => $this->context->link->getImageLink($obj->link_rewrite, $_images[0], $params['home']),
                'large_image' => $this->context->link->getImageLink($obj->link_rewrite, $_images[0], $params['large'])
                 ));
        }
        if ($total_image > 1) {
            return $this->display(__FILE__, 'ttproductimagehover.tpl');
        }
    }
	public function renderWidget(
        $hookName = null,
        array $configuration = array()
    ) {
        $cacheId = $this->getCacheId('ttproductimagehover');
        $isCached = $this->isCached($this->templateFile, $cacheId);

        if (!$isCached) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }
        return $this->fetch($this->templateFile, $cacheId);
    }
}
