<?php
/**
 * 2021 Anvanto
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 *
 *  @author Anvanto <anvantoco@gmail.com>
 *  @copyright  2021 Anvanto
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of Anvanto
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_3_0_5($object, $install = false)
{

    $ret = (bool)Db::getInstance()->execute('
      ALTER TABLE `' . _DB_PREFIX_ . 'anblogcat_lang`
        ADD (`meta_title` text);
    ');

    $ret &= (bool)Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'anblog_blog_categories` (
            `id_anblog_blog` int(11) NOT NULL DEFAULT \'0\',
            `id_anblogcat` int(11) NOT NULL DEFAULT \'0\',
            `position` int(11) NOT NULL DEFAULT \'0\',
            PRIMARY KEY (`id_anblog_blog`,`id_anblogcat`)
        ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
    ');
    return $ret;
}
