<?php
/**
 * We offer the best and most useful modules PrestaShop and modifications for your online store.
 *
 * We are experts and professionals in PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses.
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 * @author    PresTeamShop SAS (Registered Trademark) <info@presteamshop.com>
 * @copyright 2011-2022 PresTeamShop SAS, All rights reserved.
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *
 * @category  PrestaShop
 * @category  Module
 */

class OnePageCheckoutPSCronModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        parent::init();
    }

    public function initContent()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        $this->display_header = false;
        $this->display_footer = false;

        if (!$this->module->core->isModuleActive($this->module->name)
            || !$this->module->core->isVisible()
            || !$this->module->core->checkModulePTS()
        ) {
            return false;
        }

        if (!Tools::isSubmit('token')
            || Tools::encrypt($this->module->name . '/index') != Tools::getValue('token')
            || !Module::isInstalled($this->module->name)
        ) {
            die('Bad token');
        }

        parent::initContent();

        $result = $this->module->deleteEmptyAddressesOPC();

        if (isset($result['message'])) {
            die($result['message']);
        }
    }
}
