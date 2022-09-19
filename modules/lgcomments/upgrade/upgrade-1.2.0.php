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

function upgrade_module_1_2_0()
{
    $add1    = false;
    $add2    = false;
    $add3    = false;
    $add4    = false;

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_productcomments LIKE "title"')) {
        $add1 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_productcomments '.
            'ADD title text NOT NULL'
        );
    } else {
        $add1 = true;
    }

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_productcomments LIKE "answer"')) {
        $add2 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_productcomments '.
            'ADD answer text'
        );
    } else {
        $add2 = true;
    }

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_storecomments LIKE "title"')) {
        $add3 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_storecomments '.
            'ADD title text NOT NULL'
        );
    } else {
        $add3 = true;
    }

    if (!Db::getInstance()->ExecuteS('SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_storecomments LIKE "answer"')) {
        $add4 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_storecomments '.
            'ADD answer text'
        );
    } else {
        $add4 = true;
    }

    return $add1 && $add2 && $add3 && $add4;
}
