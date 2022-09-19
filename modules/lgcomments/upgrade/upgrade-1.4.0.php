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

function upgrade_module_1_4_0($object)
{
    $add1    = false;
    $add2    = false;
    $update1 = false;
    $update2 = false;

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_orders LIKE "sent"')) {
        $add1 = Db::getInstance()->Execute('ALTER TABLE '._DB_PREFIX_.'lgcomments_orders ADD sent int(11) NOT NULL');
    } else {
        $add1 = true;
    }

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_orders LIKE "date_email2"')) {
        $add2 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_orders '.
            'ADD date_email2 datetime NOT NULL'
        );
    } else {
        $add2 = true;
    }

    if (!(int)Tools::getValue('PS_LGCOMMENTS_EMAIL_TWICE', 0)) {
        $update1 = Configuration::updateValue('PS_LGCOMMENTS_EMAIL_TWICE', '0');
    } else {
        $update1 = true;
    }
    
    if (!(int)Tools::getValue('PS_LGCOMMENTS_DAYS_AFTER', 0)) {
        $update2 = Configuration::updateValue('PS_LGCOMMENTS_DAYS_AFTER', '10');
    } else {
        $update2 = true;
    }

    return $add1 && $add2 && $update1 && $update2 && $object->registerHook('displayCustomerAccount');
}
