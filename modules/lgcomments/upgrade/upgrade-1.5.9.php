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

function upgrade_module_1_5_9()
{
    $update1 = Configuration::updateValue('PS_LGCOMMENTS_CATTOPMARGIN', '-10');
    $update2 = Configuration::updateValue('PS_LGCOMMENTS_CATBOTMARGIN', '10');
    $update3 = Configuration::updateValue('PS_LGCOMMENTS_PRODTOPMARGIN', '5');
    $update4 = Configuration::updateValue('PS_LGCOMMENTS_PRODBOTMARGIN', '5');

    return $update1 && $update2 && $update3 && $update4;
}
