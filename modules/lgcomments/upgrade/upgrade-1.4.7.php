<?php
/**
 *  Please read the terms of the CLUF license attached to this module(cf "licences" folder)
 *
 * @author    Línea Gráfica E.C.E. S.L.
 * @copyright Lineagrafica.es - Línea Gráfica E.C.E. S.L. all rights reserved.
 * @license   https://www.lineagrafica.es/licenses/license_en.pdf
 *            https://www.lineagrafica.es/licenses/license_es.pdf
 *            https://www.lineagrafica.es/licenses/license_fr.pdf
 */

function upgrade_module_1_4_7()
{
    $update1 = Configuration::updateValue('PS_LGCOMMENTS_STARDESIGN1', 'plain');
    $update2 = Configuration::updateValue('PS_LGCOMMENTS_STARDESIGN2', 'yellow');
    $update3 = Configuration::updateValue('PS_LGCOMMENTS_STARSIZE', '140');
    $update4 = Configuration::updateValue('PS_LGCOMMENTS_BOXES', '1');
    $create1 = Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_multistore` '.
        '(`id_shop` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id_shop`)) '.
        'ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb')
    );
    $shops = Db::getInstance()->executeS('
        SELECT `id_shop`
        FROM `'._DB_PREFIX_.'shop`
        ');
    foreach ($shops as $shop) {
        $update5 = Db::getInstance()->Execute('
            INSERT INTO `'._DB_PREFIX_.'lgcomments_multistore`
            VALUES
            ('.(int)$shop['id_shop'].')');
    }
    return $update1 && $update2 && $update3 && $create1 && $update4 && $update5;
}
