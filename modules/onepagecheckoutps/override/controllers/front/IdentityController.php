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

class IdentityController extends IdentityControllerCore
{
    public $opc = false;

    public function init()
    {
        $opc = Module::getInstanceByName('onepagecheckoutps');
        if (Validate::isLoadedObject($opc) && $opc->core->isModuleActive($opc->name)) {
            if ($opc->core->isVisible() && $opc->checkCustomerAccessToModule()) {
                if ((bool) Configuration::get('OPC_REPLACE_IDENTITY_CONTROLLER')) {
                    $this->opc = $opc;
                }
            }
        }

        parent::init();
    }

    public function initContent()
    {
        if ($this->opc) {
            FrontController::initContent();
            $this->opc->initContentRegisterControllerOPC($this, $this->context->controller->php_self);

            return;
        }

        parent::initContent();
    }

    public function setMedia()
    {
        FrontController::setMedia();
        if ($this->opc) {
            $this->opc->getMediaFront();
        }
    }
}
