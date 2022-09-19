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

function upgrade_module_1_2_3()
{
    $update1 = false;
    $update2 = false;

    if (!(int)Tools::getValue('PS_LGCOMMENTS_DIAS', 0)) {
        $update1 = Configuration::updateValue('PS_LGCOMMENTS_DIAS', '30');
    } else {
        $update1 = true;
    }
    
    if (!(int)Tools::getValue('PS_LGCOMMENTS_DIAS2', 0)) {
        $update2 = Configuration::updateValue('PS_LGCOMMENTS_DIAS2', '7');
    } else {
        $update2 = true;
    }

    return $update1 && $update2;
}
