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

function upgrade_module_1_4_3()
{
    $rename1 = false;
    $rename2 = false;
    $rename3 = false;
    $rename4 = false;

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS '.
        'FROM '._DB_PREFIX_.'lgcomments_storecomments '.
        'LIKE "id_lgcomments_store"'
    )) {
        $rename1 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_storecomments '.
            'CHANGE id_lgcomments_store id_storecomment int(11) NOT NULL AUTO_INCREMENT'
        );
    } else {
        $rename1 = true;
    }

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS '.
        'FROM '._DB_PREFIX_.'lgcomments_productcomments '.
        'LIKE "id_lgcomments_products"'
    )) {
        $rename2 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_productcomments '.
            'CHANGE id_lgcomments_products id_productcomment int(11) NOT NULL AUTO_INCREMENT'
        );
    } else {
        $rename2 = true;
    }

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_storecomments '.
        'LIKE "sort_order"'
    )) {
        $rename3 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_storecomments '.
            'CHANGE sort_order position int(11) NOT NULL'
        );
    } else {
        $rename3 = true;
    }

    if (Db::getInstance()->ExecuteS(
        'SHOW COLUMNS FROM '._DB_PREFIX_.'lgcomments_productcomments '.
        'LIKE "sort_order"'
    )) {
        $rename4 = Db::getInstance()->Execute(
            'ALTER TABLE '._DB_PREFIX_.'lgcomments_productcomments '.
            'CHANGE sort_order position int(11) NOT NULL'
        );
    } else {
        $rename4 = true;
    }

    return $rename1 && $rename2 && $rename3 && $rename4;
}
