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

$sql = array(
    'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_orders` (
        `id_order` INT(11) NOT NULL,
        `id_customer` INT(11) NOT NULL,
        `hash` VARCHAR(60) NOT NULL,
        `voted` INT(11) NOT NULL,
        `sent` INT(11) NOT NULL,
        `date_email` DATETIME NOT NULL,
        `date_email2` DATETIME NOT NULL,
        UNIQUE KEY `id_order` (`id_order`),
        KEY `id_customer` (`id_customer`,`hash`,`voted`)
    ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb').' CHARSET=utf8',

    'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_status` (
        `id_order_status` INT(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`id_order_status`)
    ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb'),

    'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_customergroups` (
        `id_customer_group` INT(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`id_customer_group`)
    ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb'),

    'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lgcomments_multistore` (
        `id_shop` INT(11) NOT NULL AUTO_INCREMENT,
        PRIMARY KEY (`id_shop`)
    ) ENGINE='.(defined('ENGINE_TYPE') ? ENGINE_TYPE : 'Innodb'),
);

foreach ($sql as $query) {
    Db::getInstance()->execute($query);
}
