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

namespace OnePageCheckoutPS\Hook;

use Validate;

class HookActionOpcValidateVatNumber extends AbstractHook
{
    private function validateVatNumber($vatNumber, $module)
    {
        if (Validate::isLoadedObject($module) && $module->active) {
            switch ($module->name) {
                case 'checkvat':
                    if (version_compare($module->version, '1.7.11', '>=')) {
                        // include_once(_PS_MODULE_DIR_.'checkvat/classes/CV.php');
                        if (!\CV::verificationVATNumber($vatNumber)) {
                            return false;
                        }
                    } else {
                        $verifications = $module->verificationVATNumber($vatNumber);
                        if (!$verifications) {
                            return false;
                        }
                    }
                    break;
            }
        }

        return true;
    }

    protected function executeRun()
    {
        $parameters = $this->getParameters();
        $vatNumber = $parameters['vatNumber'];
        $module = $parameters['module'];

        return $this->validateVatNumber($vatNumber, $module);
    }
}
