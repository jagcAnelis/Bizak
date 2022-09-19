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

function upgrade_module_1_3_4()
{
    $rename1 = false;
    $rename2 = false;

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_storecomments '.
        'LIKE "status_comment"'
    )) {
        $rename1 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_storecomments '.
            'CHANGE status_comment active int(1) NOT NULL'
        );
    } else {
        $rename1 = true;
    }

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_productcomments '.
        'LIKE "status_comment"'
    )) {
        $rename2 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_productcomments '.
            'CHANGE status_comment active int(1) NOT NULL'
        );
    } else {
        $rename2 = true;
    }

    return $rename1 && $rename2;
}
