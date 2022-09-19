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

define('_AN_BLOG_PREFIX_', 'ANBLOG_');
require_once _PS_MODULE_DIR_.'anblog/classes/config.php';

$config = AnblogConfig::getInstance();


define('_ANBLOG_BLOG_IMG_DIR_', _PS_IMG_DIR_.'anblog/');
define('_ANBLOG_BLOG_IMG_URI_', __PS_BASE_URI__.'img/anblog/');


define('_ANBLOG_CATEGORY_IMG_URI_', _PS_IMG_DIR_.'anblog/');
define('_ANBLOG_CATEGORY_IMG_DIR_', __PS_BASE_URI__.'img/anblog/');

$link_rewrite = 'link_rewrite';
define('_AN_BLOG_REWRITE_ROUTE_', $config->get($link_rewrite, 'blog'));

if (!is_dir(_ANBLOG_BLOG_IMG_DIR_.'c')) {
    // validate module
    mkdir(_ANBLOG_BLOG_IMG_DIR_.'c', 0777, true);
}

if (!is_dir(_ANBLOG_BLOG_IMG_DIR_.'b')) {
    // validate module
    mkdir(_ANBLOG_BLOG_IMG_DIR_.'b', 0777, true);
}

require_once _PS_MODULE_DIR_.'anblog/libs/Helper.php';
require_once _PS_MODULE_DIR_.'anblog/libs/AnblogImage.php';
require_once _PS_MODULE_DIR_.'anblog/classes/anblogcat.php';
require_once _PS_MODULE_DIR_.'anblog/classes/blog.php';
require_once _PS_MODULE_DIR_.'anblog/classes/link.php';
require_once _PS_MODULE_DIR_.'anblog/classes/comment.php';
