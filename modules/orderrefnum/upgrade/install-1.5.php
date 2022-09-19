<?php
/**
* 2016 Madman
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
*  @author    Madman
*  @copyright 2016 Madman
*  @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

function upgrade_module_1_5()
{
    $config = array(
        0 => 'PS_USE_REF_NUM',
        1 => 'PS_REF_NUM_LENGTH',
        2 => 'PS_REF_NUM',
        3 => 'PS_REF_NUM_INCREASE',
        4 => 'PS_REF_NUM_MIN_INCREASE',
        5 => 'PS_USE_REF_NUM_RANDOM',
        6 => 'PS_REF_PREFIX_ON',
        7 => 'PS_REF_PREFIX',
    );
    if (Configuration::getGlobalValue('PS_MOD_REF_GLOBAL')) {
        foreach ($config as $key) {
            $opt = Configuration::getGlobalValue($key);
            if ($opt) {
                Configuration::deleteByName($key);
                $shops = Shop::getShops();
                foreach ($shops as $id_shop => $shop) {
                    Configuration::updateValue($key, $opt, null, $shop['id_shop_group'], $id_shop);
                }
            }
        }
    }
    Configuration::deleteByName('PS_MOD_REF_GLOBAL');
    return true; // Return true if success.
}
