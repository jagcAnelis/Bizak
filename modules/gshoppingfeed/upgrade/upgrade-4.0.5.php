<?php
/**
 * 2020 Terranet
 *
 * NOTICE OF LICENSE
 *
 * @author    Terranet
 * @copyright 2020 Terranet
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_4_0_5($module)
{
    if (Db::getInstance()->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'gshoppingfeed` LIKE \'google_product_category_rewrite\'') == false) {
        Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'gshoppingfeed` ADD `google_product_category_rewrite` TINYINT NOT NULL DEFAULT 0 AFTER `updated_at`');
    }

    return $module;
}
