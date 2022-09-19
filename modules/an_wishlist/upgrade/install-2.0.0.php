<?php
/**
 * 2019 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author Anvanto (anvantoco@gmail.com)
 *  @copyright  2019 anvanto.com

 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_2_0_0($object, $install = false)
{
    
    Db::getInstance()->Execute('RENAME TABLE `' . _DB_PREFIX_ . 'an_wishlist` TO `' . _DB_PREFIX_ . 'an_wishlist_products`;');
	
	Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'an_wishlist_products` CHANGE `id_an_wishlist` `id_wishlist_products` INT(10) NOT NULL AUTO_INCREMENT;');

	Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . 'an_wishlist_products` ADD `id_wishlist` INT NOT NULL AFTER `id_wishlist_products`;');

    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'an_wishlist` (
            `id_wishlist` int(10) NOT NULL AUTO_INCREMENT,
            `id_customer` int(10) unsigned NOT NULL,
            `is_guest` int(10) unsigned NOT NULL DEFAULT 0,
            `id_shop` int(10) unsigned NOT NULL,
            PRIMARY KEY  (`id_wishlist`)
            ) ENGINE=' . _MYSQL_ENGINE_ . '  DEFAULT CHARSET = utf8');
	

///////
	$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'an_wishlist_products` ');		
	$forWishlists = array();
	foreach ($result as $product){
		$forWishlists[$product['id_customer']] = array(
			'id_customer' => $product['id_customer'], 
			'is_guest' => $product['is_guest'], 
			'id_shop' => $product['id_shop']
		);
	}	
	
	foreach ($forWishlists as $list){
		$sql = 'INSERT INTO `'._DB_PREFIX_.'an_wishlist`  (`id_customer`, `is_guest`, `id_shop`) 
		VALUES ("'.$list['id_customer'].'", "'.$list['is_guest'].'", "'.$list['id_shop'].'" )';
		Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
	}

	$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'an_wishlist` ');	
	foreach ($result as $list){
		
		Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		UPDATE `' . _DB_PREFIX_ . 'an_wishlist_products` SET id_wishlist='.$list['id_wishlist'].' WHERE id_customer='.$list['id_customer'].' AND is_guest='.$list['is_guest'].'	');
	}
	
    $object->registerHook('actionCustomerAccountAdd');

    return true;
}
