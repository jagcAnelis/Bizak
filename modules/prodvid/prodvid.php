<?php
/**
 * 2007-2017 PrestaShop
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
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class Prodvid extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'prodvid';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'Active Design';
        $this->need_instance = 0;
        $this->author_address = '0xc0D7cE57752e47305707d7174B9686C0Afb229c3';
        $this->module_key = 'e728af7ba4895443c421376bcd862188';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Video Pro');
        $this->description = $this->l('Embed youtube, videos to any product');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall the module?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('PRODVID_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('actionProductSave') &&
            $this->registerHook('actionProductDelete') &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooterProduct') &&
            $this->registerHook('displayHeader') &&
            $this->registerHook('displayLeftColumnProduct') &&
            $this->registerHook('displayRightColumnProduct') &&
            $this->registerHook('displayProductAdditionalInfo') &&
            $this->registerHook('displayProductButtons') &&
            $this->registerHook('displayAdminProductsExtra');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PRODVID_LIVE_MODE');

        $arr = unserialize(Configuration::get('prodvid_list'));

        for ($i = 0; $i < count($arr); $i++) {
            Configuration::deleteByName($arr[$i]);
        }
        Configuration::deleteByName('prodvid_list');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output;
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
        } else {
            $this->context->controller->addJS($this->_path . 'views/js/back_old.js');
        }

        $this->context->controller->addCSS($this->_path . 'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            $this->context->controller->addJS($this->_path . '/views/js/front.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        } else {
            $this->context->controller->addJS($this->_path . '/views/js/front_old.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front_old.css');
        }
    }

    public function hookDisplayFooterProduct()
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) < '1.7') {
            $out = array();
            $data = array();
            if (Configuration::hasKey('prodvid_' . Tools::getValue('id_product'))) {
                $data = unserialize(Configuration::get('prodvid_' . Tools::getValue('id_product')));
                for ($i = 0; $i < count($data); $i++) {
                    $data[$i]['prodvid_embed_code'] =
                        htmlspecialchars_decode(Tools::htmlentitiesDecodeUTF8($data[$i]['prodvid_embed_code']));
                    if ($data[$i]['prodvid_position'] == 4 && $data[$i]['prodvid_enabled']) {
                        array_push($out, $data[$i]);
                    }
                }

                $this->context->smarty->assign(
                    array(
                        'prodvid_data' => $out,
                    )
                );
                if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
                    return $this->display(__FILE__, 'prodvid_front.tpl');
                } else {
                    return $this->display(__FILE__, 'prodvid_front_old.tpl');
                }
            }
        }
    }

    public function hookDisplayHeader()
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            $this->context->controller->addJS($this->_path . '/views/js/front.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front.css');
        } else {
            $this->context->controller->addJS($this->_path . '/views/js/front_old.js');
            $this->context->controller->addCSS($this->_path . '/views/css/front_old.css');
        }
    }

    public function hookDisplayLeftColumnProduct()
    {
        $out = array();
        $data = array();
        if (Configuration::hasKey('prodvid_' . Tools::getValue('id_product'))) {
            $data = unserialize(Configuration::get('prodvid_' . Tools::getValue('id_product')));
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['prodvid_embed_code'] =
                    htmlspecialchars_decode(Tools::htmlentitiesDecodeUTF8($data[$i]['prodvid_embed_code']));
                if ($data[$i]['prodvid_position'] == 1 && $data[$i]['prodvid_enabled']) {
                    array_push($out, $data[$i]);
                }
            }

            $this->context->smarty->assign(
                array(
                    'prodvid_data' => $out,
                )
            );
            if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
                return $this->display(__FILE__, 'prodvid_front.tpl');
            } else {
                return $this->display(__FILE__, 'prodvid_front_old.tpl');
            }
        }
    }

    public function hookDisplayRightColumnProduct()
    {
        $out = array();
        $data = array();
        if (Configuration::hasKey('prodvid_' . Tools::getValue('id_product'))) {
            $data = unserialize(Configuration::get('prodvid_' . Tools::getValue('id_product')));
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['prodvid_embed_code'] =
                    htmlspecialchars_decode(Tools::htmlentitiesDecodeUTF8($data[$i]['prodvid_embed_code']));
                if ($data[$i]['prodvid_position'] == 2 && $data[$i]['prodvid_enabled'] ||
                    $data[$i]['prodvid_position'] == 5 && $data[$i]['prodvid_enabled']) {
                    array_push($out, $data[$i]);
                }
            }

            $this->context->smarty->assign(
                array(
                    'prodvid_data' => $out,
                )
            );
            if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
                return $this->display(__FILE__, 'prodvid_front.tpl');
            } else {
                return $this->display(__FILE__, 'prodvid_front_old.tpl');
            }
        }
    }

    public function hookActionProductSave($params)
    {
        $id = $params['id_product'];
        $data = array();
        $prodvid_title_arr = null;
        $prodvid_embed_code_arr = null;
        $prodvid_position_arr = null;
        $prodvid_enabled_arr = null;

        $prodvid_title_arr = Tools::getValue('PRODVID_TITLE');
        $prodvid_embed_code_arr = Tools::getValue('PRODVID_EMBED_CODE');
        $prodvid_position_arr = Tools::getValue('PRODVID_POSITION');
        $prodvid_enabled_arr = Tools::getValue('PRODVID_ENABLED');

        if($prodvid_title_arr!=false){
            for ($i = 0; $i < count($prodvid_title_arr); $i++) {
                if (!Validate::isGenericName($prodvid_title_arr[$i])) {
                    $prodvid_title_arr[$i] = "";
                }
                if (!Validate::isCleanHtml($prodvid_embed_code_arr[$i], true)) {
                    $prodvid_embed_code_arr[$i] = "";
                }
                if (!Validate::isUnsignedInt($prodvid_position_arr[$i])) {
                    $prodvid_position_arr[$i] = "";
                }
                if (!Validate::isUnsignedInt($prodvid_position_arr[$i])) {
                    $prodvid_position_arr[$i] = "";
                }
                $arr = array(
                    'prodvid_title' => $prodvid_title_arr[$i],
                    'prodvid_embed_code' =>
                        htmlspecialchars(Tools::safeOutput(str_replace("\n", "", $prodvid_embed_code_arr[$i]), true)),
                    'prodvid_position' => (int)$prodvid_position_arr[$i],
                    'prodvid_enabled' => (int)$prodvid_enabled_arr[$i]
                );
                if($prodvid_embed_code_arr[$i] == "")
                    continue;
                array_push($data, $arr);
            }
        }

        Configuration::updateValue('prodvid_' . $id, serialize($data), true);
        $this->listUpdate('prodvid_' . $id);
    }

    private function listUpdate($str)
    {
        $data = array();
        if (!Configuration::hasKey('prodvid_list')) {
            array_push($data, $str);
            Configuration::updateValue('prodvid_list', serialize($data));
            return true;
        } else {
            $data = unserialize(Configuration::get('prodvid_list'));
            array_push($data, $str);
            Configuration::updateValue('prodvid_list', serialize($data));
            return false;
        }
    }

    public function hookActionProductDelete($params)
    {
        Configuration::deleteByName('prodvid_' . $params['id_product']);
    }

    public function hookDisplayAdminProductsExtra($params)
    {

        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            $id = $params['id_product'];
        } else {
            $id = Tools::getValue('id_product');
        }
        $data = array();
        if (Configuration::hasKey('prodvid_' . $id)) {
            $data = unserialize(Configuration::get('prodvid_' . $id));
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['prodvid_embed_code'] =
                    htmlspecialchars_decode(Tools::htmlentitiesDecodeUTF8($data[$i]['prodvid_embed_code']));
            }
        }

        $this->context->smarty->assign(
            array(
                'prodvid_data' => $data,
            )
        );
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            return $this->display(__FILE__, 'prodvid_product.tpl');
        } else {
            return $this->display(__FILE__, 'prodvid_product_old.tpl');
        }
    }

    public function hookDisplayProductButtons($params)
    {
        return $this->hookDisplayProductAdditionalInfo($params);
    }

    public function hookDisplayProductAdditionalInfo($params)
    {
        if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
            $id = $params["product"]["id_product"];
        } else {
            $id = $params["product"]->id;
        }
        $out = array();
        $data = array();
        if (Configuration::hasKey('prodvid_' . $id)) {
            $data = unserialize(Configuration::get('prodvid_' . $id));
            for ($i = 0; $i < count($data); $i++) {
                $data[$i]['prodvid_embed_code'] =
                    htmlspecialchars_decode(Tools::htmlentitiesDecodeUTF8($data[$i]['prodvid_embed_code']));
                if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
                    if ($data[$i]['prodvid_enabled']) {
                        array_push($out, $data[$i]);
                    }
                } else {
                    if ($data[$i]['prodvid_enabled'] && $data[$i]['prodvid_position'] == 3) {
                        array_push($out, $data[$i]);
                    }
                }
            }
            $this->context->smarty->assign(
                array(
                    'prodvid_data' => $out,
                )
            );
            if (Tools::substr(_PS_VERSION_, 0, 3) == '1.7') {
                return $this->display(__FILE__, 'prodvid_front.tpl');
            } else {
                return $this->display(__FILE__, 'prodvid_front_old.tpl');
            }
        }
    }
}
