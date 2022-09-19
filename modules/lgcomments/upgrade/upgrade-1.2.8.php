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

function upgrade_module_1_2_8()
{
    $create = Db::getInstance()->Execute(
        'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_customergroups` '.
        '(`id_customer_group` int(11) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id_customer_group`)) '.
        'ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb')
    );
    return $create;
}
