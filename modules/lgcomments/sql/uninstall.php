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

Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcomments_orders`');
Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcomments_status`');
Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcomments_customergroups`');
Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lgcomments_multistore`');
