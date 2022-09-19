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

function upgrade_module_1_6_1($module)
{
    if (version_compare(_PS_VERSION_, '1.7.0', '>=')) {
        if (!$module->registerHook('actionFrontControllerSetMedia') ||
            !$module->registerHook('displayProductExtraContent') ||
            !$module->registerHook('displayProductPriceBlock')
        ) {
            return false;
        }
    }
    return true;
}
